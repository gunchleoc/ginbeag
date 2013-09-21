<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));

include_once($projectroot."includes/templates/template.php");
include_once($projectroot."includes/templates/page.php");
include_once($projectroot."functions/guestbook.php");


//
// Guestbook master
//
class Guestbook extends Template {
    // vars that are simple strings
    var $stringvars=array();
    //var $vars=array();


    function Guestbook($postername,$email,$subject,$emailmessage, $offset=0, $showguestbookform=false, $showpost=false, $showleavemessagebutton=true, $itemsperpage=10, $title="", $listtitle="", $message="", $error="", $postadded=false)
    {
    	// header, footer and navigator

		$this->vars['header'] = new PageHeader(0,getlang("pagetitle_guestbook"));
		$this->vars['footer'] = new PageFooter();
		
		$this->stringvars['title'] = $title;


		$this->vars['navigator'] = new Navigator($page_id,$displaysisters,$navigatordepth-1,false,$showhidden);


		if(getproperty('Display Banners'))
		{
  			$this->vars['banners'] = new BannerList();
		}


		// guestbook
		if(!getproperty('Enable Guestbook'))
		{
  			$this->stringvars['disabled'] = getlang("guestbook_disabled");
		}
		else
		{
			$this->stringvars['enabled'] = "enabled";
			// show only post if post has been added
			if($postadded)
			{
				$this->stringvars['postadded'] = getlang("guestbook_messageadded");
			}
			// show guestbook entries
			else
			{
				$this->vars['entries']= new GuestbookEntryList($itemsperpage, $offset,$listtitle);
			
				if($showguestbookform)
				{
					$this->vars['guestbookform'] = new GuestbookForm($postername,$email,$subject,$emailmessage);
				}

				if($showleavemessagebutton)
				{
					$this->stringvars['leavemessage'] = getlang("guestbook_leavemessage");
				}
			}
			// when message is being sent of has just been sent
			if($showpost)
			{
				$this->vars['post'] = new GuestbookPost($postername, $email, $subject, $emailmessage);
			}
			// general messaging stuff
			if(strlen($message)>0)
			{
				$this->stringvars['message'] =$message;
			}
			if(strlen($error)>0)
			{
				$this->stringvars['error'] =$error;
			}
		}

   		$this->createTemplates();
 	}

    // assigns templates
    function createTemplates()
    {
      $this->addTemplate("guestbook.tpl");
    }

}


//
// List entries in Guestbook
//
class GuestbookEntryList extends Template {
    // vars that are simple strings
    var $stringvars=array();
    //var $vars=array();


    function GuestbookEntryList($number, $offset, $title="")
    {
   	
		if(strlen($title)>0)
		{
			$this->stringvars['title']= $title;
		}

  		$entries=getguestbookentries($number,$offset);
  
  		$this->vars['pagemenu']=new PageMenu($offset, $number, countguestbookentries());
  
  
  		if(count($entries)==0)
  		{
    		$this->stringvars['no_entries']= getlang("guestbook_nomessages");
  		}
  		else
  		{
    		for($i=0;$i<count($entries);$i++)
    		{
    			$this->listvars['entries'][] = new GuestbookEntry($entries[$i]);
    		}
  		}


   		$this->createTemplates();
 	}

    // assigns templates
    function createTemplates()
    {
      $this->addTemplate("guestbookentrylist.tpl");
    }

}



//
// Entry displayed in Guestbook
//
class GuestbookEntry extends Template {
    // vars that are simple strings
    var $stringvars=array();
    //var $vars=array();


    function GuestbookEntry($entry)
    {
    	$contents=getguestbookentrycontents($entry);
    	$this->stringvars['name']=title2html($contents["name"]);
    	$this->stringvars['date']=formatdatetime($contents["date"]);
    	$this->stringvars['subject']=title2html($contents["subject"]);
    	$this->stringvars['message']=text2html($contents["message"]);
    	
    	$this->stringvars['l_toppage']=getlang("page_topofthispage");
    	$this->stringvars['l_name']=getlang("guestbook_name");
    	$this->stringvars['l_date']=getlang("guestbook_date");
    	$this->stringvars['l_subject']=getlang("guestbook_subject");

   		$this->createTemplates();
 	}

    // assigns templates
    function createTemplates()
    {
      $this->addTemplate("guestbookentry.tpl");
    }

}



//
// Own entry displayed to person posting
//
class GuestbookPost extends Template {
    // vars that are simple strings
    var $stringvars=array();
    //var $vars=array();


    function GuestbookPost($postername, $email, $subject, $message)
    {
    	$this->stringvars['name']=title2html($postername);
    	$this->stringvars['email']=title2html($email);
    	$this->stringvars['subject']=title2html($subject);
    	$this->stringvars['message']=text2html($message);
    	
    	$this->stringvars['l_yourentry']=getlang("guestbook_yourentry");
    	$this->stringvars['l_name']=getlang("guestbook_name");
    	$this->stringvars['l_email']=getlang("guestbook_email");
    	$this->stringvars['l_message']=getlang("guestbook_message");
    	$this->stringvars['l_subject']=getlang("guestbook_subject");

   		$this->createTemplates();
 	}

    // assigns templates
    function createTemplates()
    {
      $this->addTemplate("guestbookpost.tpl");
    }

}



//
// Own entry displayed to person posting
//
class GuestbookForm extends Template {
    // vars that are simple strings
    var $stringvars=array();
    //var $vars=array();


    function GuestbookForm($postername,$email,$subject,$message)
    {
    	$this->stringvars['name']=title2html($postername);
    	$this->stringvars['email']=title2html($email);
    	$this->stringvars['subject']=title2html($subject);
    	$this->stringvars['message']=text2html($message);
    	
    	$this->stringvars['l_name']=getlang("guestbook_yourname");
    	$this->stringvars['l_email']=getlang("guestbook_youremail");
    	$this->stringvars['l_message']=getlang("guestbook_yourmessage");
    	$this->stringvars['l_subject']=getlang("guestbook_yoursubject");
    	
    	$this->stringvars['l_submit']=getlang("guestbook_submit");
    	$this->stringvars['l_cancel']=getlang("guestbook_cancel");

   		$this->createTemplates();
 	}

    // assigns templates
    function createTemplates()
    {
      $this->addTemplate("guestbookform.tpl");
    }

}

?>