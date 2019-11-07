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

require_once $projectroot."functions/pages.php";
require_once $projectroot."includes/objects/template.php";
require_once $projectroot."includes/includes.php";
require_once $projectroot."includes/objects/forms.php";
require_once $projectroot."admin/includes/objects/forms.php";
require_once $projectroot."functions/banners.php";
require_once $projectroot."admin/includes/objects/site/banners.php";
require_once $projectroot."includes/objects/elements.php";

//
//
//
class SiteBannerEditForm extends Template
{

    function __construct($id, $contents)
    {
        parent::__construct($id);

        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["postaction"] = "movebanner";
        $linkparams["action"] = "sitebanner";
        $this->stringvars['moveactionvars'] = makelinkparameters($linkparams);

        $linkparams["postaction"] = "deletebanner";
        $this->stringvars['deleteactionvars'] = makelinkparameters($linkparams);

        $linkparams["postaction"] = "editbanner";
        $this->stringvars['editactionvars'] = makelinkparameters($linkparams);
        $this->stringvars['hiddenvars'] = $this->makehiddenvars(array("bannerid" => $id));

        if($contents['header']) {
            $this->stringvars['header']=input2html($contents['header']);
        } else {
            $this->stringvars['header']="";
        }

        $this->vars['banner'] = new Banner($contents);

        if (!isbannercomplete($contents)) {
            $this->stringvars['incomplete']= "incomplete";
        }

        if(strlen($contents['image'])>0) {
            $this->stringvars['image']=$contents['image'];
        } else {
            $this->stringvars['noimage']="true";
        }

        $this->stringvars['description']=input2html($contents['description']);
        $this->stringvars['link']=$contents['link'];
        $this->stringvars['code']=input2html($contents['code']);

        $this->vars['deletebannerconfirmform']= new CheckboxForm("deletebannerconfirm", "deletebannerconfirm", "Confirm delete", false, "right");

        $this->vars['submitrow']= new SubmitRow("bannerproperties", "Submit Banner Changes", true);
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/site/bannereditform.tpl");
    }
}




//
//
//
class SiteBanners extends Template
{

    function __construct()
    {
        parent::__construct();

        $this->stringvars['displayhiddenvars'] = $this->makehiddenvars(array("postaction" => "displaybanners"));

        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["postaction"] = "displaybanners";
        $linkparams["action"] = "sitebanner";
        $this->stringvars['displayactionvars'] = makelinkparameters($linkparams);

        $linkparams["postaction"] = "addbanner";
        $this->stringvars['addactionvars'] = makelinkparameters($linkparams);

        $this->vars['displaybanners_yes'] = new RadioButtonForm($this->stringvars['jsid'], "toggledisplaybanners", 1, "Yes", getproperty('Display Banners'), "right");
        $this->vars['displaybanners_no'] = new RadioButtonForm($this->stringvars['jsid'], "toggledisplaybanners", 0, "No", !getproperty('Display Banners'), "right");


        $banners = getbanners();
        foreach ($banners as $id => $contents) {
            $this->listvars['editform'][] = new SiteBannerEditForm($id, $contents);
        }

        $this->vars['submitrow']= new SubmitRow("addbanner", "Add Banner", true);
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/site/banners.tpl");
    }
}

?>
