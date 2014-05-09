<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"functions"));

include_once($projectroot."functions/db.php");
include_once($projectroot."functions/pages.php");
include_once($projectroot."functions/publicsessions.php");
include_once($projectroot ."config.php");


if(isset($_GET["sid"])) $user=getpublicsiduser($_GET["sid"]);
else $user=0;


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
$directrestrictedpagesaccess=getmultiplefields(RESTRICTEDPAGESACCESS_TABLE, "page_id","publicuser_id = '".$user."'", array(0 => "page_id"), $orderby="page_id");

//print_r($allpages[5]);

//
//
//
function getpagetypearray($page)
{
	global $allpages;
	return $allpages[$page]['pagetype'];
}

//
//
//
function getpagetitlearray($page)
{
	global $allpages;
	return $allpages[$page]['title_page'];
}

//
//
//
function getnavtitlearray($page)
{
	global $allpages;
	return $allpages[$page]['title_navigator'];
}

//
//
//
function getnavpositionarray($page)
{
	global $allpages;
	return $allpages[$page]['position_navigator'];
}

//
//
//
function getparentarray($page)
{
	global $allpages;
	return $allpages[$page]['parent_id'];
}

//
//
//
function getchildrenarray($page,$ascdesc="ASC")
{
	global $allpages;
	$result=array();
	reset($allpages);
	while($checkpage=current($allpages))
	{
		if($checkpage['parent_id']==$page)
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
function ispublishedarray($page)
{
	global $allpages;
	return $allpages[$page]['ispublished'];
}


//
//
//
function isrootpagearray($page)
{
	global $allpages;
	if($page>0) return $allpages[$page]['parent_id']==0;
	else return false;
}




//
//
//
function displaylinksforpagearray($page)
{
	global $user, $sid;
	return (ispublishedarray($page) && (!ispagerestrictedarray($page) || hasaccesssession($page)));
}

//
//
//
function ispagerestrictedarray($page)
{
	global $allrestrictedpages;
	return array_key_exists($page,$allrestrictedpages);
}

//
// use only for current logged in public user!!
//
function hasaccessarray($page)
{
	global $allrestrictedpages, $directrestrictedpagesaccess;
	$result=true;
	if(array_key_exists($page,$allrestrictedpages))
	{
		$masterpage = $allrestrictedpages[$page]["masterpage"];
		if(array_key_exists($masterpage,$directrestrictedpagesaccess)) $result=false;
	}
	return $result;
}
?>
