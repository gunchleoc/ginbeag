<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."admin/functions/sessions.php";
require_once $projectroot."admin/includes/objects/edit/menupage.php";
require_once $projectroot."admin/includes/objects/adminmain.php";

checksession();

if(isset($_GET['page'])) { $page=$_GET['page'];
} elseif(isset($_POST['page'])) { $page=$_POST['page'];
} else { $page=0;
}

//print_r($_POST);
//print_r($_GET);

// *************************** actions ************************************** //

// page content actions
if(!$page) {
    $editpage = noPageSelectedNotice();
    $message = "Please select a page first";
    $error = true;
}
else
{
    $message = getpagelock($page);
    $error = false;
    if(!$message) {
        if(isset($_POST['sortsubpages'])) {
            $message = 'Sorted subpages from A-Z';
            sortsubpagesbyname($page);
            updateeditdata($page);
        }
        $editpage = new EditMenuSubpages($page);
    }
    else
    {
        $editpage = new pageBeingEditedNotice($message);
    }
}
$content = new AdminMain($page, "editcontents", new AdminMessage($message, $error), $editpage);
print($content->toHTML());
?>
