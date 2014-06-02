<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."functions/db.php");
include_once($projectroot."functions/referrers.php");

//
//
//
function getblockedreferrers()
{
	return getorderedcolumn("referrerurl",BLOCKEDREFERRERS_TABLE,"1","referrerurl","ASC");
}

//
//
//
function addblockedreferrer($referrer)
{
	global $db;
	if(!isreferrerblocked($referrer) && strlen($referrer)>1)
	{
		$values=array();
		$values[]=$db->setstring($referrer);
		return insertentry(BLOCKEDREFERRERS_TABLE,$values);
	}
	else
	{
		print($referrer." is already blocked");
		return false;
	}
}



//
//
//
function deleteblockedreferrer($referrer)
{
	global $db;
	deleteentry(BLOCKEDREFERRERS_TABLE,"referrerurl = '".$db->setstring($referrer)."'");
}

?>
