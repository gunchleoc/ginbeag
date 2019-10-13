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
$projectroot=substr($projectroot, 0, strrpos($projectroot, "editor"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "ajax"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."admin/functions/pagesmod.php";
require_once $projectroot."admin/functions/pagecontent/articlepagesmod.php";
require_once $projectroot."admin/functions/pagecontent/linklistpagesmod.php";
require_once $projectroot."admin/functions/pagecontent/newspagesmod.php";

//print_r($_POST);

$db->quiet_mode = true;

checksession();


header('Content-type: text/xml;	charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';

$message = getpagelock($_POST['page']);
if($message) {
    print('<message error="1">');
    print($message);
}
else {

    $page=$_POST['page'];
    $item=$_POST['item'];
    $elementtype=$_POST['elementtype'];
    $text=$_POST['savetext'];

    $success=false;

    if($elementtype=="pageintro") {
        $success=updatepageintro($page, $text);
        if($success) { $message= "Saved synopsis";
        }
    }
    elseif($elementtype=="articlesection") {
        $success=updatearticlesectiontext($item, $text);
        if($success) { $message= "Saved article section";
        }
    }
    elseif($elementtype=="link") {
        $success=updatelinkdescription($item, $text);
        if($success) { $message= "Saved link description";
        }
    }
    elseif($elementtype=="newsitemsynopsis") {
        $success=updatenewsitemsynopsistext($item, $text);
        if($success) { $message= "Saved newsitem synopsis";
        }
    }
    elseif($elementtype=="newsitemsection") {
        $success=updatenewsitemsectiontext($item, $text);
        if($success) { $message= "Saved newsitem section text";
        }
    }
    elseif($elementtype=="sitepolicy") {
        $sql = new SQLUpdateStatement(
            SPECIALTEXTS_TABLE,
            array('text'), array('id'),
            array(addslashes(utf8_decode($text)), 'sitepolicy'), 'ss'
        );
        $success = $sql->run();
        if($success) { $message= "Saved sitepolicy text";
        }
    }
    elseif($elementtype=="guestbook") {
        // TODO editing guestbook intro is broken
        $sql = new SQLUpdateStatement(
            SPECIALTEXTS_TABLE,
            array('text'), array('id'),
            array(addslashes(utf8_decode($text)), 'guestbook'), 'ss'
        );
        $success = $sql->run();
        if($success) { $message= "Saved guestbook text";
        }
    }
    elseif($elementtype=="contact") {
        $sql = new SQLUpdateStatement(
            SPECIALTEXTS_TABLE,
            array('text'), array('id'),
            array(addslashes(utf8_decode($text)), 'contact'), 'ss'
        );
        $success = $sql->run();
        if($success) { $message= "Saved contact form intro text";
        }
    }


    if($success && empty($db->error_report)) {
        print('<message error="0">');
        updateeditdata($page);
        print($message);
    }
    else
    {
        print('<message error="1">');
        print("Error saving ".$elementtype." text: ".$message
        . "<br />\n" . $db->error_report);
    }
}
print("</message>");


?>
