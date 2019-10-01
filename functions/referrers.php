<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"functions"));

include_once($projectroot."functions/db.php");

//
//
//
function isreferrerblocked($referrer)
{
	global $db;

	$query="select referrerurl from ".BLOCKEDREFERRERS_TABLE;
	$sql=$db->singlequery($query);
	$result=false;
	while(!$result && $row=$sql->fetch_row())
	{
		$result=strpos($referrer,$row[0]);
	}
	return $result;
}
?>
