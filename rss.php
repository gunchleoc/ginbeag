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
	header("HTTP/1.0 404 Not Found");
	print("HTTP 404: Sorry, but this page does not exist.");
	exit;
}

// check legal vars
include_once($projectroot."includes/legalvars.php");

include_once($projectroot."functions/pages.php");
include_once($projectroot."includes/objects/rss.php");

$page=$_GET['page'];

if(hasrssfeed($page))
{
	$printme = new RSSPage($page);
	print($printme->toHTML());
}
else
{
	header("HTTP/1.0 404 Not Found");
	$sitename=getproperty("Site Name");
	$title=title2html($sitename.' - '.getnavtitle($page));
	$rootlink=getprojectrootlinkpath();
	$link=$rootlink.'index.php?page='.$page;
	print('HTTP 404: Sorry, but there is no RSS-Feed available for this page.<p class="highlight"><a href="'.$link.'">Return to '.$title.'</a></p>');
}
$db->closedb();
?>
