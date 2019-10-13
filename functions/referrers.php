<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"functions"));

include_once($projectroot."functions/db.php");

//
//
//
function isreferrerblocked($referrer) {
	$sql = new SQLSelectStatement(BLOCKEDREFERRERS_TABLE, 'referrerurl', array('referrerurl'), array($referrer), 's');
	return !empty($sql->fetch_value());
}
?>
