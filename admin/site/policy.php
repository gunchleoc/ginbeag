<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."admin/functions/sessions.php";
require_once $projectroot."admin/includes/objects/site/policy.php";
require_once $projectroot."includes/functions.php";
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

if($postaction=='savesite') {
    $newproperties = array();
    $newproperties['Display Site Policy'] = SQLStatement::setinteger($_POST['displaypolicy']);
    $newproperties['Site Policy Title'] = fixquotes($_POST['policytitle']);

    $message .= updateproperties(SITEPROPERTIES_TABLE, $newproperties, 255);

    if (empty($message)) {
        $message="Site policy settings saved";
    } else {
        $message="Failed to save site policy";
        $error = true;
    }
}

$content = new AdminMain($page, "sitepolicy", new AdminMessage($message, $error), new SitePolicy());
print($content->toHTML());
?>
