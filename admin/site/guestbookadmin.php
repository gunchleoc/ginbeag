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
require_once $projectroot."admin/functions/guestbookmod.php";
require_once $projectroot."admin/includes/objects/site/guestbook.php";
require_once $projectroot."admin/includes/objects/adminmain.php";

checksession();
checkadmin();

if(isset($_GET['page'])) { $page=$_GET['page'];
} else { $page=0;
}

$postaction="";
if(isset($_GET['postaction'])) { $postaction=$_GET['postaction'];
}
unset($_GET['postaction']);


$message = "";
$error = false;

if($postaction ==='saveproperties') {

    $newproperties = array();

    $newproperties['Enable Guestbook']= SQLStatement::setinteger($_POST['enableguestbook']);
    $newproperties['Guestbook Entries Per Page']= SQLStatement::setinteger($_POST['guestbookperpage']);

    $message .= updateproperties(SITEPROPERTIES_TABLE, $newproperties, 255);

    if (empty($message)) {
        $message="Guestbook properties saved.";
    } else {
        $message = "Failed to save Guestbook properties";
        $error = true;
    }
}


$itemsperpage=getproperty('Guestbook Entries Per Page');

if(isset($_POST["deleteentry"])) {
    $contents = new AdminGuestbookDeleteConfirmForm($_POST['messageid']);
}
elseif(isset($_POST["deleteconfirm"])) {
    $message='Entry #'.$_POST['messageid'].' deleted.';
    deleteguestbookentry($_POST['messageid']);
}
if(!isset($_POST["deleteentry"])) {
    if(isset($_GET['offset'])) { $offset=$_GET['offset'];
    } else { $offset=0;
    }

    $contents = new AdminGuestbookEntryList($itemsperpage, $offset);
}

$content = new AdminMain($page, "siteguest", new AdminMessage($message, $error), $contents);
print($content->toHTML());
?>
