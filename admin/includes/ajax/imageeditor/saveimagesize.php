<?php
/**
 * An Gineadair Beag is a content management system to run websites with.
 *
 * PHP Version 7
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
 *
 * @category Ginbeag
 * @package  Ginbeag
 * @author   gunchleoc <fios@foramnagaidhlig.net>
 * @license  https://www.gnu.org/licenses/agpl-3.0.en.html GNU AGPL
 * @link     https://github.com/gunchleoc/ginbeag/
 */

$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "imageeditor"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "ajax"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."admin/functions/pagesmod.php";
require_once $projectroot."admin/functions/sessions.php";

$db->quiet_mode = true;

checksession();

//print_r($_POST);

header('Content-type: text/xml;	charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';

$errormessage = getpagelock($_POST['page']);
$message="";

if(!$errormessage) {

    if(!isset($_POST['page'])) { $errormessage .= " :page not defined";
    }
    if(!isset($_POST['item'])) { $errormessage .= " :item not defined";
    }
    if(!isset($_POST['elementtype'])) { $errormessage .= " :elementtype not defined";
    }
    if(!isset($_POST['autoshrink'])) { $errormessage .= " :autoshrink not defined";
    }
    if(!isset($_POST['usethumbnail'])) { $errormessage .= " :usethumbnail not defined";
    }

    if(!$errormessage) {

        $page=$_POST['page'];
        $item=$_POST['item'];
        if($_POST['autoshrink'] === "on") {
            $autoshrink=1;
        } else { $autoshrink= 0;
        }
        if($_POST['usethumbnail'] === "on") {
            $usethumbnail=1;
        } else { $usethumbnail= 0;
        }
        $elementtype=$_POST['elementtype'];

        $success=false;

        if($elementtype=="pageintro") {

            $success=updatepageintroimagesize($page, $autoshrink, $usethumbnail);
            if($success) { $message.= "Saved synopsis image size: autoshrink ".$autoshrink." - use thumbnail ".$usethumbnail;
            } else { $errormessage = "Error saving synopsis image size: autoshrink ".$autoshrink." - use thumbnail ".$usethumbnail." for page ".$page;
            }
        }

        elseif($elementtype=="articlesection") {
            include_once $projectroot."admin/functions/pagecontent/articlepagesmod.php";
            $success=updatearticlesectionimagesize($item, $autoshrink, $usethumbnail);
            if($success) { $message.="Saved section image size";
            } else { $errormessage = "Error saving section image size: autoshrink ".$autoshrink." - use thumbnail ".$usethumbnail." for page ".$page." and section ".$item;
            }
        }
        elseif($elementtype=="newsitemsection") {
            include_once $projectroot."admin/functions/pagecontent/newspagesmod.php";
            $success=updatenewsitemsectionimagesize($item, $autoshrink, $usethumbnail);
            if($success) { $message.="Saved section image size";
            } else { $errormessage = "Error saving section image size: autoshrink ".$autoshrink." - use thumbnail ".$usethumbnail." for page ".$page." and section ".$item;
            }
        }
        elseif($elementtype=="link") {
            $errormessage = "You can't change the size of images for links in a linklist";
        }
    }
}

if($errormessage || !empty($db->error_report)) {
    print('<message error="1">');
    print("error ".$errormessage . "<br />\n" . $db->error_report);
}
else
{
    print('<message error="0">');
    updateeditdata($page);
    print("success ".$message);
}
print("</message>");

?>
