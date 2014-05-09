<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprüft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."includes/objects/template.php");
include_once($projectroot."admin/functions/pagesmod.php");
include_once($projectroot."includes/objects/elements.php");

//
//
//
class SitePageTypes extends Template {

	function SitePageTypes()
	{
		parent::__construct();
		
		$pagetypes=getpagetypes();
		$keys=array_keys($pagetypes);
		
		for($i=0;$i<count($keys);$i++)
		{
			$pagetype=$keys[$i];
			$restrictions=getrestrictions($pagetype);
		
			$this->listvars['pagetype'][]=new SitePageType($pagetype, $pagetypes[$pagetype], $restrictions);
		}
	}
	
	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/site/pagetypes.tpl");
	}
}


//
//
//
class SitePageType extends Template {

	function SitePageType($pagetype, $description, $restrictions)
	{
		parent::__construct();

		$linkparams["page"] = $this->stringvars['page'];
		$linkparams["action"] = "sitepagetype";
		$this->stringvars['actionvars'] = makelinkparameters($linkparams);

		$this->stringvars['hiddenvars']='<input type="hidden" name="pagetype" value="'.$pagetype.'">';
		
		$this->stringvars['pagetype']=$pagetype;
		$this->stringvars['description']=$description;
		$this->vars['allowrootform']= new CheckboxForm("allowroot","allowroot","",$restrictions["allowroot"]);
		$this->vars['allowsimplemenuform']= new CheckboxForm("allowsimplemenu","allowsimplemenu","",$restrictions["allowsimplemenu"]);
		if($restrictions["allowself"]) $this->stringvars['allowself']="true";
	}
	
	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/site/pagetype.tpl");
	}
}

?>