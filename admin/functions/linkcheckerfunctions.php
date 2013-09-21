<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/dbmod.php");
include_once($projectroot."functions/users.php");
include_once($projectroot."functions/pages.php");

################################################################################
##                                                                            ##
##        Functions                                                           ##
##                                                                            ##
################################################################################

//
//
//
function getallarticlepagesandsourcelinks()
{
  return getmultiplefields(ARTICLES_TABLE, "page_id","1",array(0 => 'page_id', 1 => 'sourcelink', 2 => 'source'));
}

//
//
//
function getalltextfieldsforarticle($page)
{
  $result=getarticlesynopsis($page);
  $sections= getorderedcolumn("text", ARTICLESECTIONS_TABLE,"article_id = '".setinteger($page)."'","sectionnumber","ASC");
  $noofsecs=count($sections);
  for($i=0;$i<$noofsecs;$i++)
  {
    $result.=' '.$sections[$i];
  }
  return $result;
}

//
//
//
function getallgalleryintrotexts()
{
  return getmultiplefields(GALLERIES_TABLE, "page_id","1",array(0 => 'page_id', 1 => 'introtext'));
}

//
//
//
function getallexternallinks()
{
  return getmultiplefields(EXTERNALS_TABLE, "page_id","1",array(0 => 'page_id', 1 => 'link'));
}

//
//
//
function getalllinklistlinks()
{
  return getmultiplefields(LINKS_TABLE, "link_id","1",array(0 => 'link_id', 1 => 'link', 2=> 'title', 3=> 'page_id'));
}

//
//
//
function getallmenupageswithintro()
{
  return getmultiplefields(MENUS_TABLE, "page_id","1",array(0 => 'page_id', 1 => 'introtext'));
}

//
//
//
function getallnewspages()
{
  return getorderedcolumn("page_id", PAGES_TABLE,"pagetype = 'news'","page_id","ASC");
}

//
//
//
function getnewsitemandsourcelinks($page)
{
  return getmultiplefields(NEWSITEMS_TABLE, "newsitem_id","page_id = '".setinteger($page)."'",array(0 => 'newsitem_id', 1 => 'sourcelink', 2 => 'source'));
}

//
//
//
function getnewsitemsectiontexts($newsitem)
{
  $result="";
  $sections= getorderedcolumn("text", NEWSITEMSECTIONS_TABLE,"newsitem_id = '".setinteger($newsitem)."'","sectionnumber","ASC");
  $noofsecs=count($sections);
  for($i=0;$i<$noofsecs;$i++)
  {
    $result.=' '.$sections[$i];
  }
  return $result;
}

?>
