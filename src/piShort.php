<?php
//include_once "piPress.php";
//// Actual functions to call APIs
//
///* ***** Manual Start ***** */
//function loginMember($username, $password) {
//	global $extern;
//	$apiPath = endpointDotToSlash("api.v1.token");
//	$data = array(
//		"client_secret" => $extern["PI_client_secret"],
//		"client_id" => $extern["PI_client_id"],
//		"grant_type" => "password",
//		"username" => $username,
//		"password" => $password
//	);
//	
//	return trim(json_encode(sendPOST($data, $data, $apiPath), JSON_PRETTY_PRINT));
//}

function piLog($str) {
	global $verbose;
	
	if ($verbose) {
		echo $str;
	}
	
	return $str;
}
/* ***** Manual End ***** */

?>
