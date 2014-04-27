<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."functions/pagecontent/linklistpages.php");
include_once($projectroot."includes/objects/template.php");
//include_once($projectroot."admin/includes/objects/images.php");
include_once($projectroot."admin/includes/objects/editor.php");
include_once($projectroot."admin/includes/objects/imageeditor.php");

//
//
//
class AddLinklistLinkForm extends Template {
	function AddLinklistLinkForm()
	{
		parent::__construct();
		$this->stringvars['actionvars']= "?page=".$this->stringvars['page']."&action=editcontents";
	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/edit/addlinklistlinkform.tpl");
	}
}


//
//
//
class EditLinkListLinkForm extends Template {
	function EditLinkListLinkForm($linkid)
	{
		parent::__construct($linkid);
		
		$this->stringvars['javascript']="&nbsp;".prepareJavaScript($this->stringvars['jsid'], "admin/includes/javascript/messageboxes.js");
		$this->stringvars['javascript'].=prepareJavaScript($this->stringvars['jsid'], "admin/includes/javascript/editlinklist.js");
		
		$this->stringvars['hiddenvars']='<input type="hidden" id="'.$this->stringvars['jsid'].'linkid" name="linkid" value="'.$linkid.'">';
		$this->stringvars['hiddenvars'].='<input type="hidden" id="'.$this->stringvars['jsid'].'page" name="page" value="'.$this->stringvars['page'].'">';
		
		$this->stringvars['actionvars']= "?page=".$this->stringvars['page']."&link=".$linkid."&action=editcontents";
		$this->stringvars['imagelistpath']=getprojectrootlinkpath()."admin/editimagelist.php?page=".$this->stringvars['page'];
		
		$this->stringvars['linkid']=$linkid;
		
		$contents=getlinkcontents($linkid);

		$this->stringvars['linktitle']=title2html($contents['title']);
		if(!$contents['title'])
			$this->stringvars['linktitle'] = "New Link";

		$this->stringvars['linkinputtitle']=input2html($contents['title']);
		$this->stringvars['link']=$contents['link'];
		$this->stringvars['description']=text2html($contents['description']);
		
		$this->vars['imageeditor'] = new ImageEditor($this->stringvars['page'],$linkid,"link",$contents);
		
		$this->vars['editdescription']= new Editor($this->stringvars['page'],$linkid,"link","Link Description");
		$this->vars['deleteconfirmform']= new CheckboxForm("deletelinkconfirm","deletelinkconfirm","Confirm delete",false, "right");
	}
	
	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/edit/editlinklistlinkform.tpl");
	}
}


//
//
//
class EditLinklist extends Template {
	function EditLinklist($page)
	{
		parent::__construct($page,array(0=>"includes/javascript/jquery.js", 1=>"includes/javascript/jcaret.js"));
		
		$this->stringvars['javascript']="&nbsp;".prepareJavaScript($this->stringvars['jsid'], "admin/includes/javascript/messageboxes.js");
		
		$this->stringvars['actionvars']= "?page=".$this->stringvars['page']."&action=editcontents";
		$this->stringvars['imagelistpath']=getprojectrootlinkpath()."admin/editimagelist.php?page=".$this->stringvars['page'];
		
		$linkids=getlinklistitems($page);
		if(count($linkids<1)) $this->stringvars['linkform']="";
		
		for($i=0;$i<count($linkids);$i++)
		{
			$this->listvars['linkform'][] = new EditLinkListLinkForm($linkids[$i]);
		}
		
		$this->vars['addform'] = new AddLinklistLinkForm();
		$this->vars['navigationbuttons']= new PageEditNavigationButtons(new GeneralSettingsButton(),new EditPageIntroSettingsButton());
	}
	
	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/edit/editlinklist.tpl");
	}
}

?>