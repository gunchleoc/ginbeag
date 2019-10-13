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
require_once $projectroot."admin/includes/objects/forms.php";
require_once $projectroot."includes/objects/elements.php";
require_once $projectroot."admin/includes/objects/editor.php";


//
//
//
class SitePolicy extends Template
{

    function SitePolicy()
    {
        parent::__construct("sitepolicy", array(0 => "includes/javascript/jcaret.js"));
        $this->stringvars['javascript']=$this->getScripts();

        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["postaction"] = "savesite";
        $linkparams["action"] = "sitepolicy";
        $this->stringvars['actionvars'] = makelinkparameters($linkparams);

        $properties=getproperties();

        $this->vars['displaypolicy_yes'] = new RadioButtonForm("", "displaypolicy", 1, "Yes", $properties["Display Site Policy"], "right");
        $this->vars['displaypolicy_no'] = new RadioButtonForm("", "displaypolicy", 0, "No", !$properties["Display Site Policy"], "right");

        $this->stringvars['policytitle']=$properties["Site Policy Title"];

        $this->vars['policytext']= new Editor($this->stringvars['page'], 0, "sitepolicy", "Site Policy");

        $this->vars['submitrow']= new SubmitRow("submit", "Submit", true);
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/site/policy.tpl");
    }
}
?>
