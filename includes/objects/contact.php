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
require_once $projectroot."includes/objects/page.php";
require_once $projectroot."includes/objects/email.php";
require_once $projectroot."includes/functions.php";
require_once $projectroot."functions/email.php";

//
//
//
class ContactPage extends Template
{

    function __construct($email, $subject, $messagetext, $sendcopy, $userid, $token, $errormessage="", $sendmail=false)
    {
        parent::__construct();

        if(ismobile()) { $displaytype = "mobile";
        } else { $displaytype = "page";
        }

        $this->vars['header'] = new PageHeader(0, getlang("pagetitle_contact"), "", $displaytype);
        $this->vars['footer'] = new PageFooter();
        $sql = new SQLSelectStatement(SPECIALTEXTS_TABLE, 'text', array('id'), array('contact'), 's');
        $this->vars['intro'] = new PageIntro(getlang("pageintro_contact"), $sql->fetch_value(), "", true, true, "left", false, "sectiontext");
        $this->vars['navigator'] = new Navigator(0, 1, 0, $displaytype, false);

        if(getproperty('Display Banners')) {
            $this->vars['banners'] = new BannerList();
        }
        else { $this->stringvars['banners']="";
        }


        // switches
        if($sendmail) {
            if (empty($errormessage)) {
                $this->stringvars['sendmail']="true";
                $this->stringvars['l_success'] = getlang("email_thisemailwassent");
                $this->vars['emailinfo']= new EmailInfo($email, $subject, $messagetext, $sendcopy);
            } else {
                $this->stringvars['error']="true";
                $this->stringvars['errormessage'] = "";
                $this->stringvars['l_tryagain'] = $errormessage;
                $this->vars['contactform']=new ContactForm($email, $subject, $messagetext, $sendcopy, $userid, $token, $displaytype);
            }
        } elseif(!empty($errormessage)) {
            $this->stringvars['error']="true";
            $this->stringvars['errormessage'] = $errormessage;
            $this->stringvars['l_tryagain'] = getlang("email_tryagain");
            $this->vars['contactform']=new ContactForm($email, $subject, $messagetext, $sendcopy, $userid, $token, $displaytype);
        }
        else
        {
            $this->stringvars['blankform']="true";
            $this->vars['contactform']=new ContactForm("", "", "", true, $userid, $token, $displaytype);
        }
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("pages/contact/contactpage.tpl");
    }
}






//
// Form for sending an e-mail to site owners
//
class ContactForm extends Template
{
    var $displaytype;

    function __construct($email, $subject, $message, $sendcopy, $userid, $token, $displaytype)
    {
        global $emailvariables;
        $this->displaytype = $displaytype;
        parent::__construct();

        $contacts=getallcontacts();
        $descriptions = array();
        for($i=0;$i<count($contacts);$i++)
        {
            $description = stripslashes(getusername($contacts[$i]));
            $function=getcontactfunction($contacts[$i]);
            if(strlen($function)>0) { $description .=" (".stripslashes($function).")";
            }
            $descriptions[$i] = $description;
        }
        $contacts[]=0;
        $descriptions[] = getlang("email_webmaster");

        $this->stringvars['l_legend_youremail']=getlang("email_legend_youremail");
        $this->stringvars['l_legend_options']=getlang("email_legend_options");
        $this->stringvars['l_legend_youremailtous']=getlang("email_legend_youremailtous");

        $this->vars['contacts']= new OptionForm($userid, $contacts, $descriptions, "userid", getlang("email_to"), 1);

        $this->stringvars['l_emailadress']=getlang("email_address");
        $this->stringvars['emailvariable']=$emailvariables['E-Mail Address Variable'];
        $this->stringvars['address']=$email;
        $this->stringvars['l_emailsubject']=getlang("email_subject");
        $this->stringvars['subjectvariable']=$emailvariables['Subject Line Variable'];
        $this->stringvars['subject']=$subject;
        $this->stringvars['l_emailmessage']=getlang("email_message");
        $this->stringvars['messagevariable']=$emailvariables['Message Text Variable'];
        $this->stringvars['message']=$message;

        $this->vars["sendcopyform"] = new CheckboxForm("sendcopy", "sendcopy", getlang("email_sendcopy"), $sendcopy, "right");

        $this->stringvars['l_emailsendcopy']=getlang("email_sendcopy");
        if($emailvariables['Use Math CAPTCHA']) {

            $this->vars['captcha']= new MathCAPTCHA();
            $this->stringvars['l_legend_captcha']=getlang("antispam_legend_captcha");
        }

        $this->stringvars['l_sendemail']=getlang("email_sendemail");
        $this->stringvars['token'] = $token;
    }

    // assigns templates
    function createTemplates()
    {
        if($this->displaytype == "mobile") {
            $this->addTemplate("mobile/contactform.tpl");
        } else {
            $this->addTemplate("pages/contact/contactform.tpl");
        }
    }

}

?>
