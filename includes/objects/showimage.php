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
require_once $projectroot."includes/objects/page.php";

//
// Showimage main class
//
class Showimage extends Template
{

    function __construct($page,$filename,$item=0,$showhidden=false)
    {
        global $_POST, $_GET;
        parent::__construct();

        /*if($page && !$showhidden)
        {
        if(ispagerestricted($page))
        {
        //checkpublicsession($page);
        }
        }
        */
        $this->stringvars['site_description']=title2html(getproperty("Site Description"));

        $pagetitle=getlang("image_viewing");


        $image = getimage($filename);
        $caption = $image['caption'];

        if($caption) {
            if(strlen($caption)>30) { $caption = substr($caption, 0, 30)."...";
            }
            $caption = title2html($caption);
            $pagetitle = $pagetitle." - ".$caption;
        }
        $this->vars['pageintro'] = new PageIntro($pagetitle, "");

        if(ismobile()) { $displaytype = "mobile";
        } else { $displaytype = "page";
        }
        $this->vars['header']= new PageHeader($this->stringvars['page'], $pagetitle, $pagetitle, "", $displaytype);
        $this->vars['navigator'] = new Navigator($this->stringvars['page'], false, 1, $displaytype, $showhidden);

        if(getproperty('Display Banners')) {
            $this->vars['banners'] = new BannerList();
        }

        $this->vars['editdata']= new ImageEditdata($filename, $showhidden);

        $this->vars['footer'] = new PageFooter();

        $linkparams = array("page" => $this->stringvars['page']);
        if(ismobile()) { $linkparams["m"] = "on";
        }

        // link to gallery page
        if($this->stringvars['page']!=0) {
            if($showhidden) {
                $this->stringvars['returnpage']='pagedisplay.php'.makelinkparameters($linkparams);
            } else {
                $this->stringvars['returnpage']='index.php'.makelinkparameters($linkparams);
            }
            $this->stringvars['returnpagetitle']=getlang("image_viewthumbnails");
        }


        // collect items for navigation through images

        $previousitem = -1;
        $nextitem = -1;

        if($item!=0 && isset($_POST[0])) {
            // generate item array from http_post_vars
            $items=array();
            for($i=0;isset($_POST[$i]);$i++)
            {
                $items[$i] =$_POST[$i];
            }
            $previousitem = $item-1;
            $nextitem = $item+1;
        }

        elseif($this->stringvars['page']!=0) {
            // generate item array
            $items=getgalleryimagefilenames($this->stringvars['page']);
            $item = 0;
            if(isset($_GET['image'])) {
                $item = array_search($_GET['image'], $items);
            }
            $previousitem = $item-1;
            $nextitem = $item+1;
        }

        if(($this->stringvars['page']!=0 || $item!=0) && $previousitem >=0) {
            $this->stringvars['previous'] = $this->makeitemfields($items);

            $linkparams["item"] = $previousitem;
            $this->stringvars['previousitem'] = makelinkparameters($linkparams);
        }
        if(($this->stringvars['page']!=0 || $item!=0) && $nextitem >=0 && $nextitem  < count($items)) {
            $this->stringvars['next'] = $this->makeitemfields($items);

            $linkparams["item"] = $nextitem;
            $this->stringvars['nextitem'] = makelinkparameters($linkparams);
        }

        // make image
        if(strlen($filename)>1) {
            $this->stringvars['imagepath'] = getimagelinkpath($filename, getimagesubpath(basename($filename)));
            $dimensions = getimagedimensions(getimagepath($filename));
            $this->stringvars['width'] = $dimensions["width"];
            $this->stringvars['height'] = $dimensions["height"];
            $this->stringvars['simplecaption'] = title2html($caption);
            $this->vars['caption'] = new ImageCaption($image);

        }
        else
        {
            $this->stringvars['noimage'] = "No image";
        }
    }


    //
    // generates hidden fields from item array for form
    //
    function makeitemfields($items)
    {
        $result="";
        for($i=0; $i<count($items);$i++)
        {
            $result.='<input type="hidden" name="'.$i.'" value="'.$items[$i].'" />';
        }
        return $result;
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("images/showimage.tpl");
    }
}


//
// container for editdata
//
class ImageEditdata extends Template
{
    function __construct($filename, $showhidden = false)
    {
        parent::__construct();
        $editdate = getuploaddate($filename);

        if ($showhidden) {
            $editor = getdisplayname(getuploader($filename));
            $this->stringvars['footerlastedited']=sprintf(getlang("footer_imageuploadedauthor"), formatdatetime($editdate), $editor);
        } else {
            $this->stringvars['footerlastedited']=sprintf(getlang("footer_imageuploaded"), formatdatetime($editdate));
        }

        $this->stringvars['topofthispage']=getlang("pagemenu_topofthispage");
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("images/imageeditdata.tpl");
    }
}


?>
