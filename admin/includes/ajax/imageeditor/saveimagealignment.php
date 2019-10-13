<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "imageeditor"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "ajax"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."admin/functions/sessions.php";

$db->quiet_mode = true;

checksession();


header('Content-type: text/xml;	charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';

$errormessage = getpagelock($_POST['page']);
$message="";
if($errormessage) {
    print('<message error="1">');
    print($errormessage);
}
else {
    $page=$_POST['page'];
    $item=$_POST['item'];
    if($_POST['imagealign'] === "center" || $_POST['imagealign'] === "left" || $_POST['imagealign'] === "right" ) {
        $imagealign=$_POST['imagealign'];
    } else { $imagealign= "left";
    }
    $elementtype=$_POST['elementtype'];

    $success=false;

    if($elementtype=="pageintro") {
        include_once $projectroot."admin/functions/pagesmod.php";
        $success=updatepageintroimagealign($page, $imagealign);
        if($success) { $message= "Saved synopsis image align: ".$imagealign;
        } else { $errormessage = "Error saving synopsis image align ".$imagealign." for page ".$page;
        }
    }

    elseif($elementtype=="articlesection") {
        include_once $projectroot."admin/functions/pagecontent/articlepagesmod.php";
        $success=updatearticlesectionimagealign($item, $imagealign);
        if($success) { $message="Saved section image align";
        } else { $errormessage = "Error saving section image align ".$imagealign." for page ".$page." and section ".$item;
        }
    }
    elseif($elementtype=="newsitemsection") {
        include_once $projectroot."admin/functions/pagecontent/newspagesmod.php";
        $success=updatenewsitemsectionimagealign($item, $imagealign);
        if($success) { $message="Saved section image align";
        } else { $errormessage = "Error saving section image align ".$imagealign." for page ".$page." and section ".$item;
        }
    }
    elseif($elementtype=="link") {
        $errormessage = "You can't change the alignment of images for links in a linklist";
    }
    else { $errormessage = 'Error saving image align: unknown element type "'.$elementtype.'"';
    }

    if($errormessage || !empty($db->error_report)) {
        print('<message error="1">');
        print($errormessage . "<br />\n" . $db->error_report);
    }
    else
    {
        print('<message error="0">');
        updateeditdata($page);
        print($message);
    }

    //print_r($_POST);

}
print("</message>");


?>
