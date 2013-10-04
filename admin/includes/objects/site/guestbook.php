<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."includes/objects/template.php");
include_once($projectroot."includes/objects/page.php");
include_once($projectroot."functions/guestbook.php");
include_once($projectroot."includes/objects/elements.php");
include_once($projectroot."admin/includes/objects/forms.php");


//
// List entries in Guestbook
//
class AdminGuestbookEntryList extends Template {

    function AdminGuestbookEntryList($number, $offset)
    {
    	parent::__construct();
  		$entries=getguestbookentries($number,$offset);
  		
		$this->vars['enableform'] = new AdminGuestbookEnableForm();
  
  		$this->vars['pagemenu']=new PageMenu($offset, $number, countguestbookentries());
  
  
  		if(count($entries)==0)
  		{
    		$this->stringvars['no_entries']= getlang("guestbook_nomessages");
  		}
  		else
  		{
    		for($i=0;$i<count($entries);$i++)
    		{
    			$this->listvars['entries'][] = new AdminGuestbookEntry($entries[$i]);
    		}
  		}
 	}

    // assigns templates
    function createTemplates()
    {
		$this->addTemplate("admin/site/guestbookentrylist.tpl");
    }

}



//
// Entry displayed in Guestbook
//
class AdminGuestbookEntry extends Template {

    function AdminGuestbookEntry($entryid, $showdeleteform=true)
    {
    	parent::__construct();
    	
    	$this->stringvars['deleteactionvars']='?sid='.$this->stringvars['sid'].'&page='.$this->stringvars['page'].'&action=siteguest';
    	
    	$contents=getguestbookentrycontents($entryid);
    	$this->stringvars['messageid']=$entryid;
    	$this->stringvars['name']=title2html($contents["name"]);
    	$this->stringvars['email']=title2html($contents["email"]);
    	$this->stringvars['date']=formatdatetime($contents["date"]);
    	$this->stringvars['subject']=title2html($contents["subject"]);
    	$this->stringvars['message']=text2html($contents["message"]);
    	
    	if($showdeleteform) $this->stringvars['deleteform']="deleteform";
    	
    	$this->stringvars['l_toppage']=getlang("pagemenu_topofthispage");
    	$this->stringvars['l_name']=getlang("guestbook_name");
    	$this->stringvars['l_email']=getlang("guestbook_email");
    	$this->stringvars['l_date']=getlang("guestbook_date");
    	$this->stringvars['l_subject']=getlang("guestbook_subject");
 	}

    // assigns templates
    function createTemplates()
    {
		$this->addTemplate("admin/site/guestbookentry.tpl");
    }

}





//
// To confirm deleting of entry
//
class AdminGuestbookDeleteConfirmForm extends Template {

    function AdminGuestbookDeleteConfirmForm($entryid)
    {
    	parent::__construct();
    	$this->stringvars['deleteactionvars']='?sid='.$this->stringvars['sid'].'&page='.$this->stringvars['page'].'&action=siteguest';
    	$this->stringvars['messageid']=$entryid;
    	$this->vars['entry']=new AdminGuestbookEntry($entryid, false);
 	}

    // assigns templates
    function createTemplates()
    {
		$this->addTemplate("admin/site/guestbookdeleteconfirmform.tpl");
    }

}




//
// To switch guestbook on and off
//
class AdminGuestbookEnableForm extends Template {

    function AdminGuestbookEnableForm()
    {
    	parent::__construct();
    	
    	$this->stringvars['enableactionvars']='?sid='.$this->stringvars['sid'].'&page='.$this->stringvars['page'].'&postaction=saveproperties&action=siteguest';

    	$properties=getproperties();
    
    	if($properties["Enable Guestbook"])
    	{
    		$this->stringvars['guestbookdisabled']="Yes";
    	}
    	else
    	{
    		$this->stringvars['guestbookenabled']="No";
    	}
   		$this->stringvars['entriesperpage']=$properties["Guestbook Entries Per Page"];
   		
   		$this->vars['submitrow']= new SubmitRow("saveproperties","Submit",true);
 	}

    // assigns templates
    function createTemplates()
    {
	$this->addTemplate("admin/site/guestbookenableform.tpl");
    }

}

?>
