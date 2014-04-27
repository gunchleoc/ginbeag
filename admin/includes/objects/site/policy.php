<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprüft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."includes/objects/template.php");
include_once($projectroot."admin/includes/objects/forms.php");
include_once($projectroot."includes/objects/elements.php");
include_once($projectroot."admin/includes/objects/editor.php");


//
//
//
class SitePolicy extends Template {

	function SitePolicy()
  	{
  		parent::__construct("sitepolicy",array(0=>"includes/javascript/jquery.js", 1=>"includes/javascript/jcaret.js"));
  		
  		$this->stringvars['javascript']="&nbsp;".prepareJavaScript($this->stringvars['jsid'], "admin/includes/javascript/messageboxes.js");
  		
  		$this->stringvars['actionvars']='?page='.$this->stringvars['page'].'&postaction=savesite&action=sitepolicy';
  	
  		$properties=getproperties();
  		
  		$policytitle=$properties["Site Policy Title"];
  		$policytext=getdbelement("sitepolicytext",SITEPOLICY_TABLE,"policy_id",0);

		$this->vars['displaypolicy_yes'] = new RadioButtonForm("", "displaypolicy", 1, "Yes", $properties["Display Site Policy"], "right");
	    $this->vars['displaypolicy_no'] = new RadioButtonForm("", "displaypolicy", 0, "No", !$properties["Display Site Policy"], "right");

        $this->stringvars['policytitle']=$properties["Site Policy Title"];
        
        $this->vars['policytext']= new Editor($this->stringvars['page'],0,"sitepolicy","Site Policy");
        
        $this->vars['submitrow']= new SubmitRow("submit","Submit",true);
  	}

  	// assigns templates
  	function createTemplates()
  	{
    	$this->addTemplate("admin/site/policy.tpl");
  	}
}
?>