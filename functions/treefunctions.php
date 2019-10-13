<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"functions"));

include_once($projectroot."functions/db.php");
include_once($projectroot."functions/pages.php");
include_once($projectroot."functions/publicsessions.php");
include_once($projectroot ."config.php");

if(isset($_GET["sid"])) $user=getpublicsiduser($_GET["sid"]);
else $user=0;

$sql = new SQLSelectStatement(PAGES_TABLE,
	array(
		'page_id',
		'parent_id',
		'title_navigator',
		'title_page',
		'position_navigator',
		'pagetype',
		'ispublished'
	));
$sql->set_order(array('parent_id' => 'ASC', 'position_navigator' => 'ASC'));
$allpages = $sql->fetch_many_rows();

$sql = new SQLSelectStatement(RESTRICTEDPAGES_TABLE, array('page_id', 'masterpage'));
$sql->set_order(array('page_id' => 'ASC'));
$allrestrictedpages = $sql->fetch_many_rows();

$sql = new SQLSelectStatement(RESTRICTEDPAGESACCESS_TABLE, 'page_id');
$directrestrictedpagesaccess = $sql->fetch_column();

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
	foreach ($allpages as $key => $checkpage) {
		if ($checkpage['parent_id'] == $page) {
			array_push($result, $key);
		}
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
// When creating a new page, the array might be out of date
//
function ispageknownarray($page)
{
	global $allpages;
	return array_key_exists($page, $allpages);
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
