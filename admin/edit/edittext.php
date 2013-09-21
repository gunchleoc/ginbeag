<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."includes/includes.php");
include_once($projectroot."includes/functions.php");
include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/functions/pagesmod.php");
include_once($projectroot."admin/includes/templates/adminedittext.php");

$sid=$_GET['sid'];
checksession($sid);


//print_r($_GET);
//print_r($_POST);

$action="";
if(isset($_GET['action'])) $action=$_GET['action'];
elseif(isset($_POST['action'])) $action=$_POST['action'];

// todo check if unset is necessary, it's messing with pageedit
//unset($_GET['action']);
//unset($_POST['action']);

// so this doesn't start running at once on include
if($action==="edittext")
{

  $content = new EditText();
  print($content->toHTML());
}
?>
