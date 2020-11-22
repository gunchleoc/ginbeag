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

require_once $projectroot."admin/includes/objects/imageeditor.php";
require_once $projectroot."admin/functions/sessions.php";

$db->quiet_mode = true;

checksession();

$image = "";
$elementtype = $_POST['elementtype'];

switch ($elementtype) {
    case 'pageintro':
        $sql = new SQLSelectStatement(PAGES_TABLE, '*', array('page_id'), array($_POST['page']), 'i');
        $sql->set_join('image_filename', IMAGES_TABLE, 'image_filename');
    break;
    case 'articlesection':
        $sql = new SQLSelectStatement(ARTICLESECTIONS_TABLE, '*', array('articlesection_id'), array($_POST['item']), 'i');
        $sql->set_join('image_filename', IMAGES_TABLE, 'image_filename');
    break;
    case 'newsitemsection':
        $sql = new SQLSelectStatement(NEWSITEMSECTIONS_TABLE, '*', array('newsitemsection_id'), array($_POST['item']), 'i');
        $sql->set_join('image_filename', IMAGES_TABLE, 'image_filename');
    break;
    case 'link':
        $sql = new SQLSelectStatement(LINKS_TABLE, 'image_filename', array('link_id'), array($_POST['item']), 'i');
    break;
    default:
        print("Error: Unknown elementtype: $elementtype </br /> for image on page: " . $_POST['page'] . ", item: " . $_POST['item']);
        return;
}

if ($sql) {
    $imagedata = $sql->fetch_row();
}

if (!empty($db->error_report)) {
    print($db->error_report);
} elseif (!empty($imagedata)) {
    $printme = new ImageEditorImagePane($_POST['page'], $imagedata);
    print($printme->toHTML());
}

?>
