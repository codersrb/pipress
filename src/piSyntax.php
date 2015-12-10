<?php
//include_once "piPress.php";

$extern = array();
$results = array();
function restoreExternalized($someString) {
	global $extern, $persistedValues;
	
	foreach ($persistedValues as $key => $value) {
		$extern["PI_" . $key] = $value;
	}
	krsort($extern);
	
	foreach ($extern as $key => $value) {
		if (is_array($value)) {
			$value = trim(json_encode($value, JSON_PRETTY_PRINT));
		}
		
		if ($someString === $key) {
			$someString = str_replace($key, $value, $someString);
		}
	}
	return $someString;
}

function externalizeStrings($origSyntax) {
	// Super escape the escaped quotes
	$modded = str_replace("\\\"", "|ESCAPED_D_QUOTE|", $origSyntax);
	$modded = str_replace("\\'", "|ESCAPED_S_QUOTE|", $modded);

	//echo "Single/Double Quote Pass: \n" . $modded . "\n";

	// Externalize strings (we will eventually externalize all parameters)
	// BUT HOLD ON, EXTRA FUN ALERT:
	//		Single quotes don't have to be escaped within
	//		double quotes, and vice versa.
	global  $extern;

	$matchReturns = 0;
	$re = "";
	do {
		// Get index of first " and '
		// Start with the earliest one.
		$dqfirst = false;
		$re = "/\"(.*?)\"/ms"; 
		if (strpos($modded, "'") !== false && strpos($modded, "'") < strpos($modded, "\"")) {
			// Starts with single quotes
			//echo "SQuote first\n";
			$re = "/'(.*?)'/ms"; 
		} else {
			$dqfirst = true;
			//echo "DQuote first\n";
		}
		
		// Parse and exteralize one by one.

		$matchReturns = preg_match_all($re, $modded, $matches, PREG_PATTERN_ORDER);
		if ($matchReturns !== false && $matchReturns > 0) {
			//rsort($matches[0]);
			//var_dump($matches[0][0]);
			$restoredVar = $matches[0][0];
			$restoredVar = str_replace("|ESCAPED_D_QUOTE|", "\\\"", $restoredVar);
			$restoredVar = str_replace("|ESCAPED_S_QUOTE|", "\\'", $restoredVar);
			$restoredVar = substr($restoredVar, 1, strlen($restoredVar)-2);
			
			if ($dquotefirst === false) {
				
			} else {
				
			}
			
			$varName = "str" . count($extern);

			$extern[$varName] = $restoredVar;

			$modded = str_replace($matches[0][0], $varName, $modded);
		}
		
	} while ($matchReturns !== false && $matchReturns > 0);
	return $modded;
}

function trimStr($str) {
	$re = "/\\s+/ms"; 
	return preg_replace($re, "", $str);
}

function externalizeParameters($text) {
	global $extern;
	return $text;
	$modded = $text;
	// So we do have to do some regex
	//  searching here to find the parameters.
	
	// Regex for grabbing parameters from piCodes
	$re = "/\\[pi.*?:.*?\\((.*?)\\)]/ms";
	
	// Find params
	
	// Trim whitespace
	
	// Check and make sure that there are parameters
	
	// Explode by comma
	
	// Explode by colon
	
	// Check if var is already externalized
	
	// If not, externalize it.
	
	$matchReturns = 0;
	$currentOffset = 0;
	do {
		// Parse and exteralize one by one.
		
		// Find params
		$matchReturns = preg_match_all($re, $modded, $matches, PREG_PATTERN_ORDER, $currentOffset);
		
		$matchReturns = count($matches[0]);
		if ($matchReturns !== false && $matchReturns > 0) {
			$fullMatch = $matches[0][0];
			$paramsStr = $matches[1][0];
			//var_dump($matches);
			//die();
			// Trim whitespace
			$paramsStr = trimStr($paramsStr);
			
			// Check and make sure there are params
			if (strlen($paramsStr) > 0) {
				$params = explode(",", $paramsStr);
				
				foreach ($params as $fullParam) {
					$explParam = explode(":", $fullParam);
					$paramName = $explParam[0];
					$paramVal = $explParam[1];
					
					if (isset($extern[$paramVal])) {
						continue;
					}
					
					$varName = "xvar" . count($extern);
					
					$extern[$varName] = $paramVal;
					
					$paramsStr = str_replace($paramName . ":" . $paramVal, $paramName . ":" . $varName, $paramsStr);
					
				}
				//var_dump($modded);
			}
			
			$fullMatch = str_replace($matches[1][0], $paramsStr, $fullMatch);
			
			//$cmdVarName = "[xcmd" . count($extern) . "]";
			
			//$extern[$cmdVarName] = restoreExternalized($fullMatch);
			
			$modded = str_replace($matches[0][0], $fullMatch, $modded);
			
			$currentOffset = strpos($modded, $fullMatch, $currentOffset) + strlen($fullMatch);
			
			
		}
		
	} while ($matchReturns !== false && $matchReturns > 0);
	return $modded;
}


