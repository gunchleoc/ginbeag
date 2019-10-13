<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));
require_once $projectroot."admin/functions/sessions.php";
require_once $projectroot."admin/includes/objects/navigator.php";
require_once $projectroot."admin/includes/objects/adminmain.php";

checksession();

if(isset($_GET['page'])) { $page=$_GET['page'];
} else { $page=0;
}

$pagelist = new PageList();
$content = new AdminMain($page, "show", new AdminMessage("", false), $pagelist);
print($content->toHTML());
?>
