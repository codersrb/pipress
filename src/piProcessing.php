<?php
// This file does the processing
//  of parsed piCodes.

//include_once "piPress.php";

// Quick function to check if we
//  have gotten an access key and
//  get one if we need it.
function checkAuth() {
	global $hasAuthenticated;
	if ($hasAuthenticated === false) {
		initializeWithAuth();
	}
}

// Ends processing.
function piStop($code) {
	global $continueProcPi;
	$continueProcPi = false;
	return $code["params"];	
}

// Processes shortcodes.
function piShort($code) {
	global $verbose;
	// Requires auth
	checkAuth();
	
	$func = $code["function"];
	$args = $code["arguments"];
	
	// We restore the externalized arguments now
//	foreach ($args as $key => $value) {
//		$args[$key] = restoreExternalized($value);
//	}
	
	if ($verbose) {
		echo $func . "(";
	}
	
	// Get the endpoint
	$endpoint = endpointWithShortCode($func);
	
	// Fill array with the arguments in order
	//  and with default args filled in.
	$rawArgVals = null;
	
	// Manual shortcodes will not be connected to an
	//  endpoint.
	if ($endpoint !== false) {
		$rawArgVals = kvArrayToValueOnly(fillSortEndpointArgs($endpoint, $args));
	}
	else {
		// Order will matter here. Let's hope they put args in the right order.
		$rawArgVals = kvArrayToValueOnly($args);
	}
	
	if ($verbose) {
		echo implode(", ", $rawArgVals) . ");\n";
	}
	
	//$newPath = fillEndpointPathWithRequirements($endpoint, $code["arguments"]);
	//$code["arguments"] = cleanEndpointRequirementsFromData($endpoint, $code["arguments"]);
	
	return call_user_func_array($func, $rawArgVals);
}

// Can be used to call raw endpoints
function piRequest($code, $method) {
	global $verbose;
	
	// Requires auth
	checkAuth();
	
	$code["function"] = "/" . str_replace(".", "/", $code["function"]);
	
	if ($verbose) {
		echo $code["function"] . "\n";
	}
	
	$endpoint = endpointWith($code["function"], $method);
	if ($endpoint === false) {
		return json_encode(sendRequest($method, defaultHeader(), $code["arguments"], $code["function"]));
		//return false;
	}
	
	if ($method != "GET") {
		$data = formattedParametersWithData($endpoint, $data);
	}
	
	$newPath = fillEndpointPathWithRequirements($endpoint, $code["arguments"]);
	$code["arguments"] = cleanEndpointRequirementsFromData($endpoint, $code["arguments"]);
	
	return json_encode(sendRequest($method, defaultHeader(), $code["arguments"], $newPath));
}

// Can be used to call raw endpoints
function piPOST($code) {
	return piRequest($code, "POST");
}

// Can be used to call raw endpoints
function piGET($code) {
	return piRequest($code, "GET");
}

function piPATCH($code) {
	return piRequest($code, "PATCH");
}

function piDELETE($code) {
	return piRequest($code, "DELETE");
}

function piPUT($code) {
	return piRequest($code, "PUT");
}

function piString($code) {
	
	return piEcho($code);
}

// Used to print info
function piEcho($code) {
	global $verbose;
	$strParts = $code["params"];
	
	// Check for concatenation
	if (strpos($strParts, "+") !== false) {
		$strParts = explode("+", $strParts);
	} else {
		// Make an array either way
		$strParts = array($strParts);
	}
	
	// We will build the string
	$echoStr = "";
	
	// Go through the string parts
	foreach ($strParts as $part) {
		$part = piVarRaw($part);
		// Append proccessed part
		$echoStr .= stripcslashes($part);
	}
	
	if ($verbose) {
		echo $echoStr;	
	}
	
	return $echoStr;
}

// Check if a string is JSON
// Returns parsed JSON on success, false for non-JSON strings
function isJson($string) {
	if (is_array($string)) {
		return $string;
	}
	$jsonString = json_decode($string, true);
	return (json_last_error() == JSON_ERROR_NONE) ? $jsonString : false;
}

// Get a dot-syntax variable
function piVarRaw($path) {
	global $verbose;
	// Explode the dot-syntax into parts
	$varParts = explode(".", $path);
	// Reverse it so we can array_pop() to get
	//  and remove the variable part with one line.
	$varParts = array_reverse($varParts);
	
	if ($verbose) {
		var_dump($varParts);
	}
	
	// I'll come back to this...
	$firstPart = array_pop($varParts);
	$restored = restoreExternalized($firstPart);
	piLog($restored);
	$jsonString = isJSON($restored);
	
	if ($jsonString === false) {
		
		if ($restored === $firstPart) {
			return $path;
		}
		
		return $restored;
	}
	
	if ($verbose) {
		//var_dump($jsonString);
		//die();
	}
	
	// Decode the JSON string. The first one should be JSON...
	$varBase = $jsonString;//json_decode($jsonString, true);
	
	// Go through parts
	$currentVar = $varBase;
	
	while (count($varParts) > 0) {
		// Next variable name in dot-syntax
		$newVarName = array_pop($varParts);
		// Go ahead and get a restored version of the variable
		$externVarName = restoreExternalized($newVarName);
		
		if (isset($currentVar[$newVarName])) {
			// Current variable has the subobject
			$currentVar = $currentVar[$newVarName];
		}
		else if (isset($currentVar[$externVarName])) {
			// Doesn't have the subobject.
			// Check if it has the restored name
			$currentVar = $currentVar[$externVarName];
		}
		else if (isset($currentVar[intval($newVarName)])) {
			// Current variable has the subobject
			$currentVar = $currentVar[intval($newVarName)];
		}
		else if (isset($currentVar[intval($externVarName)])) {
			// Doesn't have the subobject.
			// Check if it has the restored name
			$currentVar = $currentVar[intval($externVarName)];
		}
		else {
			// Welp that didn't exist... return what we got...
			$jsonCheck = isJson($currentVar);
			if ($jsonCheck !== false) {
				$currentVar = json_encode($currentVar, JSON_PRETTY_PRINT);
			}
			array_push($varParts, $currentVar);
			$varParts = array_reverse($varParts);
			$currentVar = implode(".", $varParts);
			break;
		}
		
		// Decode JSON sub-objects
		$jsonCheck = isJson($currentVar);
		if ($jsonCheck !== false) {
			$currentVar = $jsonCheck;
		}
	}
	
	// Array check
	if (is_array($currentVar)) {
		// Our last object was an array. Encode it
		$currentVar = trim(json_encode($currentVar, JSON_PRETTY_PRINT));
	}
	
	return $currentVar;
	
}

