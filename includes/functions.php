<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
include_once($projectroot."functions/db.php");

// *************************************************************************
// Conversion functions
// *************************************************************************

//
//
//
function striptitletags($title)
{
	//$title=stripslashes($title);
	$title=stripslashes(utf8_encode($title));

	//$title=preg_replace("/&amp;#(.*);/U","&#\\1;",$title); // restore unicode characters
	//$title=str_replace("&amp;nbsp;","&nbsp;",$title); // restore &nbsp;
	//$title=str_replace('"',"&quot;",$title); // quotes
	// strip 'em
	$patterns = array(
		"/\[link\](.*?)\[\/link\]/i",
		"/\[url\](.*?)\[\/url\]/i",
		'/\[url="(.*?)"\](.*?)\[\/url\]/i',
		"/\[url=(.*?)\](.*?)\[\/url\]/i",

		"/\[b\](.*?)\[\/b\]/si",
		"/\[u\](.*?)\[\/u\]/si",
		"/\[i\](.*?)\[\/i\]/si",
		"/\[style=(.*?)\](.*?)\[\/style\]/si",
		"/\[color=(.*?)\](.*?)\[\/color\]/si",
		"/\[img\](.*?)\[\/img\]/i",
	);
	$replacements = array(
		"\\1",
		"\\1",
		"\\2",
		"\\2",

		"\\1",
		"\\1",
		"\\1",
		"\\2",
		"\\2",
		""
	);
	$title = preg_replace($patterns,$replacements, $title);
    $title=str_replace('<','&lt;', $title);
  	$title=str_replace('>','&gt;', $title);

	// Remove HTML tags
	// todo: allowtags in site properties
	$remove=array();
	array_push($remove,'a');
	array_push($remove,'b');
	array_push($remove,'br');
	array_push($remove,'caption');
	array_push($remove,'center');
	array_push($remove,'dd');
	array_push($remove,'div');
	array_push($remove,'dl');
	array_push($remove,'dt');
	array_push($remove,'em');
	array_push($remove,'embed');
	array_push($remove,'hr');
	array_push($remove,'i');
	array_push($remove,'img');
	array_push($remove,'li');
	array_push($remove,'object');
	array_push($remove,'ol');
	array_push($remove,'p');
	array_push($remove,'pre');
	array_push($remove,'span');
	array_push($remove,'strong');
	array_push($remove,'sub');
	array_push($remove,'sup');
	array_push($remove,'table');
	array_push($remove,'td');
	array_push($remove,'th');
	array_push($remove,'tr');
	array_push($remove,'ul');

	for($i=0;$i<count($remove);$i++)
	{
		$pattern='/\&lt;'.$remove[$i].'(.*?)\&gt;/';
		$title=preg_replace($pattern,"", $title);
		$pattern='/\&lt;(\/)'.$remove[$i].'(.*?)\&gt;/';
		$title=preg_replace($pattern,"", $title);
	}


	// copyright
	$title = str_replace('((C))','&copy;', $title);
	return $title;
}

//
//
//
function title2html($title)
{
  	$title=stripslashes(utf8_encode($title));

    $title=str_replace('<','&lt;', $title);
  	$title=str_replace('>','&gt;', $title);

  //$title=preg_replace("/&amp;#(.*);/U","&#\\1;",$title); // restore unicode characters
  //$title=str_replace("&amp;nbsp;","&nbsp;",$title); // restore &nbsp;
  //$title=str_replace('"',"&quot;",$title); // quotes
// bbcode to html
	$patterns = array(
		"/\[b\](.*?)\[\/b\]/si",
		"/\[u\](.*?)\[\/u\]/si",
		"/\[i\](.*?)\[\/i\]/si",
		"/\[style=(.*?)\](.*?)\[\/style\]/si"
	);
	$replacements = array(
		"<b>\\1</b>",
		"<u>\\1</u>",
		"<i>\\1</i>",
		"<span class=\"\\1\">\\2</span>"
	);
	$title = preg_replace($patterns,$replacements, $title);

	// copyright
	$title = str_replace('((C))','&copy;', $title);
	return $title;
}



//
//
//
function edittitle2html($title)
{
  	$title=stripslashes($title);

    $title=str_replace('<','&lt;', $title);
  	$title=str_replace('>','&gt;', $title);
	// bbcode to html
	$patterns = array(
		"/\[b\](.*?)\[\/b\]/si",
		"/\[u\](.*?)\[\/u\]/si",
		"/\[i\](.*?)\[\/i\]/si",
		"/\[style=(.*?)\](.*?)\[\/style\]/si"
	);
	$replacements = array(
		"<b>\\1</b>",
		"<u>\\1</u>",
		"<i>\\1</i>",
		"<span class=\"\\1\">\\2</span>"
	);
	$title = preg_replace($patterns,$replacements, $title);

	// copyright
	$title = str_replace('((C))','&copy;', $title);
	return $title;
}


