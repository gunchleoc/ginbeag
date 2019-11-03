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
require_once $projectroot."admin/includes/objects/site/antispam.php";
require_once $projectroot."admin/includes/objects/adminmain.php";
require_once $projectroot."functions/antispam.php";

checksession();
checkadmin();

if (isset($_GET['page'])) {
    $page=$_GET['page'];
} else {
    $page=0;
}

$postaction="";
if (isset($_GET['postaction'])) {
    $postaction=$_GET['postaction'];
}
unset($_GET['postaction']);

//  print_r($_POST);

$message = "";
$error = false;

if ($postaction=='savesite') {
    $newproperties = array();
    if (isset($_POST['renamevariables'])) {
        $message = rename_variables();
        if (empty($message)) {
            $message = "Renamed Variables";
        } else {
            $error = true;
            $message = "Failed to rename variables ".$message;
        }
    } else {
        if (isset($_POST['mathcaptcha'])) {
            $newproperties['Use Math CAPTCHA'] = SQLStatement::setinteger($_POST['usemathcaptcha']);
        } else if(isset($_POST['spamwords'])) {
            $newproperties['Spam Words Subject'] = fixquotes($_POST['spamwords_subject']);
            $newproperties['Spam Words Content'] = fixquotes($_POST['spamwords_content']);
        } else if(isset($_POST['floodcontrol'])) {
            $newproperties['Flood Interval'] = $_POST['flood_interval'] . " seconds";
            $newproperties['Maximum E-mails Per Minute'] = $_POST['flood_perminute'];
        }

        $message = updateproperties(ANTISPAM_TABLE, $newproperties);
        if (empty($message)) {
            $message = "Anti-Spam settings saved";
        } else {
            $error = true;
            $message = "Failed to save Anti-Spam settings ".$message;
        }
    }
}

$content = new AdminMain($page, "sitespam", new AdminMessage($message, $error), new SiteAntispam());
print($content->toHTML());
?>
