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
		$this->stringvars['username']=title2html($username);

		$linkparams["page"] = $this->stringvars['page'];
		$linkparams["action"] = "siteuserperm";
		$this->stringvars['actionvars'] = makelinkparameters($linkparams);

		$linkparams["ref"] = "siteuserperm";
		$linkparams["action"] = "siteuserlist";
		$this->stringvars['userlistlink'] = makelinkparameters($linkparams);
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
		
		$linkparams["page"] = $this->stringvars['page'];
		$linkparams["changelevel"] = "change";
		$linkparams["action"] = "siteuserperm";
		$this->stringvars['actionvars'] = makelinkparameters($linkparams);

		$this->stringvars['hiddenvars']='<input type="hidden" name="userid" value="'.$userid.'" />';
  		
		$this->stringvars['username']=title2html(getusername($userid));
  		
  		$this->stringvars['userlevel_user']=USERLEVEL_USER;
  		$this->stringvars['userlevel_admin']=USERLEVEL_ADMIN;
  		
  		if(getuserlevel($userid)==USERLEVEL_USER) $this->stringvars['levelisuser']="true";
  		elseif(getuserlevel($userid)==USERLEVEL_ADMIN)$this->stringvars['levelisadmin']="true";
  		
  		$this->vars['submitrow']= new SubmitRow("changelevel","Change Userlevel",true);
  		
		$linkparams=array();
		$linkparams["page"] = $this->stringvars['page'];
		$linkparams["username"] = $this->stringvars['username'];
		$linkparams["action"] = "siteuserperm";
		$this->stringvars['returnlink'] = makelinkparameters($linkparams);

		$linkparams=array();
		$linkparams["page"] = $this->stringvars['page'];
		$linkparams["userid"] = $userid;
		$linkparams["action"] = "siteuserman";
		$this->stringvars['managelink'] = makelinkparameters($linkparams);

		$linkparams=array();
		$linkparams["page"] = $this->stringvars['page'];
		$linkparams["action"] = "siteuserlist";
		$this->stringvars['userlistlink'] = makelinkparameters($linkparams);
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
		
		$this->stringvars['username']=title2html(getpublicusername($userid));
  
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

		$linkparams=array();
		$linkparams["page"] = $this->stringvars['page'];
		$linkparams["username"] = $this->stringvars['username'];
		$linkparams["action"] = "siteuserperm";
		$this->stringvars['returnlink'] = makelinkparameters($linkparams);

		$linkparams=array();
		$linkparams["page"] = $this->stringvars['page'];
		$linkparams["userid"] = $userid;
		$linkparams["type"] = "public";
		$linkparams["action"] = "siteuserman";
		$this->stringvars['managelink'] = makelinkparameters($linkparams);

		$linkparams=array();
		$linkparams["page"] = $this->stringvars['page'];
		$linkparams["action"] = "siteuserlist";
		$this->stringvars['userlistlink'] = makelinkparameters($linkparams)."#public";
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
  		
  		$this->stringvars['hiddenvars']='<input type="hidden" name="userid" value="'.$userid.'">';
  		$this->stringvars['hiddenvars'].='<input type="hidden" name="pageid" value="'.$page.'">';
  		
		$linkparams["page"] = $this->stringvars['page'];

		$this->stringvars['pagelink']=getprojectrootlinkpath()."admin/pagedisplay.php=".makelinkparameters($linkparams);
		$this->stringvars['pagelinktitle']=$page.": ".title2html(getnavtitle($page));

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

		$linkparams["changeaccess"] = $this->stringvars['changeaccessaction'];
		$linkparams["type"] = "public";
		$linkparams["action"] = "siteuserperm";
		$this->stringvars['actionvars'] = makelinkparameters($linkparams);
	}

  	// assigns templates
  	function createTemplates()
  	{
    	$this->addTemplate("admin/site/publicuseraccesspageform.tpl");
  	}
}
?>