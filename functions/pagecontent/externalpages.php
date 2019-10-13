<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"pagecontent"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"functions"));

include_once($projectroot."functions/db.php");

//
//
//
function getexternallink($page) {
	$sql = new SQLSelectStatement(EXTERNALS_TABLE, 'link', array('page_id'), array($page), 'i');
	return $sql->fetch_value();
}

?>
