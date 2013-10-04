<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/dbmod.php");

//
//  todo: restrictions
// todo: return error state
//
function createpage($parent_id, $title, $navtitle, $pagetype, $user_id, $ispublishable)
{
	global $db;
	
	$parent_id=$db->setinteger($parent_id);
	$title=$db->setstring($title);
	$navtitle=$db->setstring($navtitle);
	$pagetype=$db->setstring($pagetype);
	$user_id=$db->setinteger($user_id);
	$ispublishable=$db->setinteger($ispublishable);
	
	if(!$parent_id)
	{
		$parent_id=0;
	}
	$lastnavposition=1+create_getlastnavposition($parent_id);
	
	$date=date(DATETIMEFORMAT);
	
	$values=array();
	$values[]=0;
	$values[]=$parent_id;
	$values[]=$navtitle;
	$values[]=$title;
	$values[]=""; // Introtext
	$values[]=""; // Introimage
	$values[]="left"; // Intro image halign
	$values[]=$lastnavposition;
	$values[]=$pagetype;
	$values[]=$date;
	$values[]=$user_id;
	$values[]="";
	$values[]="";
	$values[]=NO_PERMISSION;
	$values[]=0;
	$values[]=$ispublishable;
	$values[]=0;
  
	$newpage = insertentry(PAGES_TABLE,$values);
	if($newpage)
	{
		$page_id=getdbelement("page_id",PAGES_TABLE, "editdate", $date);
		
		if($pagetype==="article")
		{
			createemptyarticle($page_id);
		}
		elseif($pagetype==="external")
		{
			createemptyexternal($page_id);
		}
		elseif($pagetype==="menu" || $pagetype==="articlemenu" || $pagetype==="linklistmenu")
		{
			createemptymenu($page_id);
		}
		elseif($pagetype==="news")
		{
			createemptynewspage($page_id);
		}
	}
	
	$parentrestricted=getdbelement("page_id",RESTRICTEDPAGES_TABLE, "page_id", $parent_id);
	if($parentrestricted==$parent_id && $parent_id!=0)
	{
		insertentry(RESTRICTEDPAGES_TABLE,array(0=>$page_id, 1=>getpagerestrictionmaster($parent_id)));
	}
	return $newpage;
}

//
//
//
function create_getlastnavposition($pageid)
{
	global $db;
	return getmax("position_navigator",PAGES_TABLE, "parent_id = '".$db->setinteger($pageid)."'");
}

//
//
//
function createemptyarticle($page_id)
{
	global $db;
	$now=getdate(strtotime('now'));
	
	$values=array();
	$values[]=$db->setinteger($page_id);
	$values[]=''; // author
	$values[]=''; // location
	$values[]=''; // source
	$values[]=''; // sourcelink
	$values[]=$now['mday']; // day
	$values[]=$now['mon']; // month
	$values[]=$now['year']; // year
	$values[]=1; // noofpages
	$values[]=0; // use Table of contents
	
	return insertentry(ARTICLES_TABLE,$values);
}

//
//
//
function createemptyexternal($page_id)
{
	global $db;
	return insertentry(EXTERNALS_TABLE,array(0=>$db->setinteger($page_id), 1=>''));
}


//
//
//
function createemptymenu($page_id)
{
	global $db;
	$values=array();
	$values[]=$db->setinteger($page_id);
	$values[]='1'; // navigatordepth
	$values[]='2'; // displaydepth
	$values[]='1'; // sistersinnavigator
	
	return insertentry(MENUS_TABLE,$values);
}


//
//
//
function createemptynewspage($page_id)
{
	global $db;
	$values=array();
	$values[]=$db->setinteger($page_id);
	$values[]='1';
	
	return insertentry(NEWS_TABLE,$values);
}
?>