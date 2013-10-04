<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."functions/pagecontent/externalpages.php");
include_once($projectroot."functions/pages.php");
include_once($projectroot."includes/objects/template.php");
include_once($projectroot."includes/includes.php");
include_once($projectroot."includes/objects/forms.php");
include_once($projectroot."includes/objects/page.php");
include_once($projectroot."admin/includes/objects/editor.php"); // todo only imported for the header for now. Overkill?




//
//
//
class DeletePageConfirmForm extends Template {

	function DeletePageConfirmForm()
	{
		parent::__construct();
		
		$this->stringvars['actionvars']= "?sid=".$this->stringvars['sid']."&page=".$this->stringvars['page'];
		
		$this->stringvars['pagetitle']=title2html(getpagetitle($this->stringvars['page']));
		
		$children=getchildren($this->stringvars['page']);
		if(count($children))
		{
			$this->stringvars['deletemessage']="Are you sure you want to delete these pages?";
			for($i=0;$i<count($children);$i++)
			{
				$this->listvars['subpages'][]= new NavigatorBranch($children[$i],"simple",5000,0,"",true);
			}
		}
		else
		{
			$this->stringvars['deletemessage']="Are you sure you want to delete this page?";
		}
	}
	
	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/deletepageconfirmform.tpl");
	}
}

//
//
//
class FindNewParentForm extends Template {

	function FindNewParentForm()
	{
		parent::__construct();
		$this->stringvars['actionvars']= "?sid=".$this->stringvars['sid']."&page=".$this->stringvars['page']."&action=findnewparent";
	}
	
	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/findnewparentform.tpl");
	}
}



//
//
//
class SelectNewParentForm extends Template {

	function SelectNewParentForm()
	{
		parent::__construct();
		
		$this->stringvars['actionvars']= "?sid=".$this->stringvars['sid']."&page=".$this->stringvars['page']."&action=newparent";
		$this->stringvars['pagetitle']=title2html(getpagetitle($this->stringvars['page']));
		
		$values=array();
		$descriptions=array();
		
		$allpages= getmovetargets($this->stringvars['page']);
		$i=0;
		if(array_key_exists(0,$allpages))
		{
			$values[]=0;
			$descriptions[]="Site Root";
			$i=1;
		}
		
		for(;$i<count($allpages);$i++)
		{
			$values[]=$allpages[$i];
			$descriptions[]=$allpages[$i].': '.title2html(getnavtitle($allpages[$i]));
		}
		
		$this->vars['targetform']= new OptionForm(0,$values,$descriptions,"parentnode","Move this page to:",20);
		$this->stringvars['cancellocation']='?action=edit&sid='.$this->stringvars['sid'].'&page='.$this->stringvars['page'];
	}
	
	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/selectnewparentform.tpl");
	}
}




//
//
//
class RestrictAccessForm extends Template {

	function RestrictAccessForm()
	{
		parent::__construct();
		
		$this->stringvars['actionvars']= "?sid=".$this->stringvars['sid']."&page=".$this->stringvars['page']."&action=restrictaccess";
		$this->stringvars['usersactionvars']= "?sid=".$this->stringvars['sid']."&page=".$this->stringvars['page']."&action=restrictaccessusers";
		
		$accessrestricted=isthisexactpagerestricted($this->stringvars['page']);
		
		$this->vars['restrict_yes']= new RadioButtonForm("","restrict","1","Yes",$accessrestricted);
		$this->vars['restrict_no']= new RadioButtonForm("","restrict","0","No",!$accessrestricted);
		
		$this->vars['submitrow']= new SubmitRow("restrictaccess","Change Access Restriction",true);
		
		if($accessrestricted)
		{
			$this->stringvars['accessrestricted']="Access restricted";
			$accessusers=getallpublicuserswithaccessforpage($this->stringvars['page']);
			if(count($accessusers)==0)
			{
				$this->stringvars['restricteduserlist']='<em>No users have access to this page</em>';
			}
			else
			{
				$this->stringvars['restricteduserlist']='<span class="highlight">The following users have access to this page:</span><br /><em>';
				for($i=0;$i<count($accessusers);$i++)
				{
					$this->stringvars['restricteduserlist'].=input2html(getpublicusername($accessusers[$i]))." ";
				}
				$this->stringvars['restricteduserlist'].='</em>';
			}

			$values=array();
			$descriptions=array();
			$allpublicusers=getallpublicusers();
			
			for($i=0;$i<count($allpublicusers);$i++)
			{
				$values[]=$allpublicusers[$i];
				$descriptions[]=title2html(getpublicusername($allpublicusers[$i]));
			}
			$this->vars['selectusers']= new OptionForm(0,$values,$descriptions,"selectusers[]","Select Users: ",5, "multiple");
		}
	}
	
	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/restrictaccessform.tpl");
	}
}

// todo: code duplication with adminnewspage
//
//
class PermissionsForm extends Template {

