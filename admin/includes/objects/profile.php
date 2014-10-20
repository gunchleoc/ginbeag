<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/includes/objects/forms.php");
include_once($projectroot."functions/pages.php");
include_once($projectroot."includes/objects/template.php");
include_once($projectroot."includes/includes.php");
include_once($projectroot."includes/objects/forms.php");
include_once($projectroot."includes/objects/page.php");


//
//
//
class ProfilePage extends Template {

	function ProfilePage($userid,$message="")
	{
		parent::__construct();

		$this->stringvars['actionvars']= makelinkparameters(array("page" => $this->stringvars['page']));
		$this->stringvars['returnvars']= "admin.php".makelinkparameters(array("page" => $this->stringvars['page']));
		$this->stringvars['username']=getusername($userid);
		$this->stringvars['email']=getuseremail($userid);
		$this->stringvars['contactfunction']=getcontactfunction($userid);
		$this->vars['is_contact']= new CheckBoxForm("iscontact","Is Contact","",getiscontact($userid));
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
class RegisterPage extends Template {

	function RegisterPage($user, $email,$message,$showform=true)
	{
		parent::__construct();
		$this->stringvars['stylesheet']=getCSSPath("main.css");
		$this->stringvars['stylesheetcolors']= getCSSPath("colors.css");
		$this->stringvars['adminstylesheet']=getCSSPath("admin.css");
		$this->stringvars['headertitle']= title2html(getproperty("Site Name")).' - Webpage building';

		if(strlen($message)>0)
        	$this->stringvars['message']=$message;

		$this->stringvars['user']=input2html($user);
    	$this->stringvars['email']=$email;
    	if($showform)
      		$this->stringvars['showform']="show form";

      $this->vars['submitrow'] = new SubmitRow("submit", "Register", true);
	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/registerpage.tpl");
	}
}
?>
