<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"functions"));

include_once($projectroot."functions/db.php");

################################################################################
##                                                                            ##
##        Functions                                                           ##
##                                                                            ##
################################################################################


function getallcategorieswithname($cattype)
{
	if($cattype==CATEGORY_NEWS) $table = CATEGORIES_NEWS_TABLE;
	elseif($cattype==CATEGORY_ARTICLE) $table = CATEGORIES_ARTICLES_TABLE;
	else  $table = CATEGORIES_IMAGES_TABLE;
	return getmultiplefields($table, "category_id","1", array(0 => '*'), $orderby="parent_id, name");
}

//
//
//
function getcategorynamessorted($categories, $cattype)
{
	if($cattype==CATEGORY_NEWS) $table = CATEGORIES_NEWS_TABLE;
	elseif($cattype==CATEGORY_ARTICLE) $table = CATEGORIES_ARTICLES_TABLE;
	else  $table = CATEGORIES_IMAGES_TABLE;

	if(count($categories>0))
	{
		$condition="category_id IN ('".implode($categories,"', '")."')";
		return getorderedcolumn("name", $table, $condition, "name");
	}
	else return array();
}

//
//
//
function getcategoryname($catid, $cattype)
{
	global $db;
	if($cattype==CATEGORY_NEWS) $table = CATEGORIES_NEWS_TABLE;
	elseif($cattype==CATEGORY_ARTICLE) $table = CATEGORIES_ARTICLES_TABLE;
	else  $table = CATEGORIES_IMAGES_TABLE;

	return getdbelement("name",$table, "category_id", $db->setinteger($catid));
}

//
//
//
function getcategorychildren($catid, $cattype)
{
	global $db;
	if($cattype==CATEGORY_NEWS) $table = CATEGORIES_NEWS_TABLE;
	elseif($cattype==CATEGORY_ARTICLE) $table = CATEGORIES_ARTICLES_TABLE;
	else  $table = CATEGORIES_IMAGES_TABLE;

	return getorderedcolumn("category_id",$table,"parent_id = '".$db->setinteger($catid)."'","name","ASC");
}


//
//
//
function getcategoryparent($catid, $cattype)
{
	global $db;
	if($cattype==CATEGORY_NEWS) $table = CATEGORIES_NEWS_TABLE;
	elseif($cattype==CATEGORY_ARTICLE) $table = CATEGORIES_ARTICLES_TABLE;
	else  $table = CATEGORIES_IMAGES_TABLE;

	return getdbelement("parent_id",$table, "category_id",$db->setinteger($catid));
}

//
//
//
function isroot($catid, $cattype)
{
	$parentid=getcategoryparent($catid, $cattype);
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
	return getorderedcolumn("page_id",ARTICLECATS_TABLE,"category = '".$db->setinteger($catid)."'", "page_id","ASC");
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
function getcategoriesforpage($page)
{
	global $db;
	return getcolumn("category",ARTICLECATS_TABLE, "page_id = '".$db->setinteger($page)."'");
}

//
//
//
function getcategoriesfornewsitem($newsitem)
{
	global $db;
	return getcolumn("category",NEWSITEMCATS_TABLE, "newsitem_id = '".$db->setinteger($newsitem)."'");
}

?>
