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
// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot, 0, strrpos($projectroot, "objects"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."includes/functions.php";
require_once $projectroot."includes/objects/template.php";
require_once $projectroot."includes/objects/forms.php";
require_once $projectroot."admin/includes/objects/images.php";


//
// Templating for Section Images
//
class ImageEditor extends Template
{

    function __construct($page, $elementid, $elementtype, $contents)
    {
        parent::__construct($page.'-'.$elementid, array(), array(0 => "admin/includes/javascript/imageeditor.js"));
        $this->stringvars['javascript']=$this->getScripts();

        $this->stringvars['image'] = isset($contents['image_filename']) ? $contents['image_filename'] : '';

        switch ($elementtype) {
            case "pageintro":
                $this->stringvars['title'] = 'Synopsis';
            break;
            case "articlesection":
            case "newsitemsection":
                $this->stringvars['title'] = empty($contents['sectiontitle']) ? 'Section' : 'Section "' . $contents['sectiontitle'] . '"';
            break;
            case "link":
                $this->stringvars['title'] = empty($contents['title']) ? 'Link' : 'Link "' . $contents['title'] . '"';
        }

        $this->stringvars['elementtype']=$elementtype;
        $this->stringvars['imagelistpath']=getprojectrootlinkpath()."admin/editimagelist.php".makelinkparameters(array("page" => $this->stringvars['page']));
        $this->vars['filenamepane'] = new ImageEditorFilenamePane($page, $elementid, $this->stringvars['image'], $elementtype);

        if ($elementtype=="link") {
            $this->vars['imagepane'] = new ImageEditorImagePane($page, $contents);
            $this->stringvars['alignmentpane'] = '';
            $this->stringvars['sizepane'] = '';
        } elseif($this->stringvars['image']) {
            $this->vars['imagepane'] = new ImageEditorImagePane($page, $contents);
            $this->vars['alignmentpane'] = new ImageEditorAlignmentPane($page, $elementid, $contents['imagealign']);
            $this->vars['sizepane'] = new ImageEditorSizePane($page, $elementid, $contents['imageautoshrink'], $contents['usethumbnail']);
        } else {
            $this->stringvars['imagepane'] = '';
            $this->stringvars['alignmentpane'] = '';
            $this->stringvars['sizepane'] = '';
        }
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/imageeditor.tpl");
    }
}


//
// Templating for assigning an image to a section
//
class ImageEditorFilenamePane extends Template
{

    function __construct($page,$elementid, $image, $elementtype)
    {
        parent::__construct($page.'-'.$elementid);

        $this->stringvars['image']=$image;
        $this->stringvars['elementtype']=$elementtype;
        $this->stringvars['imagefilename']=$image;
        if($this->stringvars['image']) { $this->stringvars['submitname']="Add / Change Image";
        } else { $this->stringvars['submitname']="Remove Image";
        }
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/imageeditorfilenamepane.tpl");
    }
}



//
// Templating for Image alignment within a section.
// Planned feature: scale image yes/no, use thumbnail yes/no
//
class ImageEditorAlignmentPane extends Template
{

    function __construct($page,$elementid, $imagealign)
    {
        parent::__construct($page.'-'.$elementid);

        $this->stringvars['submitname'] ="Save image alignment";

        if(!$imagealign) { $imagealign="left";
        }
        $this->vars['left_align_button']= new RadioButtonForm($this->stringvars["jsid"], "imagealign", "left", "Left", $imagealign==="left", "right");
        $this->vars['center_align_button']= new RadioButtonForm($this->stringvars["jsid"], "imagealign", "center", "Center", $imagealign==="center", "right");
        $this->vars['right_align_button']= new RadioButtonForm($this->stringvars["jsid"], "imagealign", "right", "Right", $imagealign==="right", "right");
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/imageeditoralignmentpane.tpl");
    }
}


//
// Templating for Image alignment within a section.
// Planned feature: scale image yes/no, use thumbnail yes/no
//
class ImageEditorSizePane extends Template
{

    function __construct($page,$elementid, $autoshrink, $usethumbnail)
    {
        parent::__construct($page.'-'.$elementid);

        $this->stringvars['submitname'] ="Save image size options";

        $this->vars['shrink_on_button']= new RadioButtonForm($this->stringvars["jsid"], "autoshrink", "on", "Shrink", $autoshrink, "right");
        $this->vars['shrink_off_button']= new RadioButtonForm($this->stringvars["jsid"], "autoshrink", "off", "Don't Shrink", !$autoshrink, "right");
        $this->vars['thumbnail_on_button']= new RadioButtonForm($this->stringvars["jsid"], "usethumbnail", "on", "Use Thumbnail", $usethumbnail, "right");
        $this->vars['thumbnail_off_button']= new RadioButtonForm($this->stringvars["jsid"], "usethumbnail", "off", "Don't Use Thumbnail", !$usethumbnail, "right");

    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/imageeditorsizepane.tpl");
    }
}



//
// Templating for showing the image in the form
//
class ImageEditorImagePane extends Template
{

    function __construct($page, $imagedata)
    {
        parent::__construct();

        if (!empty($imagedata['image_filename']) && imageexists($imagedata['image_filename'])) {
            $this->vars['image'] = new CaptionedImageAdmin($imagedata, $page);
        } else {
            $this->stringvars['image'] = isset($imagedata['image_filename']) ? $imagedata['image_filename'] : '';
        }
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/imageeditorimagepane.tpl");
    }
}

?>
