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
require_once $projectroot."includes/functions.php";

//
// Image with thumbhail & link to showimage.php
//
class Image extends Template
{

    function __construct($filename, $imageautoshrink, $usethumbnail, $params = array(), $showhidden=false)
    {
        global $projectroot;

        parent::__construct();

        $params["image"] = $filename;
        if(ismobile()) { $params["m"] = "on";
        }

        $image="";
        $alttext=title2html(getcaption($filename));
        if(!$alttext) { $alttext = $filename;
        }

        $thumbnail=getthumbnail($filename);
        $filepath=getimagepath($filename);
        $thumbnailpath = getthumbnailpath($filename, $thumbnail);
        if(file_exists($filepath) && !is_dir($filepath)) {
            if(ismobile()) {
                $usethumbnail = true;
                $extension = substr($filename, strrpos($filename, "."), strlen($filename));
                $thumbname = substr($filename, 0, strrpos($filename, ".")).'_thn'.$extension;
                $path = $projectroot.getproperty("Image Upload Path").getimagesubpath(basename($filename));

                // make sure a mobile thumbnail exists
                if (extension_loaded('gd') && function_exists('gd_info')) {
                    if(!file_exists($path."/mobile/".$thumbname)) {
                        include_once $projectroot."functions/imagefiles.php";
                        createthumbnail($path, $filename, getproperty("Mobile Thumbnail Size"), true);
                    }
                }

                $path = $path."/mobile/".$thumbname;
                if(file_exists($path)) {
                    $thumbnailpath = $path;
                    $thumbnail = $thumbname;
                }
            }

            if($usethumbnail && $thumbnail && file_exists($thumbnailpath)) {
                $dimensions=getimagedimensions($thumbnailpath);
                if($showhidden) {
                    $image='<a href="'.getprojectrootlinkpath().'admin/showimage.php'.makelinkparameters($params).'"><img src="'.getimagelinkpath($thumbnail, getimagesubpath($filename)).'" width="'.$dimensions["width"].'" height="'.$dimensions["height"].'" alt="'.$alttext.'" title="'.$alttext.'" border="0"></a>';
                } else {
                    $image='<a href="'.getprojectrootlinkpath().'showimage.php'.makelinkparameters($params).'"><img src="'.getimagelinkpath($thumbnail, getimagesubpath($filename)).'" width="'.$dimensions["width"].'" height="'.$dimensions["height"].'" alt="'.$alttext.'" title="'.$alttext.'" border="0"></a>';
                }
            }
            else
            {
                $dimensions=calculateimagedimensions($filepath, $imageautoshrink);
                if($showhidden) {
                    $image='<a href="'.getprojectrootlinkpath().'admin/showimage.php'.makelinkparameters($params).'"><img src="'.getimagelinkpath($filename, getimagesubpath($filename)).'" width="'.$dimensions["width"].'" height="'.$dimensions["height"].'" title="'.$alttext.'" alt="'.$alttext.'" border="0"></a>';
                } else {
                    $image='<a href="'.getprojectrootlinkpath().'showimage.php'.makelinkparameters($params).'"><img src="'.getimagelinkpath($filepath, getimagesubpath($filename)).'" width="'.$dimensions["width"].'" height="'.$dimensions["height"].'" title="'.$alttext.'" alt="'.$alttext.'" border="0"></a>';
                }
            }
        }
        else
        {
            $image='<span class="smalltext">Image <i>'.$filename.'</i></span>';
        }
        $this->stringvars['image']=$image;
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("images/image.tpl");
    }
}


//
// Image with caption used in most page types
//
class CaptionedImage extends Template
{

