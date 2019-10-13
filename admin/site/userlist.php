<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."admin/functions/sessions.php";
require_once $projectroot."admin/includes/objects/site/users.php";
require_once $projectroot."admin/includes/objects/adminmain.php";

checksession();
checkadmin();

if(isset($_GET['page'])) { $page=$_GET['page'];
} else { $page=0;
}

if(isset($_GET['ref'])) { $ref=$_GET['ref'];
} else { $ref="";
}

// print_r($_POST);
// print_r($_GET);

$content = new AdminMain($page, "siteuserlist", new AdminMessage("", false), new SiteUserlist($ref));
print($content->toHTML());
?>
