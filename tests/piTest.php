<?php
//include_once "piPress.php";

function genShortCodes() {
	global $endpoints, $verbose;
	//$shortNames = explode("\n", file_get_contents(__DIR__ . "/../autogen/shortFunctionNames.txt"));
	//var_dump($shortNames);
	$genStr = "<?php\n";
	//$genStr .= 'include_once "piPress.php";' . "\n";
	$cnt = 0;
	foreach ($endpoints as $point) {
		
		$allArgs = argsForEndpoint($point);
		//echo str_replace("/", ".", $point["Path"]) . " -> " . $shortNames[$cnt] . "(" . implode(", ", $allArgs) . ")\n";
		//echo "[piGET:" . str_replace("/", ".", substr($point["Path"], 1)) . "(" . implode(", ", $allArgs) . ")]\n";
		$adjusted = array();
		$tracker = $allArgs;
		foreach ($allArgs as $arg) {
			$defaultVal = defaultValueForEndpointArgument($point, $arg);
			
			if ($defaultVal !== null) {
				
				if (is_string($defaultVal)) {
					$defaultVal = '"' . $defaultVal . '"';
				} else if (is_bool($defaultVal)) {
					$defaultVal = $defaultVal ? "true" : "false";
				}
				
				else {
					$defaultVal = strval($defaultVal);
				}
				
				$tracker = array_diff($tracker, array($arg));
				$arg .= " = " . $defaultVal;
				array_push($adjusted, $arg);
			}
			
			
		}
		
		$adjusted = array_merge($tracker, $adjusted);
		
		$arrCreator = array();
		foreach ($allArgs as $value) {
			array_push($arrCreator, '"' . $value . '" => $' . $value);
		}
		
	 	$genStr .= "function " . $point["ShortCode"] . '($' . implode(', $', $adjusted) . ") {" .
		'
		$method = "' . $point["Method"] . '";
		$endpoint = endpointWith("' . $point["Path"] . '", $method);
		global $verbose;
		checkAuth();
		
		$data = array(' . implode(",\n\t", $arrCreator) . '
		);
		
		if ($method != "GET") {
			$data = formattedParametersWithData($endpoint, $data);
		}
		
		$newPath = fillEndpointPathWithRequirements($endpoint, $data);
		$data = cleanEndpointRequirementsFromData($endpoint, $data);
		return send' . $point["Method"] . '(defaultHeader(), $data, $newPath);' .
		"\n}\n\n";
		$cnt++;
	}
	$genStr .= '?>';
	echo $genStr;
	file_put_contents("autogen/piShort-auto.php", $genStr);
}

//genShortCodes();
//$verbose = true;
if ($verbose) {
	$verbose = false;
	$testPage = file_get_contents("tests/newPiSyntax.js");
	$modded = fullParsePi($testPage);
	echo "******************\nParsed File:\n" . $modded . "\n******************\n";
}

?>