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
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));

require_once $projectroot."includes/objects/template.php";
require_once $projectroot."functions/images.php";
require_once $projectroot."functions/imagefiles.php";

//
// Image with thumbhail & link to showimage.php
//
class Image extends Template
{

    function __construct($filename, $imagedata, $params = array(), $showhidden = false) {
        global $projectroot;

        parent::__construct();

        $params["image"] = $filename;
        if (ismobile()) {
            $params["m"] = "on";
        }

        if (!isset($imagedata['image_filename'])) {
            $imagedata['image_filename'] = $filename;
        }

        if (!isset($imagedata['imageexists'])) {
            $imagedata = self::make_imagedata($imagedata);
        }

        $alttext = title2html(empty($imagedata['caption']) ? $imagedata['image_filename'] : $imagedata['caption']);
        if (empty($alttext)) {
            $alttext = $filename;
        }

        if ($imagedata['imageexists']) {
            $targetstring = '';
            $rootlink = getprojectrootlinkpath();
            if (!isset($imagedata['link'])) {
                $imagedata['link'] = $showhidden ?
                    $rootlink . 'admin/showimage.php' . makelinkparameters($params) :
                    $rootlink . 'showimage.php' . makelinkparameters($params);
            } else {
                $targetstring = 'target="_blank"';
            }

            $src = $imagedata['usethumbnail'] ?
                getimagelinkpath($imagedata['thumbnail_filename'], $imagedata['thumbnailpath']) :
                getimagelinkpath($filename, $imagedata['path']);
            // Make sure that the browser won't cache while editing
            if ($showhidden) {
                 $src .= "?" . time();
            }

            $image = '<a href="'.$imagedata['link'].'" '.$targetstring.'><img src="'.$src.'" width="'.$imagedata['width'].'" height="'.$imagedata['height'].'" alt="'.$alttext.'" title="'.$alttext.'" border="0" /></a>';
        } else {
            $image='<span class="smalltext">Image <i>'.$filename.'</i></span>';
        }
        $this->stringvars['image']=$image;
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("images/image.tpl");
    }

    // Determine image dimensions and take care of mobile thumbnail
    public static function make_imagedata($imagedata) {
        global $projectroot;

        $imagedata['width'] = getproperty("Thumbnail Size");
        $imagedata['height'] = IMAGECAPTION_LINEHEIGHT;

        // Ensure image is known
        $filename_dataset = getimage($imagedata['image_filename']);
        if (empty($filename_dataset)) {
            $imagedata['imageexists'] = false;
            return $imagedata;
        }

        // Ensure complete dataset
        $imagedata = array_merge($imagedata, $filename_dataset);
        if (!isset($imagedata['usethumbnail'])) {
            $imagedata['usethumbnail'] = true;
        }
        if (!isset($imagedata['imageautoshrink'])) {
            $imagedata['imageautoshrink'] = true;
        }

        // Ensure file exists
        $filename = $imagedata['image_filename'];
        $filepath = getimagepath($filename, $imagedata['path']);
        $imagedata['imageexists'] = file_exists($filepath) && !is_dir($filepath);
        if (!$imagedata['imageexists']) {
            return $imagedata;
        }

        // Calculate dimensions and thumbnail
        $imagedata['usethumbnail'] = $imagedata['usethumbnail'] || ismobile();

        if (!$imagedata['usethumbnail']) {
            $dimensions = calculateimagedimensions($filepath, $imagedata['imageautoshrink']);
            $imagedata['width'] = $dimensions['width'];
            $imagedata['height'] = $dimensions['height'];
            return $imagedata;
        }

        $imagedata['width'] = getproperty('Thumbnail Size');
        $thumbnail = $imagedata['thumbnail_filename'];

        $thumbnailpath = getimagepath($thumbnail, $imagedata['path']);
        $thumbnailrelativepath = $imagedata['path'];

        if (ismobile()) {
            $extension = substr($filename, strrpos($filename, "."), strlen($filename));
            $thumbnail = make_thumbnail_filename($filename);
            $path = $projectroot . getproperty("Image Upload Path") . $imagedata['path'];

            // make sure a mobile thumbnail exists
            if (!file_exists("$path /mobile/ $thumbnail")) {
                if (extension_loaded('gd') && function_exists('gd_info')) {
                    include_once $projectroot . 'functions/imagefiles.php';
                    createthumbnail($path, $filename, getproperty("Mobile Thumbnail Size"), true);
                }
            }

            $path = "$path /mobile/ $thumbnail";
            if (file_exists($path)) {
                $thumbnailpath = $path;
                $thumbnailrelativepath = "$path /mobile/";
            }
        }

        $imagedata['usethumbnail'] = !empty($thumbnail) && file_exists($thumbnailpath) && !is_dir($thumbnailpath);

        if ($imagedata['usethumbnail']) {
            $dimensions = getimagedimensions($thumbnailpath);
            $imagedata['width'] = $dimensions['width'];
            $imagedata['height'] = $dimensions['height'];
        } else {
            $dimensions = calculateimagedimensions($filepath, $imagedata['imageautoshrink']);
            $imagedata['width'] = $dimensions['width'];
            $imagedata['height'] = $dimensions['height'];
        }

        $imagedata['thumbnail_filename'] = $thumbnail;
        $imagedata['thumbnailpath'] = $thumbnailrelativepath;
        return $imagedata;
    }
}


