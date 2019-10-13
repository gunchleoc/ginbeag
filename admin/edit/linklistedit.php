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
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."admin/functions/sessions.php";
require_once $projectroot."admin/functions/pagecontent/linklistpagesmod.php";
require_once $projectroot."admin/includes/objects/edit/linklistpage.php";
require_once $projectroot."includes/objects/elements.php";
require_once $projectroot."includes/objects/images.php";
require_once $projectroot."includes/functions.php";
require_once $projectroot."admin/includes/objects/adminmain.php";

checksession();

if(isset($_GET['page'])) { $page=$_GET['page'];
} else { $page=0;
}


//print_r($_POST);
//print_r($_GET);

// *************************** actions ************************************** //

// page content actions
if(!$page) {
    $editpage = noPageSelectedNotice();
    $message = "Please select a page first";
    $error = true;
}
else
{
    $message = getpagelock($page);
    $error = false;
    if(!$message) {
        // update linklist
        if(isset($_POST['addlink'])) {
            $message = 'Added new link';
            addlink($page, fixquotes($_POST['title']), $_POST['link'], $_POST['imagefilename'], fixquotes($_POST['description']));
        }
        elseif(isset($_POST['deletelink'])) {
            $message = 'Deleted link <i>'.title2html(getlinktitle($_GET['link'])).'</i>';
            if(isset($_POST['deletelinkconfirm'])) {
                deletelink($_GET['link']);
                updateeditdata($page);
            }
            else
            {
                $message = 'In order to delete a link, you have to check "Confirm delete".';
                $error = true;
            }
        }
        elseif(isset($_POST['movelinkup'])) {
            $message = 'Moved link up';
            movelink($_GET['link'], "up", $_POST['positions']);
            updateeditdata($page);
        }
        elseif(isset($_POST['movelinkdown'])) {
            $message = 'Moved link down';
            movelink($_GET['link'], "down", $_POST['positions']);
            updateeditdata($page);
        }
        elseif(isset($_POST['sortlinks'])) {
            $message = 'Sorted links from A-Z';
            sortlinksbyname($page);
            updateeditdata($page);
        }
        $editpage = new EditLinklist($page);
    }
    else
    {
        $editpage = pageBeingEditedNotice($message);
    }
}
$content = new AdminMain($page, "editcontents", new AdminMessage($message, $error), $editpage);
print($content->toHTML());
?>
