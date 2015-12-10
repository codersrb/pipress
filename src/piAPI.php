<?php

// Stuff to know:
// KV = Key-Value
//
// Fuzz = Method for testing security & stability
//  of programs by passing data that probably
//  shouldn't be passed to the program. In this
//  context, we will most likely be fuzzing to
//  see if we get escalated privilages or 500s
//
// You will get one commented cURL request in this,
//  I am using the standard cURL calls, look up the
//  reference for more info.


// ReverseRegex is a very cool library I found that is free,
//  and will create a string to match a given regex.
// It looks like if you don't limit matches (i.e. \d+), the
//  library will hang (most likely trying to generate an 
//  infinite-length string).

//include_once "../piPress.php";
//use ReverseRegex\Lexer;
//use ReverseRegex\Parser;
//use ReverseRegex\Generator\Scope;
//use ReverseRegex\Random\MersenneRandom;
//require_once __DIR__ . "/../lib/ReverseRegex/vendor/autoload.php";

// Base URL for the API calls.
$apiBase = "https://uat.pisociety.com";

// ClientAccess should be set to 'Authorization: Bearer {access_token}'
//  There is a function in this file to get that string.
$clientAccess = "";
$clientAccessKey = "";
// PersistedValues is a KV
$persistedValues = array();

// ReverseRegex quick function. saves a lot of lines of code.
function getReverseRegex($reg) {
	// Taken out of an example that came with the library.
//	$reg = str_replace("\s+", "[A-Z]{6}", $reg);
//	$reg = str_replace("\S+", "[A-Z]{6}", $reg);
//	$lexer = new  Lexer($reg);
//	$parser    = new Parser($lexer,new Scope(),new Scope());
//	$generator = $parser->parse()->getResult();
//	$result = '';
//	$random = new MersenneRandom(rand());
//	$generator->generate($result,$random); 
//	
//	return $result;
}

function sendRequest($method, $headers, $data, $path) {
	switch ($method) {
		case "GET":
			return sendGET($headers, $data, $path);
		case "POST":
			return sendPOST($headers, $data, $path);
		case "PATCH":
			return sendPATCH($headers, $data, $path);
		case "PUT":
			return sendPUT($headers, $data, $path);
		case "DELETE":
			return sendDELETE($headers, $data, $path);
		default:
			break;
	}
	return null;
}

// Sends a basic GET request. Returns decoded JSON response (key-value array).
function sendGET($headers, $data, $path) {
	global $apiBase, $verbose;
	$queryString = http_build_query($data);
	array_push($headers,'Content-Type: application/json');
	if (strlen($queryString) > 0) {
		$queryString = "?" . $queryString;
	}
	
	// Create request url with api base url + api path + query string
	$reqPath = $apiBase . $path . $queryString;
	if ($verbose) {
		echo "\nGET: " . $reqPath . "\n";
	}
	// Echo some info because... why not?
	
	
	// Use cURL for sending the GET request.
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	// Set URL
	curl_setopt($curl, CURLOPT_URL, 
	    $reqPath
	);
	// Set headers
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	
	// Decode response (needs error handling).
	$content = json_decode(curl_exec($curl), true);
	
	return $content;
}

// Sends a basic POST request. Returns decoded JSON response (key-value array).
function sendPOST($headers, $data, $path) {
	global $apiBase, $verbose;
	
	
	if ($path == "/api/v1/token") {
		array_push($headers,'Content-Type: application/x-www-form-urlencoded');
		$data = http_build_query($data);
	} else {
		array_push($headers,'Content-Type: application/json');
		//var_dump($headers);
		$data = json_encode($data);
		//die($data);
	}
	
	
	// Create request path
	$reqPath = $apiBase . $path;
	
	if ($verbose) {
		echo "\nPOST: " . $reqPath . "\n";
	}
	
	
	// Do the cURL. If you need more info on cURL,
	//  look up the reference. It's built into PHP.
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_URL, 
	    $reqPath
	);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	
	$content = json_decode(curl_exec($curl), true);
	return $content;
}

// Sends a basic PATCH request. Returns decoded JSON response (key-value array).
function sendPATCH($headers, $data, $path) {
	global $apiBase, $verbose;
	
	$data = json_encode($data);
	array_push($headers,'Content-Type: application/json');
	//var_dump($data);
	// Make the request path
	$reqPath = $apiBase . $path;
	
	if ($verbose) {
		echo "\nPATCH: " . $reqPath . "\n";
	}
	
	// Do the cURL. If you need more info on cURL,
	//  look up the reference. It's built into PHP.
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_URL, 
	    $reqPath
	);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	
	$content = json_decode(curl_exec($curl), true);
	return $content;
}

