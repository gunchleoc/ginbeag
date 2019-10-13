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
// zweimal, weil nur auf "a" geprüft wird
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."includes/objects/template.php";
require_once $projectroot."includes/objects/forms.php";
require_once $projectroot."includes/objects/elements.php";
require_once $projectroot."admin/includes/objects/forms.php";

//
//
//
class SiteRandomItems extends Template
{

    function SiteRandomItems()
    {
        parent::__construct();

        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["postaction"] = "savesite";
        $linkparams["action"] = "siteiotd";
        $this->stringvars['actionvars'] = makelinkparameters($linkparams);

        $properties=getproperties();

        $potdcats=explode(",", $properties["Picture of the Day Categories"]);
        $potdcatnames=array();
        if(!count($potdcats)) {
            $potdcatlistoutput=getlang("form_cat_allcats");
        }
        else
        {
            for($j=0;$j<count($potdcats);$j++)
            {
                array_push($potdcatnames, getcategoryname($potdcats[$j], CATEGORY_IMAGE));
            }
            sort($potdcatnames);
            $potdcatlistoutput=title2html(implode(", ", $potdcatnames));
        }

        $this->vars['displaypotd_yes'] = new RadioButtonForm($this->stringvars['jsid'], "displaypotd", 1, "Yes", $properties["Display Picture of the Day"], "right");
        $this->vars['displaypotd_no'] = new RadioButtonForm($this->stringvars['jsid'], "displaypotd", 0, "No", !$properties["Display Picture of the Day"], "right");

        $this->stringvars['potdlist']= $potdcatlistoutput;
        $this->vars['potdcatform']=new CategorySelectionForm(true, "", CATEGORY_IMAGE);

        $this->vars['displayaotd_yes'] = new RadioButtonForm($this->stringvars['jsid'], "displayaotd", 1, "Yes", $properties["Display Article of the Day"], "right");
        $this->vars['displayaotd_no'] = new RadioButtonForm($this->stringvars['jsid'], "displayaotd", 0, "No", !$properties["Display Article of the Day"], "right");

        $this->stringvars['aotdpages']= $properties["Article of the Day Start Pages"];

        $this->vars['submitrow']= new SubmitRow("submit", "Submit", true);
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/site/iotd.tpl");
    }
}
?>
