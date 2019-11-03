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
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."admin/functions/sessions.php";
require_once $projectroot."admin/functions/bannersmod.php";
require_once $projectroot."admin/functions/files.php";
require_once $projectroot."includes/objects/page.php";
require_once $projectroot."admin/includes/objects/site/banners.php";
require_once $projectroot."includes/functions.php";
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

//print_r($_POST);

if($postaction=='editbanner') {
    if(strlen($_POST['code'])>0) {
        $message .= 'Edited banner #'.$_POST['bannerid'].'code <i>'.$_POST['header'].'</i>';
        updatebannercode($_POST['bannerid'], fixquotes($_POST['header']), $_POST['code']);
    }
    else
    {
        $filename=$_FILES['image']['name'];
        if(strlen($filename)>0) {
            $filename=cleanupfilename($filename);
            $contents = getbannercontents($_POST['bannerid']);
            deletefile("img/banners", $contents['image']);
            $errorcode = replacefile("img/banners", "image", $filename);
            if($errorcode == UPLOAD_ERR_OK) {
                $success = true;
                $message .= "Replaced banner image with: ".$filename;
            }
            else
            {
                $message .= "<br />Error ".$errorcode.": ".fileerrors($errorcode)." ";
                $success = false;
                $error = true;
            }
        }
        else
        {
            $contents=getbannercontents($_POST['bannerid']);
            $filename=$contents['image'];
            $success=true;
            $message .= "Replace banner contents";
        }
        if($success) {
            $message='Edited banner #'.$_POST['bannerid'].': <i>'.$_POST['header'].'</i>';
            updatebanner($_POST['bannerid'], fixquotes($_POST['header']), $filename, fixquotes($_POST['description']), $_POST['link']);
            if(!isbannercomplete($_POST['bannerid'])) {
                $message .= 'This banner is not complete and will not be displayed! Please fill out all required fields.';
                $error = true;
            }
        }
        else
        {
            $message .= 'Failed to edit banner #'.$_POST['bannerid'].': error uploading image!';
            $error = true;
        }
    }
}
elseif($postaction=='addbanner') {
    if (!empty($_POST['code'])) {
        $message .= 'Added banner code <i>'.$_POST['header'].'</i>';
        $banner = addbannercode(fixquotes($_POST['header']), $_POST['code']);
        if (!$banner > 0) {
            $message .= ' Error adding banner with code.';
            $error = true;
        }
    }
    else {
        $filename=$_FILES['image']['name'];
        $filename=cleanupfilename($filename);
        $errorcode = replacefile("img/banners", "image", $filename);
        if($errorcode == UPLOAD_ERR_OK) {
            $banner=addbanner(fixquotes($_POST['header']), basename($filename), fixquotes($_POST['description']), $_POST['link']);
            $message='Added banner <i>'.$_POST['header'].'</i>';
            if(!isbannercomplete($banner)) {
                $message .= ' This banner #'.$banner.' is not complete and will not be displayed! Please fill out all required fields.';
                $error = true;
            }
        } else {
            $message .= 'Failed to add banner: error uploading image!';
            $message .= "<br />Error ".$errorcode.": ".fileerrors($errorcode)." ";
            $error = true;
        }
    }
}
elseif($postaction=='movebanner') {
    if(isset($_POST['movebannerup'])) {
        $message='Moving banner #'.$_POST['bannerid'].' up';
        movebanner($_POST['bannerid'], "up", $_POST['positions']);
    }
    else
    {
        $message='Moving banner #'.$_POST['bannerid'].' down';
        movebanner($_POST['bannerid'], "down", $_POST['positions']);
    }
}
elseif($postaction=='deletebanner') {
    if(isset($_POST['deletebannerconfirm'])) {
        $message='Deleted banner #'.$_POST['bannerid'];
        deletebanner($_POST['bannerid']);
    }
    else
    {
        $message = 'You have to check "Confirm delete" in order to delete banner #'.$_POST['bannerid'];
        $error = true;
    }
}

elseif($postaction=='displaybanners') {
    $newproperties = array('Display Banners' => SQLStatement::setinteger(trim($_POST['toggledisplaybanners'])));
    $message = updateproperties(SITEPROPERTIES_TABLE, $newproperties, 255);
    if (empty($message)) {
        $properties = getproperties(); // need to update global variable
        $message .= "Changed banner display options";
    } else {
        $message = "Failed to save Guestbook properties";
        $error = true;
    }
}

unset($_POST['bannerid']);

$content = new AdminMain($page, "sitebanner", new AdminMessage($message, $error), new SiteBanners());
print($content->toHTML());
?>
