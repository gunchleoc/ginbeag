<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."functions/pages.php");
include_once($projectroot."includes/templates/template.php");
include_once($projectroot."includes/includes.php");
include_once($projectroot."includes/templates/forms.php");
include_once($projectroot."admin/includes/templates/adminforms.php");
include_once($projectroot."functions/banners.php");
include_once($projectroot."admin/includes/templates/sitebanners.php");


//
//
//
class SiteBannerEditForm extends Template {

  function SiteBannerEditForm($banner_id)
  {

	$this->stringvars['moveactionvars']=$this->makeactionvars("get",array(),array("bannerid" => $banner_id, "action" => "movebanner"));
    $this->stringvars['deleteactionvars']=$this->makeactionvars("get",array(),array("bannerid" => $banner_id, "action" => "deletebanner"));
    $this->stringvars['banneractionvars']=$this->makeactionvars("get",array(),array("bannerid" => $banner_id, "action" => "editbanner"));
	
  	
    $contents=getbannercontents($banner_id);
    if($contents['header'])
    {
      $this->stringvars['header']=input2html($contents['header']);
    }
    $this->vars['banner'] = new Banner($banner_id,true);

    if(!isbannercomplete($banner_id))
    {
    	$this->stringvars['incomplete']= "incomplete";
    }
    
  	if(strlen($contents['image'])>0)
  	{
		$this->stringvars['image']=$contents['image'];
  	}
	$this->stringvars['description']=input2html($contents['description']);
	$this->stringvars['link']=$contents['link'];
	$this->stringvars['code']=input2html($contents['code']);
	$this->stringvars['id']=$banner_id;

    $this->createTemplates();

  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/sitebannereditform.tpl");
  }
}




//
//
//
class SiteBanners extends Template {

  function SiteBanners($message="")
  {
  	//global $sid, $projectroot; 
  	
	$this->vars['header'] = new HTMLHeader("Banners","Webpage Building", $message);
	$this->vars['footer'] = new HTMLFooter();

    $this->stringvars['addactionvars']=$this->makeactionvars("get",array("bannerid" => "bannerid"),array("action" => "addbanner"));
    $this->stringvars['displayactionvars']=$this->makeactionvars("get",array("bannerid" => "bannerid"),array("action" => "displaybanners"));
	
  	
  	if(getproperty('Display Banners'))
  	{
  		$this->stringvars['displaybanners'] =' checked';
  		$this->stringvars['not_displaybanners'] ='';
  	}
  	else
  	{
  		$this->stringvars['displaybanners'] ='';
  		$this->stringvars['not_displaybanners'] =' checked';
  	}
  	
  	$banners=getbanners();
  	for($i=0;$i<count($banners);$i++)
  	{
  		$this->listvars['editform'][] = new SiteBannerEditForm($banners[$i]);
  	}  	

    $this->createTemplates();

  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/sitebanners.tpl");
  }
}

?>
