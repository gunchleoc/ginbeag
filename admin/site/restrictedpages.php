<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."functions/db.php");
include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/includes/objects/site/restrictedpages.php");
include_once($projectroot."admin/includes/objects/adminmain.php");

checksession();

if(isset($_GET['page'])) $page=$_GET['page'];
else $page=0;

$content = new AdminMain($page, "sitepagerestrict", new AdminMessage("", false), new SiteRestrictedPages());
print($content->toHTML());
?>
