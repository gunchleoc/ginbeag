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
require_once $projectroot."admin/includes/objects/site/pagetypes.php";
require_once $projectroot."admin/includes/objects/adminmain.php";

checksession();
checkadmin();

if(isset($_GET['page'])) { $page=$_GET['page'];
} else { $page=0;
}

$message = "";
$error = false;

// print_r($_POST);
// print_r($_GET);

if(isset($_POST['pagetypesettings'])) {
    $allowroot=0;
    if(isset($_POST['allowroot'])) {
        $allowroot=1;
    }
    $allowsimplemenu=0;
    if(isset($_POST['allowsimplemenu'])) {
        $allowsimplemenu=1;
    }
    updaterestrictions($_POST['pagetype'], $allowroot, $allowsimplemenu);
    $message=('Changed settings for <i>'.$_POST['pagetype'].'</i>');
}

$content = new AdminMain($page, "sitepagetype", new AdminMessage($message, $error), new SitePageTypes());
print($content->toHTML());
?>
