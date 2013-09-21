<?php
$projectroot=dirname(__FILE__);

// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."functions/db.php");
//include_once($projectroot."includes/objects/elements.php");
include_once($projectroot."includes/objects/newspage.php");

//
//
//
class Preview extends Template {
	function Preview($newsitem=0,$linkparams="")
  	{
    	parent::__construct();
    	
    	$this->stringvars['stylesheet']=getCSSPath("main.css");
		$this->stringvars['adminstylesheet']=getCSSPath("admin.css");
		$this->stringvars['headertitle']= title2html(getproperty("Site Name")).' - Webpage building';

		$this->vars['content']= new Newsitem($newsitem,0,true,true,false);
	}

  	// assigns templates
  	function createTemplates()
  	{
    	$this->addTemplate("admin/preview.tpl");
  	}
}
?>