<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."admin/functions/sessions.php";
require_once $projectroot."admin/functions/guestbookmod.php";
require_once $projectroot."admin/includes/objects/site/guestbook.php";
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

if($postaction ==='saveproperties') {

    $newproperties = array();

    $newproperties['Enable Guestbook']= SQLStatement::setinteger($_POST['enableguestbook']);
    $newproperties['Guestbook Entries Per Page']= SQLStatement::setinteger($_POST['guestbookperpage']);

    $message .= updateproperties(SITEPROPERTIES_TABLE, $newproperties, 255);

    if (empty($message)) {
        $message="Guestbook properties saved.";
    } else {
        $message = "Failed to save Guestbook properties";
        $error = true;
    }
}


$itemsperpage=getproperty('Guestbook Entries Per Page');

if(isset($_POST["deleteentry"])) {
    $contents = new AdminGuestbookDeleteConfirmForm($_POST['messageid']);
}
elseif(isset($_POST["deleteconfirm"])) {
    $message='Entry #'.$_POST['messageid'].' deleted.';
    deleteguestbookentry($_POST['messageid']);
}
if(!isset($_POST["deleteentry"])) {
    if(isset($_GET['offset'])) { $offset=$_GET['offset'];
    } else { $offset=0;
    }

    $contents = new AdminGuestbookEntryList($itemsperpage, $offset);
}

$content = new AdminMain($page, "siteguest", new AdminMessage($message, $error), $contents);
print($content->toHTML());
?>
