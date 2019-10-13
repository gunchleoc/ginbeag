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

require_once $projectroot."functions/db.php";
require_once $projectroot."includes/objects/images.php";

// check legal vars
require_once $projectroot."admin/includes/legaladminvars.php";

require_once $projectroot."includes/includes.php";
require_once $projectroot."functions/images.php";
require_once $projectroot."functions/pages.php";
require_once $projectroot."includes/objects/showimage.php";
require_once $projectroot."admin/functions/sessions.php";

// print_r($_POST);
 //print_r($_GET);

checksession();

$nextitem=0;
$previousitem=0;
$image="";
$item=0;

if(isset($_GET['page'])) { $page=$_GET['page'];
} else { $page=0;
}

if(isset($_GET['image'])) {
    $image=$_GET['image'];
}
if(isset($_GET['item'])) {
    $item=$_GET['item'];
    // get image from item array
    $image=$_POST[$_GET['item']];
}

$showimage = new Showimage($page, $image, $item, true);
print($showimage->toHTML());
?>
