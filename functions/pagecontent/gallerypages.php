<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"pagecontent"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"functions"));

include_once($projectroot."functions/db.php");

//
//
//
function getgalleryimage($galleryitem_id)
{
	global $db;
  return getdbelement("image_filename",GALLERYITEMS_TABLE, "galleryitem_id",$db->setinteger($galleryitem_id));
}

//
//
//
function getgalleryimages($page_id)
{
	global $db;
  return getorderedcolumn("galleryitem_id",GALLERYITEMS_TABLE, "page_id='".$db->setinteger($page_id)."'", "position", "ASC");
}

//
//
//
function getgalleryimagefilenames($page_id, $showrefused,$showhidden=false)
{
	global $db;
  $result=array();
  
  if($showrefused || $showhidden)
  {
    $result=getorderedcolumn("image_filename",GALLERYITEMS_TABLE, "page_id='".$db->setinteger($page_id)."'", "position", "ASC");
  }
  else
  {
    $query="select gallery.image_filename from ".GALLERYITEMS_TABLE." as gallery, ".IMAGES_TABLE." as images where ";
    $query.="gallery.page_id='".$db->setinteger($page_id)."' AND gallery.image_filename = images.image_filename AND images.permission <> ".PERMISSION_REFUSED." order by position ASC";
    $sql=$db->singlequery($query);
    if($sql)
    {
      while($row=mysql_fetch_row($sql))
      {
        array_push($result,$row[0]);
      }
    }
  }
  return $result;
}


//
//
//
function getgalleryimageposition($galleryitem_id)
{
	global $db;
	return getdbelement("position", GALLERYITEMS_TABLE, "galleryitem_id",$db->setinteger($galleryitem_id));
}

//
//
//
function getlastgalleryimageposition($page_id)
{
	global $db;
  return getmax("position", GALLERYITEMS_TABLE, "page_id ='".$db->setinteger($page_id)."'");
}

//
//
//
function countgalleryimages($page_id)
{
	global $db;
	return countelementscondition("page_id", GALLERYITEMS_TABLE, "page_id ='".$db->setinteger($page_id)."'");
}
?>