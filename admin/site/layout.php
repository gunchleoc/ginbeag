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
require_once $projectroot."admin/includes/objects/site/layout.php";
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

if($postaction=='savesite' && isset($_POST['submit'])) {
    $newproperties = array();
    if (isset($_POST['linksonsplashpage'])) {
        $list = SQLStatement::prepare_integer_list($_POST['linksonsplashpage']);
        if (empty($list['errormessage'])) {
            $newproperties['Links on Splash Page'] = $list['content'];
        } else {
            $message .= $list['errormessage'];
            $success = false;
        }
    }

    $newproperties['Default Template'] = trim($_POST['defaulttemplate']);

    $newproperties['Site Name'] = fixquotes(trim($_POST['sitename']));
    $newproperties['Site Description'] = fixquotes(trim($_POST['sitedescription']));
    $newproperties['Left Header Image'] = trim($_POST['leftimage']);
    $newproperties['Left Header Link'] = trim($_POST['leftlink']);
    $newproperties['Right Header Image'] = trim($_POST['rightimage']);
    $newproperties['Right Header Link'] = trim($_POST['rightlink']);

    $newproperties['Footer Message'] = fixquotes(trim($_POST['footermessage']));

    $newproperties['News Items Per Page'] = SQLStatement::setinteger(trim($_POST['newsperpage']));
    $newproperties['Gallery Images Per Page'] = SQLStatement::setinteger(trim($_POST['galleryimagesperpage']));

    $newproperties['Show All Links on Splash Page'] = SQLStatement::setinteger(trim($_POST['alllinksonsplashpage']));
    $newproperties['Display Site Description on Splash Page'] = SQLStatement::setinteger(trim($_POST['showsd']));
    $newproperties['Splash Page Font'] = trim($_POST['spfont']);
    $newproperties['Splash Page Image'] = trim($_POST['spimage']);

    $message .= updateproperties(SITEPROPERTIES_TABLE, $newproperties, 255);

    // Splash page texts go into the specialtexts table
    $sql = new SQLUpdateStatement(
        SPECIALTEXTS_TABLE,
        array('text'), array('id'),
        array(fixquotes(trim($_POST['sptext1'])), 'splashpage1'), 'ss'
    );
    $success = $sql->run();

    $sql = new SQLUpdateStatement(
        SPECIALTEXTS_TABLE,
        array('text'), array('id'),
        array(fixquotes(trim($_POST['sptext2'])), 'splashpage2'), 'ss'
    );
    $success = $sql->run() && $success;

    if ($success && empty($message)) {
        $message = "Layout properties saved";
    } else {
        $message = "Failed to save layout properties:" . $message;
        $error = true;
    }
}

$content = new AdminMain($page, "sitelayout", new AdminMessage($message, $error), new SiteLayout());
print($content->toHTML());
?>