// Sends a basic DELETE request. Returns decoded JSON response (key-value array).
function sendDELETE($headers, $data, $path) {
	global $apiBase, $verbose;
	//var_dump($headers);
	$data = json_encode($data);
	array_push($headers,'Content-Type: application/json');
	//var_dump($data);
	// Make the request path
	$reqPath = $apiBase . $path;
	
	if ($verbose) {
		echo "\nDELETE: " . $reqPath . "\n";
	}
	
	// Do the cURL. If you need more info on cURL,
	//  look up the reference. It's built into PHP.
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_URL, 
	    $reqPath
	);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	
	$content = json_decode(curl_exec($curl), true);
	return $content;
}

// Sends a basic PUT request. Returns decoded JSON response (key-value array).
function sendPUT($headers, $data, $path) {
	global $apiBase, $verbose;
	
	$data = json_encode($data);
	array_push($headers,'Content-Type: application/json');
	//var_dump($data);
	// Make the request path
	$reqPath = $apiBase . $path;
	
	if ($verbose) {
		echo "\nPUT: " . $reqPath . "\n";
	}
	
	// Do the cURL. If you need more info on cURL,
	//  look up the reference. It's built into PHP.
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_URL, 
	    $reqPath
	);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	
	$content = json_decode(curl_exec($curl), true);
	return $content;
}


function endpointFilters($endpoint) {
	$allFilters = array();
	if (isset($endpoint["Filters"])) {
		$allFilters = $endpoint["Filters"];

	}
	return $allFilters;
}

function endpointFilterNames($endpoint) {
	$allFilters = array();
	$filters = endpointFilters($endpoint);

	foreach ($filters as $filter) {
		$name = $filter["Name"];
		array_push($allFilters, $name);
	}
	return $allFilters;
}


function endpointRequirements($endpoint) {
	$allReqs = array();
	if (isset($endpoint["Requirements"])) {
		$allReqs = $endpoint["Requirements"];
	}
	return $allReqs;
}

function endpointRequirementNames($endpoint) {
	$allReqs = array();
	$requirements = endpointRequirements($endpoint);
	foreach ($requirements as $req) {
		$name = $req["Name"];
		array_push($allReqs, $name);
	}
	return $allReqs;
}

function endpointParameters($endpoint) {
	$paramArr = array();
	if (isset($endpoint["Parameters"])) {
		$paramArr = $endpoint["Parameters"];
	}
	return $paramArr;
}

function endpointParameterNames($endpoint) {
	$paramArr = array();
	$parameters = endpointParameters($endpoint);
	
	$subArrays = array();
	foreach ($parameters as $param) {
		$type = $param["Type"];
		
		if (substr($type, 0, 6) == "object") {
			$name = $param["Parameter"];
			array_push($subArrays, $name);
		}
	}
	rsort($subArrays);
	foreach ($parameters as $param) {
		$name = $param["Parameter"];
		
		$subObj = false;
		foreach ($subArrays as $value) {
			if (strpos($name, $value . "[") === 0) {
				$name = str_replace($value . "[", "", substr($name, 0, strlen($name)-1));
				break;
			}
		}
		array_push($paramArr, $name);
	}
	return $paramArr;
}

function endpointHasFilter($endpoint, $filter) {
	return in_array($filter, endpointFilterNames($endpoint));
}

function endpointHasRequirement($endpoint, $req) {
	return in_array($req, endpointRequirementNames($endpoint));
}

function endpointHasParameter($endpoint, $param) {
	return in_array($param, endpointParameterNames($endpoint));
}

function formattedParametersWithData($endpoint, $data) {
	$parameters = endpointParameters($endpoint);
	
	if (count($parameters) ===  0) {
		return $data;
	}
	
	$paramArr = array();
	$subArrays = array();
	foreach ($parameters as $param) {
		$type = $param["Type"];
		
		if (substr($type, 0, 6) == "object") {
			$name = $param["Parameter"];
			array_push($subArrays, $name);
		}
	}
	rsort($subArrays);
	foreach ($parameters as $param) {
		$name = $param["Parameter"];
		
		$subObj = false;
		foreach ($subArrays as $value) {
			if (strpos($name, $value . "[") === 0) {
				$name = str_replace($value . "[", "", substr($name, 0, strlen($name)-1));
				$subObj = $name;
				break;
			}
		}
		if ($subObj === false) { // Needs to be BOOLEAN false.
			// This is a normal parameter.
			$paramArr[$name] = $data[$name];
		}
		else {
			// This is a child of a sub-array.
			if (isset($paramArr[$value][$subObj]) && isset($data[$subObj])) {
				$paramArr[$value][$subObj] = $data[$subObj];
			}
			
		}
	}
	//var_dump($data);
	return $paramArr;
}

