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
require $projectroot."admin/includes/legalimagevars.php";

require_once $projectroot."admin/functions/sessions.php";
require_once $projectroot."admin/functions/imagesmod.php";
require_once $projectroot."admin/functions/categoriesmod.php";
require_once $projectroot."admin/functions/usersmod.php";
require_once $projectroot."admin/functions/files.php";
require_once $projectroot."includes/includes.php";
require_once $projectroot."admin/includes/objects/imagelist.php";

//print("post: ");
//print_r($_POST);
//print("<br />get: ");
//print_r($_GET);

clear_browser_cache_headers();

checksession();

// clear unused names of submit buttons
unset($_GET['nodelete']);
unset($_GET["doorder"]);
unset($_GET["addunknownfile"]);

// clear filter
if(isset($_GET['clear'])) {
    unset($_GET['clear']);
    unset($_GET['filter']);
    unset($_GET['s_caption']);
    unset($_GET['s_categoriesblank']);
    unset($_GET['s_copyright']);
    unset($_GET['s_copyrightblank']);
    unset($_GET['s_filename']);
    unset($_GET['s_missing']);
    unset($_GET['s_missingthumb']);
    unset($_GET['s_nothumb']);
    unset($_GET['s_selectedcat']);
    unset($_GET['s_source']);
    unset($_GET['s_sourceblank']);
    unset($_GET['s_unknown']);
    unset($_GET['s_unused']);
    unset($_GET['s_uploader']);
}

$filter=false;
if(isset($_GET['filter'])) {
    $filter=true;
    unset($_GET['filter']);
}

$offset=0;
if(isset($_GET['offset']) && $_GET['offset'] > 0) { $offset=$_GET['offset'];
} elseif(isset($_POST['offset']) && $_POST['offset'] > 0) { $offset=$_POST['offset'];
}

if(isset($_GET['number']) && $_GET['number']>0) { $number=$_GET['number'];
} else { $number = getproperty('Imagelist Images Per Page');
}

$page=0;
if(isset($_GET['page'])) { $page=$_GET['page'];
}

$action="";
if(isset($_GET['action'])) { $action=$_GET['action'];
} elseif(isset($_POST['action'])) { $action=$_POST['action'];
}

unset($_GET['action']);
unset($_POST['action']);

$order="filename";
if(isset($_GET['order'])) { $order=$_GET['order'];
}

$ascdesc="asc";
if(isset($_GET['ascdesc'])) { $ascdesc=$_GET['ascdesc'];
}

$filename="";
if(isset($_POST['filename'])) { $filename=$_POST['filename'];
} elseif(isset($_GET['filename'])) { $filename=$_GET['filename'];
}

$caption="";
if(isset($_POST['caption'])) { $caption=fixquotes($_POST['caption']);
}

$source="";
if(isset($_POST['source'])) { $source=fixquotes($_POST['source']);
}

$sourcelink="";
if(isset($_POST['sourcelink'])) { $sourcelink=$_POST['sourcelink'];
}

$copyright="";
if(isset($_POST['copyright'])) { $copyright=fixquotes($_POST['copyright']);
}

$permission=NO_PERMISSION;
if(isset($_POST['permission'])) { $permission=$_POST['permission'];
}

$selectedcats=array();
if(isset($_POST['selectedcat'])) { $selectedcats=$_POST['selectedcat'];
}

$form=false;
$message="";
$error = false;
$displayeditform=false;
$success=false;

