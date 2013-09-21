<?php

// *************************************************************************
// Conversion functions
// *************************************************************************

//
//
//
function striptitletags($title)
{
  	$title=stripslashes(utf8_encode($title));
  
    $title=str_replace('<','&lt;', $title);
  	$title=str_replace('>','&gt;', $title);
  //$title=preg_replace("/&amp;#(.*);/U","&#\\1;",$title); // restore unicode characters
  //$title=str_replace("&amp;nbsp;","&nbsp;",$title); // restore &nbsp;
  //$title=str_replace('"',"&quot;",$title); // quotes
// strip 'em
  $patterns = array(
           "/\[b\](.*?)\[\/b\]/si",
           "/\[u\](.*?)\[\/u\]/si",
           "/\[i\](.*?)\[\/i\]/si",
           "/\[style=(.*?)\](.*?)\[\/style\]/si",
           "/\[color=(.*?)\](.*?)\[\/color\]/si"
       );
  $replacements = array(
           "\\1",
           "\\1",
           "\\1",
           " - \\2",
           " - \\2"
       );
  $title = preg_replace($patterns,$replacements, $title);

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
  $text=stripslashes(utf8_encode($text));
  // lt and gt
  $text=str_replace('<','&lt;', $text);
  $text=str_replace('>','&gt;', $text);
  
  
  //$text = str_replace('&amp;','&', $text);
//info at atjeff dot co dot nz
// http://de.php.net/manual/en/function.preg-replace.php

// bbcode to html
  $patterns = array(
           "/\[link\](.*?)\[\/link\]/i",
           "/\[url\](.*?)\[\/url\]/i",
           "/\[url=(.*?)\](.*?)\[\/url\]/i",

           "/\[style=(.*?)\](.*?)\[\/style\]/si",
           "/\[color=(.*?)\](.*?)\[\/color\]/si",
           "/\[img\](.*?)\[\/img\]/i",
           "/\[b\](.*?)\[\/b\]/si",
           "/\[u\](.*?)\[\/u\]/si",
           "/\[i\](.*?)\[\/i\]/si"
       );
  $replacements = array(
           "<a href=\"\\1\" target=\"_blank\">\\1</a>",
           "<a href=\"\\1\" target=\"_blank\">\\1</a>",
           "<a href=\"\\1\" target=\"_blank\">\\2</a>",

           "<span class=\"\\1\">\\2</span>",
           "<font color=\"\\1\">\\2</font>",
           "<img src=\"\\1\">",
           "<b>\\1</b>",
           "<u>\\1</u>",
           "<i>\\1</i>"
       );
  $text = preg_replace($patterns,$replacements, $text);
  
  
  // parsing nested lists
  
  while(preg_match("/\[list\](.*?)\[\/list\]/si",$text)
     || preg_match("/\[list=(.*?)\](.*?)\[\/list\]/si",$text))
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
  


// adam at releod dot com
// auto URL
$text = preg_replace("/(http:\/\/(.*)\/)[\s]/", "<a href=\\1>\\1</a> ", $text);
//$text = preg_replace("/(http:\/\/([w|\/|\.]*)[\W])/", "dummy", $text);

//remove sid from local links
  $patterns = array(
           "/http:(.*?)".getproperty("Domain Name")."(.*?)(sid=)(\w*?)(&)/",
           "/http:(.*?)".getproperty("Domain Name")."(.*?)(\?sid=|&sid=)(\w*?)(\W|\s|$)/",
           "/(".str_replace("/","\/",getprojectrootlinkpath()).")(index|.*admin.*|.*includes.*|.*functions.*)(.php)(.*)/"
       );
  $replacements = array(
           "http:\\2",
           "http:\\2\\5",
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
  array_push($preserve,'b');
  array_push($preserve,'i');
  array_push($preserve,'a');
  array_push($preserve,'img');
  array_push($preserve,'span');
  array_push($preserve,'p');
  array_push($preserve,'br');
  array_push($preserve,'div');
  array_push($preserve,'center');
  array_push($preserve,'em');
  array_push($preserve,'strong');
  array_push($preserve,'pre');
  array_push($preserve,'ul');
  array_push($preserve,'ol');
  array_push($preserve,'li');
  array_push($preserve,'dl');
  array_push($preserve,'dt');
  array_push($preserve,'dd');
  array_push($preserve,'hr');
  array_push($preserve,'embed');
  array_push($preserve,'object');

  for($i=0;$i<count($preserve);$i++)
  {
    $pattern='/\&lt;'.$preserve[$i].'(.*?)\&gt;/';
    $text=preg_replace($pattern,"<".$preserve[$i]."\\1>", $text);
    $pattern='/\&lt;(\/)'.$preserve[$i].'(.*?)\&gt;/';
    $text=preg_replace($pattern,"<\\1".$preserve[$i]."\\2>", $text);
  }
  //unicode characters
//  $text=preg_replace("/&amp;#(.*);/U","&#\\1;",$text);

  // line break
  $text=nl2br($text);
  
  // copyright
  $text = str_replace('((C))','&copy;', $text);
  return $text;
}


//
// remove backslashes and whitespaces etc.
//
function cleanupfilename($filename)
{
  return preg_replace("/[^\.A-Z0-9_-]/i","_",stripslashes($filename));
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
function makecategorylist($categories,$printheader=true)
{
  $result="";
  $categorynames=getcategorynamessorted($categories);

  $catlistoutput=implode(", ",$categorynames);
  if($printheader) $result.='<p><span class="highlight">Categories: </span>';
  $result.='<span class="gen">';

  if($catlistoutput)
  {
    $result.=title2html($catlistoutput);
  }
  elseif($printheader) $result.="None";

  if($printheader) $result.='</p>';
  $result.='</span>';
  return $result;
}


//
//
//
function makearticledate($day,$month,$year)
{
  $result="";
  if($year!="0000")
  {
    $result=$year;

    if($month)
    {
      $month=getlangarray("date_month",$month);
      $result=$month.' '.$result;
      if($day)
      {
        $result=$day.' '.$result;
      }
    }
  }

  return $result;
}

//
//
//
function calculateimagedimensions($filename,$factor=1)
{
  if(!($factor>=0)) $factor=1;
  $resized=false;
  if(file_exists($filename)&& filetype($filename)=="file")
  {
    $imageproperties=@getimagesize($filename);
    $width=$imageproperties[0];
    $height=$imageproperties[1];

    if($factor>0)
    {
      $maxdimension=MAXIMAGEDIMENSION/$factor+1;

      if($width>$maxdimension)
      {
        $resized=true;
        $test=ceil($width / $maxdimension);
        $width=floor($width/$test);
        $height=floor($height/$test);
      }
      if($height>$maxdimension)
      {
        $resized=true;
        $test=ceil($height / $maxdimension);
        $width=floor($width/$test);
        $height=floor($height/$test);
      }
    }
    return array("width" => $width, "height" => $height, "resized" => $resized);
  }
  else return array("width" => 0, "height" => 0, "resized" => false);
}

//
//
//
function getimagelinkpath($filename)
{
  $localpath=getproperty("Local Path");
  $domain=getproperty("Domain Name");
  $imagepath=getproperty("Image Upload Path");
  $result='http://'.$domain.'/';
  if($localpath) $result.=$localpath.'/';
  return $result.$imagepath.'/'.rawurlencode(basename($filename));
}



//
//
//
function getbannerlinkpath($filename)
{
  $localpath=getproperty("Local Path");
  $domain=getproperty("Domain Name");
  $result='http://'.$domain.'/';
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
  $result='http://'.$domain.'/';
  if($localpath) $result.=$localpath.'/';
  return $result;
}

//
//
//
function makelinkparameters($assoc_array, $showSID=false)
{
  $params="";
  $counter=0;
//  print_r($assoc_array);
  if(count($assoc_array>0))
  {
    $keys=array_keys($assoc_array);
//  print_r($keys);
    $values=array_values($assoc_array);
    if(!$showSID && array_key_exists(0, $keys) && ($keys[0]==="sid"))
    {
      $counter++;
    }
//    print("test".$keys[$counter]."test");
    if(array_key_exists($counter, $keys))
    {
      $params.="?".$keys[$counter]."=".$values[$counter];
    }
    $counter++;
    for($i=$counter;$i<count($keys);$i++)
    {
      if($showSID || !($keys[$i]==="sid"))
      {
        if(strlen($values[$i])>0)
          $params.="&".$keys[$i]."=".$values[$i];
      }
    }
  }
  return $params;
}


//
//
function getclientip()
{
  $clientip=$_SERVER['REMOTE_ADDR'];
  $result=($clientip === long2ip(ip2long($clientip)));
  if($result)
  {
    $result=ip2long($clientip);
  }
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
?>
