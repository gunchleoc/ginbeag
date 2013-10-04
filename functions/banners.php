<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"functions"));

include_once($projectroot."functions/db.php");

################################################################################
##                                                                            ##
##        Functions                                                           ##
##                                                                            ##
################################################################################

// ***************************  article ************************************* //


//
//
//
function getbannercontents($banner_id)
{
	global $db;
	return getrowbykey(BANNERS_TABLE, "banner_id", $db->setinteger($banner_id));
}

//
//
//
function getbanners()
{
	return getorderedcolumn("banner_id", BANNERS_TABLE, 1, "position", "ASC");
}

//
//
//
function isbannercomplete($banner_id)
{
	$contents=getbannercontents($banner_id);
	$result=true;
	if(!strlen($contents['image'])>0 || !strlen($contents['description'])>0 || !strlen($contents['link'])>0)
	{
		$result=false;
	}
	if(!$result && strlen($contents['code'])>0) $result=true;
	return $result;
}
?>
