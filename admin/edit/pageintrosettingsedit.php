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
require_once $projectroot."admin/functions/pagesmod.php";
require_once $projectroot."admin/includes/objects/adminmain.php";

//print("Post: ");
//print_r($_POST);
//print("<br/Get: ");
//print_r($_GET);

checksession();

if(isset($_GET['page'])) { $page=$_GET['page'];
} else { $page=0;
}

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
        $pagetype = getpagetype($page);
        if($pagetype === "article") {
            include_once $projectroot."admin/includes/objects/edit/articlepage.php";
            $editpage = new EditArticle($page);
        }
        elseif($pagetype === "menu" || $pagetype === "articlemenu") {
            include_once $projectroot."admin/includes/objects/edit/menupage.php";
            $editpage = new EditMenu($page);
        }
        elseif($pagetype === "news") {
            include_once $projectroot."admin/includes/objects/edit/newspage.php";
            //archiving
            if(isset($_POST['archivenewsitems'])) {
                $editpage = new ArchiveNewsItemsForm();
            }
            elseif(isset($_POST['doarchivenewsitems'])) {
                include_once $projectroot."admin/functions/pagescreate.php";
                $dateok=true;
                if($_POST['year'] == $_POST['oldestyear']) {
                    if($_POST['month'] < $_POST['oldestmonth']) {
                        $dateok=false;
                    }
                    elseif($_POST['month'] == $_POST['oldestmonth']) {
                        if($_POST['day'] < $_POST['oldestday']) {
                            $dateok=false;
                        }
                    }
                }
                if(!$dateok) {
                    $message = "The selected date must not be older than the start date!";
                    $error = true;
                }
                elseif(!checkdate($_POST['month'], $_POST['day'], $_POST['year'])) {
                    $message = "The selected date does not exist!";
                    $error = true;
                }
                else
                {
                    // TODO redirect to the newly created page
                    // do the archiving
                    $moveditems=archivenewsitems($page, $_POST['day'], $_POST['month'], $_POST['year']);
                    if($moveditems > 0) {
                        $message = "Moved ".$moveditems." newsitem(s) to new page.";
                    }
                    else
                    {
                        $message = "No newsitems to move.";
                        $error = true;
                    }
                }
                $editpage = new ArchiveNewsItemsForm();
                updateeditdata($page);
            } // doarchivenewsitems
            // rss
            elseif(isset($_POST['enablerss'])) {
                addrssfeed($page);
                $message = "RSS enabled for this newspage";
                $editpage = new EditNews($page);
            }
            elseif(isset($_POST['disablerss'])) {
                removerssfeed($page);
                $message = "RSS disabled for this newspage";
                $editpage = new EditNews($page);
            }
            // display order
            elseif(isset($_POST['setdisplayorder'])) {
                setdisplaynewestnewsitemfirst($page, $_POST['displayorder']);
                updateeditdata($page);
                $editpage = new EditNews($page);
            }
            else
            {
                $editpage = new EditNews($page);
            }
        }
        else
        {
            include_once $projectroot."admin/includes/objects/edit/pageintro.php";
            $editpage = new EditPageIntro($page);
        }
    }
    // locked page
    else
    {
        $editpage = new pageBeingEditedNotice($message);
    }
}
$content = new AdminMain($page, "editpageintro", new AdminMessage($message, $error), $editpage);
print($content->toHTML());
?>
