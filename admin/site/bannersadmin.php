<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

// check legal vars
include($projectroot."admin/includes/legalsitevars.php");

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/functions/bannersmod.php");
include_once($projectroot."admin/functions/files.php");
include_once($projectroot."admin/includes/adminelements.php");
include_once($projectroot."includes/functions.php");
include_once($projectroot."includes/templates/page.php");
include_once($projectroot."includes/templates/elements.php");
include_once($projectroot."admin/includes/templates/sitebanners.php");

//  print_r($_GET);
//  print_r($_POST);

$sid=$_GET['sid'];
checksession($sid);

$action="";
if(isset($_GET['action'])) $action=$_GET['action'];
elseif(isset($_POST['action'])) $action=$_POST['action'];

unset($_GET['action']);
unset($_POST['action']);



$message="";

if($action=='editbanner')
{
  if(strlen($_POST['code'])>0)
  {
    $message='Edited banner #'.$_GET['bannerid'].'code <i>'.$_POST['header'].'</i>';
    updatebannercode($_GET['bannerid'], $_POST['header'], $_POST['code']);
  }
  else
  {
    $filename=$_FILES['image']['name'];
    if(strlen($filename)>0)
    {
      $filename=cleanupfilename($filename);
      deletefile("img/banners",$_POST['oldimage']);
      $success= replacefile($_FILES,"img/banners","image",$filename);
    }
    else
    {
      $contents=getbannercontents($_GET['bannerid']);
      $filename=$contents['image'];
      $success=true;
    }
    if($success)
    {
      $message='Edited banner #'.$_GET['bannerid'].': <i>'.$_POST['header'].'</i>';
      updatebanner($_GET['bannerid'], $_POST['header'], $filename,$_POST['description'],$_POST['link']);
      if(!isbannercomplete($_GET['bannerid']))
      {
        $message='This banner is not complete and will not be displayed! Please fill out all required fields.';
      }
    }
    else
    {
      $message='Failed to edit banner #'.$_GET['bannerid'].': error uploading image!';
    }
  }
}
elseif($action=='addbanner')
{
  if(strlen($_POST['code'])>0)
  {
    $message='Added banner code <i>'.$_POST['header'].'</i>';
    addbannercode($_POST['header'], $_POST['code']);
  }
  else
  {
    $filename=$_FILES['image']['name'];
    $filename=cleanupfilename($filename);
    $success= replacefile($_FILES,"img/banners","image",$filename);

    if($success)
    {
      $banner_id=addbanner($_POST['header'], $filename ,$_POST['description'],$_POST['link']);
      $message='Added banner <i>'.$_POST['header'].'</i>';
      if(!isbannercomplete($banner_id))
      {
        $message='This banner is not complete and will not be displayed! Please fill out all required fields.';
      }
    }
    else
    {
      $message='Failed to add banner: error uploading image!';
    }
  }
}
elseif($action=='movebanner')
{
  if(isset($_POST['movebannerup']))
  {
    $message='Moving banner #'.$_GET['bannerid'].' up';
    movebanner($_GET['bannerid'], "up", $_POST['positions']);
  }
  else
  {
    $message='Moving banner #'.$_GET['bannerid'].' down';
    movebanner($_GET['bannerid'], "down", $_POST['positions']);
  }
}
elseif($action=='deletebanner')
{
  if(isset($_POST['deletebannerconfirm']))
  {
    $message='Deleted banner #'.$_GET['bannerid'];
    deletebanner($_GET['bannerid']);
  }
  else
  {
    $message='You have to check "Confirm delete" in order to delete banner #'.$_GET['bannerid'];
  }
}

elseif($action=='displaybanners')
{
  updateentries(SITEPROPERTIES_TABLE,array('Display Banners' =>$_POST['toggledisplaybanners']),"property_name","property_value");
  $properties = getproperties(); // need to update global variable
}

unset($_GET['bannerid']);
$banners = new SiteBanners($message);
print($banners->toHTML());

?>
