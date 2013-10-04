<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"functions"));

include_once($projectroot."functions/db.php");
include_once($projectroot."functions/pages.php");
include_once($projectroot."functions/publicsessions.php");
include_once($projectroot ."config.php");


if(isset($_GET["sid"])) $user_id=getpublicsiduser($_GET["sid"]);
else $user_id=0;


################################################################################
##                                                                            ##
##        Functions                                                           ##
##                                                                            ##
################################################################################

$fields=array();
$fields[]='page_id';
$fields[]='parent_id';
$fields[]='title_navigator';
$fields[]='title_page';
$fields[]='position_navigator';
$fields[]='pagetype';
$fields[]='ispublished';

$allpages=getmultiplefields(PAGES_TABLE, "page_id","1", $fields, $orderby="parent_id, position_navigator");

$allrestrictedpages=getmultiplefields(RESTRICTEDPAGES_TABLE, "page_id","1", array(0 => "page_id", 1 => "masterpage"), $orderby="page_id");
$directrestrictedpagesaccess=getmultiplefields(RESTRICTEDPAGESACCESS_TABLE, "page_id","publicuser_id = '".$user_id."'", array(0 => "page_id"), $orderby="page_id");

//print_r($allpages[5]);

//
//
//
function getpagetypearray($page_id)
{
	global $allpages;
	return $allpages[$page_id]['pagetype'];
}

//
//
//
function getpagetitlearray($page_id)
{
	global $allpages;
	return $allpages[$page_id]['title_page'];
}

//
//
//
function getnavtitlearray($page_id)
{
	global $allpages;
	return $allpages[$page_id]['title_navigator'];
}

//
//
//
function getnavpositionarray($page_id)
{
	global $allpages;
	return $allpages[$page_id]['position_navigator'];
}

//
//
//
function getparentarray($page_id)
{
	global $allpages;
	return $allpages[$page_id]['parent_id'];
}

//
//
//
function getchildrenarray($page_id,$ascdesc="ASC")
{
	global $allpages;
	$result=array();
	reset($allpages);
	while($checkpage=current($allpages))
	{
		if($checkpage['parent_id']==$page_id)
		{
			array_push($result,$checkpage['page_id']);
		}
		next($allpages);
	}
	return $result;
}

//
//
//
function ispublishedarray($page_id)
{
	global $allpages;
	return $allpages[$page_id]['ispublished'];
}


//
//
//
function isrootpagearray($page_id)
{
	global $allpages;
	if($page_id>0) return $allpages[$page_id]['parent_id']==0;
	else return false;
}




//
//
//
function displaylinksforpagearray($sid,$page_id)
{
	global $user_id;
	return (ispublishedarray($page_id) && (!ispagerestrictedarray($page_id) || hasaccesssession($sid, $page_id)));
}

//
//
//
function ispagerestrictedarray($page_id)
{
	global $allrestrictedpages;
	return array_key_exists($page_id,$allrestrictedpages);
}

//
// use only for current logged in public user!!
//
function hasaccessarray($page_id)
{
	global $allrestrictedpages, $directrestrictedpagesaccess;
	$result=true;
	if(array_key_exists($page_id,$allrestrictedpages))
	{
		$masterpage = $allrestrictedpages[$page_id]["masterpage"];
		if(array_key_exists($masterpage,$directrestrictedpagesaccess)) $result=false;
	}
	return $result;
}
?>
