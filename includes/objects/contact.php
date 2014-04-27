<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));

include_once($projectroot."includes/objects/template.php");
include_once($projectroot."includes/objects/page.php");
include_once($projectroot."includes/objects/email.php");
include_once($projectroot."functions/email.php");


//
// Contact master - todo
//
class ContactPage extends Template {
    
    function ContactPage($email,$subject,$messagetext, $sendcopy, $userid,$errormessage="", $sendmail=false)
    {
    	parent::__construct();
    	// header, footer and navigator
		// todo why encoding?
		$this->vars['header'] = new PageHeader(0,utf8_decode(getlang("pagetitle_contact")));
		$this->vars['footer'] = new PageFooter();
		$this->vars['navigator'] = new Navigator(0,1,0,false,false);

		if(getproperty('Display Banners'))
		{
  			$this->vars['banners'] = new BannerList();
		}
		else $this->stringvars['banners']="";
		

		// switches
		if($errormessage!="")
		{
			$this->stringvars['error']="true";
			$this->stringvars['errormessage']=$errormessage;
			$this->vars['emailinfo']= new EmailInfo($email,$subject,$messagetext,$sendcopy);
			$this->stringvars['l_tryagain']=getlang("email_tryagain");
			$this->vars['contactform']=new ContactForm($email,$subject,$messagetext,$sendcopy,$userid);
		}
		elseif($sendmail)
		{
			$this->stringvars['sendmail']="true";
			$this->vars['emailinfo']= new EmailInfo($email,$subject,$messagetext,$sendcopy);
			$this->stringvars['l_success']=getlang("email_thisemailwassent");
		}
		else
		{
			$this->stringvars['blankform']="true";
			$this->stringvars['l_pageintro']=getlang("pageintro_contact");
			$this->vars['contactform']=new ContactForm("","","",true,$userid);		
		}
 	}

    // assigns templates
    function createTemplates()
    {
		$this->addTemplate("contactpage.tpl");
    }
}






//
// Form for sending an e-mail to site owners
//
class ContactForm extends Template {

    function ContactForm($email,$subject,$message,$sendcopy,$userid)
    {
    	global $emailvariables;
    	parent::__construct();

    	$contacts=getallcontacts();
  		$descriptions = array();
  		for($i=0;$i<count($contacts);$i++)
  		{
  			$description = stripslashes(getusername($contacts[$i]));
  			$function=getcontactfunction($contacts[$i]);
  			if(strlen($function)>0) $description .=" (".stripslashes($function).")";
			$descriptions[$i] = $description;
  		}
		$contacts[]=0;
		$descriptions[] = getlang("email_webmaster");

    	$this->stringvars['l_legend_youremail']=getlang("email_legend_youremail");
    	$this->stringvars['l_legend_options']=getlang("email_legend_options");
    	$this->stringvars['l_legend_youremailtous']=getlang("email_legend_youremailtous");

    	$this->vars['contacts']= new OptionForm($userid,$contacts,$descriptions,"userid", getlang("email_to"), 1);
    	
    	$this->stringvars['l_emailadress']=getlang("email_address");
    	$this->stringvars['emailvariable']=$emailvariables['E-Mail Address Variable']['property_value'];
    	$this->stringvars['address']=$email;
    	$this->stringvars['l_emailsubject']=getlang("email_subject");
    	$this->stringvars['subjectvariable']=$emailvariables['Subject Line Variable']['property_value'];
    	$this->stringvars['subject']=$subject;
    	$this->stringvars['l_emailmessage']=getlang("email_message");
    	$this->stringvars['messagevariable']=$emailvariables['Message Text Variable']['property_value'];
    	$this->stringvars['message']=$message;

		$this->vars["sendcopyform"] = new CheckboxForm("sendcopy", "sendcopy", getlang("email_sendcopy"), $sendcopy, "right");

    	$this->stringvars['l_emailsendcopy']=getlang("email_sendcopy");
  		if($emailvariables['Use Math CAPTCHA']['property_value'])
  		{
  			
    		$this->vars['captcha']= new MathCAPTCHA();
    		$this->stringvars['l_legend_captcha']=getlang("antispam_legend_captcha");
    	}
    	
    	$this->stringvars['l_sendemail']=getlang("email_sendemail");

 	}

    // assigns templates
    function createTemplates()
    {
		$this->addTemplate("contactform.tpl");
    }

}

?>