//
// strip shlashes and handle unicode
//
function input2html($text)
{
 	$text=stripslashes(utf8_encode($text));
  	// lt and gt
  	$text=str_replace('<','&lt;', $text);
  	$text=str_replace('>','&gt;', $text);

	return $text;
}


//
//
//
function text2html($text)
{
	global $_GET;

	$text=utf8_encode($text);
	$text=stripslashes(stripslashes($text));
	// lt and gt
	$text=str_replace('<','&lt;', $text);
	$text=str_replace('>','&gt;', $text);

	//info at atjeff dot co dot nz
	// http://de.php.net/manual/en/function.preg-replace.php


	// strip color tags for print view
	if(isset($_GET['printview'])) $text = preg_replace("/\[color=(.*?)\](.*?)\[\/color\]/si", "\\2", $text);
	else $text = preg_replace("/\[color=(.*?)\](.*?)\[\/color\]/si", "<span style=\"color:\\1\">\\2</span>", $text);

	// bbcode to html
	$patterns = array(
		"/\[link\](.*?)\[\/link\]/i",
		"/\[url\](.*?)\[\/url\]/i",
		'/\[url="(.*?)"\](.*?)\[\/url\]/i',
		"/\[url=(.*?)\](.*?)\[\/url\]/i",

		"/\[style=(.*?)\](.*?)\[\/style\]/si",
		"/\[img\](.*?)\[\/img\]/i",
		"/\[b\](.*?)\[\/b\]/si",
		"/\[u\](.*?)\[\/u\]/si",
		"/\[i\](.*?)\[\/i\]/si"
	);
	$replacements = array(
		"<a href=\"\\1\" target=\"_blank\">\\1</a>",
		"<a href=\"\\1\" target=\"_blank\">\\1</a>",
		"<a href=\\1 target=\"_blank\">\\2</a>",
		"<a href=\"\\1\" target=\"_blank\">\\2</a>",

		"<span class=\"\\1\">\\2</span>",
		"<img src=\"\\1\">",
		"<b>\\1</b>",
		"<u>\\1</u>",
		"<i>\\1</i>"
	);
	$text = preg_replace($patterns,$replacements, $text);


	// parsing nested lists
	while(preg_match("/\[list\](.*?)\[\/list\]/si",$text) || preg_match("/\[list=(.*?)\](.*?)\[\/list\]/si",$text))
	{
    	$patterns = array(
			"/\[list\](.*?)\[\/list\]/si",
			"/\[list=(.*?)\](.*?)\[\/list\]/si",
			"/\[\*\](.*?)/s"
		);
		$replacements = array(
			"<ul>\\1</ul>",
			"<ol type=\"\\1\">\\2</ol>",
			"<li>\\1"
		);
		$text = preg_replace($patterns,$replacements, $text);
	}


	// tables
	$patterns = array(
		"/\[tr\](\s+?)\[/si",
		"/\[\/tr\](\s+?)\[/si",

		"/\[td\](\s+?)\[/si",
		"/\[\/td\](\s+?)\[/si",

		"/\[th\](\s+?)\[/si",
		"/\[\/th\](\s+?)\[/si",

		"/\[caption\](\s+?)\[/si",
		"/\[\/caption\](\s+?)\[/si",

		"/\[table\](\s+?)\[/si",
		"/\](\s+?)\[\/table\]/si",
	);
	$replacements = array(
		"[tr][",
		"[/tr][",

		"[td][",
		"[/td][",

		"[th][",
		"[/th][",

		"[caption][",
		"[/caption][",

		"[table][",
		"][/table]",
	);
	$text = preg_replace($patterns,$replacements, $text);

	while(preg_match("/\[table(.*?)\](.*?)\[tr\](.*?)\[td(.*?)\](.*?)\[\/td\](.*?)\[\/tr\](.*?)\[\/table\]/si",$text))
	{
		$patterns = array(
			"/\[table(.*?)\](.*?)\[tr\](.*?)\[td(.*?)\](.*?)\[\/td\](.*?)\[\/tr\](.*?)\[\/table\]/si",
		);
		$replacements = array(
			"[table\\1]\\2[tr]\\3<td\\4>\\5</td>\\6[/tr]\\7[/table]",
		);
		$text = preg_replace($patterns,$replacements, $text);
	}
	$patterns = array(
		"/\[tr\](.*?)\[\/tr\]/si",
		"/\[th(.*?)\](.*?)\[\/th\]/si",
		"/\[caption\](.*?)\[\/caption\]/si",
		"/\[table(.*?)\](.*?)\[\/table\]/si",
	);
	$replacements = array(
		"<tr>\\1</tr>",
		"<th\\1>\\2</th>",
		"<caption>\\1</caption>",
		"<table\\1>\\2</table>",
	);
	$text = preg_replace($patterns,$replacements, $text);

	// Auto URL
	$text = preg_replace("/(https?:\/\/(\S*))(\/?)(\s|$)/", '<a href="\\1\\3">\\2</a>\\4', $text);

	//remove sid from local links
	$serverprotocol=getproperties()['Server Protocol'];
	$patterns = array(
		"/http(s){0,1}:(.*?)".getproperty("Domain Name")."(.*?)(sid=)(\w*?)(&)/",
		"/http(s){0,1}:(.*?)".getproperty("Domain Name")."(.*?)(\?sid=|&sid=)(\w*?)(\W|\s|$)/",
		"/(".str_replace("/","\/",getprojectrootlinkpath()).")(index|.*admin.*|.*includes.*|.*functions.*)(.php)(.*)/"
	);
	$replacements = array(
		$serverprotocol.getproperty("Domain Name")."\\3",
		$serverprotocol.getproperty("Domain Name")."\\3\\6",
		"\\4"
	);
	$text = preg_replace($patterns,$replacements, $text);

	// remove target="_blank" from internal links
	$patterns = array(
		"/(\")(\?)(.*)(target=\"_blank\")/"
	);
	$replacements = array(
		"\\1\\2\\3"
	);
	$text = preg_replace($patterns,$replacements, $text);

	// restore HTML tags
	// todo: allowtags in site properties
	$preserve=array();
	array_push($preserve,'a');
	array_push($preserve,'b');
	array_push($preserve,'br');
	array_push($preserve,'caption');
	array_push($preserve,'center');
	array_push($preserve,'dd');
	array_push($preserve,'div');
	array_push($preserve,'dl');
	array_push($preserve,'dt');
	array_push($preserve,'em');
	array_push($preserve,'embed');
	array_push($preserve,'hr');
	array_push($preserve,'i');
	array_push($preserve,'img');
	array_push($preserve,'li');
	array_push($preserve,'object');
	array_push($preserve,'ol');
	array_push($preserve,'p');
	array_push($preserve,'pre');
	array_push($preserve,'span');
	array_push($preserve,'strong');
	array_push($preserve,'sub');
	array_push($preserve,'sup');
	array_push($preserve,'table');
	array_push($preserve,'td');
	array_push($preserve,'th');
	array_push($preserve,'tr');
	array_push($preserve,'ul');

	for($i=0;$i<count($preserve);$i++)
	{
		$pattern='/\&lt;'.$preserve[$i].'(.*?)\&gt;/';
		$text=preg_replace($pattern,"<".$preserve[$i]."\\1>", $text);
		$pattern='/\&lt;(\/)'.$preserve[$i].'(.*?)\&gt;/';
		$text=preg_replace($pattern,"<\\1".$preserve[$i]."\\2>", $text);
	}

	// line break
	$text=nl2br($text);

	// copyright
	$text = str_replace('((C))','&copy;', $text);
	return $text;
}


