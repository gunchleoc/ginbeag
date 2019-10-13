<?php
$projectroot=dirname(__FILE__)."/";

require_once $projectroot."functions/db.php";
require_once $projectroot."includes/functions.php";

// anti bot nonsense links
// ********************************* achtung - bot secure ist server-spezifisch!
$testpath = "/".getproperty("Local Path");
if(getproperty("Local Path") == "") { $testpath = "";
}

if(!((isset($_SERVER["ORIG_PATH_TRANSLATED"]) && $_SERVER["ORIG_PATH_TRANSLATED"] == $projectroot."guestbook.php") 
    || $_SERVER["PHP_SELF"] == $testpath."/guestbook.php")
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
require_once $projectroot."functions/guestbook.php";
require_once $projectroot."includes/objects/page.php";
require_once $projectroot."includes/objects/forms.php";
require_once $projectroot."includes/objects/guestbook.php";

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

/// init variables for guestbook constructur
$postername="";
$addy="";
$subject="";
$messagetext="";
$offset=0;
$showguestbookform=false;
$showpost=false;
$showleavemessagebutton=true;
$itemsperpage=getproperty('Guestbook Entries Per Page');
$title="";
$listtitle="";
$message="";
$error="";
$postadded=false;


//display new contact form
if(isset($_POST['post'])) {
    $showguestbookform=true;
    $listtitle=getlang("guestbook_latestentries");
    $showleavemessagebutton=false;
    $title=getlang("guestbook_leavemessageguestbook");
}
// submit an entry to the guestbook
elseif(isset($_POST['submitpost'])) {
    if($token && checktoken($token)) {
        $showguestbookform = true;
        $listtitle = getlang("guestbook_latestentries");
        $showleavemessagebutton = false;
        $showpost = true;

        $postername = trim($_POST['postername']);
        $postername = fixquotes($postername);
        $addy = trim($_POST[$emailvariables['E-Mail Address Variable']]);
        $subject = trim($_POST[$emailvariables['Subject Line Variable']]);
        $subject = html_entity_decode(fixquotes($subject));
        $messagetext = trim(stripslashes($_POST[$emailvariables['Message Text Variable']]));
        $messagetext = html_entity_decode(fixquotes($messagetext));

        $error = emailerror($addy, utf8_decode($subject), utf8_decode($messagetext), 0);
        if(strlen($postername) < 1) {
            $error = errormessage("guestbook_needname").$error;
        }
        if(!$error) {
            $showguestbookform = false;
            $postadded = true;
            $offset = 0;
            addguestbookentry($postername, $addy, $subject, $messagetext);
            sendemail($addy, $subject, $messagetext, 1, getproperty("Admin Email Address"), true);
        }
    }
    else
    {
        $showguestbookform = true;
        $postadded = false;
        $showleavemessagebutton = false;
        $error = errormessage("guestbook_invalidtoken");
        $token = createtoken();
    }
}
// display entries
else
{
    $title=getlang("pageintro_guestbook");
    if(isset($_GET["offset"])) { $offset=$_GET["offset"];
    } else { $offset=0;
    }
}

$postername=utf8_decode($postername);
$subject=utf8_decode($subject);
$messagetext=utf8_decode($messagetext);

if(!$token) { $token = createtoken();
}

$guestbook = new Guestbook($postername, $addy, $subject, $messagetext, $token, $offset, $showguestbookform, $showpost, $showleavemessagebutton, $itemsperpage, $title, $listtitle, $message, $error, $postadded);
print($guestbook->toHTML());
?>
