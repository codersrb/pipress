<?php
include __DIR__ . "/piPress.php";
//var_dump($_POST);
//die();
//$_POST["payload"] = '[piEcho:"Tests" + "\n"]';
if (isset($_POST["payload"])) {
	//echo base64_decode($_POST["payload"]);
	$data = base64_decode(base64_decode($_POST["payload"]));
	echo fullParsePi($data);
}

?>