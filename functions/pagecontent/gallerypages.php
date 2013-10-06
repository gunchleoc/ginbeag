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
function getgalleryimagefilenames($page, $showrefused,$showhidden=false)
{
	global $db;
	$result=array();
	
	if($showrefused || $showhidden)
	{
		$result=getorderedcolumn("image_filename",GALLERYITEMS_TABLE, "page_id='".$db->setinteger($page)."'", "position", "ASC");
	}
	else
	{
		$query="select gallery.image_filename from ".GALLERYITEMS_TABLE." as gallery, ".IMAGES_TABLE." as images where ";
		$query.="gallery.page_id='".$db->setinteger($page)."' AND gallery.image_filename = images.image_filename AND images.permission <> ".PERMISSION_REFUSED." order by position ASC";
		$result = getdbresultcolumn($query);
	}
	return $result;
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