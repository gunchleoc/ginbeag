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

// check legal vars

require $projectroot."admin/includes/legaladminvars.php";

require_once $projectroot."admin/functions/sessions.php";
require_once $projectroot."admin/functions/pagesdelete.php";
require_once $projectroot."admin/includes/objects/forms.php";
require_once $projectroot."admin/includes/objects/page.php";
require_once $projectroot."admin/includes/objects/adminmain.php";

checksession();

if(isset($_GET['page'])) { $page=$_GET['page'];
} else { $page=0;
}

$action="";
if(isset($_GET['action'])) { $action=$_GET['action'];
} elseif(isset($_POST['action'])) { $action=$_POST['action'];
}

unset($_GET['action']);
unset($_POST['action']);

// print_r($_POST);
// print_r($_GET);
$message = "";
$error = false;

// *************************** actions ************************************** //

if($page<=0) {
    $editpage = noPageSelectedNotice();
    $message = "Please select a page first";
    $error = true;
}
elseif($action==="delete") {
    $editpage = new DeletePageConfirmForm();
}
elseif(isset($_POST["executedelete"])) {
    $pagename = title2html(getpagetitle($page));
    $parent=getparent($page);
    $deletepage=deletepage($page);
    $deletepage--;
    unlockpage($page);
    $message='Deleted the following page(s): "'.title2html($pagename).'"<br />'.$deletepage.' subpages were included in delete.';
    $editpage = new DoneRedirect($parent, "Page Deleted", array("action" => "show"), "admin.php", "View parent page");
}
elseif(isset($_POST["nodelete"])) {
    unlockpage($page);
    $message="Deleting aborted: ".title2html(getpagetitle($page));
    $editpage = new DoneRedirect($page, "Delete Page Aborted", array("action" => "show"), "admin.php", "View the page");
}

$content = new AdminMain($page, "pagedelete", new AdminMessage($message, $error), $editpage);
print($content->toHTML());
?>
