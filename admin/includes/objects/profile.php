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
require_once $projectroot."functions/pages.php";
require_once $projectroot."includes/objects/template.php";
require_once $projectroot."includes/includes.php";
require_once $projectroot."includes/objects/forms.php";
require_once $projectroot."includes/objects/page.php";


//
//
//
class ProfilePage extends Template
{

    function __construct($userid,$message="")
    {
        parent::__construct();

        $this->stringvars['actionvars']= makelinkparameters(array("page" => $this->stringvars['page']));
        $this->stringvars['returnvars']= "admin.php".makelinkparameters(array("page" => $this->stringvars['page']));
        $this->stringvars['username']=getdisplayname($userid);
        $this->stringvars['email']=getuseremail($userid);
        $this->stringvars['contactfunction']=getcontactfunction($userid);
        $this->vars['is_contact']= new CheckBoxForm("iscontact", "Is Contact", "", getiscontact($userid));
        $this->vars['submitrow_account'] = new SubmitRow("submit", "Change Account Settings", true, true, $this->stringvars['returnvars'], $this->stringvars["jsid"]);
        $this->vars['submitrow_contact'] = new SubmitRow("contact", "Change Contact Page Options", true, true, $this->stringvars['returnvars'], $this->stringvars["jsid"]);
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/profilepage.tpl");
    }
}

//
//
//
class RegisterPage extends Template
{

    function __construct($user, $email,$message,$showform=true)
    {
        parent::__construct();
        $this->stringvars['stylesheet']=getCSSPath("main.css");
        $this->stringvars['stylesheetcolors']= getCSSPath("colors.css");
        $this->stringvars['adminstylesheet']=getCSSPath("admin.css");
        $this->stringvars['headertitle']= title2html(getproperty("Site Name")).' - Webpage building';

        if(strlen($message)>0) {
            $this->stringvars['message']=$message;
        }

        $this->stringvars['user']=input2html($user);
        $this->stringvars['email']=$email;
        if($showform) {
              $this->stringvars['showform']="show form";
        }

         $this->vars['submitrow'] = new SubmitRow("submit", "Register", true);
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/registerpage.tpl");
    }
}
?>