// get rid of qurly quotes
function fixquotes($text)
{

    // get rid of smart quotes etc. http://www.toao.net/48-replacing-smart-quotes-and-em-dashes-in-mysql
    // First, replace UTF-8 characters.
    $text = str_replace(
 		array("\xe2\x80\x98", "\xe2\x80\x99", "\xe2\x80\x9c", "\xe2\x80\x9d", "\xe2\x80\x93", "\xe2\x80\x94", "\xe2\x80\xa6"),
 		array("'", "'", '"', '"', '-', '--', '...'),
 		$text);
	// Next, replace their Windows-1252 equivalents.
 	$text = str_replace(
 		array(chr(145), chr(146), chr(147), chr(148), chr(150), chr(151), chr(133)),
 		array("'", "'", '"', '"', '-', '--', '...'),
		$text);
	return htmlentities($text, ENT_QUOTES, 'UTF-8', false);
}


//
// remove backslashes and whitespaces etc.
//
function cleanupfilename($filename)
{
	return strtolower(preg_replace("/[^\.A-Z0-9_-]/i","_",stripslashes($filename)));
}


// *************************************************************************
// Comparison functions
// *************************************************************************

//
// true if $string ends with $suffix
//
function str_endswith($string, $suffix)
{
	return strlen($suffix)>0 && substr($string,strlen($string)-strlen($suffix))==$suffix;
}

//
// true if $string starts with $prefix
//
function str_startswith($string, $prefix)
{
	return strlen($prefix)>0 && substr($string,0,strlen($prefix))==$prefix;
}

//
// true if string $haystack contains string $needle
//
function str_containsstr($haystack, $needle)
{
	return strlen($needle)>0 && strpos($haystack,$needle) >=0;
}

// *************************************************************************
// Page element and image functions
// *************************************************************************


