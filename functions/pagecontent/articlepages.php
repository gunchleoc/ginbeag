<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"pagecontent"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"functions"));

include_once($projectroot."functions/db.php");

//
//
//
function getarticlepagecontents($page_id)
{
	global $db;
  return getrowbykey(ARTICLES_TABLE, "page_id", $db->setinteger($page_id));
}



//
//
//
function numberofarticlepages($page_id)
{
	global $db;
  return getdbelement("numberofpages",ARTICLES_TABLE, "page_id", $db->setinteger($page_id));
}


//
//
//
function getlastarticlesection($article_id,$pagenumber)
{
	global $db;
  $condition="article_id ='".$db->setinteger($article_id)."' and pagenumber ='".$db->setinteger($pagenumber)."'";
  return getmax("sectionnumber",ARTICLESECTIONS_TABLE, $condition);
}


//
// the section number on the page. Not the primary key!!!
//
function getarticlesections($page_id, $pagenumber)
{
	global $db;
  $condition= "(article_id='".$db->setinteger($page_id)."'";
  $condition.= " AND pagenumber='".$db->setinteger($pagenumber)."')";
  
  return getorderedcolumn("articlesection_id",ARTICLESECTIONS_TABLE, $condition, "sectionnumber", "ASC");
}



//
// for printview
//
function getallarticlesections($page_id)
{
	global $db;
  $condition= "article_id='".$db->setinteger($page_id)."'";
  return getorderedcolumn("articlesection_id",ARTICLESECTIONS_TABLE, $condition, "pagenumber, sectionnumber", "ASC");
}

//
//
//
function getarticlesectioncontents($section_id)
{
	global $db;
  return getrowbykey(ARTICLESECTIONS_TABLE, "articlesection_id", $db->setinteger($section_id));
}

//
//
//
function getarticlesectiontitle($section_id)
{
	global $db;
  return getdbelement("sectiontitle",ARTICLESECTIONS_TABLE, "articlesection_id", $db->setinteger($section_id));
}

//
//
//
function getarticlesectiontext($section_id)
{
	global $db;
  return getdbelement("text",ARTICLESECTIONS_TABLE, "articlesection_id", $db->setinteger($section_id));
}

//
//
//
function getarticlesectionnumber($section_id)
{
	global $db;
  return getdbelement("sectionnumber",ARTICLESECTIONS_TABLE, "articlesection_id", $db->setinteger($section_id));
}

?>