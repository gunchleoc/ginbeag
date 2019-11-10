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

require_once $projectroot."functions/pagecontent/gallerypages.php";
require_once $projectroot."includes/objects/template.php";
require_once $projectroot."includes/objects/elements.php";
require_once $projectroot."includes/objects/images.php";
require_once $projectroot."includes/objects/page.php";
require_once $projectroot."includes/includes.php";

//
//
//
class GalleryCaptionedImage extends Template
{

    function __construct($image, $width, $height, $showhidden)
    {
        parent::__construct();

        // Make the image
        $this->vars['image'] = new Image($image['image_filename'], array('imageautoshrink' => true, 'usethumbnail' => true), array("page" => $this->stringvars['page']), $showhidden);

        // Make the caption
        $this->vars['caption'] = new ImageCaption($image, IMAGECAPTION_MAXCHARS);

        // CS stuff
        $this->stringvars['halign'] = "float:left; ";
        $this->stringvars['width'] = "" . $width . "px";
        $this->stringvars['height'] =  "" . $height . "px";
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("images/captionedimage.tpl");
    }
}

//
// main class for gallery pages
//
class GalleryPage extends Template
{

    function __construct($introcontents, $offset = 0, $showhidden = false) {
        global $projectroot;

        parent::__construct();

        $this->vars['pageintro'] = new PageIntro($introcontents['title_page'], $introcontents['introtext'], "introtext", $introcontents, $showhidden);

        // Pagemenu
        $imagesperpage=getproperty("Gallery Images Per Page");
        $noofimages = countgalleryimages($this->stringvars['page']);
        if (!$offset) {
            $offset=0;
        }
        $this->vars['pagemenu']= new PageMenu($offset, $imagesperpage, $noofimages);


        // Images
        $images = getgalleryimageslimit($this->stringvars['page'], $offset, $imagesperpage);

        if (ismobile()) {
            // create images
            foreach ($images as $id => $image) {
                if (!empty($image['image_filename'])) {
                    $this->listvars['galleryimage'][] = new CaptionedImage($image, $showhidden);
                }
            }
        } else {
            $items = array();

            // Determine image dimensions
            $width = getproperty("Thumbnail Size") + IMAGECAPTION_LINEHEIGHT;
            $height = 0;
            $charsperline = $width / 8; // Counted 25 chars for width = 208;

            foreach ($images as $id => $image) {
                if (empty($image['image_filename'])) {
                    unset($image);
                    continue;
                }
                $image['usethumbnail'] = true;
                $image['imageautoshrink'] = true;

                $image = Image::make_imagedata($image);

                $captionlength = 10; // A bit of extra for concatenation
                $captionlength += min(strlen($image['caption']), IMAGECAPTION_MAXCHARS);
                $captionlength += min(strlen($image['source']), IMAGECAPTION_MAXCHARS);
                $captionlength += min(strlen($image['copyright']), IMAGECAPTION_MAXCHARS);

                if ($image['permission'] == PERMISSION_GRANTED) {
                    $captionlength += strlen(getlang("image_bypermission"));
                }

                $lines = floor($captionlength / $charsperline);

                $width = max($width, $image['width'] + IMAGECAPTION_LINEHEIGHT);
                $width = max($width, $image['width']);

                $height = max($height, $image['height'] + $lines * IMAGECAPTION_LINEHEIGHT);
                array_push($items, $image);
            }

            $height += 2 * IMAGECAPTION_LINEHEIGHT;

            // Now we have the dimensions. Add the images.
            foreach ($items as $image) {
                $this->listvars['galleryimage'][] = new GalleryCaptionedImage($image, $width, $height, $showhidden);
            }
        }
        $this->vars['editdata']= new Editdata($introcontents, $showhidden);
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("pages/gallery/gallerypage.tpl");
    }
}

?>