//
//
//
function makearticledate($day,$month,$year)
{
	$result="";

	if($year != "0000")
	{
		if($month)
		{
			if($day)
			{
				$result = lang_date_day_format($day)." ".getlangarray("date_month_format", $month)." ".$year;
			}
			else
			{
				$result = getlangarray("date_month", $month)." ".$year;
			}
		}
		else
		{
			$result = $year;
		}
	}
	return $result;
}


//
//
//
function getimagedimensions($filepath)
{
	$width=0;
	$height=0;

	if(file_exists($filepath) && filetype($filepath)=="file")
	{
		$imageproperties=@getimagesize($filepath);
		$width=$imageproperties[0];
		$height=$imageproperties[1];
	}
	return array("width" => $width, "height" => $height);
}



//
//
//
function calculateimagedimensions($filepath, $autoshrink=false)
{
	$result = getimagedimensions($filepath);
	$result["resized"] = false;

	if($autoshrink)
	{
		// todo Mobile Thumbnail Size
		if($result["width"] > getproperty("Thumbnail Size"))
		{
			$result["resized"] = true;
			$factor = ceil($result["width"] / getproperty("Thumbnail Size")); // add a little more because captioned images are framed
			$result["width"] = floor($result["width"] / $factor);
			$result["height"] = floor($result["height"] / $factor);
		}
		if($result["height"]>getproperty("Thumbnail Size"))
		{
			$result["resized"] = true;
			$factor = ceil($result["height"] / getproperty("Thumbnail Size"));
			$result["width"] = floor($result["width"] / $factor);
			$result["height"] = floor($result["height"] / $factor);
		}
	}
	return $result;
}

//
//
//
function getimagelinkpath($filename,$subpath)
{
	$localpath=getproperty("Local Path");
	$domain=getproperty("Domain Name");
	$imagepath=getproperty("Image Upload Path");
	$result=getproperties()['Server Protocol'].$domain.'/';
	if($localpath) $result.=$localpath.'/';
	$result.=$imagepath.$subpath.'/'.rawurlencode(basename($filename));
	return $result;
}

//
//
//
function getimagepath($filename)
{
	global $projectroot;
	return $projectroot.getproperty("Image Upload Path").getimagesubpath(basename($filename)).'/'.$filename;
}

//
//
//
function getthumbnailpath($imagefilename, $thumbnailfilename)
{
	global $projectroot;
	return $projectroot.getproperty("Image Upload Path").getimagesubpath(basename($imagefilename)).'/'.$thumbnailfilename;
}


//
//
//
function getbannerlinkpath($filename)
{
	$localpath=getproperty("Local Path");
	$domain=getproperty("Domain Name");
	$result=getproperties()['Server Protocol'].$domain.'/';
	if($localpath) $result.=$localpath.'/';
	return $result.'img/banners/'.rawurlencode(basename($filename));
}


//
//
//
function getprojectrootlinkpath()
{
	$localpath=getproperty("Local Path");
	$domain=getproperty("Domain Name");
	$result=getproperties()['Server Protocol'].$domain.'/';
	if($localpath) $result.=$localpath.'/';
	return $result;
}

//
//
//
function makelinkparameters($assoc_array, $include_sid=true)
{
	global $sid;
	$params="";
	$keys=array_keys($assoc_array);
	$key = current($keys);
	if($key)
	{
		$params.="?".$key."=".$assoc_array[$key];

		while($key = next($keys))
		{
			if(!($key === "sid" && strlen($sid) > 0))
			{
				if(var_is_array($assoc_array[$key]))
				{
					$subkeys=array_keys($assoc_array[$key]);
					while($subkey = next($subkeys))
					{
						$params.="&".$key."=".$assoc_array[$key][$subkey];
					}
				}
				else
				{
					if(strlen($assoc_array[$key]) > 0)
						$params.="&".$key."=".$assoc_array[$key];
				}
			}
		}
		if($include_sid && strlen($sid) > 0)
		{
			$params.="&sid=".$sid;
		}
	}
	elseif($include_sid && strlen($sid) > 0)
	{
		$params.="?sid=".$sid;
	}
	return $params;
}


//
// returns true if the var is an array
//
function var_is_array($var)
{
	return ( (array) $var == $var);
}


//
//
function getclientip()
{
	$clientip=$_SERVER['REMOTE_ADDR'];
	$result=($clientip === long2ip(ip2long($clientip)));
	if($result) $result=ip2long($clientip);
	return $result;
}


// seed with microseconds
function make_seed()
{
	list($usec, $sec) = explode(' ', microtime());
	return (float) $sec + ((float) $usec * 100000);
}


//
// for efficiency testing
//
function microtime_float()
{
	if (@phpversion() < '5.0.0')
	{
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}
	else
	{
		return microtime(true);
	}
}

//
//
//
function ismobile()
{
	global $_GET;
	return isset($_GET["m"]);
}
?>