function fullExternalize($page) {
	return externalizeParameters(externalizeStrings($page));
}

function getRawPiCodes($page) {
	$currentOffset = 0;
	$re = "/\\[(pi.*?):([^\\(]*?|(.*?)\\((.*?)\\))\\]/ms"; 
	$matchReturns = preg_match_all($re, $page, $matches, PREG_SET_ORDER);
	$piCodes = array();
	foreach ($matches as $code) {
		
		$theCode = array(
		"name" => trimStr($code[1]),
		"params" => trimStr($code[2]),
		"function" => null,
		"arguments" => null
		);
		
		if (isset($code[3])) {
			$theCode["function"] = $code[3];//"/" . str_replace(".", "/", $code[3]);
			
			if (isset($code[4])) {
				$argumentStr = trimStr($code[4]);
				$parsedArgs = array();
				
				
				$args = explode(",", $argumentStr);
				
				foreach ($args as $arg) {
					$explArg = explode(":", $arg);
					$argName = $explArg[0];
					$argVal = $explArg[1];
					
					$parsedArgs[$argName] = $argVal;//restoreExternalized($argVal);
				}
				
				$theCode["arguments"] = $parsedArgs;
			}
			
		}
		array_push($piCodes, $theCode);
	}
	
	return $piCodes;
}

function endpointDotToSlash($path) {
	return "/" . str_replace(".", "/", $path);
}

function kvArrayToValueOnly($kvArr) {
	$arr = array();
	foreach ($kvArr as $key => $value) {
		array_push($arr, $value);
	}
	
	return $arr;
}

function fillSortEndpointArgs($point, $args) {
	
	$allArgs = argsForEndpoint($point);
	
	$adjusted = array();
	$tracker = $allArgs;
	
	foreach ($allArgs as $arg) {
		$defaultVal = defaultValueForEndpointArgument($point, $arg);
		
		if ($defaultVal !== null) {
			
			$tracker = array_diff($tracker, array($arg));
			//$arg .= " = " . $defaultVal;
			array_push($adjusted, $arg);
		}
		
		
	}
	
	$adjusted = array_merge($tracker, $adjusted);
	//var_dump($adjusted);
	$finalAdj = array();
	foreach ($adjusted as $argName) {
		if (isset($args[$argName])) {
			$finalAdj[$argName] = $args[$argName];
			//array_push($finalAdj, $args[$argName]);
		} else {
			$finalAdj[$argName] = defaultValueForEndpointArgument($point, $argName);
			//array_push($finalAdj, defaultValueForEndpointArgument($point, $argName));
		}
	}
	
	return $finalAdj;
}

function fillResults($page, $results) {
	$results = array_reverse($results);
	//var_dump($results);
	//die();
	$modded = $page;
	$re = "/\\[(pi.*?):([^\\(]*?|(.*?)\\((.*?)\\))\\]/ms";

	$matchReturns = 0;
	$currentOffset = 0;
	do {
		// Parse and exteralize one by one.
		
		// Find params
		$matchReturns = preg_match_all($re, $modded, $matches, PREG_PATTERN_ORDER, $currentOffset);
		//var_dump($matches);
		//$matchReturns = count($matches[0]);
		if ($matchReturns !== false && $matchReturns > 0) {
			$fullMatch = $matches[0][0];
			//var_dump($fullMatch);
			//die();
			
			if (count($results) == 0) {
				break;
			}
			
			$theFill = array_pop($results);
			//var_dump($theFill);
			$currentOffset = strpos($modded, $fullMatch, $currentOffset);
			$lastPart = substr($modded, $currentOffset);
			
			$pos = strpos($lastPart, $fullMatch);
			if ($pos !== false) {
			    $lastPart = substr_replace($lastPart, $theFill, $pos, strlen($fullMatch));
			}
			
			//var_dump(substr($modded, 0, $currentOffset));
			$modded = substr($modded, 0, $currentOffset) . $lastPart;
			$currentOffset = $currentOffset + strlen($theFill);
			//var_dump($currentOffset);
		}
		
	} while ($matchReturns !== false && $matchReturns > 0);
	return $modded;
}

function initializeSyntax($page) {
	global $codes, $origSyntax;
	$origSyntax = $page;
	//$modded = externalizeParameters(externalizeStrings($origSyntax));
	$modded = fullExternalize($origSyntax);
	$codes = getRawPiCodes($modded);
	//genShortCodes();
	return $modded;
}

function fullParsePi($page) {
	$modded = initializeSyntax($page);
	return piProcessPage();
}

$codes = null;
// Set external constants now
$extern["PI_client_secret"] = $piOptions["client_secret"];
$extern["PI_client_id"] = $piOptions["client_id"];
$originalSyntax = null;




//echo json_encode(sendGET(defaultHeader(), $codes[0]["arguments"], $codes[0]["function"]));
//die();
//var_dump($extern);
//$modded = restoreExternalized($modded);

//echo $modded . "\n";
?>