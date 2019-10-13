<?php
/*
 * An Gineadair Beag is a content management system to run websites with.
 *
 * Copyright (C) 2005-2019 GunChleoc
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

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
