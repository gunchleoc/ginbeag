<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/pagecontent/gallerypagesmod.php");
include_once($projectroot."functions/pagecontent/gallerypages.php");
include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/functions/pagesmod.php");
include_once($projectroot."admin/includes/objects/edit/gallerypage.php");
include_once($projectroot."includes/objects/elements.php");
include_once($projectroot."admin/includes/objects/adminmain.php");

if(isset($_GET['sid'])) $sid=$_GET['sid'];
else $sid="";
checksession($sid);

if(isset($_GET['page'])) $page=$_GET['page'];
else $page=0;

if(isset($_GET['offset'])) $offset=$_GET['offset'];
else $offset=0;

if(isset($_GET['showall']) || isset($_POST['showall'])) $showall=true;
else $showall=false;

$imagesperpage=6;

$message="";

//print_r($_POST);
//print_r($_GET);

// *************************** actions ************************************** //

// page content actions

$message = getpagelock($page);
if(!$message)
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
    $noofimages=countgalleryimages($page);
    $offset = $noofimages-$noofimages%$imagesperpage;
    
  }
  elseif(isset($_POST['removegalleryimage']))
  {
    $message = 'Removed image <i>'.getgalleryimage($_POST['galleryitemid']).'</i>';
    if(isset($_POST['removeconfirm']))
    {
      	removegalleryimage($_POST['galleryitemid']);
      	updateeditdata($page, $sid);
     	$noofimages=countgalleryimages($page);
     	if($offset>=$noofimages)
     	{
     		$offset=(floor(($noofimages-1)/$imagesperpage))*$imagesperpage;
     	}
    }
    else
    {
      $message = 'In order to remove an image, you have to check "Confirm remove".';
    }
  }
  elseif(isset($_POST['moveimageup']))
  {
    $message = 'Moved image <i>'.$_POST['imagefilename'].'</i> up';
    movegalleryimage($_POST['galleryitemid'],"up", $_POST['positions']);
    $offset=(floor(($_GET['pageposition']-$_POST['positions'])/$imagesperpage))*$imagesperpage-$imagesperpage;
    if($offset<0) $offset=0;
    updateeditdata($page, $sid);
  }
  elseif(isset($_POST['moveimagedown']))
  {
    $message = 'Moved image <i>'.$_POST['imagefilename'].'</i> down';
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

  $editpage = new EditGallery($page,$offset,$imagesperpage,$showall);
}
else
{
  $editpage = new DonePage("This page is already being edited","&action=show","admin.php","View this page");
}

$content = new AdminMain($page,"editcontents",$message,$editpage);
print($content->toHTML());
$db->closedb();
?>
