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

require_once $projectroot."admin/functions/sessions.php";
require_once $projectroot."admin/functions/categoriesmod.php";

//print_r($_POST);
//print_r($_GET);

$db->quiet_mode = true;

checksession();

$filename="";
if(isset($_POST['filename'])) { $filename=$_POST['filename'];
}

$selectedcats=array();
if(isset($_POST['selectedcat'])) { $selectedcats=$_POST['selectedcat'];
}

$success = addimagecategories($filename, $selectedcats);

header('Content-type: text/xml;	charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';

if($success !== false && empty($db->error_report)) {
    print('<message error="0">');
    print("Added new Categories to ".$filename.".");
}
else
{
    print('<message error="1">');
    print("Error Adding new Categories to ".$filename."!"
    . "<br />\n" . $db->error_report);
}
print("</message>");
?>
