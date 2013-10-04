<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"pagecontent"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/dbmod.php");

//
//
//
function updateexternallink($page_id, $link)
{
	global $db;
	return updatefield(EXTERNALS_TABLE,"link",$db->setstring($link) ,"page_id='".$db->setinteger($page_id)."'");
}

?>