<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

// check legal vars
require_once $projectroot."admin/includes/legaladminvars.php";

require_once $projectroot."admin/functions/usersmod.php";
require_once $projectroot."admin/functions/sessions.php";
require_once $projectroot."functions/email.php";


if(isset($_GET['user'])&&isset($_GET['key'])) {
    if(hasactivationkey($_GET['user'], $_GET['key'])) {
        $message='Activated user account for '.title2html($_GET["user"]);
        activateuser($_GET['user'], $_GET['key']);
        sendactivationnotification($_GET['user'], getuseremail(getuserid($_GET['user'])));
    }
    else
    {
        $message='The user account for '.title2html($_GET["user"]).' is already activated';
    }
}
else
{
    $message='No user to activate';
}

$header = new HTMLHeader("Activated user", "Webpage Building", $message);
print($header->toHTML());
$footer = new HTMLFooter();
print($footer->toHTML());

//
//
//
function sendactivationnotification($username,$recipient)
{
    $message="Welcome ".$username."!";
    $message.="\r\n\r\nYour webpage editing account has been activated";
    $message.="\r\n\r\nYou can logon at ".getprojectrootlinkpath().'admin/login.php';
    $subject="Your account has been activated";
    sendplainemail($subject, $message, $recipient);
}
?>
