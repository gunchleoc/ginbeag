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
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."admin/includes/objects/forms.php";
require_once $projectroot."functions/pagecontent/gallerypages.php";
require_once $projectroot."includes/objects/template.php";
require_once $projectroot."admin/includes/objects/imageeditor.php";
require_once $projectroot."admin/includes/objects/editor.php";


//
//
//
class ShowAllImagesButton extends Template
{
    function __construct($isshowall=true,$noofimages,$imagesperpage)
    {
        parent::__construct();

        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["noofimages"] = $noofimages;
        $linkparams["action"] = "editcontents";
        $this->stringvars['actionvars']= makelinkparameters($linkparams);

        if($isshowall) {
            $this->stringvars['name']="showall";
            $this->stringvars['value']="Show all images (".$noofimages.")";
        }
        else
        {
            $this->stringvars['name']="dontshowall";
            $this->stringvars['value']="Show ".$imagesperpage." images per page";
        }
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/edit/showallimagesbutton.tpl");
    }
}


//
//
//
class GalleryImageForm extends Template
{
    function __construct($imageid, $imagedata, $offset, $noofimages, $showall) {
        parent::__construct($imageid, array(), array(0 => "admin/includes/javascript/editgallery.js"));
        $this->stringvars['javascript']=$this->getScripts();

        $linkparams["page"] = $this->stringvars['page'];
        $this->stringvars['imagelistpath']=getprojectrootlinkpath()."admin/editimagelist.php".makelinkparameters($linkparams);

        $linkparams["noofimages"] = $noofimages;
        $linkparams["offset"] = $offset;
        $linkparams["action"] = "editcontents";
        $this->stringvars['actionvars']= makelinkparameters($linkparams);

        $this->stringvars['imageid']=$imageid;

        $hiddenvars["galleryitemid"] = $imageid;
        if($showall) { $hiddenvars["showall"] = "true";
        }
        $this->stringvars['hiddenvars'] = $this->makehiddenvars($hiddenvars);

        $this->stringvars['imagefilename'] = $imagedata['image_filename'];
        $this->vars['image'] = new CaptionedImageAdmin($imagedata, $this->stringvars['page']);

        if(!getthumbnail($this->stringvars['imagefilename'])) {
            $this->stringvars['no_thumbnail']="This image has no thumbnail";
        }

        $this->vars['removeconfirmform']= new CheckboxForm("removeconfirm", "removeconfirm", "Confirm remove", false, "right");
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/edit/galleryimageform.tpl");
    }
}


//
//
//
class AddGalleryImageForm extends Template
{
    function __construct($offset, $noofimages, $showall)
    {
        parent::__construct();

        $linkparams["page"] = $this->stringvars['page'];
        $this->stringvars['imagelistpath']=getprojectrootlinkpath()."admin/editimagelist.php".makelinkparameters($linkparams);

        $linkparams["noofimages"] = $noofimages+1;
        $linkparams["offset"] = $offset;
        $linkparams["action"] = "editcontents";
        $this->stringvars['actionvars']= makelinkparameters($linkparams);

        $hiddenvars = array();
        if($showall) { $hiddenvars["showall"] = 1;
        }
        $this->stringvars['hiddenvars'] = $this->makehiddenvars($hiddenvars);
        $this->vars['submitrow'] = new SubmitRow("addgalleryimage", "Add Image", true);
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/edit/addgalleryimageform.tpl");
    }
}


//
//
//
class ReindexGalleryForm extends Template
{
    function __construct($showall)
    {
        parent::__construct();

        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["action"] = "editcontents";
        $this->stringvars['actionvars']= makelinkparameters($linkparams);

        $hiddenvars = array();
        if($showall) { $hiddenvars["showall"] = "true";
        }
        $this->stringvars['hiddenvars'] = $this->makehiddenvars($hiddenvars);
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/edit/reindexgalleryform.tpl");
    }
}



//
//
//
class EditGallery extends Template
{

    function __construct($page, $offset, $imagesperpage, $showall)
    {
        parent::__construct($page, array(0 => "includes/javascript/jcaret.js"));
        $this->stringvars['javascript']=$this->getScripts();

        $noofimages = countgalleryimages($page);
        $images = array();

        if ($showall) {
            $offset = 0;
            $noofdisplayedimages = $noofimages;
            $images = getgalleryimages($page);
        }
        else
        {
            $noofdisplayedimages = $imagesperpage;
            $images = getgalleryimageslimit($page, $offset, $noofdisplayedimages);
        }

        $this->vars['showallbutton'] = new ShowAllImagesButton(!$showall, $noofimages, $imagesperpage);
        $this->vars['pagemenu'] = new PageMenu($offset, $noofdisplayedimages, $noofimages);

        if($noofimages > 0) {
            foreach($images as $id => $filename) {
                $this->listvars['imageform'][] = new GalleryImageForm($id, $filename, $offset, $noofimages, $showall);
            }
        }
        else
        {
            $this->stringvars['imageform']="There are no images in this gallery";
        }

        $this->vars['addform'] = new AddGalleryImageForm($offset, $noofimages, $showall);
        $this->vars['reindexform'] = new ReindexGalleryForm($showall);
        $this->vars['navigationbuttons']= new PageEditNavigationButtons(new GeneralSettingsButton(), new EditPageIntroSettingsButton());
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/edit/editgallery.tpl");
    }
}

?>
