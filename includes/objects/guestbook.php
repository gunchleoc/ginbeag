<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));

require_once $projectroot."includes/objects/template.php";
require_once $projectroot."includes/objects/page.php";
require_once $projectroot."includes/objects/email.php";
require_once $projectroot."includes/functions.php";
require_once $projectroot."functions/guestbook.php";

//
// Guestbook master
// TODO edit guestbook intro is broken
//
class Guestbook extends Template
{

    function Guestbook($postername,$email,$subject,$emailmessage,  $token, $offset=0, $showguestbookform=false, $showpost=false, $showleavemessagebutton=true, $itemsperpage=10, $title="", $listtitle="", $message="", $error="", $postadded=false)
    {
        parent::__construct();

        $this->stringvars['title'] = $title;
        $sql = new SQLSelectStatement(SPECIALTEXTS_TABLE, 'text', array('id'), array('guestbook'), 's');
        $this->vars['intro'] = new PageIntro(utf8_decode($title), $sql->fetch_value(), "", true, true, "left", false, "sectiontext");
        if(ismobile()) { $displaytype = "mobile";
        } else { $displaytype = "page";
        }

        $this->vars['header'] = new PageHeader(0, utf8_decode(getlang("pagetitle_guestbook")), "", $displaytype);
        $this->vars['footer'] = new PageFooter();


        $this->vars['navigator'] = new Navigator(0, 1, 0, $displaytype, false);


        if(getproperty('Display Banners')) {
            $this->vars['banners'] = new BannerList();
        }


        // guestbook
        if(!getproperty('Enable Guestbook')) {
            $this->stringvars['disabled'] = getlang("guestbook_disabled");
        }
        else
        {
            $this->stringvars['enabled'] = "enabled";
            // show only post if post has been added
            if($postadded) {
                $this->stringvars['postadded'] = getlang("guestbook_messageadded");
            }

            // show guestbook entries
            else
            {
                $this->vars['entries']= new GuestbookEntryList($itemsperpage, $offset, $listtitle);

                if($showguestbookform) {
                    $this->vars['guestbookform'] = new GuestbookForm($postername, $email, $subject, $emailmessage, $token);
                }

                if($showleavemessagebutton) {
                    $this->stringvars['leavemessage'] = getlang("guestbook_leavemessage");
                }

            }
            // when message is being sent of has just been sent
            if($showpost) {
                $this->vars['post'] = new GuestbookPost($postername, $email, $subject, $emailmessage);
            }

            // general messaging stuff
            if(strlen($message)>0) {
                $this->stringvars['message'] =$message;
            }

            if(strlen($error)>0) {
                $this->stringvars['error'] =$error;
            }
        }
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("pages/guestbook/guestbook.tpl");
    }

}


//
// List entries in Guestbook
//
class GuestbookEntryList extends Template
{

    function GuestbookEntryList($number, $offset, $title="")
    {
        parent::__construct();
        if(strlen($title)>0) {
            $this->stringvars['title']= $title;
        }

        $entries=getguestbookentries($number, $offset);

        $this->vars['pagemenu']=new PageMenu($offset, $number, countguestbookentries());


        if(count($entries)==0) {
            $this->stringvars['no_entries']= getlang("guestbook_nomessages");
        }
        else
        {
            for($i=0;$i<count($entries);$i++)
            {
                $this->listvars['entries'][] = new GuestbookEntry($entries[$i]);
            }
        }
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("pages/guestbook/guestbookentrylist.tpl");
    }

}



//
// Entry displayed in Guestbook
//
class GuestbookEntry extends Template
{

    function GuestbookEntry($entry)
    {
        parent::__construct();
        $contents=getguestbookentrycontents($entry);
        $this->stringvars['name']=title2html($contents["name"]);
        $this->stringvars['date']=formatdatetime($contents["date"]);
        $this->stringvars['subject']=title2html($contents["subject"]);
        $this->stringvars['message']=text2html($contents["message"]);

        $this->stringvars['l_toppage']=getlang("pagemenu_topofthispage");
        $this->stringvars['l_name']=getlang("guestbook_name");
        $this->stringvars['l_date']=getlang("guestbook_date");
        $this->stringvars['l_subject']=getlang("guestbook_subject");
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("pages/guestbook/guestbookentry.tpl");
    }

}



//
// Own entry displayed to person posting
//
class GuestbookPost extends Template
{

    function GuestbookPost($postername, $email, $subject, $message)
    {
        parent::__construct();
        $this->stringvars['name']=title2html($postername);
        $this->stringvars['email']=title2html($email);
        $this->stringvars['subject']=title2html($subject);
        $this->stringvars['message']=text2html($message);

        $this->stringvars['l_yourentry']=getlang("guestbook_yourentry");
        $this->stringvars['l_name']=getlang("guestbook_name");
        $this->stringvars['l_email']=getlang("guestbook_email");
        $this->stringvars['l_message']=getlang("guestbook_message");
        $this->stringvars['l_subject']=getlang("guestbook_subject");
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("pages/guestbook/guestbookpost.tpl");
    }

}



//
// Own entry displayed to person posting
//
class GuestbookForm extends Template
{

    function GuestbookForm($postername, $email, $subject, $message, $token)
    {
        global $emailvariables;

        parent::__construct();

        $this->stringvars['name']=title2html($postername);
        $this->stringvars['email']=title2html($email);
        $this->stringvars['subject']=title2html($subject);
        $this->stringvars['message']=text2html($message);

        $this->stringvars['emailvariable']=$emailvariables['E-Mail Address Variable'];
        $this->stringvars['subjectvariable']=$emailvariables['Subject Line Variable'];
        $this->stringvars['messagevariable']=$emailvariables['Message Text Variable'];



        $this->stringvars['l_name']=getlang("guestbook_yourname");
        $this->stringvars['l_email']=getlang("guestbook_youremail");
        $this->stringvars['l_message']=getlang("guestbook_yourmessage");
        $this->stringvars['l_subject']=getlang("guestbook_yoursubject");

        $this->stringvars['l_legend_yourmessage']=getlang("guestbook_legend_yourmessage");
        $this->stringvars['l_legend_yourmessagetous']=getlang("guestbook_legend_yourmessagetous");


        if($emailvariables['Use Math CAPTCHA']) {
            $this->vars['captcha']= new MathCAPTCHA();
            $this->stringvars['l_legend_captcha']=getlang("antispam_legend_captcha");
        }

        $this->stringvars['l_submit']=getlang("guestbook_submit");
        $this->stringvars['l_cancel']=getlang("guestbook_cancel");
        $this->stringvars['token'] = $token;
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("pages/guestbook/guestbookform.tpl");
    }

}

?>
