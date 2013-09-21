<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprüft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."includes/objects/template.php");
include_once($projectroot."admin/includes/objects/forms.php");
include_once($projectroot."includes/objects/elements.php");
include_once($projectroot."admin/includes/objects/editor.php");
include_once($projectroot."admin/includes/objects/elements.php");



//
//
//
class SitePolicy extends Template {

	function SitePolicy()
  	{
  		parent::__construct("sitepolicy",array(0=>"includes/javascript/jquery.js", 1=>"includes/javascript/jcaret.js"));
  		
  		$this->stringvars['javascript']="&nbsp;".prepareJavaScript($this->stringvars['jsid'], "admin/includes/javascript/messageboxes.js");
  		
  		$this->stringvars['actionvars']='?sid='.$this->stringvars['sid'].'&page='.$this->stringvars['page'].'&postaction=savesite&action=sitepolicy';
  	
  		$properties=getproperties();
  		
  		$policytitle=$properties["Site Policy Title"];
  		$policytext=getdbelement("sitepolicytext",SITEPOLICY_TABLE,"policy_id",0);

        if($properties["Display Site Policy"])
        {
        	$this->stringvars['policyon']="true";
        }
        else
        {
        	$this->stringvars['policyoff']="true";
        }
        
        $this->stringvars['policytitle']=$properties["Site Policy Title"];
        
        $this->vars['policytext']= new Editor(0,0,"sitepolicy","Site Policy");
        
        $this->vars['submitrow']= new SubmitRow("submit","Submit",true);
  	}

  	// assigns templates
  	function createTemplates()
  	{
    	$this->addTemplate("admin/site/policy.tpl");
  	}
}
?>