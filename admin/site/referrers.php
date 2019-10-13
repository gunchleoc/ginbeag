<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."functions/db.php";
require_once $projectroot."admin/functions/sessions.php";
require_once $projectroot."admin/functions/referrersmod.php";
require_once $projectroot."admin/includes/objects/site/referrers.php";
require_once $projectroot."admin/includes/objects/adminmain.php";

checksession();
checkadmin();

if(isset($_GET['page'])) { $page=$_GET['page'];
} else { $page=0;
}

$message = "";
$error = false;

// print_r($_POST);
// print_r($_GET);

if(isset($_POST['unblock'])) {
    $referrers= new SiteReferrerUnblockForm($_POST['referrer']);
}
else
{
    if(isset($_POST['confirmunblock'])) {
        $message='Unblocked Referrer <i>'.$_POST['referrer'].'</i>';
        deleteblockedreferrer($_POST['referrer']);
    }
    elseif(isset($_POST['block'])) {
        $message='Blocked Referrer <i>'.$_POST['referrer'].'</i>';
        addblockedreferrer(trim($_POST['referrer']));
    }
    $referrers= new SiteReferrers();
}

$content = new AdminMain($page, "sitereferrers", new AdminMessage($message, $error), $referrers);
print($content->toHTML());
?>
