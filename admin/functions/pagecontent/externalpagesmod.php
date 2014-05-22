<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"pagecontent"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."functions/db.php");

//
//
//
function updateexternallink($page, $link)
{
	global $db;
	return updatefield(EXTERNALS_TABLE,"link",$db->setstring($link) ,"page_id='".$db->setinteger($page)."'");
}

?>