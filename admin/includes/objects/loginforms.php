<?php
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

    function AdminLoginForm($username)
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

    function AdminLoginHeader($message,$isredirect=false,$redirecturl="",$urltext="")
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

    function ForgotEmailForm($username)
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

    function ForgotPasswordForm($username)
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
