<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."includes/objects/template.php");
include_once($projectroot."admin/functions/usersmod.php");
include_once($projectroot."admin/functions/publicusersmod.php");
include_once($projectroot."includes/objects/elements.php");
include_once($projectroot."admin/includes/objects/forms.php");


//
//
//

class SiteSelectUserPermissionsForm extends Template {

	function SiteSelectUserPermissionsForm($username="")
  	{
		parent::__construct();
		$this->stringvars['username']=$username;
		$this->stringvars['userlistlink']="?page=".$this->stringvars["page"]."&action=siteuserlist&ref=userpermissions";
		$this->stringvars['actionvars']="?page=".$this->stringvars["page"]."&action=siteuserperm";
	}

  	// assigns templates
  	function createTemplates()
  	{
    	$this->addTemplate("admin/site/selectuserpermissionsform.tpl");
  	}
}

//
//
//

class SiteUserLevelForm extends Template {

	function SiteUserLevelForm($userid)
  	{
		parent::__construct();
		
		$this->stringvars['actionvars']="?page=".$this->stringvars["page"]."&changelevel=change&action=siteuserperm";
  		
  		$this->stringvars['userid']=$userid;
  		$this->stringvars['username']=getusername($userid);
  		
  		$this->stringvars['userlevel_user']=USERLEVEL_USER;
  		$this->stringvars['userlevel_admin']=USERLEVEL_ADMIN;
  		
  		if(getuserlevel($userid)==USERLEVEL_USER) $this->stringvars['levelisuser']="true";
  		elseif(getuserlevel($userid)==USERLEVEL_ADMIN)$this->stringvars['levelisadmin']="true";
  		
  		$this->vars['submitrow']= new SubmitRow("changelevel","Change Userlevel",true);
  		
  		$this->stringvars["returnlink"]='?page='.$this->stringvars['page'].'&username='.$this->stringvars['username'].'&action=siteuserperm';
  		$this->stringvars["managelink"]='?page='.$this->stringvars['page'].'&userid='.$userid.'&action=siteuserman';
  		$this->stringvars["userlistlink"]='?page='.$this->stringvars['page'].'&action=siteuserlist';
	}

  	// assigns templates
  	function createTemplates()
  	{
    	$this->addTemplate("admin/site/userlevelform.tpl");
  	}
}


//
//
//
class SitePublicUserAccessForm extends Template {

	function SitePublicUserAccessForm($userid)
  	{
		parent::__construct();
		
  		$this->stringvars['username']=getpublicusername($userid);
  
  		$userpages=getpageaccessforpublicuser($userid);
  		$restrictedpages=getrestrictedpages();
  		$restrictedpagesnoaccess=array();
  		for($i=0;$i<count($restrictedpages);$i++)
  		{
    		if(!hasaccess($userid, $restrictedpages[$i]))
    		{
      			array_push($restrictedpagesnoaccess, $restrictedpages[$i]);
    		}
  		}
  		
		for($i=0;$i<count($userpages);$i++)
   		{
   			$this->listvars['pageswithaccess'][]= new SitePublicUserAccessPageForm($userid,$userpages[$i],true);
		}
		for($i=0;$i<count($restrictedpagesnoaccess);$i++)
    	{
    		$this->listvars['pagesnoaccess'][]= new SitePublicUserAccessPageForm($userid,$restrictedpagesnoaccess[$i],false);
		}
		
  		$this->stringvars["returnlink"]='?page='.$this->stringvars['page'].'&username='.$this->stringvars['username'].'&action=siteuserperm';
  		$this->stringvars["managelink"]='?page='.$this->stringvars['page'].'&userid='.$userid.'&type=public&action=siteuserman';
  		$this->stringvars["userlistlink"]='?page='.$this->stringvars['page'].'&action=siteuserlist#public';

	}

  	// assigns templates
  	function createTemplates()
  	{
    	$this->addTemplate("admin/site/publicuseraccessform.tpl");
  	}
}

//
//
//
class SitePublicUserAccessPageForm extends Template {

	function SitePublicUserAccessPageForm($userid,$page,$hasaccess)
  	{
  		parent::__construct();
  		
		$this->stringvars['pagelink']=getprojectrootlinkpath()."admin/pagedisplay.php?page=".$page;
		$this->stringvars['pagelinktitle']=$page.": ".title2html(getnavtitle($page));
		$this->stringvars['userid']=$userid;
		$this->stringvars['userpage']=$page;

  		if($hasaccess)
  		{
  			$this->stringvars['changeaccessaction']="removepage";
  			$this->stringvars['changeaccesslabel']="Remove access to this page";
  		}
  		else
  		{
  			$this->stringvars['changeaccessaction']="addpage";
  			$this->stringvars['changeaccesslabel']="Add access to this page";

  		}
  		$this->stringvars['actionvars']="?page=".$this->stringvars["page"]."&changeaccess=".$this->stringvars['changeaccessaction']."&type=public&action=siteuserperm";
	}

  	// assigns templates
  	function createTemplates()
  	{
    	$this->addTemplate("admin/site/publicuseraccesspageform.tpl");
  	}
}
?>