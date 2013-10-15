<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."includes/objects/template.php");
include_once($projectroot."includes/objects/elements.php");
include_once($projectroot."admin/includes/objects/forms.php");


//
//
//
class SiteAntispam extends Template {

	function SiteAntispam()
	{
		global $projectroot;
		parent::__construct();
		
		$this->stringvars['actionvars']='?page='.$this->stringvars['page'].'&postaction=savesite&action=sitespam';
		
		$this->stringvars['hiddenvars']='<input type="hidden" name="postaction" value="savesite" />';
		
		$variables=getmultiplefields(ANTISPAM_TABLE, "property_name", "1", array(0 => 'property_name', 1 => 'property_value'));
		
		
		if($variables['Use Math CAPTCHA']['property_value'])
			$this->stringvars['usemathcaptcha']=" checked";
		else
			$this->stringvars['not_usemathcaptcha']=" checked";
		
		$this->vars['submitrow']= new SubmitRow("submit","Submit",true);
	}
	
	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/site/antispam.tpl");
	}
}


?>