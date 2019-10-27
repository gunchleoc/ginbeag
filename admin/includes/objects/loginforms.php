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
require_once $projectroot."includes/objects/template.php";
require_once $projectroot."includes/functions.php";

//
//
//
class AdminLoginForm extends Template
{

    function __construct($username)
    {
        global $_GET;
        parent::__construct();

        $this->stringvars['params']=makelinkparameters($_GET);
        $this->stringvars['username']=$username;
        $this->stringvars['forgetfullink'] = makelinkparameters(array("user" => urlencode($username), "forgetful" => "on"));
        $this->vars['submitrow'] = new SubmitRow("submit", "Login", false, true, "admin.php", $this->stringvars["jsid"]);
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/adminloginform.tpl");
    }
}

//
//
//
class AdminLoginHeader extends Template
{

    function __construct($message,$isredirect=false,$redirecturl="",$urltext="")
    {
        parent::__construct();

        $this->stringvars['stylesheet']=getCSSPath("main.css");
        $this->stringvars['stylesheetcolors']= getCSSPath("colors.css");
        $this->stringvars['adminstylesheet']=getCSSPath("admin.css");
        $this->stringvars['headertitle']= title2html(getproperty("Site Name")).' - Webpage building';

        if(strlen($message)>0) {
            $this->stringvars['message']=$message;
        }

        if(strlen($isredirect)>0) {
            $this->stringvars['is_redirect']="redirect";
        }

        if(strlen($redirecturl)>0) {
            $this->stringvars['url']=$redirecturl;
            $this->stringvars['url_text']=$urltext;
        }
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/adminloginheader.tpl");
    }
}

//
//
//
class ForgotEmailForm extends Template
{

    function __construct($username)
    {
        parent::__construct();
        $this->stringvars['username']=$username;
        $this->vars['submitrow'] = new SubmitRow("requestemail", "Request password", false, true, "login.php", $this->stringvars["jsid"]);
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/forgotemailform.tpl");
    }
}



//
//
//
class ForgotPasswordForm extends Template
{

    function __construct($username)
    {
        parent::__construct();
        $this->stringvars['username']=$username;
        $this->stringvars['forgetfullink'] = makelinkparameters(array("user" => urlencode($username), "superforgetful" => "on"));
        $this->vars['submitrow'] = new SubmitRow("requestpassword", "Request password", false, true, "login.php", $this->stringvars["jsid"]);
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/forgotpasswordform.tpl");
    }
}
?>
