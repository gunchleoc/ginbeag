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

require_once $projectroot."includes/objects/template.php";
require_once $projectroot."includes/functions.php";

//
//
//
class LoginForm extends Template
{


    function __construct($username,$error="")
    {
        global $_GET;
        parent::__construct();
        $this->stringvars['params']=makelinkparameters($_GET);
        $this->stringvars['username']=title2html($username);

        $this->stringvars['l_legend_login']=getlang("login_legend_login");
        $this->stringvars['l_legend_login_data']=getlang("login_legend_logindata");
        $this->stringvars['l_username']=getlang("login_username");
        $this->stringvars['l_password']=getlang("login_password");
        $this->stringvars['l_submit']=getlang("login_submit");
        $this->stringvars['l_cancel']=getlang("login_cancel");
        $this->stringvars['l_home']=getlang("navigator_home");
        $this->stringvars['homelink']=getprojectrootlinkpath();

        if(strlen($error)>0) {
            $this->stringvars['error']=$error;
            $this->stringvars['pagetitle']=getlang("login_error");
            $this->stringvars['tryagain']=getlang("login_error_tryagain");
        }
        else
        {
            $this->stringvars['pagetitle']=getlang("login_pagetitle");
        }
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("loginform.tpl");
    }
}


?>
