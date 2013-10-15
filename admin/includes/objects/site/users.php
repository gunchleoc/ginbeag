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
class SiteCreatePublicUser extends Template {

	function SiteCreatePublicUser($username, $message="", $newuserid=-1)
	{
		global $projectroot;
		parent::__construct();
		
		$this->stringvars['actionvars']='?page='.$this->stringvars['page'].'&action=siteusercreate';
		$this->stringvars['username']=$username;
		
		if($newuserid >= 0)
			$this->stringvars['newuserlinks']='<p><a href="admin.php?page='.$this->stringvars['page'].'&userid='.$newuserid.'&type=public&action=siteuserman">Manage this user</a></p>';
		else
			$this->stringvars['newuserlinks']="";

		$this->vars['submitrow']= new SubmitRow("createuser","Create User",false,true,"admin.php?page=".$this->stringvars['page']."&action=siteuserman");
	}
	
	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/site/createpublicuser.tpl");
	}
}



//
//
//
class SiteUserlist extends Template {

	function SiteUserlist($ref)
  	{
  		parent::__construct();
    
    	$users=getallusers();
    
		for($i=0; $i<count($users);$i++)
  		{
  			$this->listvars['adminusers'][]=new SiteUserlistAdminUser($users[$i],$ref);
  		}
  		
		$users=getallpublicusers();
		
		for($i=0; $i<count($users);$i++)
  		{
  			$this->listvars['publicusers'][]=new SiteUserlistPublicUser($users[$i],$ref);
  		}
  	}

  	// assigns templates
  	function createTemplates()
  	{
    	$this->addTemplate("admin/site/userlist.tpl");
  	}
}



//
//
//
class SiteUserlistAdminUser extends Template {

	function SiteUserlistAdminUser($userid,$ref)
	{
		parent::__construct();
    	
    	$lastlogin = getlastlogin($userid);
    	$retries = getretries($userid);
    	
    	$this->stringvars['username']=getusername($userid);

		if($ref =="userpermissions")
  		{
  			$this->stringvars['reflink']='?page='.$this->stringvars['page'].'&userid='.$userid.'&action=siteuserperm';
  		}
		else if($ref =="usermanagement")
  		{
  			$this->stringvars['reflink']='?page='.$this->stringvars['page'].'&userid='.$userid.'&action=siteuserman';
  		}
  		else
  		{
  			$this->stringvars['managelink']='admin.php?page='.$this->stringvars['page'].'&userid='.$userid.'&action=siteuserman';
  			$this->stringvars['permissionslink']='admin.php?page='.$this->stringvars['page'].'&userid='.$userid.'&action=siteuserperm';
  		}
  		
  		$this->stringvars['email']=getuseremail($userid);
  		
  		if(getiscontact($userid)) $this->stringvars['iscontact']="Yes";
  		else $this->stringvars['iscontact']="&mdash;";
  		
  		$this->stringvars['contactfunction']=getcontactfunction($userid);
  		
  		if(isactive($userid)) $this->stringvars['isactive']="Yes";
  		else $this->stringvars['isactive']="&mdash;";
  		
  		$userlevel = getuserlevel($userid);

  		if($userlevel==USERLEVEL_USER) $this->stringvars['userlevel']="User";
  		elseif($userlevel==USERLEVEL_ADMIN) $this->stringvars['userlevel']="Administrator";
  		
  		$this->stringvars['lastlogin']=getlastlogin($userid);
  		$this->stringvars['retries']=getretries($userid);
	}

 	 // assigns templates
 	function createTemplates()
  	{
    	$this->addTemplate("admin/site/userlistadminuser.tpl");
  	}
}

//
//
//
class SiteUserlistPublicUser extends Template {

	function SiteUserlistPublicUser($userid,$ref)
  	{
  		parent::__construct();
    
    	$this->stringvars['username']=getpublicusername($userid);

		if($ref =="userpermissions")
  		{
  			$this->stringvars['reflink']='?page='.$this->stringvars['page'].'&userid='.$userid.'&type=public&action=siteuserperm';
  		}
		else if($ref =="usermanagement")
  		{
  			$this->stringvars['reflink']='?page='.$this->stringvars['page'].'&userid='.$userid.'&type=public&action=siteuserman';
  		}
  		else
  		{
  			$this->stringvars['managelink']='admin.php?page='.$this->stringvars['page'].'&userid='.$userid.'&type=public&action=siteuserman';
  			$this->stringvars['permissionslink']='admin.php?page='.$this->stringvars['page'].'&userid='.$userid.'&type=public&action=siteuserperm';
  		}    
    
  		if(ispublicuseractive($userid)) $this->stringvars['isactive']="Yes";
  		else $this->stringvars['isactive']="&mdash;";
  		
  		$userpages=getpageaccessforpublicuser($userid);
  		
  		$noofpages=count($userpages);
  		if(!$noofpages>0)
  		{
  		
  			$this->stringvars['userpages']='<div align="center"> &mdash; </div>';
  		}
  		else
  		{
  			$this->stringvars['userpages']='';
  		
  			for($i=0;$i<$noofpages;$i++)
  			{
    			if($i>0)
    			{
     				$this->stringvars['userpages'].=' &ndash; ';
    			}
    			$this->stringvars['userpages'].='<a href="'.getprojectrootlinkpath().'admin/pagedisplay.php?page='.$userpages[$i].'" target="_blank">'.$userpages[$i].": ".title2html(getnavtitle($userpages[$i])).'</a>';
  			}
  		}
 	}