//
// Image with caption used in most page types
//
class CaptionedImage extends Template
{

    function __construct($imagedata, $linkparams=array(), $showhidden=false) {
        parent::__construct();

        // CSS stuff
        if (ismobile()) {
            $this->stringvars['halign'] = '';
            $this->stringvars['center'] = 'center';
        } else {
            $imagealign = isset($imagedata['imagealign']) ? $imagedata['imagealign'] : 'left';
            switch ($imagealign) {
                case 'right':
                    $this->stringvars['halign']="float:right; ";
                break;
                case 'left':
                    $this->stringvars['halign'] = "float:left; ";
                break;
                case 'center':
                    $this->stringvars['halign'] = '';
                    $this->stringvars['center'] = 'center';
            }
        }

        // Get the dimensions and thumbnail data
        $filename = $imagedata['image_filename'];
        $imagedata = Image::make_imagedata($imagedata);

        if (!ismobile()) {
            $imagedata['width'] += IMAGECAPTION_LINEHEIGHT;
            $this->stringvars['width'] = $imagedata['width'];
        }

        // make the image
        $this->vars['image'] = new Image($filename, $imagedata, $linkparams, $showhidden);
        $this->vars['caption'] = new ImageCaption($imagedata);

    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("images/captionedimage.tpl");
    }
}


//
// Formats an image caption with source etc.
//
class ImageCaption extends Template
{

    function __construct($image)
    {
        global $projectroot;
        parent::__construct();
        $result="";

        $caption = isset($image['caption']) ? title2html($image['caption']) : '';
        $source = isset($image['source']) ? title2html($image['source']) : '';
        $sourcelink = isset($image['sourcelink']) ? $image['sourcelink'] : '';
        $copyright = isset($image['copyright']) ? title2html($image['copyright']) : '';
        $permission = isset($image['permission']) ? $image['permission'] : NO_PERMISSION;

        // now assemble it
        if ($caption) {
            $captiontitle = $caption;
            if (strlen($caption) > IMAGECAPTION_MAXCHARS) {
                $caption = substr(html_entity_decode($caption, ENT_QUOTES, 'UTF-8'), 0, IMAGECAPTION_MAXCHARS);
                $caption = substr($caption, 0, strrpos($caption, " ")) . "&nbsp;…";
            }
            $result .= '<span title="'.$captiontitle.'">'.$caption.'</span>';
        }
        if ($source) {
            $sourcetitle = $source;
            if (strlen($source) > IMAGECAPTION_MAXCHARS) {
                $source = substr(html_entity_decode($source, ENT_QUOTES, 'UTF-8'), 0, IMAGECAPTION_MAXCHARS);
                $source = substr($source, 0, strrpos($source, " ")) . "&nbsp;…";
            }
            if ($caption) {
                $result .= '<br>';
            }
            $result .= '<span title="' . getlang("image_image") . $sourcetitle . '">' . getlang("image_image");
            if ($sourcelink) {
                $result .= '<a href="' . $sourcelink . '" title="' . $sourcetitle . '" target="_blank">';
            }
            $result .= $source;
            if ($sourcelink) {
                $result .= '</a>';
            }
            $result .= '</span>';
        }
        if ($copyright) {
            $copyrighttitle = $copyright;
            if (strlen($copyright) > IMAGECAPTION_MAXCHARS) {
                $copyright = substr(html_entity_decode($copyright, ENT_QUOTES, 'UTF-8'), 0, IMAGECAPTION_MAXCHARS);
                $copyright = substr($copyright, 0, strrpos($copyright, " ")) . "&nbsp;…";
            }

            if ($caption || $source) {
                $result .= '.<br>';
            }
            $result .= '<span title="&copy; ' . $copyrighttitle . '">&copy; ' . $copyright . '.</span>';
        }
        if ($permission == PERMISSION_GRANTED) {
            if (!$copyright) {
                $result .= '.';
            }
            $result .= getlang("image_bypermission");
        }

        $this->stringvars['caption'] = $result;
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("images/imagecaption.tpl");
    }
}
?>
