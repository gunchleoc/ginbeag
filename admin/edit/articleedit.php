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
require_once $projectroot."admin/functions/pagecontent/articlepagesmod.php";
require_once $projectroot."admin/includes/objects/edit/articlepage.php";
require_once $projectroot."includes/objects/elements.php";
require_once $projectroot."admin/includes/objects/adminmain.php";

checksession();

if(isset($_GET['page'])) { $page=$_GET['page'];
} else { $page=0;
}

if(isset($_GET['offset'])) { $articlepage=$_GET['offset']+1;
} else if(isset($_GET['articlepage'])) { $articlepage=$_GET['articlepage'];
} else { $articlepage=1;
}

if(isset($_GET['articlesection'])) { $articlesection=$_GET['articlesection'];
} else { $articlesection=0;
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
        if(isset($_POST['addarticlepage'])) {
            $lastpage = numberofarticlepages($page);
            if(getlastarticlesection($page, $lastpage)) {
                addarticlepage($page);
                $articlepage = $lastpage+1;
            }
            else
            {
                $articlepage = $lastpage;
                $message = "You cannot add a page while the last page is still empty";
                $error = true;
            }
            $editpage = new EditArticlePage($articlepage);
        }
        elseif(isset($_POST['addarticlesection'])) {
            addarticlesection($page, $articlepage);
            $editpage = new EditArticlePage($articlepage);
            $message = "Added section";
        }
        elseif(isset($_POST['deletesection'])) {
            $editpage = new DeleteArticleSectionConfirm($articlepage, $articlesection);
        }
        elseif(isset($_POST['confirmdeletesection'])) {
            deletearticlesection($articlesection);
            updateeditdata($page);
            $editpage = new EditArticlePage($articlepage);
            $message = "Deleted section";
        }
        elseif(isset($_POST['nodeletesection'])) {
            $editpage = new EditArticlePage($articlepage);
            $message = "Deleting aborted";
        }
        elseif(isset($_POST['deletelastarticlepage'])) {
            $noofpages = numberofarticlepages($page);
            if($noofpages > 1) {
                if(!getlastarticlesection($page, $noofpages)) {
                    deletelastarticlepage($page);
                    updateeditdata($page);
                    $articlepage--;
                    $message = 'Deleted page #'.$articlepage.' of this article';
                }
                else
                {
                    $message = "Could not delete page because there are still some sections in it";
                    $error = true;
                }
            }
            else
            {
                $articlepage = 1;
                $message = "Could not delete page because there is only 1 page left";
                $error = true;
            }
            $editpage = new EditArticlePage($articlepage);
        }
        elseif(isset($_POST['movesectionup'])) {
            $articlepage = movearticlesection($articlesection, $articlepage, "up");
            updateeditdata($page);
            $editpage = new EditArticlePage($articlepage);
            $message = "Moved section up";
        }
        elseif(isset($_POST['movesectiondown'])) {
            $articlepage = movearticlesection($articlesection, $articlepage, "down");
            updateeditdata($page);
            $editpage = new EditArticlePage($articlepage);
            $message = "Moved section down";
        }
        else
        {
            $editpage = new EditArticlePage($articlepage);
        }
    }
    else
    {
        $editpage = new pageBeingEditedNotice($message);
    }
}
$content = new AdminMain($page, "editcontents", new AdminMessage($message, $error), $editpage);
print($content->toHTML());
?>