  	// assigns templates
  	function createTemplates()
  	{
   		$this->addTemplate("admin/site/userlistpublicuser.tpl");
  	}
}

//
//
//
class SiteSelectUserForm extends Template {

	function SiteSelectUserForm($username="")
  	{
		parent::__construct();
		
		$this->stringvars['username']=$username;
		$this->stringvars['userlistlink']="?page=".$this->stringvars["page"]."&action=siteuserlist&ref=usermanagement";
		$this->stringvars['selectactionvars']="?page=".$this->stringvars["page"]."&action=siteuserman";
		$this->stringvars['createactionvars']="?page=".$this->stringvars["page"]."&action=siteusercreate";
	}

  	// assigns templates
  	function createTemplates()
  	{
    	$this->addTemplate("admin/site/selectuserform.tpl");
  	}
}

//
//
//
class SiteAdminUserProfileForm extends Template {

	function SiteAdminUserProfileForm($userid)
  	{
		parent::__construct();
		
		$this->stringvars['profileactionvars']="?page=".$this->stringvars["page"]."&profile=change&action=siteuserman";
  		$this->stringvars['activateactionvars']="?page=".$this->stringvars["page"]."&action=siteuserman";
  		$this->stringvars['passgenactionvars']="?page=".$this->stringvars["page"]."&generate=generate&action=siteuserman";
  		$this->stringvars['contactactionvars']="?page=".$this->stringvars["page"]."&contact=contact&action=siteuserman";
	
  		$this->stringvars['username']=getusername($userid);
  		$this->stringvars['userid']=$userid;
  		$this->stringvars['email']=getuseremail($userid);
  		$this->stringvars['contactfunction']=getcontactfunction($userid);
  		
  		if(isactive($userid)) $this->stringvars['isactive']="true";
  		else $this->stringvars['notactive']="true";
  		
  		if(getiscontact($userid)) $this->stringvars['iscontact']="true";
  		
  		$this->stringvars["returnlink"]='?page='.$this->stringvars['page'].'&username='.$this->stringvars['username'].'&action=siteuserman';
  		$this->stringvars["permissionslink"]='?page='.$this->stringvars['page'].'&userid='.$userid.'&action=siteuserperm';
  		$this->stringvars["userlistlink"]='?page='.$this->stringvars['page'].'&action=siteuserlist';
	}

  	// assigns templates
  	function createTemplates()
  	{
    	$this->addTemplate("admin/site/adminuserprofileform.tpl");
  	}
}

//
//
//
class SitePublicUserProfileForm extends Template {

	function SitePublicUserProfileForm($userid)
  	{
  		parent::__construct();
  		
  		$this->stringvars['username']=getpublicusername($userid);
  		$this->stringvars['userid']=$userid;
  		$this->stringvars['profileactionvars']="?page=".$this->stringvars["page"]."&profile=change&type=public&action=siteuserman";
  		$this->stringvars['activateactionvars']="?page=".$this->stringvars["page"]."&type=public&action=siteuserman";
  		
  		if(ispublicuseractive($userid)) $this->stringvars['isactive']="true";
  		else $this->stringvars['notactive']="true";
  		
  		$linktarget='?page='.$this->stringvars['page'].'&username='.$this->stringvars['username'].'&action=siteuserman';
  		
  		$this->vars['submitrow']= new SubmitRow("profile","Change Password",false,true,$linktarget);
  		
  		$this->stringvars["returnlink"]=$linktarget;
  		$this->stringvars["permissionslink"]='?page='.$this->stringvars['page'].'&userid='.$userid.'&type=public&action=siteuserperm';
  		$this->stringvars["userlistlink"]='?page='.$this->stringvars['page'].'&action=siteuserlist#public';
	}

  	// assigns templates
  	function createTemplates()
  	{
    	$this->addTemplate("admin/site/publicuserprofileform.tpl");
  	}
}
?>