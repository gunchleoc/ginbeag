<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."functions/pages.php");
include_once($projectroot."includes/objects/template.php");
include_once($projectroot."includes/includes.php");
include_once($projectroot."includes/objects/forms.php");
include_once($projectroot."admin/includes/objects/forms.php");
include_once($projectroot."functions/banners.php");
include_once($projectroot."admin/includes/objects/site/banners.php");
include_once($projectroot."includes/objects/elements.php");

//
//
//
class SiteBannerEditForm extends Template {

	function SiteBannerEditForm($banner)
	{
		parent::__construct($banner);
		
		$linkparams["page"] = $this->stringvars['page'];
		$linkparams["postaction"] = "movebanner";
		$linkparams["action"] = "sitebanner";
		$this->stringvars['moveactionvars'] = makelinkparameters($linkparams);

		$linkparams["postaction"] = "deletebanner";
		$this->stringvars['deleteactionvars'] = makelinkparameters($linkparams);

		$linkparams["postaction"] = "editbanner";
		$this->stringvars['editactionvars'] = makelinkparameters($linkparams);

		$this->stringvars['hiddenvars']='<input type="hidden" name="bannerid" value="'.$banner.'" />';
		
		$contents=getbannercontents($banner);
		if($contents['header'])
			$this->stringvars['header']=input2html($contents['header']);
		else
			$this->stringvars['header']="";
		
		$this->vars['banner'] = new Banner($banner,true);
		
		if(!isbannercomplete($banner))
			$this->stringvars['incomplete']= "incomplete";
		
		if(strlen($contents['image'])>0)
			$this->stringvars['image']=$contents['image'];
		else
			$this->stringvars['noimage']="true";

		$this->stringvars['description']=input2html($contents['description']);
		$this->stringvars['link']=$contents['link'];
		$this->stringvars['code']=input2html($contents['code']);

		$this->vars['deletebannerconfirmform']= new CheckboxForm("deletebannerconfirm","deletebannerconfirm","Confirm delete",false, "right");
		
		$this->vars['submitrow']= new SubmitRow("bannerproperties","Submit Banner Changes",true);
	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/site/bannereditform.tpl");
	}
}




//
//
//
class SiteBanners extends Template {

	function SiteBanners()
	{
		parent::__construct();
		
		$this->stringvars['displayhiddenvars']='<input type="hidden" name="postaction" value="displaybanners" />';

		$linkparams["page"] = $this->stringvars['page'];
		$linkparams["postaction"] = "displaybanners";
		$linkparams["action"] = "sitebanner";
		$this->stringvars['displayactionvars'] = makelinkparameters($linkparams);

		$linkparams["postaction"] = "addbanner";
		$this->stringvars['addactionvars'] = makelinkparameters($linkparams);

		$this->vars['displaybanners_yes'] = new RadioButtonForm($this->stringvars['jsid'], "toggledisplaybanners", 1, "Yes", getproperty('Display Banners'), "right");
	    $this->vars['displaybanners_no'] = new RadioButtonForm($this->stringvars['jsid'], "toggledisplaybanners", 0, "No", !getproperty('Display Banners'), "right");
		
		$banners=getbanners();
		for($i=0;$i<count($banners);$i++)
		{
			$this->listvars['editform'][] = new SiteBannerEditForm($banners[$i]);
		}
		
		$this->vars['submitrow']= new SubmitRow("addbanner","Add Banner",true);
	}
	
	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/site/banners.tpl");
	}
}

?>
