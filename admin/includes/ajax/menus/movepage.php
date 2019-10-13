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
$projectroot=substr($projectroot, 0, strrpos($projectroot, "menus"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "ajax"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."admin/functions/pagecontent/menupagesmod.php";
require_once $projectroot."admin/functions/sessions.php";

//print_r($_POST);

$db->quiet_mode = true;

checksession();


$success=false;
$message ="";

if(isset($_POST['moveup'])) {
    $message = " Up";
    $success = movepage($_POST['moveid'], "up", $_POST['positions']);
    updateeditdata($_POST['page']);
}
elseif(isset($_POST['movedown'])) {
    $message = " Down";
    $success = movepage($_POST['moveid'], "down", $_POST['positions']);
    updateeditdata($_POST['page']);
}
elseif(isset($_POST['movetop'])) {
    $message = " to the Top";
    $success = movepage($_POST['moveid'], "top");
    updateeditdata($_POST['page']);
}
else
{
    $message = " to the Bottom";
    $success = movepage($_POST['moveid'], "bottom");
    updateeditdata($_POST['page']);
}


header('Content-type: text/xml;	charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';

if($success >=0  && empty($db->error_report)) {
    print('<message error="0">');
    updateeditdata($_POST['page']);
    print("Moved Subpage".$message);
}
else
{
    print('<message error="1">');
    print("Error Moving Subpage".$message. "<br />\n" . $db->error_report);
}
print("</message>");

?>
