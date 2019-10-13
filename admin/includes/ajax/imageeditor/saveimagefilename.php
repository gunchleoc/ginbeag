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
if($errormessage || !empty($db->error_report)) {
    print('<message error="1">');
    print($errormessage . "<br />\n" . $db->error_report);
}
else {
    $page=$_POST['page'];
    $item=$_POST['item'];
    $imagefilename=trim($_POST['imagefilename']);
    $elementtype=$_POST['elementtype'];

    $success=false;

    if(!($imagefilename==="" || imageexists($imagefilename))) {
        $errormessage = "Error saving image ".$imagefilename." - we don't have this image!";
    }
    else
    {

        if($elementtype=="pageintro") {
            include_once $projectroot."admin/functions/pagesmod.php";
            $success=updatepageintroimagefilename($page, $imagefilename);
            if($success) {
                if($imagefilename) { $message= "Saved synopsis image: ".$imagefilename;
                } else { $message= "Removed image from synopsis";
                }
            }
            else { $errormessage = "Error saving synopsis image ".$imagefilename." for page ".$page;
            }
        }
        elseif($elementtype=="articlesection") {
            include_once $projectroot."admin/functions/pagecontent/articlepagesmod.php";
            $success=updatearticlesectionimagefilename($item, $imagefilename);
            if($success) {
                if($imagefilename) { $message="Saved section image: ".$imagefilename;
                } else { $message= "Removed image from section";
                }
            }
            else { $errormessage = "Error saving section image ".$imagefilename." for page ".$page." and section ".$item;
            }
        }
        elseif($elementtype=="newsitemsection") {
            include_once $projectroot."admin/functions/pagecontent/newspagesmod.php";
            $success=updatenewsitemsectionimagefilename($item, $imagefilename);
            if($success) {
                if($imagefilename) { $message="Saved section image: ".$imagefilename;
                } else { $message= "Removed image from section";
                }
            }
            else { $errormessage = "Error saving section image ".$imagefilename." for page ".$page." and section ".$item;
            }
        }
        elseif($elementtype=="link") {
            include_once $projectroot."admin/functions/pagecontent/linklistpagesmod.php";
            $success= updatelinkimagefilename($item, $imagefilename);
            if($success) {
                if($imagefilename) { $message="Saved link image: ".$imagefilename;
                } else { $message= "Removed image from link";
                }
            }
            else { $errormessage = "Error saving link image ".$imagefilename." for page ".$page." and link ".$item;
            }
        }
        else { $errormessage = 'Error saving image: unknown element type "'.$elementtype.'"';
        }
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
    //print_r($_POST);

}
print("</message>");


?>