	function PermissionsForm($permissions)
	{
		parent::__construct();
		
		$this->stringvars['actionvars']= "?sid=".$this->stringvars['sid']."&page=".$this->stringvars['page']."&action=setpermissions";
		
		$accessrestricted=ispagerestricted($this->stringvars['page']);
		
		$this->stringvars['copyright']=input2html($permissions['copyright']);
		$this->stringvars['image_copyright']=input2html($permissions['image_copyright']);
		
		$this->vars['permission_granted']= new RadioButtonForm("","permission",PERMISSION_GRANTED,"Permission granted",$permissions['permission']==PERMISSION_GRANTED,"right");
		$this->vars['no_permission']= new RadioButtonForm("","permission",NO_PERMISSION,"No permission",$permissions['permission']==NO_PERMISSION,"right");
		$this->vars['permission_refused']= new RadioButtonForm("","permission",PERMISSION_REFUSED,"Permission refused",$permissions['permission']==PERMISSION_REFUSED,"right");
		
		if($accessrestricted)
		{
		  $showrefused=showpermissionrefusedimages($this->stringvars['page']);
		  $this->stringvars['accessrestricted']="Access restricted";
		  $this->vars['showrefused_yes']= new RadioButtonForm("","show","1","Yes",$showrefused);
		  $this->vars['showrefused_no']= new RadioButtonForm("","show","0","No",!$showrefused);
		}
		$this->vars['submitrow']= new SubmitRow("setpermissions","Change Copyright and Permissions",true);
	}
	
	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/permissionsform.tpl");
	}
}

//
//
//
class RenamePageForm extends Template {

	function RenamePageForm()
	{
		parent::__construct();
		
		$this->stringvars['actionvars']= "?sid=".$this->stringvars['sid']."&page=".$this->stringvars['page']."&action=rename";
		$this->stringvars['navtitle']=input2html(getnavtitle($this->stringvars['page']));
		$this->stringvars['pagetitle']=input2html(getpagetitle($this->stringvars['page']));
		$this->vars['submitrow']= new SubmitRow("submit","Rename",true);
	}
	
	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/renamepageform.tpl");
	}
}


//
//
//
class SetPublishableForm extends Template {

	function SetPublishableForm()
	{
		parent::__construct();
		
		$this->stringvars['actionvars']= "?sid=".$this->stringvars['sid']."&page=".$this->stringvars['page']."&action=setpublishable";
		
		$permissionrefused=permissionrefused($this->stringvars['page']);
		if(!$permissionrefused)
		{
			$ispublishable=ispublishable($this->stringvars['page']);
			$this->stringvars['not_permissionrefused']="Permission not refused";
			$this->vars['publishable_yes']= new RadioButtonForm("","ispublishable","public","Public page",$ispublishable);
			$this->vars['publishable_no']= new RadioButtonForm("","ispublishable","internal","Internal page",!$ispublishable);
		}
		else
			$this->stringvars['permissionrefused']="Permission refused";
		  
		$this->vars['submitrow']= new SubmitRow("submit","Change Setting",true);
	}
	
	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/setpublishableform.tpl");
	}
}


//
//
//
class ExternalForm extends Template {

	function ExternalForm()
	{
		parent::__construct();
		
		$this->stringvars['actionvars']= "?sid=".$this->stringvars['sid']."&page=".$this->stringvars['page']."&action=edit";
		$this->stringvars['link']=getexternallink($this->stringvars['page']);
	}
	
	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/externalform.tpl");
	}
}


//
//
//
class EditPage extends Template {

	function EditPage($page)
	{
		$jslibraries = array(0=>"includes/javascript/jquery.js", 1=>"includes/javascript/jcaret.js");
		$jscripts = array(0=>"admin/includes/javascript/messageboxes.js", 1=>"admin/includes/javascript/editpage.js");
		parent::__construct($page,$jslibraries,$jscripts);
    
		$pagetype=getpagetype($page);
		$permissions=getcopyright($page);

		if($pagetype==="external")
		{
			$this->vars['contentsform']= new ExternalForm();
			$this->vars['navigationbuttons']= new PageEditNavigationButtons("","");
		}
		elseif($pagetype==="menu" || $pagetype==="articlemenu")
		{
			$this->vars['navigationbuttons']= new PageEditNavigationButtons(new EditPageIntroSettingsButton(),"");
		}
		else
		{
			$this->vars['navigationbuttons']= new PageEditNavigationButtons(new EditPageIntroSettingsButton(),new EditPageContentsButton());
		}

		$this->vars['renamepageform']= new RenamePageForm();

		$this->vars['setpublishableform']= new SetPublishableForm();

		if($pagetype!=="external")
		{
			$this->vars['permissionsform']= new PermissionsForm($permissions);
			$this->vars['restrictaccessform']=  new RestrictAccessForm();
		}
      
		$this->vars['movepageform']= new MovePageForm($page,$page);

		$this->vars['findnewparentform']= new FindNewParentForm();
	}

	// assigns templates
	function createTemplates()
	{
    	$this->addTemplate("admin/editpage.tpl");
	}
}
?>