function piVar($code) {
	// Just passes piCode params to piVarRaw...
	return piVarRaw($code["params"]);
}

function piDefine($code) {
	global $extern;
	$paramString = $code["params"];
	
	if (strpos($paramString, "->") === false) {
		$paramString = '$->' . $paramString;
	}
	
	$parts = explode("->", $paramString);
	$toStore = $parts[0];
	
	if (strlen($toStore) == 0) {
		$toStore = '$';
	}
	
	$constName = $parts[1];
	
	if (strlen($constName) == 0) {
		die("Empty constant name");
	}
	
	$toStore = piVarRaw($toStore);
	
	$extern[$constName] = $toStore;
	
	return $toStore;
}

//function piSetAuth($code) {
//	global $persistedValues, $extern;
//	
//	return piVarRaw($code["params"]);
//}


// This is the base piCode handler.
// It determines what will proccess
//  a given code.
function piCode($code) {
	global $results, $verbose, $persistedValues, $extern, $hasAuthenticated, $continueProcPi;
	if ($verbose) {
		echo "\nProc Code: [" . $code["name"] . ":" . $code["params"] . "]";
	}
	
	//$code["params"] = piVarRaw($code["params"]);
	
	// Let's come back...
	if (isset($code["arguments"])) {
		piLog(" -> [" . $code["name"] . ":" . $code["function"] . "(");
		$argLogArr = array();
		foreach ($code["arguments"] as $key => $value) {
			//piLog("\nDEBUG: " . $value . "\n");
			$code["arguments"][$key] = piVarRaw($value);
			array_push($argLogArr, $key . ":" . $code["arguments"][$key] . "");
		}
		piLog(implode(",", $argLogArr));
		piLog(")]\n");
	} else {
		piLog(" -> [" . $code["name"] . ":" . $code["params"] . "]\n");
	}
	
	
	
	// We will fill result and return it.
	$result = "";
	
	// We will only actually store piEchos
	//  for final output.
	$shouldStore = false;
	
	// Decide where to proccess the piCode.
	if ($code["name"] == "piGET") {
		$result = piGET($code);
	}
	else if ($code["name"] == "piPOST") {
		$result = piPOST($code);
	}
	else if ($code["name"] == "piPATCH") {
		$result = piPATCH($code);
	}
	else if ($code["name"] == "piDELETE") {
		$result = piDELETE($code);
	}
	else if ($code["name"] == "piPUT") {
		$result = piPUT($code);
	}
	else if ($code["name"] == "pi") {
		$result = piShort($code);
	}
	else if ($code["name"] == "piString") {
		$result = piString($code);
	}
	else if ($code["name"] == "piEcho") {
		$result = piEcho($code);
		$shouldStore = true;
	}
	else if ($code["name"] == "piVar") {
		$result = piVar($code);
		$shouldStore = true;
	} 
	else if ($code["name"] == "piStop") {
		$result = piStop($code);
	}
	else if ($code["name"] == "piDefine") {
		$result = piDefine($code);
	}
	else {
		if ($verbose) {
			echo "Code not found: " . $code["name"] . "\n";
		}
		
		return "";
	}
	
	// Bind $n to the result (persists)
	$extern['$' . count($results)] = $result;
	
	// Bind $ to the result (lasts for 1 cmd)
	$extern['$'] = $result;
	
	if ($shouldStore !== true) {
		$result = "";
	}
	
	array_push($results, $result);
	
	return $result;
}

// Test this file.
function piProcessPage() {
	global $codes, $piCodeResults, $origSyntax, $verbose, $continueProcPi;
	
	if ($verbose) {
		echo "\n******************\nProc Output: \n";
	}

	
	foreach ($codes as $code) {
		array_push($piCodeResults, piCode($code));
		if ($continueProcPi !== true) {
			$continueProcPi = true;
			break;
		}
	}
	if ($verbose) {
		echo "\n******************\nFinal Output: \n";
	}
	//echo "\n\n\n";
	return fillResults($origSyntax, $piCodeResults);// . "\n";
}

$piCodeResults = array();

$continueProcPi = true;

?>
