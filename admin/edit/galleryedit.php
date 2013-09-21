<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/functions/pagesmod.php");
include_once($projectroot."admin/edit/edittext.php");
include_once($projectroot."includes/includes.php");
include_once($projectroot."includes/functions.php");
include_once($projectroot."admin/includes/adminelements.php");
include_once($projectroot."admin/includes/templates/adminforms.php");
include_once($projectroot."admin/includes/templates/adminelements.php");
include_once($projectroot."admin/includes/templates/admingallerypage.php");


$sid=$_GET['sid'];
checksession($sid);

$page=$_GET['page'];

$offset=0;
if(isset($_GET['offset'])) $offset=$_GET['offset'];

$imagesperpage=6;
if(isset($_GET['showall']) || isset($_POST['showall']))
  $showall=true;
else $showall=false;

$message="";

//print_r($_POST);
//print_r($_GET);

// *************************** actions ************************************** //

// page content actions

$pagelockmessage = getpagelock($page);
if(!$pagelockmessage)
{
  // update gallery
  if(isset($_POST['addgalleryimage']))
  {
    $filename=trim($_POST['imagefilename']);
    if(imageexists($filename))
    {
      addgalleryimage($page,$filename);
      updateeditdata($page, $sid);
      $message = 'Added image';
      if(!getthumbnail($filename))
      {
        $message .= '. Please create a thumbnail for this image!';
      }
    }
    else
    {
      $message = 'Image <i>'.$filename.'</i> does not exist.';
    }
  }
  elseif(isset($_POST['changegalleryimage']))
  {
    $filename=trim($_POST['imagefilename']);
    if(imageexists($filename))
    {
      $message = 'Changed image';
      changegalleryimage($_POST['galleryitemid'], $filename);
      updateeditdata($page, $sid);
      if(!getthumbnail($filename))
      {
        $message .= '. Please create a thumbnail for this image!';
      }
    }
    else
    {
      $message = 'Image <i>'.$filename.'</i> does not exist.';
    }
  }
  elseif(isset($_POST['removegalleryimage']))
  {
    $message = 'Removed image <i>'.getgalleryimage($_POST['galleryitemid']).'</i>';
    if(isset($_POST['removeconfirm']))
    {
      removegalleryimage($_POST['galleryitemid']);
      updateeditdata($page, $sid);
    }
    else
    {
      $message = 'In order to remove an image, you have to check "Confirm remove".';
    }
  }
  elseif(isset($_POST['moveimageup']))
  {
    $message = 'Moving image <i>'.$filename.'</i> up';
    movegalleryimage($_POST['galleryitemid'],"up", $_POST['positions']);
    $offset=(floor(($_GET['pageposition']-$_POST['positions'])/$imagesperpage))*$imagesperpage;
    if($offset<0) $offset=0;
    updateeditdata($page, $sid);
  }
  elseif(isset($_POST['moveimagedown']))
  {
    $message = 'Moving image <i>'.$filename.'</i> down';
    movegalleryimage($_POST['galleryitemid'],"down", $_POST['positions']);

    $offset=(floor(($_GET['pageposition']+$_POST['positions'])/$imagesperpage))*$imagesperpage;

    if($offset>$_GET['noofimages'])
    {
      $offset=(floor($noofimages/$imagesperpage))*$imagesperpage;
    }
    updateeditdata($page, $sid);
  }
  elseif(isset($_POST['reindex']))
  {
    reindexgallerypositions($page);
    $message = 'Reindexed Gallery';
    updateeditdata($page, $sid);
  }

  $editpage = new EditGallery($page,$message,$offset,$imagesperpage,$showall);
}
else
{
  $editpage = new DonePage($page,"This page is already being edited",$pagelockmessage,"&action=editcontents&override=on","galleryedit.php","Override lock and edit");
}

print($editpage->toHTML());
?>
