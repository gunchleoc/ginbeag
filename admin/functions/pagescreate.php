<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/dbmod.php");

//
//  todo: restrictions
// todo: return error state
//
function createpage($parent, $title, $navtitle, $pagetype, $user, $ispublishable)
{
	global $db;
	
	$parent=$db->setinteger($parent);
	$title=$db->setstring($title);
	$navtitle=$db->setstring($navtitle);
	$pagetype=$db->setstring($pagetype);
	$user=$db->setinteger($user);
	$ispublishable=$db->setinteger($ispublishable);
	
	if(!$parent)
	{
		$parent=0;
	}
	$lastnavposition=1+create_getlastnavposition($parent);
	
	$date=date(DATETIMEFORMAT);
	
	$values=array();
	$values[]=0;
	$values[]=$parent;
	$values[]=$navtitle;
	$values[]=$title;
	$values[]=""; // Introtext
	$values[]=""; // Introimage
	$values[]="left"; // Intro image halign
	$values[]=$lastnavposition;
	$values[]=$pagetype;
	$values[]=$date;
	$values[]=$user;
	$values[]="";
	$values[]="";
	$values[]=NO_PERMISSION;
	$values[]=0;
	$values[]=$ispublishable;
	$values[]=0;
  
	$newpage = insertentry(PAGES_TABLE,$values);
	if($newpage)
	{
		$page=getdbelement("page_id",PAGES_TABLE, "editdate", $date);
		
		if($pagetype==="article")
		{
			createemptyarticle($page);
		}
		elseif($pagetype==="external")
		{
			createemptyexternal($page);
		}
		elseif($pagetype==="menu" || $pagetype==="articlemenu" || $pagetype==="linklistmenu")
		{
			createemptymenu($page);
		}
		elseif($pagetype==="news")
		{
			createemptynewspage($page);
		}
	}
	
	$parentrestricted=getdbelement("page_id",RESTRICTEDPAGES_TABLE, "page_id", $parent);
	if($parentrestricted==$parent && $parent!=0)
	{
		insertentry(RESTRICTEDPAGES_TABLE,array(0=>$page, 1=>getpagerestrictionmaster($parent)));
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
function createemptyarticle($page)
{
	global $db;
	$now=getdate(strtotime('now'));
	
	$values=array();
	$values[]=$db->setinteger($page);
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
function createemptyexternal($page)
{
	global $db;
	return insertentry(EXTERNALS_TABLE,array(0=>$db->setinteger($page), 1=>''));
}


//
//
//
function createemptymenu($page)
{
	global $db;
	$values=array();
	$values[]=$db->setinteger($page);
	$values[]='1'; // navigatordepth
	$values[]='2'; // displaydepth
	$values[]='1'; // sistersinnavigator
	
	return insertentry(MENUS_TABLE,$values);
}


//
//
//
function createemptynewspage($page)
{
	global $db;
	$values=array();
	$values[]=$db->setinteger($page);
	$values[]='1';
	
	return insertentry(NEWS_TABLE,$values);
}
?>