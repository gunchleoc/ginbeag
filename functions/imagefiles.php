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
$projectroot=substr($projectroot, 0, strrpos($projectroot, "functions"));

//
// security: exif_imagetype() to make sure it's an image
//
if (!function_exists('exif_imagetype')) {
    function exif_imagetype($filename)
    {
        if ((list($width, $height, $type, $attr) = getimagesize($filename)) !== false) { return $type;
        }
        return false;
    }
}

//
// creates a thumbnail for the file
//
function createthumbnail($path, $filename, $ismobile = false)
{
    global $projectroot;

    if (!file_exists($path."/".$filename)) {
        return false;
    }

    $extension=substr($filename, strrpos($filename, "."), strlen($filename));
    $imagename=substr($filename, 0, strrpos($filename, "."));
    $thumbname=$imagename.'_thn'.$extension;

    if($ismobile) {
        if(!file_exists($path."/mobile")) {
            mkdir($path."/mobile", 0757);
            @copy($projectroot.getproperty("Image Upload Path")."/index.html", $path."/mobile/index.html");
            @copy($projectroot.getproperty("Image Upload Path")."/index.php", $path."/mobile/index.php");
        }
        return createresizedimage($path."/".$filename, $path."/mobile/".$thumbname, getproperty("Mobile Thumbnail Size"), true);
    }
    else
    {
        return createresizedimage($path."/".$filename, $path."/".$thumbname, getproperty("Thumbnail Size"), false);
    }
}

//
// resizes the width of an image down to the default width
//
function resizeimagewidth($path, $filename)
{
    return createresizedimage($path."/".$filename, $path."/".$filename, getproperty("Image Width"), true);
}


//
// scales the image size in $oldfile down to $pixelsand saves it to $newfile
//
function createresizedimage($oldfile, $newfile, $pixels, $widthonly = false)
{
    $success = false;
    if (extension_loaded('gd') && function_exists('gd_info')) {
        if(file_exists($oldfile)) {
            $imagetype = exif_imagetype($oldfile);

            if($imagetype == IMAGETYPE_GIF && function_exists('imagecreatefromgif')) {
                $image = @imagecreatefromgif($oldfile);
                if($image) {
                    $image = scaleimage($image, $pixels, $widthonly);
                    if($image) { $success = @imagegif($image, $newfile);
                    }
                }
            }
            elseif($imagetype == IMAGETYPE_JPEG && function_exists('imagecreatefromjpeg')) {
                $image = @imagecreatefromjpeg($oldfile);
                if($image) {
                    $image = scaleimage($image, $pixels, $widthonly);
                    if($image) { $success = @imagejpeg($image, $newfile, 90);
                    }
                }
            }
            elseif($imagetype == IMAGETYPE_PNG && function_exists('imagecreatefrompng')) {
                $image = @imagecreatefrompng($oldfile);
                if($image) {
                    $image = scaleimage($image, $pixels, $widthonly);
                    if($image) { $success = @imagepng($image, $newfile, 9);
                    }
                }
            }
            elseif($imagetype == IMAGETYPE_WBMP && function_exists('imagecreatefromwbmp')) {
                $image = @imagecreatefromwbmp($oldfile);
                if($image) {
                    $image = scaleimage($image, $pixels, $widthonly);
                    if($image) { $success = @imagewbmp($image, $newfile);
                    }
                }
            }
            elseif($imagetype == IMAGETYPE_XBM && function_exists('imagecreatefromxbm')) {
                $image = @imagecreatefromxbm($oldfile);
                if($image) {
                    $image = scaleimage($image, $pixels, $widthonly);
                    if($image) { $success = @imagexbm($image, $newfile);
                    }
                }
            }
        }
        else if (DEBUG) { print("File not found: ".basename($oldfile));
        }
    }
    else { print("No GD extension found");
    }
    return $success;
}


//
// Scales a gd library image down to $pixels size
//
function scaleimage($image, $pixels, $widthonly = false)
{
    $dimensions = array("width" => imagesx($image), "height" => imagesy($image), "resized" => false);

    if($dimensions["width"] > $pixels) {
        $dimensions["resized"] = true;
        $factor = $dimensions["width"] / $pixels;
        $dimensions["width"] = floor($dimensions["width"] / $factor);
        $dimensions["height"] = floor($dimensions["height"] / $factor);
    }
    if(!$widthonly && $dimensions["height"] > $pixels) {
        $dimensions["resized"] = true;
        $factor = $dimensions["height"] / $pixels;
        $dimensions["width"] = floor($dimensions["width"] / $factor);
        $dimensions["height"] = floor($dimensions["height"] / $factor);
    }
    if(!$dimensions["resized"]) { return $image;
    }

    $result = imagecreatetruecolor($dimensions["width"], $dimensions["height"]);
    $success = @imagecopyresampled($result, $image, 0, 0, 0, 0, $dimensions["width"], $dimensions["height"], imagesx($image), imagesy($image));

    if($success) { return $result;
    } else { return false;
    }
}

?>
