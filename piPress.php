<?php
/**
* Plugin Name: piPress
* Plugin URI: http://piSociety.com/
* Description: piSociety API plugin for WordPress
* Version: 1.0.2
* Author: piSociety
* Author URI: https://www.piSociety.com/
* License: N/A
*/



/*---------------------------------------------*/
/*---------------------------------------------*/

//PiPress
$piOptions = array(
	"client_secret" => "REDACTED",
	"client_id" => "REDACTED"
	);

if (function_exists("get_option")) {
	include "piAdmin.php";
	$options = get_option( 'piPress_settings');
	$piOptions = array(
		"client_secret" => $options[piPress_text_field_0],
		"client_id" => $options[piPress_text_field_1]
	);
	
}

include "piBeta.php";
include "autogen/piShort-auto.php";
include "src/piAPI.php";
include "src/piProcessing.php";
include "src/piShort.php";
include "src/piSyntax.php";
include "tests/piTest.php";
//include "piCall.php";

function shortcodeWrapper($atts, $content = '') {
	return fullParsePi($content);
}

if (function_exists("add_shortcode")) {
	// I was seeing if WP shortcodes would be an easier installation
	//	method. WIP, so I am disabling it.
	//add_shortcode('piCodes', 'shortcodeWrapper');
}

?>