<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"language"));


include_once($projectroot."includes/functions.php");

if(isset($defaultlanguage) && file_exists($projectroot."language/".$defaultlanguage.".php")) include_once($projectroot."language/".$defaultlanguage.".php");
else include_once($projectroot."language/en.php");

// todo: language choice
//include_once($projectroot."language/en.php");
//include_once($projectroot."language/gd.php");
//include_once($projectroot."language/chartest.php");

// get lang for key string
function getlang($element)
{
	global $lang;
	if(array_key_exists($element,$lang)) return $lang[$element];
	else return "[".$element."]";
}

// get lang from array of key strings
function getlangarray($element,$index)
{
	global $lang;
	$result = $lang[$element][$index];
	//$result = utf8_decode($lang[$element]);
	
  /*$text=stripslashes(utf8_encode($text));
  $text=preg_replace("/&amp;#(.*);/U","&#\\1;",$text); // restore unicode characters
  $text=str_replace("&amp;nbsp;","&nbsp;",$text); // restore &nbsp;
  
	
 //$result=preg_replace("/&amp;#(.*);/U","&#\\1;",$result); // restore unicode characters
  $title=str_replace("&amp;nbsp;","&nbsp;",$title); // restore &nbsp;
  $title=str_replace('"',"&quot;",$title); // quotes
	*/
	
	return $result;
}

?>
