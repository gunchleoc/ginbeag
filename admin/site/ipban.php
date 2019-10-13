<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."functions/db.php";
require_once $projectroot."admin/functions/sessions.php";
require_once $projectroot."admin/includes/objects/site/ipban.php";
require_once $projectroot."admin/includes/objects/adminmain.php";

checksession();
checkadmin();

if(isset($_GET['page'])) { $page=$_GET['page'];
} else { $page=0;
}

$message = "";
$error = false;

if(isset($_POST['banipallrestricted'])) {
    $ip=trim($_POST['ip']);
    if($ip === long2ip(ip2long($ip))) {
        addbannedipforrestrictedpages($ip);
    }
    else
    {
        $message='<i>'.$ip.'</i> is not a valid IP address.';
        $error = true;
    }
}
elseif(isset($_POST['unbanipallrestricted'])) {
    removebannedipforrestrictedpageas($_POST['ip']);
    $message='<i>'.$_POST['ip'].'</i> has been unbanned.';
}


$content = new AdminMain($page, "siteipban", new AdminMessage($message, $error), new SiteIPBan());
print($content->toHTML());
?>
