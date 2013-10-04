<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprüft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."includes/objects/template.php");
include_once($projectroot."admin/includes/objects/forms.php");
include_once($projectroot."admin/includes/objects/editor.php");
include_once($projectroot."admin/includes/objects/imageeditor.php");


//
//
//
class EditPageIntro extends Template {
	function EditPageIntro($page)
	{
		parent::__construct($page,array(0=>"includes/javascript/jquery.js", 1=>"includes/javascript/jcaret.js"));
		$this->stringvars['javascript']="&nbsp;".prepareJavaScript($this->stringvars['jsid'], "admin/includes/javascript/messageboxes.js");

		$this->vars['intro']= new Editor($page,0,"pageintro","Synopsis");
		$this->vars['imageeditor'] = new ImageEditor($page,0,"pageintro",array("image"=>getpageintroimage($page), "halign" =>getpageintrohalign($page)));
		$this->vars['navigationbuttons']= new PageEditNavigationButtons(new GeneralSettingsButton(),new EditPageContentsButton());
	}
	
	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/edit/editpageintro.tpl");
	}
}

?>