    function __construct($filename, $imageautoshrink, $usethumbnail, $halign="left", $linkparams=array(), $showhidden=false)
    {
        global $projectroot;
        parent::__construct();

        // CSS stuff
        if (ismobile() || $halign == "center") {
            $this->stringvars['halign']="";
            $this->stringvars['center']="center";
        }
        elseif ($halign == "right") {
            $this->stringvars['halign']="float:right; ";
        }
        elseif ($halign == "left") {
            $this->stringvars['halign']="float:left; ";
        }
        else
        {
            $this->stringvars['halign']=$halign;
        }

        // determine image dimensions
        $width=getproperty("Thumbnail Size");

        $filepath=getimagepath($filename);
        $thumbnail = getthumbnail($filename);
        $thumbnailpath=getthumbnailpath($filename, $thumbnail);

        if(ismobile()) {
            $usethumbnail = true;
            $extension = substr($filename, strrpos($filename, "."), strlen($filename));
            $thumbname = substr($filename, 0, strrpos($filename, ".")).'_thn'.$extension;
            $path = $projectroot.getproperty("Image Upload Path").getimagesubpath(basename($filename));

            // make sure a mobile thumbnail exists
            if (extension_loaded('gd') && function_exists('gd_info')) {
                if(!file_exists($path."/mobile/".$thumbname)) {
                    include_once $projectroot."functions/imagefiles.php";
                    createthumbnail($path, $filename, getproperty("Mobile Thumbnail Size"), true);
                }
            }

            $path = $path."/mobile/".$thumbname;
            if(file_exists($path)) {
                $thumbnailpath = $path;
                $thumbnail = $thumbname;
            }
        }

        if($usethumbnail) {
            if(thumbnailexists($thumbnail) && file_exists($thumbnailpath) && !is_dir($thumbnailpath)) {
                $dimensions = getimagedimensions($thumbnailpath);
                $width = $dimensions["width"];

            }
            else if(imageexists($filename) && file_exists($filepath) && !is_dir($filepath)) {
                $dimensions = getimagedimensions($filepath);
                $width = $dimensions["width"];

            }
        }
        else if(imageexists($filename) && file_exists($filepath) && !is_dir($filepath)) {
            $dimensions=calculateimagedimensions($filepath, $imageautoshrink);
            $width=$dimensions["width"];
        }

        $width = $width + IMAGECAPTIONLINEHEIGHT;
        $this->stringvars["width"] = $width;

        // make the image
        if(imageexists($filename)) {
            $this->vars['image'] = new Image($filename, $imageautoshrink, $usethumbnail, $linkparams, $showhidden);
        }
        else { $this->stringvars['image']='<i>'.$filename.'</i>';
        }

        $this->vars['caption'] = new ImageCaption($filename);

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

    function __construct($filename)
    {
        global $projectroot;
        parent::__construct();
        $result="";

        $captionfontsize=10;
        $maxchars = 50;
        if(ismobile()) { $maxchars = 20;
        }

        $image=getimage($filename);

        if(array_key_exists("caption", $image)) { $caption=$image['caption'];
        } else { $caption="";
        }

        if(array_key_exists("source", $image)) { $source=$image['source'];
        } else { $source="";
        }

        if(array_key_exists("sourcelink", $image)) { $sourcelink=$image['sourcelink'];
        } else { $sourcelink="";
        }

        if(array_key_exists("copyright", $image)) { $copyright=$image['copyright'];
        } else { $copyright="";
        }

        if(array_key_exists("permission", $image)) { $permission=$image['permission'];
        } else { $permission=NO_PERMISSION;
        }

        $caption=title2html($caption);
        $source=title2html($source);
        $copyright=title2html($copyright);

        // now assemble it
        if($caption) {
            $captiontitle=$caption;
            if(strlen($caption) > $maxchars) {
                $caption = substr(html_entity_decode($caption, ENT_QUOTES, 'UTF-8'), 0, $maxchars)."...";
            }
            $result.='<span title="'.$captiontitle.'">'.$caption.'</span>';
        }
        if($source) {
            $sourcetitle=$source;
            if(strlen($source) > $maxchars) {
                $source = substr(html_entity_decode($source, ENT_QUOTES, 'UTF-8'), 0, $maxchars)."...";
            }
            if($caption) {
                $result.='<br>';
            }
            $result.='<span title="'.getlang("image_image").$sourcetitle.'">'.getlang("image_image");
            if($sourcelink) {
                $result.='<a href="'.$sourcelink.'" title="'.$sourcetitle.'" target="_blank">';
            }
            $result.=$source;
            if($sourcelink) {
                $result.='</a>';
            }
            $result.='</span>';
        }
        if($copyright) {
            $copyrighttitle=$copyright;
            if(strlen($copyright) > $maxchars) {
                $copyright = substr(html_entity_decode($copyright, ENT_QUOTES, 'UTF-8'), 0, $maxchars)."...";
            }

            if($caption || $source) {
                $result.='.<br>';
            }
            $result.='<span title="&copy; '.$copyrighttitle.'">&copy; '.$copyright.'.</span>';
        }
        if($permission==PERMISSION_GRANTED) { $result.=getlang("image_bypermission");
        }

        $this->stringvars['caption']=$result;
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("images/imagecaption.tpl");
    }
}

?>
