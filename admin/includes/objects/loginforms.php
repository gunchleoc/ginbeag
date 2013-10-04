<?php
$projectroot=dirname(__FILE__);

// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

//include_once($projectroot."functions/pages.php");
include_once($projectroot."includes/objects/template.php");
include_once($projectroot."includes/functions.php");
//include_once($projectroot."includes/objects/forms.php");
//include_once($projectroot."functions/images.php");

//
//
//
class AdminLoginForm extends Template {

    function AdminLoginForm($username)
    {
		global $_GET;
		parent::__construct();
		
		$this->stringvars['params']=makelinkparameters($_GET);
		$this->stringvars['username']=title2html($username);
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
class AdminLoginHeader extends Template {

    function AdminLoginHeader($message,$isredirect=false,$redirecturl="",$urltext="")
    {
		parent::__construct();

		$this->stringvars['stylesheet']=getCSSPath("main.css");
		$this->stringvars['adminstylesheet']=getCSSPath("admin.css");
		$this->stringvars['headertitle']= title2html(getproperty("Site Name")).' - Webpage building';
		
		if(strlen($message)>0)
        	$this->stringvars['message']=$message;
		
		if(strlen($isredirect)>0)
        	$this->stringvars['is_redirect']="redirect";

      	if(strlen($redirecturl)>0)
      	{
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
class ForgotEmailForm extends Template {

    function ForgotEmailForm($username)
    {
    	parent::__construct();
		$this->stringvars['username']=title2html($username);
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
class ForgotPasswordForm extends Template {

    function ForgotPasswordForm($username)
    {
    	parent::__construct();
		$this->stringvars['username']=title2html($username);
    }

    // assigns templates
    function createTemplates()
    {
       $this->addTemplate("admin/forgotpasswordform.tpl");
    }
}
?>