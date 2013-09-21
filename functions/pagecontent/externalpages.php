<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"pagecontent"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"functions"));

include_once($projectroot."functions/db.php");

//
//
//
function getexternallink($page_id)
{
	global $db;
  return getdbelement("link",EXTERNALS_TABLE, "page_id", $db->setinteger($page_id));
}

?>