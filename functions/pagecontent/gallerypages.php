<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"pagecontent"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"functions"));

include_once($projectroot."functions/db.php");

//
//
//
function getgalleryimage($galleryitem)
{
	global $db;
	return getdbelement("image_filename",GALLERYITEMS_TABLE, "galleryitem_id",$db->setinteger($galleryitem));
}

//
//
//
function getgalleryimages($page)
{
	global $db;
	return getorderedcolumn("galleryitem_id",GALLERYITEMS_TABLE, "page_id='".$db->setinteger($page)."'", "position", "ASC");
}


//
//
//
function getgalleryimageslimit($page, $offset, $number)
{
	global $db;
	return getorderedcolumnlimit("galleryitem_id",GALLERYITEMS_TABLE, "page_id='".$db->setinteger($page)."'", "position", $db->setinteger($offset), $db->setinteger($number), "ASC");
}

//
//
//
function getgalleryimagefilenames($page)
{
	global $db;
	return getorderedcolumn("image_filename",GALLERYITEMS_TABLE, "page_id='".$db->setinteger($page)."'", "position", "ASC");
}


//
//
//
function getgalleryimageposition($galleryitem)
{
	global $db;
	return getdbelement("position", GALLERYITEMS_TABLE, "galleryitem_id",$db->setinteger($galleryitem));
}

//
//
//
function getlastgalleryimageposition($page)
{
	global $db;
	return getmax("position", GALLERYITEMS_TABLE, "page_id ='".$db->setinteger($page)."'");
}

//
//
//
function countgalleryimages($page)
{
	global $db;
	return countelementscondition("page_id", GALLERYITEMS_TABLE, "page_id ='".$db->setinteger($page)."'");
}
?>
