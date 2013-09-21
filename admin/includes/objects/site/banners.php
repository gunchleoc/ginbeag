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
include_once($projectroot."admin/includes/objects/elements.php");

//
//
//
class SiteBannerEditForm extends Template {

  function SiteBannerEditForm($banner_id)
  {
  	parent::__construct();
  	
  	$this->stringvars['moveactionvars']='?sid='.$this->stringvars['sid'].'&page='.$this->stringvars['page'].'&postaction=movebanner&action=sitebanner';
  	$this->stringvars['deleteactionvars']='?sid='.$this->stringvars['sid'].'&page='.$this->stringvars['page'].'&postaction=deletebanner&action=sitebanner';
  	$this->stringvars['editactionvars']='?sid='.$this->stringvars['sid'].'&page='.$this->stringvars['page'].'&postaction=editbanner&action=sitebanner';
  	
    $contents=getbannercontents($banner_id);
    if($contents['header'])
    {
      $this->stringvars['header']=input2html($contents['header']);
    }
    else $this->stringvars['header']="";
    
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
	$this->stringvars['jsid']=$banner_id;
	$this->stringvars['bannerid']=$banner_id;
	
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
   	$this->stringvars['displayactionvars']='?sid='.$this->stringvars['sid'].'&page='.$this->stringvars['page'].'&postaction=displaybanners&action=sitebanner';
  	$this->stringvars['addactionvars']='?sid='.$this->stringvars['sid'].'&page='.$this->stringvars['page'].'&postaction=addbanner&action=sitebanner';
  	
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
  	
  	$this->vars['submitrow']= new SubmitRow("addbanner","Add Banner",true);
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/site/banners.tpl");
  }
}

?>
