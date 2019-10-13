<?php
/*
 * An Gineadair Beag is a content management system to run websites with.
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
 */

$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."includes/objects/template.php";
require_once $projectroot."includes/objects/elements.php";
require_once $projectroot."admin/includes/objects/forms.php";


//
//
//
class SiteAntispam extends Template
{

    function SiteAntispam()
    {
        global $projectroot;
        parent::__construct();

        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["postaction"] = "savesite";
        $linkparams["action"] = "sitespam";
        $this->stringvars['actionvars']= makelinkparameters($linkparams);

        $this->stringvars['hiddenvars'] = $this->makehiddenvars(array("postaction" => "savesite"));


        $sql = new SQLSelectStatement(ANTISPAM_TABLE, array('property_name', 'property_value'));
        $variables = $sql->fetch_two_columns();

        // Math CAPTCHA
        $this->vars['usemathcaptcha_yes'] = new RadioButtonForm($this->stringvars['jsid'], "usemathcaptcha", 1, "Yes", $variables['Use Math CAPTCHA'], "right");
        $this->vars['usemathcaptcha_no'] = new RadioButtonForm($this->stringvars['jsid'], "usemathcaptcha", 0, "No", !$variables['Use Math CAPTCHA'], "right");
        $this->vars['mathcaptcha_submitrow']= new SubmitRow("mathcaptcha", "Submit", true);

        // Spam Words
        $this->stringvars['spamwords_subject'] = $variables['Spam Words Subject'];
        $this->stringvars['spamwords_content'] = $variables['Spam Words Content'];

        $this->vars['spamwords_submitrow']= new SubmitRow("spamwords", "Submit", true);
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/site/antispam.tpl");
    }
}


?>
