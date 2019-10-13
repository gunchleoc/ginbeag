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

require_once $projectroot."admin/functions/pagecontent/gallerypagesmod.php";
require_once $projectroot."functions/pagecontent/gallerypages.php";
require_once $projectroot."admin/functions/sessions.php";
require_once $projectroot."admin/functions/pagesmod.php";
require_once $projectroot."admin/includes/objects/edit/gallerypage.php";
require_once $projectroot."includes/objects/elements.php";
require_once $projectroot."includes/objects/images.php";
require_once $projectroot."admin/includes/objects/adminmain.php";

checksession();

//print_r($_POST);
//print_r($_GET);

if(isset($_GET['page'])) { $page=$_GET['page'];
} else { $page=0;
}

if(isset($_GET['offset'])) { $offset=$_GET['offset'];
} else { $offset=0;
}

if(isset($_GET['showall']) || isset($_POST['showall'])) { $showall=true;
} else { $showall=false;
}

$imagesperpage=6;

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
        // update gallery
        if(isset($_POST['addgalleryimage'])) {
            $filename = trim($_POST['imagefilename']);
            if(imageexists($filename)) {
                addgalleryimage($page, $filename);
                updateeditdata($page);
                $message = 'Added image';
                if(!getthumbnail($filename)) {
                    $message .= ', but please create a thumbnail for this image!';
                    $error = true;
                }
            }
            else
            {
                $message = 'Image <i>'.$filename.'</i> does not exist.';
                $error = true;
            }
            $noofimages = countgalleryimages($page);
            $offset = (ceil($noofimages / $imagesperpage) - 1) * $imagesperpage;
        }
        elseif(isset($_POST['removegalleryimage'])) {
            $message = 'Removed image <i>'.getgalleryimage($_POST['galleryitemid']).'</i>';
            if(isset($_POST['removeconfirm'])) {
                removegalleryimage($_POST['galleryitemid'], $page);
                updateeditdata($page);
                $noofimages = countgalleryimages($page);
                if($offset >= $noofimages) {
                    $offset = (ceil($noofimages / $imagesperpage) - 1) * $imagesperpage;
                }
            }
            else
            {
                $message = 'In order to remove an image, you have to check "Confirm remove".';
                $error = true;
            }
        }
        elseif(isset($_POST['moveimageup'])) {
            $message = 'Moved image <i>'.$_POST['imagefilename'].'</i> up';
            movegalleryimage($_POST['galleryitemid'], "up", $_POST['positions']);
            $newpos = getgalleryimageposition($_POST['galleryitemid']);
            $offset = $newpos - ($newpos % $imagesperpage);
            updateeditdata($page);
        }
        elseif(isset($_POST['moveimagedown'])) {
            $message = 'Moved image <i>'.$_POST['imagefilename'].'</i> down';
            movegalleryimage($_POST['galleryitemid'], "down", $_POST['positions']);
            $newpos = getgalleryimageposition($_POST['galleryitemid']);
            $offset = $newpos - ($newpos % $imagesperpage);
            updateeditdata($page);
        }
        elseif(isset($_POST['reindex'])) {
            reindexgallerypositions($page);
            $message = 'Reindexed Gallery';
            updateeditdata($page);
        }
        $editpage = new EditGallery($page, $offset, $imagesperpage, $showall);
    }
    else
    {
        $editpage = new pageBeingEditedNotice($message);
    }
}
$content = new AdminMain($page, "editcontents", new AdminMessage($message, $error), $editpage);
print($content->toHTML());
?>
