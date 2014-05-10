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

		$linkparams["page"] = $this->stringvars['page'];
		$linkparams["postaction"] = "savesite";
		$linkparams["action"] = "sitespam";
		$this->stringvars['actionvars']= makelinkparameters($linkparams);

		$this->stringvars['hiddenvars'] = $this->makehiddenvars(array("postaction" => "savesite"));
		
		$variables=getmultiplefields(ANTISPAM_TABLE, "property_name", "1", array(0 => 'property_name', 1 => 'property_value'));

		$this->vars['usemathcaptcha_yes'] = new RadioButtonForm($this->stringvars['jsid'], "usemathcaptcha", 1, "Yes", $variables['Use Math CAPTCHA']['property_value'], "right");
	    $this->vars['usemathcaptcha_no'] = new RadioButtonForm($this->stringvars['jsid'], "usemathcaptcha", 0, "No", !$variables['Use Math CAPTCHA']['property_value'], "right");
		
		$this->vars['submitrow']= new SubmitRow("submit","Submit",true);
	}
	
	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/site/antispam.tpl");
	}
}


?>