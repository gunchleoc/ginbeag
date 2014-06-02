<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"pagecontent"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"functions"));

include_once($projectroot."functions/db.php");

//
//
//
function getarticlepagecontents($page)
{
	global $db;
	return getrowbykey(ARTICLES_TABLE, "page_id", $db->setinteger($page));
}



//
//
//
function numberofarticlepages($page)
{
	global $db;
	return getdbelement("numberofpages",ARTICLES_TABLE, "page_id", $db->setinteger($page));
}


//
//
//
function getlastarticlesection($page,$pagenumber)
{
	global $db;
	$condition="article_id ='".$db->setinteger($page)."' and pagenumber ='".$db->setinteger($pagenumber)."'";
	return getmax("sectionnumber",ARTICLESECTIONS_TABLE, $condition);
}


//
// the section number on the page. Not the primary key!!!
//
function getarticlesections($page, $pagenumber)
{
	global $db;
	$condition= "(article_id='".$db->setinteger($page)."'";
	$condition.= " AND pagenumber='".$db->setinteger($pagenumber)."')";
	return getorderedcolumn("articlesection_id",ARTICLESECTIONS_TABLE, $condition, "sectionnumber", "ASC");
}



//
// for printview
//
function getallarticlesections($page)
{
	global $db;
	$condition= "article_id='".$db->setinteger($page)."'";
	return getorderedcolumn("articlesection_id",ARTICLESECTIONS_TABLE, $condition, "pagenumber, sectionnumber", "ASC");
}

//
//
//
function getarticlesectioncontents($articlesection)
{
	global $db;
	return getrowbykey(ARTICLESECTIONS_TABLE, "articlesection_id", $db->setinteger($articlesection));
}

//
//
//
function getarticlesectiontitle($articlesection)
{
	global $db;
	return getdbelement("sectiontitle",ARTICLESECTIONS_TABLE, "articlesection_id", $db->setinteger($articlesection));
}

//
//
//
function getarticlesectiontext($articlesection)
{
	global $db;
	return getdbelement("text",ARTICLESECTIONS_TABLE, "articlesection_id", $db->setinteger($articlesection));
}

//
//
//
function getarticlesectionnumber($articlesection)
{
	global $db;
	return getdbelement("sectionnumber",ARTICLESECTIONS_TABLE, "articlesection_id", $db->setinteger($articlesection));
}

?>
