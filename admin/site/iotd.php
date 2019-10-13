<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."admin/functions/sessions.php";
require_once $projectroot."admin/includes/objects/site/iotd.php";
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

//  print_r($_GET);

if($postaction=='savesite') {
    $newproperties = array();
    $newproperties['Display Picture of the Day'] = SQLStatement::setinteger($_POST['displaypotd']);
    if (isset($_POST['selectedcat'])) {
        $list = SQLStatement::prepare_integer_list($_POST['selectedcat']);
        if (empty($list['errormessage'])) {
            $newproperties['Picture of the Day Categories'] = $list['content'];
        } else {
            $message .= $list['errormessage'];
        }
    }

    $newproperties['Display Article of the Day'] = SQLStatement::setinteger($_POST['displayaotd']);
    $list = SQLStatement::prepare_integer_list($_POST['aotdpages']);
    if (empty($list['errormessage'])) {
        $newproperties['Article of the Day Start Pages'] = $list['content'];
    } else {
        $message .= $list['errormessage'];
    }

    $message .= updateproperties(SITEPROPERTIES_TABLE, $newproperties, 255);

    if (empty($message)) {
        $message="Random Items of the Day saved";
    } else {
        $message = "Failed to save Random Items of the Day" . $message;
        $error = true;
    }
}

$content = new AdminMain($page, "siteiotd", new AdminMessage($message, $error), new SiteRandomItems());
print($content->toHTML());
?>
