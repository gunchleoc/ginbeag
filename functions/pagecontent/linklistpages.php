<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"pagecontent"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"functions"));

include_once($projectroot."functions/db.php");

//
//
//
function getlinklistitems($page)
{
	global $db;
	return getorderedcolumn("link_id",LINKS_TABLE, "page_id='".$db->setinteger($page)."'", "position", "ASC");
}

//
//
//
function getlinktitle($link)
{
	global $db;
	return getdbelement("title",LINKS_TABLE, "link_id", $db->setinteger($link));
}

//
//
//
function getlinkcontents($link)
{
	global $db;
	return getrowbykey(LINKS_TABLE, "link_id", $db->setinteger($link));
}

//
//
//
function getlastlinkposition($page)
{
	global $db;
	return getmax("position",LINKS_TABLE, "page_id ='".$db->setinteger($page)."'");
}

//
//
//
function getlinkdescription($link)
{
	global $db;
	return getdbelement("description",LINKS_TABLE, "link_id", $db->setinteger($link));
}
?>
