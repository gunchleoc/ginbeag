<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"menus"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"ajax"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/includes/objects/edit/menupage.php");
include_once($projectroot."admin/functions/sessions.php");

//print_r($_POST);


$sid=$_POST['sid'];
checksession($sid);

$subpageids=getallsubpageids($_POST['page']);
$printme= new MenuMovePageFormContainer ($_POST['page'],$subpageids);
print("<div> test ".$_POST['page']."</div>");
print($printme->toHTML());
?>