<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));

require_once $projectroot."includes/objects/template.php";
require_once $projectroot."includes/functions.php";

//
//
//
class LoginForm extends Template
{


    function LoginForm($username,$error="")
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
