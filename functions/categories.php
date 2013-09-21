<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"functions"));

include_once($projectroot."functions/db.php");

################################################################################
##                                                                            ##
##        Functions                                                           ##
##                                                                            ##
################################################################################


function getallcategorieswithname()
{
  return getmultiplefields(CATEGORIES_TABLE, "category_id","1", array(0 => '*'), $orderby="parent_id, name");
}

//
//
//
function getcategorynamessorted($categories)
{
  if(count($categories>0))
  {
    $condition="category_id IN ('".implode($categories,"', '")."')";
    return getorderedcolumn("name", CATEGORIES_TABLE, $condition, "name");
  }
  else return array();
}

//
//
//
function getcategoryname($catid)
{
	global $db;
  return getdbelement("name",CATEGORIES_TABLE, "category_id", $db->setinteger($catid));
}

//
//
//
function getcategorychildren($catid)
{
	global $db;
  return getorderedcolumn("category_id",CATEGORIES_TABLE,"parent_id = '".$db->setinteger($catid)."'","name","ASC");
}


//
//
//
function getcategoryparent($catid)
{
	global $db;
  return getdbelement("parent_id",CATEGORIES_TABLE, "category_id",$db->setinteger($catid));
}

//
//
//
function isroot($catid)
{
  $parentid=getcategoryparent($catid);
  return $parentid==0;
}


//
//
//
function getcategoryimages($catid)
{
	global $db;
  return getorderedcolumn("image_filename",IMAGECATS_TABLE,"category = '".$db->setinteger($catid)."'","image_filename","ASC");
}

//
//
//
function getcategorypages($catid)
{
	global $db;
  return getorderedcolumn("page_id",PAGECATS_TABLE,"category = '".$db->setinteger($catid)."'", "page_id","ASC");
}

//
//
//
function getcategorynewsitems($catid)
{
	global $db;
  return getorderedcolumn("newsitem_id",NEWSITEMCATS_TABLE,"category = '".$db->setinteger($catid)."'", "newsitem_id","ASC");
}


//
//
//
function getcategoriesforimage($filename)
{
	global $db;
  return getcolumn("category",IMAGECATS_TABLE, "image_filename = '".$db->setstring($filename)."'");
}

//
//
//
function getcategoriesforpage($page_id)
{
	global $db;
  return getcolumn("category",PAGECATS_TABLE, "page_id = '".$db->setinteger($page_id)."'");
}

//
//
//
function getcategoriesfornewsitem($newsitem_id)
{
	global $db;
  return getcolumn("category",NEWSITEMCATS_TABLE, "newsitem_id = '".$db->setinteger($newsitem_id)."'");
}

?>