function fillEndpointPathWithRequirements($endpoint, $data) {
	//var_dump($endpoint);
	//var_dump($data);
	$path = $endpoint["Path"];
	if (isset($endpoint["Requirements"])) {
		$requirements = $endpoint["Requirements"];

		foreach ($requirements as $req) {
			$name = $req["Name"];
			$result = '';
			
			if (isset($data[$name])) {
				$result = $data[$name];
			}
			
			$path = str_replace("{" . $name . "}", $result, $path);
			
		}
	}
	return $path;
}

function cleanEndpointRequirementsFromData($endpoint, $data) {
	
	if (isset($endpoint["Requirements"])) {
			$requirements = $endpoint["Requirements"];

			foreach ($requirements as $req) {
				$name = $req["Name"];
				$result = '';
				
				if (isset($data[$name])) {
					if (!(endpointHasFilter($endpoint, $name) || endpointHasParameter($endpoint, $name))) {
						unset($data[$name]);
					}
				}
				
			}
		}
	return $data;
}


// This function tests a GET endpoint
function testGET($endpoint) {
	global $apiBase, $persistedValues;
	
	// There is a JSON file that hase the data.
	// I will do my best to create a schema soon.
	
	// Endpoint.Path is the path to the API call. Ex. '/api/v1/someApiCall'
	$path = $endpoint["Path"];
	
	// Set up the KV query string array.
	$qstrArr = array();
	
	// After fill the query string array,
	//  we will turn it into a string.
	$qstr = "";
	
	// Filters are parameters used to tell
	//  the API info about what to return.
	// Ex. 'limit' may define how many results
	//  the API will return.
	if (isset($endpoint["Filters"])) { // Not everything will have filters
		$filters = $endpoint["Filters"];
		
		// Iterate through the 'filters' array.
		foreach ($filters as $filter) {
			
			// Filter.Name is the filter/parameter name.
			$name = $filter["Name"];
			
			// Filter.Information is an object
			//  with very helpful info, such as
			//  a regex that the value needs to
			//  match (Requirement), documentation
			//  (Description), and a default value
			//  (Default)
			$info = $filter["Information"];
			
			// So Chris's regex requirements have some errors and/or things that break ReverseRegex.
			$defaultLen = strlen(strval($info["Default"]));
			$reg = str_replace("+", "{1," . $defaultLen > 0 ? $defaultLen : 1 . "}", $info["Requirement"]);
			$result = getReverseRegex($reg);
			
			// Set the KV for filter name => value
			$qstrArr[$name] = $result;
			
			// For now, if it has a default, we
			//  won't define that parameter.
			// Later, we will create all kinds
			//  of query strings to fuzz the
			//  API.
			if (strval($info["Default"]) !== "") {
				unset($qstrArr[$name]);
			}
		}
		
	}
	// We are now done with the filters.
	
	// Requirements are for endpoints that have
	//  variables passed in the URL path.
	// Ex. '/api/v1/categories/{category}/cards'
	//  would have 'category' as a requirement.
	//  If category=21, the path would be
	//  '/api/v1/categories/21/cards'.
	if (isset($endpoint["Requirements"])) { // They don't all have requirements.
		$requirements = $endpoint["Requirements"];
		
		// Iterate through the requirements
		foreach ($requirements as $req) {
			// Name is the variable name.
			$name = $req["Name"];
			
			// We fill result with the req. value.
			$result = '';
			
			// Here we check for persisted values
			//  such as 'member' or 'token'
			if (isset($persistedValues[$name])) {
				// The persisted value exists.
				// Use it as the req. value.
				$result = $persistedValues[$name];
			}
			else {
				// No persisted value for this variable.
				
				// Requirement seems to be empty at this
				//  time, so we will fill empty regex req.
				//  with a random 6 character string (UC).
				if ($req["Requirement"] == "") {
					$req["Requirement"] = "[A-Z]{6}";
				}
				
				// Fix Chris's regex issues.
				$req["Requirement"] = str_replace("\s+", "[A-Z]{6}", $req["Requirement"]);
				$reg = str_replace("+", "{2}", $req["Requirement"]);
				
				// Fill the result with a matching regex.
				$result = getReverseRegex($reg);
			}
			
			// Replace '{var_name}' in the path
			//  with the result.
			$path = str_replace("{" . $name . "}", $result, $path);
		}
	}
	
	// And finally, we send the GET request, and
	//  return the parsed JSON response. cURL blocks
	//  the current thread FYI.
	$queryString = http_build_query($qstrArr);
		
		if (strlen($queryString) > 0) {
			$queryString = "?" . $queryString;
		}
		
	return array($path . $queryString => sendGET(defaultHeader(), $qstrArr, $path));
}