if(isset($_POST["addimage"])) {
    unset($_POST["addimage"]);
    $filename=$_FILES['filename']['name'];
    $thumbnail=$_FILES['thumbnail']['name'];

    if(!$filename) {
        $message = 'Please select an image for upload';
        $error = true;
    }
    else
    {
        $newname=$_POST['newname'];

        // Make new path for each month to avoid directory that is too full
        $subpath = makeimagesubpath();

        // create path in file system if necessary and set permissions
        $imagedir=$projectroot.getproperty("Image Upload Path").$subpath;
        if(!file_exists($imagedir)) {
            mkdir($imagedir, 0757);
        }
        $copyindexsuccess = @copy($projectroot.getproperty("Image Upload Path")."/index.html", $imagedir."/index.html");
        $copyindexsuccess = $copyindexsuccess & @copy($projectroot.getproperty("Image Upload Path")."/index.php", $imagedir."/index.php");
        if(!$copyindexsuccess ) {
            $message .= 'SECURITY WARNING: unable to create index files in '.$imagedir.'. Please use FTP to copy these files from <em>'.$projectroot.getproperty("Image Upload Path").'</em> for security reasons!';
            $error = true;
        }
        if (!empty($newname)) {
            $filename = $newname . '.' . pathinfo($filename, PATHINFO_EXTENSION);
        }
        $filename=cleanupfilename($filename);
        $filename=str_replace("_thn.", ".", $filename);

        $pathinfo = pathinfo($filename);
        $extension = $pathinfo['extension'];
        $imagename = $pathinfo['filename'];;

        if (strlen($filename) > 40) {
            $imagename = substr($imagename, 0, 40);
            $filename = $imagename . '.' . $extension;
            if (isset($_POST['filename'])) {
                $_POST['filename'] = $filename;
            }
            if (isset($_GET['filename'])) {
                $_GET['filename'] = $filename;
            }
        }

        if(imageexists($filename)) {
            $message .= 'Image already exists: '.$filename.'';
            $error = true;
        }
        else
        {
            $errorcode = uploadfile(getproperty("Image Upload Path").$subpath, "filename", $filename);
            if($errorcode == UPLOAD_ERR_OK) {
                $success = true;
            }
            else
            {
                $message .= "<br />Error ".$errorcode.": ".fileerrors($errorcode)." ";
                $success = false;
                $error = true;
            }
        }
        if($success) {
            addimage($filename, $subpath, $caption, $source, $sourcelink, $copyright, $permission);
            addimagecategories($filename, $selectedcats);
            $filename=basename($filename);

            if(isset($_POST["resizeimage"])) {
                $resizesuccess = resizeimagewidth($projectroot.getproperty("Image Upload Path").$subpath, $filename);

                if($resizesuccess) {
                    $message .= "Image <em>".$filename."</em> resized successfully.";
                }
                else
                {
                    $message .= "<br />Failed to resize image. ";
                    $error = true;
                }
            }
            if(!isset($_POST["dontcreatethumbnail"])) {
                // Mobile thumbnails are created on the fly, we only create a desktop thumbnail here
                // Don't upscale small images
                if (should_have_thumbnail(array('image_filename' => $filename, 'path' => $subpath), getproperty("Thumbnail Size"))) {
                    $thsuccess = createthumbnail($projectroot.getproperty("Image Upload Path").$subpath, $filename);

                    if($thsuccess) {
                        addthumbnail($filename, $imagename.'_thn.'.$extension);
                        $message .= "Thumbnail for <em>".$filename."</em> created successfully. ";
                    }
                    else
                    {
                        $message .= "Failed to create thumbnail. ";
                        $error = true;
                    }
                } else {
                    $message .= "No thumbnail needed. ";
                }
            }
            elseif($thumbnail) {
                $newthumbname=$imagename.'_thn'.$extension;

                $errorcode = uploadfile(getproperty("Image Upload Path").$subpath, "thumbnail", $newthumbname);
                if($errorcode == UPLOAD_ERR_OK) {
                    $thsuccess = true;
                }
                else
                {
                    $message .= "<br />Error ".$errorcode.": ".fileerrors($errorcode)." ";
                    $thsuccess = false;
                    $error = true;
                }

                if($thsuccess) {
                    addthumbnail($filename, $newthumbname);
                    $message="Thumbnail for <em>".$filename."</em> uploaded successfully.";
                }
                else
                {
                    $message .= "<br />Failed to upload thumbnail. ";
                    $error = true;
                }
            }
        }
        if($success) {
            $message.="Added Image";
            $displayeditform=true;
        }
        else
        {
            $message .= "<br />Failed to upload image. ";
            $error = true;
        }
    }
}
elseif($action==="replaceimage") {
    $displayeditform = true;
    $newfilename=$_FILES['newfilename']['name'];
    $imagedata = getimage($filename);

    if (!$newfilename) {
        $message = "Please select an image for upload";
        $error = true;
    } elseif(empty($imagedata)) {
        $message = "The image you wish to replace does not exist: ".$filename;
        $error = true;
    } else {
        $extensionerror = checkextension($filename, $newfilename);
        if (!empty($extensionerror)) {
            $message .= $extensionerror;
            $error = true;
        } else {
            $uploadpath = getproperty("Image Upload Path").$imagedata['path'];
            $errorcode = replacefile($uploadpath, "newfilename", $filename);
            if($errorcode == UPLOAD_ERR_OK) {
                $message="Replaced Image";
                $displayeditform=true;
                $imagedata = getimage($filename);
                deletemobilethumbnail($imagedata);

                if (!empty($imagedata['thumbnail_filename']) && extension_loaded('gd') && function_exists('gd_info')) {
                    deletethumbnail($filename);
                    $thsuccess = createthumbnail($projectroot.$uploadpath, $filename);
                    if ($thsuccess) {
                        addthumbnail($filename, make_thumbnail_filename($filename));
                        $message .= ". Thumbnail created successfully.";
                    } else {
                        $message .= ". Failed to create thumbnail. Try using the 'generate tThumbnail' button.";
                        $error = true;
                    }
                }
            } else {
                $message .= "Failed to replace the image file.";
                $message .= "<br />Error ".$errorcode.": ".fileerrors($errorcode)." ";
                $error = true;
            }
        }
    }
    unset($_FILES);
}
elseif($action==="resizeimage") {
    $displayeditform = true;
    $resizesuccess = resizeimagewidth(getimagedir(getimagesubpath($filename)), $filename);

    if($resizesuccess) {
        $message .= "Image <em>".$filename."</em> resized successfully.";
    }
    else
    {
        $message .= "<br />Failed to resize image. ";
        $error = true;
    }
}
elseif($action==="addthumb") {
    $displayeditform = true;
    $thumbnail=$_FILES['thumbnail']['name'];

    if ($thumbnail) {
        $pathinfo = pathinfo($filename);
        $imageextension = $pathinfo['extension'];
        $extension = pathinfo($thumbnail, PATHINFO_EXTENSION);
        $extensionerror = checkextension($filename, $thumbnail);
        if (!empty($extensionerror)) {
            $message .= $extensionerror;
            $error = true;
        } else {
            $thumbnail = $pathinfo['filename'] . '_thn.' . $extension;
            $errorcode = uploadfile(getproperty("Image Upload Path").getimagesubpath($filename), "thumbnail", $thumbnail);
            if ($errorcode == UPLOAD_ERR_OK) {
                addthumbnail($filename, $thumbnail);
            } else {
                $message .= "<br />Error ".$errorcode.": ".fileerrors($errorcode)." ";
                $error = true;
            }
        }

        if (!$error) {
            $message = "Uploaded thumbnail.";
        } else {
            $message .= "Failed to upload thumbnail. ";
            $error = true;
        }
    }
    else
    {
        $message = "Please select a file before upload";
        $error = true;
    }
}
elseif($action==="replacethumb") {
    $displayeditform = true;
    $thumbnail=$_FILES['thumbnail']['name'];

    if(!$thumbnail) {
        $message = "Please select an image for upload";
        $error = true;
    }
    else
    {
        $imagedata = getimage($filename);
        $thumbnailfilename = $imagedata['thumbnail_filename'];
        $extensionerror = checkextension($filename, $thumbnailfilename);
        if (!empty($extensionerror)) {
            $message = $extensionerror;
            $error = true;
        } else {
            $errorcode = replacefile(getproperty("Image Upload Path").$imagedata['path'], "thumbnail", $thumbnailfilename);
            if ($errorcode !== UPLOAD_ERR_OK) {
                $message = "Error $errorcode: " . fileerrors($errorcode) . ". ";
                $error = true;
            }
        }

        if (!$error) {
            $message="Replaced Thumbnail";
        }
        else
        {
            $message .= " Failed to upload thumbnail. ";
            $error = true;
        }
    }
}
elseif($action==="createthumbnail") {
    $displayeditform = true;

    $imagedata = getimage($filename);
    if (!empty($imagedata['thumbnail_filename'])) {
        deletethumbnail($filename);
    }

    $thsuccess = createthumbnail(getimagedir($imagedata['path']), $filename);

    if($thsuccess) {
        addthumbnail($filename, make_thumbnail_filename($filename));
        $message .= "Thumbnail for <em>".$filename."</em> created successfully.";
    }
    else
    {
        $message .= "<br />Failed to create thumbnail. ";
        $error = true;
    }
}
elseif($action==="addunknownfile") {
    $displayeditform = true;
    $filename=$_POST['filename'];

    if(imageexists($filename)) {
        $message = "Image already exists: ".$filename;
        $error = true;
    }
    else
    {
        if(isset($_POST['subpath'])) { $subpath = $_POST['subpath'];
        } else { $subpath = "";
        }
        addimage($filename, $subpath, $caption, $source, $sourcelink, $copyright, $permission);
        addimagecategories($filename, $selectedcats);
        $thumbnail = make_thumbnail_filename($filename);
        if (file_exists(getimagepath($thumbnail, $subpath))) {
            addthumbnail($filename, $thumbnail);
        }
        $message="Added Image";
    }
}
elseif($action==="delete") {
    $form = new DeleteImageConfirmForm($filename);
}
elseif($action==="deletethumbnail") {
    $form=new DeleteThumbnailConfirmForm($filename);
}
elseif($action==="deleteunknownfile") {
    if(isset($_POST['deletefileconfirm'])) {
        if (isset($_POST['subpath'])) {
            $subpath = $_POST['subpath'];
        } else {
            $subpath = "";
        }
        $success = deletefile(getproperty("Image Upload Path").$subpath, $filename);
        if ($success) {
            $message = "File <em>$filename</em> deleted.";
        } else {
            $message = "Error deleting file <em>$filename</em>.";
            $error = true;
        }
    } else {
        $message = "File delete not confirmed!";
        $error = true;
    }
}
elseif($action==="executedelete") {
    if(isset($_POST['delete'])) {
        $pages=pagesforimage($filename);
        $newsitems=newsitemsforimage($filename);
        if (empty($pages) && empty($newsitems)) {
            $imagedata = getimage($filename);
            deletemobilethumbnail($imagedata);

            $imagedir = getproperty("Image Upload Path").$imagedata['path'];

            $thumbnail = $imagedata['thumbnail_filename'];
            if (!empty($thumbnail)) {
                if(!file_exists($projectroot."/".$imagedir."/".$thumbnail)) { $success = true;
                } else { $success = deletefile($imagedir, $thumbnail);
                }

                if($success) {
                    deletefile($imagedir, $filename);
                    if(!file_exists($filename)) {
                        deleteimage($filename);
                        $message = "Deleted image <em>".$filename."</em>";
                    }
                    else
                    {
                        $message = "Failed to delete image file <em>".$filename."</em> from dir <em>".$imagedir."</em>";
                        $displayeditform = true;
                        $error = true;
                    }
                }
                else
                {
                    $message = "Failed to delete  thumbnail file <em>".$thumbnail."</em> from dir <em>".$imagedir."</em>";
                    $displayeditform = true;
                    $error = true;
                }
            }
            else
            {
                deletefile($imagedir, $filename);
                if(!file_exists($filename)) {
                    deleteimage($filename);
                    $message = "Deleted image <em>".$filename."</em>";
                }
                else
                {
                    $message = "Failed to delete image file <em>".$filename."</em> from dir <em>".$imagedir."</em>";
                    $displayeditform = true;
                    $error = true;
                }
            }
        }
        else
        {
            $message="Could not delete image, because it is still used in the following page(s): ";
            $displayeditform=true;
            for($i=0;$i<count($pages);$i++)
            {
                $message.='<a href="admin.php'.makelinkparameters(array("page" => $pages[$i])).'" target="_blank">#'.$pages[$i].'</a>';
            }
            $message.='<br />And in the following Newsitem(s): ';
            for($i=0;$i<count($newsitems);$i++)
            {
                $newspage=getpagefornewsitem($newsitems[$i]);
                $linkparameters=array();
                $linkparameters["page"] = $newspage;
                $linkparameters["offset"] = getnewsitemoffset($newspage, 1, $newsitems[$i], true);
                $linkparameters["action"] = "editcontents";
                $message.='<a href="edit/newsedit.php'.makelinkparameters($linkparameters).'" target="_blank">#'.$newsitems[$i].' on page #'.$newspage.'</a>. ';
            }
            $error = true;
        }
    }
    else
    {
        $message="Deleting aborted";
        $displayeditform=true;
    }
}
elseif($action==="executethumbnaildelete") {
    $displayeditform = true;
    if (isset($_POST['delete'])) {
        $imagedata = getimage($filename);
        $thumbnail = $imagedata['thumbnail_filename'];
        if (!empty($thumbnail)) {
            $success = deletethumbnail($filename);
            if ($success) {
                $message="Thumbnail for <em>$filename</em> deleted.";
            } else {
                $message = "Failed to delete thumbnail file <em>$thumbnail</em> from dir <em>$imagedir</em>";
                $error = true;
            }
        } else {
            $message = "No thumbnail found!";
            $error = true;
        }
    } else {
        $message="Deleting aborted";
    }
    unset($_GET['action']);
    unset($_POST['action']);
}

if($form) {
    $adminimagepage = new AdminImagePage($filename, $form, new AdminMessage($message, $error));
}
else
{
    $addimageform = new AddImageForm($filename, $caption, $source, $sourcelink, $copyright, $permission, isset($_POST["dontcreatethumbnail"]),  isset($_POST["resizeimage"]));
    $form = new ImageList($offset);
    $adminimagepage = new AdminImagePage($filename, $form, new AdminMessage($message, $error), $addimageform, $displayeditform);
}

print($adminimagepage->toHTML());

//
// when replacing an images, the extension must be the same
//
function checkextension($oldfile, $newfile) {
    $oldextension = pathinfo($oldfile, PATHINFO_EXTENSION);
    $newextension = pathinfo($newfile, PATHINFO_EXTENSION);
    if (mb_strtolower($oldextension, 'UTF-8') === mb_strtolower($newextension, 'UTF-8')) {
        return "";
    }
    return "Wrong file extension <em> $newextension </em>, expected <em> $oldextension </em>. ";
}

// Ensure that the browser won't cache the old images
function clear_browser_cache_headers() {
    header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
    header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Cache-Control: post-check=0, pre-check=0', false);
    header('Pragma: no-cache');
}

?>
