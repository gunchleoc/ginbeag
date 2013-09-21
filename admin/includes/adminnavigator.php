<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));
include_once($projectroot."functions/pages.php");
include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."includes/includes.php");
include_once($projectroot."admin/includes/templates/siteadminnavigator.php");


$sid=$_GET['sid'];
checksession($sid);

$navigator = new SiteAdminNavigator($_GET['page']);
print($navigator->toHTML());
?>