function testPATCH($endpoint) {
	global $apiBase, $persistedValues;
	
	// See testGET() documentation for the
	//  'Requirements' block of code in this function.
	$path = $endpoint["Path"];
	
	if (isset($endpoint["Requirements"])) {
		$requirements = $endpoint["Requirements"];
		
		foreach ($requirements as $req) {
			$name = $req["Name"];
			$result = '';
			
			if (isset($persistedValues[$name])) {
				$result = $persistedValues[$name];
			}
			else {
				if ($req["Requirement"] == "") {
					$req["Requirement"] = "[A-Z]{6}";
				}
				$req["Requirement"] = str_replace("\s+", "[A-Z]{6}", $req["Requirement"]);
				$reg = str_replace("+", "{2}", $req["Requirement"]);
				
				$result = getReverseRegex($reg);
			}
			
			$path = str_replace("{" . $name . "}", $result, $path);
		}
	}
	
	// PATCH-specific code:
	
	// jsonParams will be filled with
	//  an encoded KV array.
	$jsonParams = "";
	
	if (isset($endpoint["Parameters"])) {
		// This endpoint has parameters.
		
		$parameters = $endpoint["Parameters"];
		
		// KV array for the parameters that we
		//  will pass.
		$paramArr = array();
		
		// KV array for sub-arrays.
		// This is used to keep track
		//  of the sub-array names.
		$subArrays = array();
		
		// First pass to setup sub-arrays
		foreach ($parameters as $param) {
			$type = $param["Type"];
			
			if (substr($type, 0, 6) == "object") {
				// It's an array
				$name = $param["Parameter"];
				
				// Make an array for this value.
				$paramArr[$name] = array();
				
				// Push this array name to subArrays.
				array_push($subArrays, $name);
			}
		}
		
		// Sort the sub-array names
		//  by longest first (decending).
		// This ensures that we don't
		//  match 'password' sub-array
		//  with 'encrypted_password'.
		// This is a one of 3 layers to
		//  prevent that.
		rsort($subArrays);
		
		// Now we iterate through the parameters.
		foreach ($parameters as $param) {
			$name = $param["Parameter"];
			
			// Check to see if this is a
			//  object in a sub-array.
			// Ex. 'password[confirm]' is
			//  is assigned to Parameters=>password=>confirm.
			
			$subObj = false; // Flag for marking it.
			
			// Iterate through the sub-arrays.
			foreach ($subArrays as $value) {
				
				// Layer 2 & 3 to prevent a false-positive
				//  match.
				// Layer 2: Check for the sub-array + '['
				//  since '[' will only allow matches if
				//  the sub-array name is directly before
				//  the child object's name.
				// Layer 3: Make sure that match is at
				//  index 0. Ensures that there are no
				//  prefixes that don't match.
				if (strpos($name, $value . "[") === 0) {
					// So it is a child of this sub-array.
					
					// Remove 'array_name[' and ']'
					$name = str_replace($value . "[", "", substr($name, 0, strlen($name)-1));
					
					// subObj is set to the sub-array name
					$subObj = $value;
					
					// Break; we found a match after 3 verification
					//  layers. We don't need to keep looking.
					break;
				}
			}
			
			// The standard check for persisted values.
			// See testGET() for full documentation.
			// Only PATCH specific differences will be
			//  commented.
			$result = '';
			if (isset($persistedValues[$name])) {
				$result = $persistedValues[$name];
			}
			else {
				$type = $param["Type"];
				
				// If the param is a 'object (RepeatedType)'
				//  then it is a sub-array. We need to skip it.
				if (substr($type, 0, 6) == "object") {
					continue;
				}
				
				// So types are defined as the PHP type (mostly).
				// Make a regex to allow ReverseRegex to work its
				//  magic.
				$type = str_replace("integer", "\d{2}", $type);
				$type = str_replace("string", "[A-Z]{6}", $type);
				
				// Some don't have anything for the type.
				// Default to a 6 character string (UC).
				if ($type == "") {
					$type = "[A-Z]{6}";
				}
				
				$reg = $type;
				
				// Fill the result with ReverseRegex
				$result = getReverseRegex($reg);
				
			}
			
			if ($subObj === false) { // Needs to be BOOLEAN false.
				// This is a normal parameter.
				$paramArr[$name] = $result;
			}
			else {
				// This is a child of a sub-array.
				$paramArr[$value][$subObj] = $result;
			}
		}
		
		// Encode to JSON.
		$jsonParams = json_encode($paramArr);
		
		// We just encoded a KV array, so make it an JSON object.
		$jsonParams = "{" . substr($jsonParams, 1, strlen($jsonParams)-2) . "}";
	}
	
	$data = $jsonParams;
	
	// Send the request and return the parsed JSON.
	return sendPATCH(defaultHeader(), $data, $path);
}

