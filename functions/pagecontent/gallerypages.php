<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "pagecontent"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "functions"));

require_once $projectroot."functions/db.php";

//
//
//
function getgalleryimage($galleryitem) 
{
    $sql = new SQLSelectStatement(GALLERYITEMS_TABLE, 'image_filename', array('galleryitem_id'), array($galleryitem), 'i');
    return $sql->fetch_value();
}

//
//
//
function getgalleryimages($page) 
{
    $sql = new SQLSelectStatement(GALLERYITEMS_TABLE, 'galleryitem_id', array('page_id'), array($page), 'i');
    $sql->set_order(array('position' => 'ASC'));
    return $sql->fetch_column();
}


//
//
//
function getgalleryimageslimit($page, $offset, $number) 
{
    $sql = new SQLSelectStatement(GALLERYITEMS_TABLE, 'galleryitem_id', array('page_id'), array($page), 'i');
    $sql->set_order(array('position' => 'ASC'));
    $sql->set_limit($number, $offset);
    return $sql->fetch_column();
}

//
//
//
function getgalleryimagefilenames($page) 
{
    $sql = new SQLSelectStatement(GALLERYITEMS_TABLE, 'image_filename', array('page_id'), array($page), 'i');
    $sql->set_order(array('position' => 'ASC'));
    return $sql->fetch_column();
}


//
//
//
function getgalleryimageposition($galleryitem) 
{
    $sql = new SQLSelectStatement(GALLERYITEMS_TABLE, 'position', array('galleryitem_id'), array($galleryitem), 'i');
    return $sql->fetch_value();
}

//
//
//
function getlastgalleryimageposition($page) 
{
    $sql = new SQLSelectStatement(GALLERYITEMS_TABLE, 'position', array('page_id'), array($page), 'i');
    $sql->set_operator('max');
    $result = $sql->fetch_value();
    if (!$result) { $result = 1;
    }
    return $result;
}

//
//
//
function countgalleryimages($page) 
{
    $sql = new SQLSelectStatement(GALLERYITEMS_TABLE, 'page_id', array('page_id'), array($page), 'i');
    $sql->set_operator('count');
    return $sql->fetch_value();
}
?>
