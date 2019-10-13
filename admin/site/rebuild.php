<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."functions/db.php";
require_once $projectroot."admin/functions/sessions.php";
require_once $projectroot."admin/functions/pagesmod.php";
require_once $projectroot."admin/includes/objects/site/rebuildindices.php";
require_once $projectroot."admin/includes/objects/adminmain.php";

checksession();
checkadmin();

if(isset($_GET['page'])) { $page=$_GET['page'];
} else { $page=0;
}

$postaction="";
if(isset($_GET['postaction'])) { $postaction=$_GET['postaction'];
}
unset($_GET['postaction']);

$message = "";
$error = false;

if($postaction==='restrictedpages') {
    $message = rebuildaccessrestrictionindex();
}

$content = new AdminMain($page, "siteind", new AdminMessage($message, $error), new SiteRebuildIndices($message));
print($content->toHTML());
?>