function argsForEndpoint($point) {
	$reqs = endpointRequirementNames($point);
	$params = endpointParameterNames($point);
	$filters = endpointFilterNames($point);
 	return array_unique(array_merge($reqs, $params, $filters));
}

function defaultValueForEndpointArgument($point, $arg) {
	$params = endpointParameters($point);
	//$reqs = endpointRequirements($point);
	$filters = endpointFilters($point);
	$toRet = null;
	///*
	if (endpointHasParameter($point, $arg)) {
		foreach ($params as $param) {
			if ($param["Parameter"] == $arg) {
				if (isset($param["Required?"])) {
					$toRet = $param["Required?"] ? null : false;
					
				}
			}
		}
	}
	/*
	if (endpointHasRequirement($point, $arg)) {
		
	}
	//*/
	if (endpointHasFilter($point, $arg)) {
		foreach ($filters as $filter) {
			if ($filter["Name"] == $arg) {
				if (isset($filter["Information"]["Default"]) && $filter["Information"]["Default"] != "") {
					return $filter["Information"]["Default"];
				}
			}
		}
	}
	
	return $toRet;
	
}

// This will give back a header array
//  for cURL containing Authorization
//  and Content-Type.
function defaultHeader() {
	global $persistedValues;
	//global $clientAccess;
	//echo($clientAccess . "\n");
	return array(getBearerStringWithToken($persistedValues["client_token"]));
}

function getBearerStringWithToken($token) {
	return "Authorization: Bearer " . $token;
}

// Here we get the client access token
function getClientAccess() {
	global $apiBase, $clientAccessKey, $piOptions, $persistedValues;
	$path = '/api/v1/token';
	
	// Hardcoded query string :-/
	$params = array(
	"client_secret" => $piOptions["client_secret"],
	"client_id" => $piOptions["client_id"],
	"grant_type" => "client_credentials"
	);
	// Return the header string.
	$clientAccessKey = sendGET(array(), $params, $path)["access_token"];
	setAuthToken($clientAccessKey);
	return "Authorization: Bearer " . $clientAccessKey;
}

// Here we get the client access token
function getMemberAccess($member) {
	global $persistedValues;
	
	// Return the header string.
	return "Authorization: Bearer " . $persistedValues["token"];
}

