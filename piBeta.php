<?php

//include_once "piPress.php";

function is_json($string) {
 json_decode($string);
 return (json_last_error() == JSON_ERROR_NONE);
}

$piPress = new PiPress();
	class PiObject {
		var $properties = array();
		
		public function __construct() {
			//$this->properties = $props;
		}
		
	}
	
	class PiPress extends PiObject
	{
		
		var $basePath = "./json/";
		
		public static function createDocumentation($var) {
			
		}
		
		public static function writeObjectParts($obj) {
			////var_dump("writing obj: " . $obj["varname"]);
			////var_dump($obj);
			$adjObj = array();
			foreach ($obj as $key => $value) {
				$strkey = strval($key);
				if (substr($strkey, 0, 1) == "/") {
					$strkey = substr($strkey, 1);
				}
				
				$adjObj[$strkey] = $value;
			}
			
			$obj = $adjObj;
			
			foreach ($obj as $key => $value) {
				
				$strkey = strval($key);
				if ($value == null) {
					////var_dump("reading null: " . $obj["varname"] . "." . $strkey);
					$value = PiPress::getVar($obj["varname"] . "." . $strkey);
					
				}
				
				if (is_string($value) && is_json($value)) {
					////var_dump("json string");
					$value = json_decode($value, true);
				}
				
				////var_dump("dump1: ");
				////var_dump($value);
				if (is_array($value)) {
					
					$value["varname"] = $obj["varname"] . "." . $strkey;
					
					//var_dump($value["varname"]);
					PiPress::writeObjectParts($value);
					$obj[$key] = null;
				}
				
				
			}
			//PiPress::createDocumentation($obj);
			file_put_contents("./" . $obj["varname"] . ".json", json_encode($obj));
		}
		
		
		public static function createSampleData() {
			
			function makeRandomWord() {
				$syl = array(
					"not", "van", "mik", "fon", "kan", "tac", "ber", "den", "sol", "hel", "man", "mit", "ban", "hoff", "cen", "fog", "lock"
				);
				
				$conn = array(
				"a", "e", "i", "o"
				);
				
				$tot = rand(2, 4);
				$word = "";
				for ($x = 0; $x < $tot-1; $x++) {
					$word .= $syl[rand(0, count($syl)-1)];
					if (rand()%2 == 0) {
						$word .= $conn[rand(0, count($conn)-1)];
					}
				}
				
				$word .= $syl[rand(0, count($syl)-1)];
				
				return $word;
			}
			
			// Make a member
			$c1 = array(
				"varname" => "member",
				"name" => array(
					"first" => ucfirst(makeRandomWord()),
					"middle" => ucfirst(makeRandomWord()),
					"last" => ucfirst(makeRandomWord())
					),
				"wallet" => array(
					"points" => rand(100, 5000000),
					"cards" => array(
						"Best Buy", "PF Chang's", "Coldstone", "Some Other Card", "Drawing A Blank", "McDonald's", "Chipotle", "Moe's", "Last Card"
					)
				),
				"referredMembers" => array(
				
				),
				
				"referredBy" => array(
				
				)
			
			);
			PiPress::writeObjectParts($c1);
			//die();
		}
		
		public static function getVar($fullPropStr) {
			global $piPress;
			//var_dump(scandir("./"));
			////var_dump("Getting: " . $fullPropStr);
			if (isset($piPress->properties[$fullPropStr])) {
				////var_dump("Already set: " . $fullPropStr);
				return $piPress->properties[$fullPropStr];
			}
			
			$objectPropTree = explode(".", $fullPropStr);
			
			$curProp = PiPress::getBaseVar($objectPropTree[0]);
			
			
			$evoProp = $objectPropTree[0];
			
			for ($x = 1; $x < count($objectPropTree); $x++) {
				////var_dump($curProp);
				if ($fullPropStr == "api.members.get.responses.200.body.example.0.wallet.cards") {
					//var_dump($curProp);
				}
				$adjObj = array();
				
				foreach ($curProp as $key => $value) {
					$strkey = strval($key);
					if (substr($strkey, 0, 1) == "/") {
						$strkey = substr($strkey, 1);
					}
					
					$adjObj[$strkey] = $value;
				}
				
				$curProp = $adjObj;
				
				$prop = $objectPropTree[$x];
				$strkey = strval($prop);
				
				
				//$prop = $objectPropTree[$x];
				
				if (is_numeric($prop)) {
					$prop = intval($prop);
					if ($prop == 200) {
						$prop = strval($prop);
					}
				}
				
				
				$evoProp .= "." . strval($prop);
				
				/*if (!isset($curProp[$prop]) && isset($curProp["/" . $prop])) {
					$curProp[$prop] = $curProp["/" . $prop];
				}*/
				
				if ($curProp[$prop] == null) {
					//var_dump("Null: " . $evoProp);
					$curProp[$prop] = $piPress->fetchVarByName($evoProp);
					//var_dump("After read: ");
					//var_dump($curProp[$prop]);
				}
				
				if (!(isset($curProp[$prop]))) {
					//var_dump($curProp);
					//echo($prop);
					//die();
				}
				
				$curProp = $curProp[$prop];
			}
			
			$piPress->properties[$fullPropStr] = $curProp;
			
			//die();
			return $curProp;
			
		}
		
		public function fetchVarByName($name) {//
			$fname = $this->basePath . $name . ".json";
			////var_dump("Reading: " . $fname);
			if (file_exists($fname)) {
				////var_dump("Exists: " . $fname);
				$this->properties[$name] = json_decode(file_get_contents($fname), true);
			}
			
			else {
				$this->properties[$name] = array("error" => "Object not found");
			}
			$this->properties[$name]["varname"] = $name;
			return $this->properties[$name];
		}
		
		public static function getBaseVar($name) {
			global $piPress;
			
			if (!isset($piPress->properties[$name])) {
				$piPress->fetchVarByName($name);
			}
			
			if (!isset($piPress->properties[$name])) {
				return array("error" => "Object not found");
			}
			
			else {
				return $piPress->properties[$name];
			}
			
			
			return array("error" => "Object not found");
		}
	}
	
	
	class PiCommand
	{
		var $params = "";
		var $secret = false;
		var $textIndex = 0;
		var $fullString = "";
		
		public function fillWithResult($pageText) {
			return substr_replace($pageText, $this->result(), $this->textIndex, strlen($this->fullString));
		}
		
		public static function stripWhitespace($str) {
			$re = "/[[:space:]]/"; 
			$subst = ""; 
			 
			$result = preg_replace($re, $subst, $str);
			return $result;
		}
		
		public function postConstruction() {
			// Use this to avoid overriding the default constructor
		}
		
		public function __construct($params) {
			$this->params = PiCommand::stripWhitespace($params);
			$this->postConstruction();
		}
		
		public function result() {
			return "";
		}
		
		public function doesNeedVariables() {
			return false;
		}
		
		public function neededVariables() {
			return "[Not Setup]";
		}
		
		public function isPrivate() {
			return $this->secret;
		}
		
		public function isInitializer() {
			return  is_a($this, 'PiInitializer');
		}
		
	}
	
	
	class PiVar extends PiCommand
	{
		var $objectPropTree = array();
		public function postConstruction() {
			$this->objectPropTree = explode(".", $this->params);
		}
		
		public function doesNeedVariables() {
			return true;
		}
		
		public function neededVariables() {
			return $this->params;
		}
		
		public function result() {
			return PiVar::staticResult($this);
		}
		
		public static function staticResult($piVarCmd) {
			/*$curProp = PiPress::getBaseVar($piVarCmd->objectPropTree[0]);
			
			for ($x = 1; $x < count($piVarCmd->objectPropTree); $x++) {
				
				$prop = $piVarCmd->objectPropTree[$x];
				
				if (is_numeric($prop)) {
					$prop = intval($prop);
				}
				
				$curProp = $curProp[$prop];
			}
			
			return $curProp;*/
			////var_dump(PiPress::getVar($piVarCmd->params));
			return PiPress::getVar($piVarCmd->params);
		}
		
		public static function getVarProp($propString) {
			$piVar = new PiVar($propString);
			return PiVar::staticResult($piVar);
		}
	}
	
	
	class PiInitializer extends PiCommand {
		var $secret = true;
		var $textContent = "";
		
		public function isCorrectTerminator($possibleTerm) {
			return false;
		}
	}
	
	
	class PiIteratorStart extends PiInitializer
	{
		var $secret = false;
		var $arrayProperty = "";
		var $iteratorVariableName = "";
		
		public function postConstruction() {
			//var_dump("iterator start");
			// Split the array->itr param
			if (strpos($this->params, "->") !== false && strpos($this->params, "->") > 0)
			{
				$tempArray = explode("->", $this->params, 2);
				
				if (count($tempArray) < 2)
				{
					//echo("Not enough parameters for PiIteratorStart: " . $this->params . "\n");
				}
				
				else
				{
					$this->arrayProperty = $tempArray[0];
					$this->iteratorVariableName = $tempArray[1];
				}
				
				
			}
			
			else
			{
				//echo("Invalid parameters for PiIteratorStart: " . $this->params . "\n");
			}
			
		}
		
		public function isCorrectTerminator($possibleTerm) {
			
			if (is_a($possibleTerm, 'PiIteratorEnd')) {
				if ($possibleTerm->params == $this->iteratorVariableName) {
					return true;
				}
			}
			
			return false;
		}
		
		public function result() {
			//echo("hrer1\n");
			//var_dump($this->arrayProperty);
			$theProp = PiVar::getVarProp($this->arrayProperty);
			//echo("hrer2\n");
			//var_dump($this->arrayProperty);
			//var_dump($theProp);
			
			$embedded = Parser::extractPiCommandsFromString($this->textContent);
			$myItrs = array();
			
			foreach ($embedded as $cmd) {
				if (is_a($cmd, "PiIterator")) {
					$cmd->arrayProperty = $this->arrayProperty;
					$cmd->itrName = $this->iteratorVariableName;
					array_push($myItrs, $cmd);
				}
			}
			
			$result = "";
			
			for ($x = 0; $x < count($theProp)-1; $x++) {
				$mutableText = $this->textContent . "";
				//var_dump("umm");
				//var_dump($this->textContent);
				
				$adjustment = 0;
				
				foreach ($myItrs as $cmd) {
					$cmd->myIdx = $x;
					$oldIdx = $cmd->textIndex;
					$cmd->textIndex -= $adjustment;
					
					$mutableText = $cmd->fillWithResult($mutableText);
					$adjustment = strlen($this->textContent) - strlen($mutableText);
					$cmd->textIndex = $oldIdx;
					//echo("adjustment: " . strval($adjustment) . "\n");
				} // Need to adjust indexes after text replacement
				
				
				
				$result .= $mutableText;
				//var_dump($result);
			}
			//var_dump($result);
			//die();
			return $result;
		}
		
		public function doesNeedVariables() {
			return true;
		}
		
		public function neededVariables() {
			return $this->arrayProperty;
		}
	}
	
	
	class PiTerminator extends PiCommand {
		var $secret = true;
	}
	
	
	class PiIteratorEnd extends PiTerminator {
		var $secret = false;
	}
	
	class PiIterator extends PiCommand {
		var $arrayProperty = "";
		var $myIdx = 0;
		var $itrName = "";
		public function postConstruction() {
			
			
		}
		
		public function result() {
			$objTree = split("\\.", $this->params);
			if ($objTree[0] == $this->itrName) {
				$objTree[0] = $this->arrayProperty . "." . $this->myIdx;
				$newParams = implode(".", $objTree);
				//var_dump("[piVar:" . $newParams . "]");
				
				return "[piVar:" . $newParams . "]";
			}
			
			// Didn't match up... (Temp code)
			return $this->fullString;
		}
		
	}
	
	
	/*
		Parser
			Base class for parsing piCommands into objects.
	*/
	class Parser
	{
		
		// Extracts all piCommands from a string
		public static function extractPiCommandsFromString($stringToParse)
		{
			$re = "/\\[(pi.*?):(.*?)\\]/";
			
			$str = $stringToParse;
			
			$subst = "$1"; 
		 	$cmds = array();
			$curOffset = 0;
			
			preg_replace_callback($re,
			function ($matches) use (&$cmds, &$re, &$stringToParse, &$curOffset, &$fullMatch){
				//////var_dump($matches);
				
				$fullMatch = str_replace("&gt;", ">   ",$matches[0]);
				$cmdName = str_replace("&gt;", ">   ",$matches[1]);
				$parameters = str_replace("&gt;", ">   ",$matches[2]);
				
				preg_match($re, $stringToParse, $idxFinds, PREG_OFFSET_CAPTURE, $curOffset);
				
				$theIdx = $idxFinds[0][1];
				$curOffset = $theIdx + strlen($fullMatch);
				
				if ( class_exists($cmdName) && in_array("PiCommand", class_parents($cmdName) ))
				{
					
					$theCmd = new $cmdName($parameters);
					
					$theCmd->fullString = $fullMatch;
					$theCmd->textIndex = $theIdx;
					
					if ($theCmd->isPrivate())
					{
						//echo("Command is private: " . $cmdName . "\n");
					} 
					
					else
					{
						array_push($cmds, $theCmd);
					}
					
				} 
				
				else
				{
					//echo("Command not defined: " . $cmdName . "\n");
				}
				
			},
			$str);
			
			//////var_dump($cmds);
			
			return $cmds;
		}
		
		
	}
	
	// Additions to Parser specific to page contexts
	class PageParser extends Parser
	{
		var $pageText = "";
		var $allCmds = array();
		
		public function __construct($text) {
			$this->pageText = $text;
			//$this->allCmds = $this->extractPiCommandsFromString($text);
		}
				
		
		function getTextBetweenCmds($cmd1, $cmd2) {
			$startIdx = $cmd1->textIndex;
			$endIdx = $cmd2->textIndex;
						
			$adjStart = $startIdx + strlen($cmd1->fullString);
			//echo substr($this->pageText, $adjStart, $endIdx - $adjStart);
			return substr($this->pageText, $adjStart, $endIdx - $adjStart);
		}
		
		function setupIterators() {
			
			$initTerm = array();
			
			$cmds = $this->allCmds;
			
			foreach ($cmds as $idx => $value) { // 1
				if ($value->isInitializer()) { //2
					
					foreach ($cmds as $idx2 => $value2) { //3
						
						if ($idx2 > $idx) { //4
							if ($value->isCorrectTerminator($value2)) { //5
								$value->textContent = $this->getTextBetweenCmds($value, $value2);
								array_push($initTerm, $value);
								break;
							} ///5
							
						} ///4
						
					} ///3
					
					
				} ///2
				if (count($initTerm) > 0) {
					break;
				}
			} ///1
			
			////var_dump($initTerm);
			
			$initLen = strlen($this->pageText);
			
			foreach ($initTerm as $key => $value) {
				//echo strlen($value->textContent);
				
				$this->pageText = substr_replace($this->pageText, "", $value->textIndex + strlen($value->fullString), strlen($value->textContent));
				$newLen = strlen($this->pageText);
				$subAmt = $initLen - $newLen;
				
				foreach ($this->allCmds as $key2 => $value2) {
					if ($value2->textIndex > $value->textIndex) {
						$this->allCmds[$key2]->textIndex -= $subAmt;
					}
				}
				
				$initLen = $newLen;
			}
			
			foreach ($initTerm as $key => $value) {
				$this->pageText = $value->fillWithResult($this->pageText);
				break; // Just one for now
			}
			
			//////var_dump($this->allCmds);
			//echo($this->pageText);
			return $this->pageText;
			
		}
		
		public function fullParse() {
			return fullParsePi($this->pageText);
			
			$this->allCmds = $this->extractPiCommandsFromString($this->pageText);
			$itrsFinished = false;
			
			while (!$itrsFinished) {
				//////var_dump($this->pageText);
				//echo("hrer\n");
				$tempTxt = $this->pageText;
				//var_dump($this->pageText);
				$this->pageText = $this->setupIterators();
				$itrsFinished = $this->pageText == $tempTxt;
				$this->allCmds = $this->extractPiCommandsFromString($this->pageText);
			}
						
			while (count($this->allCmds) > 0) {
				$last = array_pop($this->allCmds);
				$this->pageText = $last->fillWithResult($this->pageText);
				
				$this->allCmds = $this->extractPiCommandsFromString($this->pageText);
			}
			
			return $this->pageText;
		}
		
	}
	
	//PiPress::createSampleData();
	//////var_dump(PiPress::getVar("piSchemas"));
	//$obj = PiPress::getVar("api");
	
	
	//*/
	////var_dump(PiPress::getVar("api.members.get"));
	//die();
	//PiPress::writeObjectParts($obj);
	//die();
	// Tests
	//var_dump();
	//$str = "the_content()";//"<!DOCTYPE html>\n<html>\n<body>\n\n<h4>Card unordered list (manual):</h4>\n<ul>\n  <li>Best Buy</li>\n  <li>PF Chang's</li>\n  <li>Coldstone</li>\n</ul>\n\n<h4>Card unordered list (piPress):</h4>\n<ul>\n  [piIteratorStart:member.wallet.cards->card]\n\n <li>[piIterator:card]</li>\n  [piIteratorEnd:card]\n</ul>\n\n</body>\n</html>"; 
	//var_dump($str);
	//$pP = new PageParser($str);
	
	//echo("hrer1\n");
	
	//$newPage = $pP->fullParse();
	//echo("hrer2\n");
	//file_put_contents("orig.html", $str);
	//var_dump( $newPage);
	//file_put_contents("parsed.html", $newPage);	
	//echo $newPage;
	
	//$cmdsInString = Parser::extractPiCommandsFromString("[piVar:something] [piIteratorStart:Var.ArrayProp -> itr] [piNotAFunc:2-3] [piIteratorStart:blah->itr] should mess up [piIterator:itr] [piIteratorEnd:itr]");
	
?>