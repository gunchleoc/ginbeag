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

//print_r($_POST);

checksession();

$image="";

$elementtype=$_POST["elementtype"];

if($elementtype=="pageintro") {
    include_once $projectroot."functions/pages.php";
    $image=getpageintroimage($_POST['page']);
}
elseif($elementtype=="articlesection") {
    include_once $projectroot."functions/pagecontent/articlepages.php";
    $contents = getarticlesectioncontents($_POST['item']);
    $image = $contents['sectionimage'];
}
elseif($elementtype=="newsitemsection") {
    include_once $projectroot."functions/pagecontent/newspages.php";
    $image = getnewsitemsectionimage($_POST['item']);
}
elseif($elementtype=="link") {
    include_once $projectroot."functions/pagecontent/linklistpages.php";
    $contents=getlinkcontents($_POST['item']);
    $image=$contents["image"];
}
else { print ("Error: Unknown elementtype: ".$elementtype."</br /> for image on page: ".$_POST['page'].", item: ".$_POST['item']);
}
if (!empty($db->error_report)) {
    print($db->error_report);
} else if($image) {
    $printme = new ImageEditorImagePane($_POST['page'], $image);
    print($printme->toHTML());
}

?>
