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
$projectroot=substr($projectroot, 0, strrpos($projectroot, "galleries"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "ajax"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."admin/functions/imagesmod.php";
require_once $projectroot."admin/functions/pagecontent/gallerypagesmod.php";
require_once $projectroot."admin/functions/sessions.php";

//print_r($_POST);

$db->quiet_mode = true;

checksession();

header('Content-type: text/xml;	charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';

$message = getpagelock($_POST['page']);
if ($message) {
    print('<message error="1">');
    print($message);
} else {

    $page = $_POST['page'];
    $filename = trim($_POST['imagefilename']);

    $success = false;
    $message ="";

    if (imageexists($filename)) {
        $success = changegalleryimage($_POST['galleryitemid'], $filename);
    } else {
        $message = '. Image does not exist.';
    }

    if ($success && empty($db->error_report)) {
        print('<message error="0">');
        updateeditdata($page);
        print("Changed Image : ".$filename);
    } else {
        print('<message error="1">');
        print("Error Changing Image : ".$filename.$message
        . "<br />\n" . $db->error_report);
    }
}
print("</message>");
?>
