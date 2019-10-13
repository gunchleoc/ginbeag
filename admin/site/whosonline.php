<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."admin/functions/sessions.php";
require_once $projectroot."admin/includes/objects/site/whosonline.php";
require_once $projectroot."admin/includes/objects/adminmain.php";

checksession();

if(isset($_GET['page'])) { $page=$_GET['page'];
} else { $page=0;
}

$content = new AdminMain($page, "siteonline", new AdminMessage("", false), new SiteWhosOnline());
print($content->toHTML());
?>
