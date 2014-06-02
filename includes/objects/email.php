<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));

include_once($projectroot."language/languages.php");
include_once($projectroot."includes/objects/template.php");

//
// Summary info when Email is (being) sent
//
class EmailInfo  extends Template {

    function EmailInfo($email,$subject,$messagetext,$sendcopy)
    {
    	parent::__construct();

    	$this->stringvars['l_email_enteredmessage']=getlang("email_enteredmessage");
    	$this->stringvars['l_email']=getlang("email_email");
    	$this->stringvars['email']=$email;
    	$this->stringvars['l_subject']=getlang("email_subject");
    	$this->stringvars['subject']=stripslashes($subject);
    	$this->stringvars['l_message']=getlang("email_message");
    	$this->stringvars['message']=stripslashes(nl2br($messagetext));

    	if($sendcopy)
    	{
			$this->stringvars['sendcopy']=getlang("email_copyrequested");
		}
		else
		{
			$this->stringvars['sendcopy']=getlang("email_nocopyrequested");
		}
    }

    function createTemplates()
    {
		$this->addTemplate("emailinfo.tpl");
    }
}

//
// Antispam - MathCAPCHA
//
class MathCAPTCHA  extends Template {

    function MathCAPTCHA()
    {
    	global $emailvariables;
    	parent::__construct();

		$captcha=makemathcaptcha();
		$this->stringvars['captcha_question']=$captcha["question"];
		$this->stringvars['captchareplyvariable']=$emailvariables['Math CAPTCHA Reply Variable']['property_value'];
		$this->stringvars['hiddenvars'] = $this->makehiddenvars(array($emailvariables['Math CAPTCHA Answer Variable']['property_value'] => $captcha["answer"]));
    }

    function createTemplates()
    {
		$this->addTemplate("mathcaptcha.tpl");
    }
}

?>