// Makes a randomish member.
function makeMember() {
	global $piOptions;
	// Make a random email
	$reg = "will\+[a-z]{7}@quadland\.com";			
	$email = getReverseRegex($reg);
	
	// Make a random username
	$reg = "[a-z]{7}";
	$username = getReverseRegex($reg);
	
	// Create the body data.
	$makeBody = array(
	'city' => 'Winston Salem',
	'email' => $email,
	'firstName' => 'Test',
	'country' => 'USA',
	'username' => $username,
	'lastName' => 'Quadland',
	'state' => 'NC',
	'streetAddress' => '720 W 5th St',
	'phoneNumber' => '5558675309',
	'mobileNumber' => '5558675309',
	'zipPostalCode' => '27101'
	);
	
	// Send the POST to make the member
	$content = sendPOST(defaultHeader(), $makeBody, '/api/v1/members');
	
	// Save the token. We need it to
	//  confirm the member.
	$token = $content["Token"];
	
	// Next request: Register the member
	$data = array(
	"token" => $token,
	"member" => $email,
	"password" => array(
		"password" => "pass1",
		"confirm" => "pass1"
	)
	);
	
	// API path
	$path = '/api/v1/members/' . $email . '/registrations/' . $token;
	
	// Send the request
	$content = sendPATCH(defaultHeader(), $data, $path);
	
	// Next request: Authorize the user (AKA login).
	$authPath = '/api/v1/token';
	
	// Hardcoded stuff needs to be changed at some point...
	//$authQstr = '?client_secret=1nf0oilqavc0g4ock8k800cswgoo0s04wgk8skgcg00044ogog&client_id=2_2f1e18hbyyasgoco40o00o0k4c0ksccg0kso44ok80w8884w08&grant_type=password&username=' . $email . '&password=pass1';
	
	$params = array(
	"client_secret" => $piOptions["client_secret"],
	"client_id" => $piOptions["client_id"],
	"grant_type" => "password",
	"username" => $email,
	"password" => "pass1"
	);
	
	// Send the login request
	$content = sendGET(defaultHeader(), $params, $authPath);
	
	// Return the needed info...
	return array("member" => $email, "token" => $content["access_token"], "password" => array("password" => "pass1", "confirm" => "pass1"));
}

function endpointWith($path, $type) {
	global $endpoints;
	
	foreach ($endpoints as $endpoint) {
		if ($endpoint["Path"] == $path && $endpoint["Method"] == $type) {
			return $endpoint;
		}
	}
	
	return false;
}

function endpointWithShortCode($shortcode) {
	global $endpoints;
	
	foreach ($endpoints as $endpoint) {
		if ($endpoint["ShortCode"] == $shortcode) {
			return $endpoint;
		}
	}
	
	return false;
}


function testEndpoints() {
	global $endpoints;
	// Go through all of the endpoints and test them.
	$output = array();
	foreach ($endpoints as $key => $value) {
		if ($value["Method"] == "GET") {
			$output[$value["Path"]] = testGET($value);
		} 
		else if ($value["Method"] == "PATCH") {
			//var_dump(testPATCH($value));
		}
		
	}
	
	echo "\n\n" . json_encode($output) . "\n";
}

function initializeWithAuth() {
	global $clientAccess, $persistedValues, $clientAccessToken, $hasAuthenticated;
	// Get the initial access token
	//$clientAccess = getClientAccess();
	
	// Save member info as persisted values
	//$persistedValues = makeMember();
	//$persistedValues["client_token"] = $clientAccessToken;
	
	//$clientAccess = getMemberAccess($persistedValues["member"]);
	$hasAuthenticated = true;
}

function addShortCodes() {
	global $endpoints;
	$newEndpoints = array();
	$shortNames = explode("\n", file_get_contents(__DIR__ . "/../autogen/shortFunctionNames.txt"));
	
	$cnt = 0;
	foreach ($endpoints as $point) {
		foreach ($shortNames as $short) {
			$xpl = explode("->", $short);
			if ($point["Path"] == $xpl[0]) {
				if ($point["Method"] == $xpl[1]) {
					
					if (strlen($xpl[2])) {
						$point["ShortCode"] = $xpl[2];
					}
					
					
				}
				
			}
		}
		//$point["ShortCode"] = $shortNames[$cnt];
		array_push($newEndpoints, $point);
		$cnt++;
		
	}
	
	$endpoints = $newEndpoints;
}

function setAuthToken($token) {
	global $persistedValues;
	$persistedValues["client_token"] = $token;
}


$hasAuthenticated = false;

//var_dump($clientAccess);
// Read endpoint JSON
$endpoints = json_decode(file_get_contents(__DIR__ . "/../autogen/jsonApiOutput.json"), true);
addShortCodes();
//
//foreach ($endpoints as $point) {
//	if (isset($point["ShortCode"])) {
//		echo $point["Path"] ."->" . $point["Method"] . "->" . $point["ShortCode"] . "\n";
//	} else {
//		echo $point["Path"]  . "->"  . $point["Method"] . "->\n";
//	}
//	
//}
//die();
//$reg = "will\+[a-z]{7}@quadland\.com";
$email = "will\+" . strval(time()) . '@quadland.com';
//echo $email . "\n";
$persistedValues["member"] = $email;
//echo $persistedValues["member"];
//testEndpoints();
//$verbose = false;
?>