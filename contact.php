<?php
$projectroot=dirname(__FILE__)."/";

require_once $projectroot."functions/db.php";
require_once $projectroot."includes/functions.php";

// anti bot nonsense links
// ********************************* achtung - bot secure ist server-spezifisch!
$testpath = "/".getproperty("Local Path");
if(getproperty("Local Path") == "") { $testpath = "";
}

if(!((isset($_SERVER["ORIG_PATH_TRANSLATED"]) && $_SERVER["ORIG_PATH_TRANSLATED"] == $projectroot."contact.php") 
    || $_SERVER["PHP_SELF"] == $testpath."/contact.php")
) {
    //    print("test: ".$_SERVER["PHP_SELF"]);
    header("HTTP/1.0 404 Not Found");
    print("HTTP 404: Sorry, but this page does not exist.");
    exit;
}


// check legal vars
require_once $projectroot."includes/legalvars.php";
require_once $projectroot."functions/email.php";
require_once $projectroot."functions/antispam.php";
require_once $projectroot."includes/objects/contact.php";

if(isset($_GET['sid'])) { $sid = $_GET['sid'];
} elseif(isset($_POST['sid'])) { $sid = $_POST['sid'];
} else { $sid="";
}

if(strlen($sid) > 0 && ! ispublicloggedin()) {
    $sid="";
    unset($_GET['sid']);
    unset($_POST['sid']);
}

if(isset($_POST['token'])) { $token = $_POST['token'];
} else { $token = "";
}

$email="";
$subject="";
$messagetext="";
$sendcopy="";
$userid="";
$emailinfo="";
$errormessage="";
$sendmail=false;


if(isset($_GET['user'])) {
    $recipient=getuseremail($_GET['user']);
    $userid=getuserid($_GET['user']);
}
elseif(isset($_POST['userid'])) {
    $userid=$_POST['userid'];
    if($userid==0) {
        $recipient=getproperty("Admin Email Address");
    } else {
        $recipient=getuseremail($userid);
    }
}
else
{
    $recipient=getproperty("Admin Email Address");
    $userid=0;
}


// check data and send e-mail
if(isset($_POST[$emailvariables['E-Mail Address Variable']])) {
    if($token && checktoken($token)) {
        // get vars
        $email=trim($_POST[$emailvariables['E-Mail Address Variable']]);
        $subject=trim($_POST[$emailvariables['Subject Line Variable']]);
        $messagetext=trim(stripslashes($_POST[$emailvariables['Message Text Variable']]));
        $sendcopy=isset($_POST['sendcopy']) && $_POST['sendcopy'];

        $errormessage = emailerror($email, $subject, $messagetext, $sendcopy);

        if($errormessage=="") {
            $sendmail=true;
            sendemail($email, $subject, $messagetext, $sendcopy, $recipient);
        }
    }
    else
    {
        $errormessage = errormessage("email_invalidtoken");
        $token = createtoken();
    }
}

if(!$token) { $token = createtoken();
}

$contactpage = new ContactPage($email, $subject, $messagetext, $sendcopy, $userid, $token, $errormessage, $sendmail);
print($contactpage->toHTML());
?>
