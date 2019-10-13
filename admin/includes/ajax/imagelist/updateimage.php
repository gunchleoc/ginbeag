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
$projectroot=substr($projectroot, 0, strrpos($projectroot, "imagelist"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "ajax"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."admin/functions/imagesmod.php";
require_once $projectroot."admin/includes/objects/imagelist.php";
require_once $projectroot."admin/functions/sessions.php";

//print_r($_POST);
//print_r($_GET);

$db->quiet_mode = true;

checksession();

$filename=$_POST['filename'];
$image=getimage($filename);
$thumbnail = getthumbnail($filename);
$printme= new AdminImage($filename, $image['uploaddate'], $image['editor_id'], $thumbnail, true);

if (empty($db->error_report)) {
    print($printme->toHTML());
} else {
    print($db->error_report);
}
?>
