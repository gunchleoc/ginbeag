<?php

$projectroot=dirname(__FILE__)."/";

include_once($projectroot."functions/db.php");

// anti bot nonsense links
// ********************************* achtung - bot secure ist server-spezifisch!
$testpath = "/".getproperty("Local Path");
if(getproperty("Local Path") == "") $testpath = "";

if(!((isset($_SERVER["ORIG_PATH_TRANSLATED"]) && $_SERVER["ORIG_PATH_TRANSLATED"] == $projectroot."rss.php") ||
	$_SERVER["PHP_SELF"] == $testpath."/rss.php"))
{
//	print("test: ".$_SERVER["PHP_SELF"]);
	header("HTTP/1.0 404 Not Found");
	print("HTTP 404: Sorry, but this page does not exist.");
	exit;
}

include_once($projectroot."includes/functions.php");
include_once($projectroot."includes/includes.php");
include_once($projectroot."functions/pages.php");
include_once($projectroot."functions/pagecontent/newspages.php");

$page=$_GET['page'];
$rootlink=getprojectrootlinkpath();
$sitename=getproperty("Site Name");

$title=title2html($sitename.' - '.getnavtitle($page));
$link=$rootlink.'index.php?page='.$page;

if(hasrssfeed($page))
{
	$description=title2html(getpagetitle($page));
	$language="en-us";
	
	$permissions=getcopyright($page);
	$copyright=title2html($permissions['copyright']);
	
	$imageurl=getproperty("Left Header Image");
	if(!$imageurl) $imageurl=getproperty("Right Header Image");
	$imageurl=$rootlink.'img/'.$imageurl;
	$imagetitle=title2html($sitename);
	$imagelink=$rootlink;
	
	
	// get newsitems, needed here for pubdate
	$newsitemsperpage=getproperty("News Items Per Page");
	if(!($newsitemsperpage>0)) $newsitemsperpage = 5;
	$newsitems=getpublishednewsitems($page,$newsitemsperpage,0);
	
	$contents=getnewsitemcontents($newsitems[0]);
	$pubDate=@date("r", strtotime($contents['date']));
	$lastBuildDate=@date("r", strtotime(geteditdate($page)));
	//$category=title2html(getproperty("Google Keywords"));
	
	//header( "Content-type: text/xml" );
	
	print('<');
	print('?xml version="1.0" encoding="utf-8"?');
	print('>');
  //<<!DOCTYPE rss SYSTEM "http://my.netscape.com/publish/formats/rss-0.91.dtd">
?>

<rss version="0.91" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#">
  <channel>


<title><![CDATA[<?php print(html2xml($title));?>]]></title>
<link><![CDATA[<?php print($link);?>]]></link>
<description><![CDATA[<?php print(html2xml($description));?>]]></description>
<language><?php print($language);?></language>
<?php
	if($copyright) print('<copyright><![CDATA['.html2xml($copyright).']]></copyright>');
?>
<image>
<url><![CDATA[<?php print($imageurl);?>]]></url>
<title><![CDATA[<?php print(html2xml($imagetitle));?>]]></title>
<link><![CDATA[<?php print($imagelink);?>]]></link>
</image>

<pubDate><?php print($pubDate);?></pubDate>
<lastBuildDate><?php print($lastBuildDate);?></lastBuildDate>

<?php

	for($i=0;$i<count($newsitems);$i++)
	{
		$contents=getnewsitemcontents($newsitems[$i]);
		//  print_r($contents);
		//  print('<br />');
		$title=title2html($contents['title']);
		$description=text2html($contents['synopsis']);
		if($contents['source'])
		{
			$source.=title2html($contents['source']);
			$description.='<p>Source: '.title2html($contents['source']).'</p>';
		}
		$sourcelink=$contents['sourcelink'];
		if(str_startswith($sourcelink,'?page='))
		{
			$sourcelink=$rootlink.'index.php'.$sourcelink;
		}
		$pubDate=@date("r", strtotime($contents['date']));
  
/*  $categories=getcategoriesfornewsitem($newsitems[$i]);
  $categorynames=array();
  for($j=0;$j<count($categories);$j++)
  {
    array_push($categorynames,title2html(getcategoryname($categories[$j])));
  }
  sort($categorynames);
  $catlistoutput=implode(", ",$categorynames);*/
?>

<item>
  <title><![CDATA[<?php print(html2xml($title));?>]]></title>
  <description><![CDATA[<?php print(html2xml($description));?>]]></description>
<?php
	    if($sourcelink)
	    {
			print('<link><![CDATA['.$sourcelink.']]></link>');
	    }
	    else
	    {
			print('<link><![CDATA['.$link.']]></link>');
	    }
	    if($source && $sourcelink)
	    {
			print('<source url="'.link2xml($sourcelink).'"><![CDATA[');
			print(html2xml($source));
			print(']]></source>');
	    }
?>
  <pubDate><?php print($pubDate);?></pubDate>
  
  <guid><?php print($rootlink.$newsitems[$i].'-'.$pubDate);?></guid>
</item>

<?php
	}
}
else
{
//	print("test: ".$_SERVER["PHP_SELF"]);
	header("HTTP/1.0 404 Not Found");
	print('HTTP 404: Sorry, but there is no RSS-Feed available for this page.<p class="highlight"><a href="'.$link.'">Return to '.$title.'</a></p>');
	exit;
}



$db->closedb();

?>
 </channel>
</rss>

<?php

function html2xml($text)
{
	return strip_tags($text,'<p> </p> <br> <br />');
}

function link2xml($link)
{
	$url=parse_url($link);
    $result="";
    if($url['scheme']) $result.=$url['scheme'].'://';
    elseif($url['host']) $result.='http://';
    $result.=$url['host'];
    return $result;
}
?>
