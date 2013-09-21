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
include_once($projectroot."admin/includes/templates/adminlinklistpage.php");

$sid=$_GET['sid'];
checksession($sid);

$page=$_GET['page'];

//print_r($_POST);
//print_r($_GET);

// *************************** actions ************************************** //

// page content actions

$message="";
$pagelockmessage = getpagelock($page);
if(!$pagelockmessage)
{
  // update linklist
  if(isset($_POST['changelinklistimage']))
  {
    $message='Changed image';
    $filename=trim($_POST['imagefilename']);
    if(imageexists($filename))
    {
      updatelinklistimage($page,$filename);
      updateeditdata($page, $sid);
    }
    else
    {
      $message='Image <i>'.$filename.'</i> does not exist.';
    }
  }
  elseif(isset($_POST['removelinklistimage']))
  {
    $message='Removing image <i>'.$filename.'</i> from linklist';
    if($_POST['removeconfirm'])
    {
      updatelinklistimage($page,"");
      updateeditdata($page, $sid);
    }
    else
    {
      $message='In order to remove an image, you have to check "Confirm remove".';
    }
  }
  elseif(isset($_POST['addlink']))
  {
    $message='Added new link';
    addlink($page,$_POST['title'],$_POST['link'],$_POST['imagefilename'],$_POST['description']);
  }
  elseif(isset($_POST['deletelink']))
  {
    $message='Deleting link <i>'.title2html(getlinktitle($_GET['link'])).'</i>';
    if($_POST['deletelinkconfirm'])
    {
      deletelink($_GET['link']);
      updateeditdata($page, $sid);
    }
    else
    {
      $message='In order to delete a link, you have to check "Confirm delete".';
    }
  }
  elseif(isset($_POST['linkproperties']))
  {
    $message='Editing link properties';
    updatelinkproperties($_GET['link'],$_POST['title'],$_POST['link']);
    updateeditdata($page, $sid);
  }
  elseif(isset($_POST['changelinkimage']))
  {
    $message='Changed link image';
    $filename=trim($_POST['imagefilename']);
    if(imageexists($filename) || strlen($filename)==0)
    {
      updatelinkimage($_GET['link'],$filename);
      updateeditdata($page, $sid);
    }
    else
    {
      $message='Image <i>'.$filename.'</i> does not exist.';
    }
  }
  elseif(isset($_POST['removelinkimage']))
  {
    $message='Removing link image <i>'.$filename.'</i>';
    if(isset($_POST['removeconfirm']))
    {
      updatelinkimage($_GET['link'],"");
      updateeditdata($page, $sid);
    }
    else
    {
      $message='In order to remove an image, you have to check "Confirm remove".';
    }
  }
  elseif(isset($_POST['movelinkup']))
  {
    $message='Moving link up';
    movelink($_GET['link'], "up", $_POST['positions']);
    updateeditdata($page, $sid);
  }
  elseif(isset($_POST['movelinkdown']))
  {
    $message='Moving link down';
    movelink($_GET['link'], "down", $_POST['positions']);
    updateeditdata($page, $sid);
  }
  $editpage = new EditLinklist($page,$message);
}
else
{
  $editpage = new DonePage($page,"This page is already being edited",$pagelockmessage,"&action=editcontents&override=on","linklistedit.php","Override lock and edit");
}

print($editpage->toHTML());
?>
