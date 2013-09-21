<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprüft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."includes/objects/template.php");
include_once($projectroot."includes/objects/elements.php");
include_once($projectroot."admin/includes/objects/elements.php");

//
//
//
class SiteTechnical extends Template {

	function SiteTechnical()
  	{
  		parent::__construct();
  		
  		$this->stringvars['actionvars']='?sid='.$this->stringvars['sid'].'&page='.$this->stringvars['page'].'&postaction=savesite&action=sitetech';
  	
  		$properties=getproperties();
  		
  		$this->stringvars["googlekeywords"]=input2html($properties["Google Keywords"]);
  		$this->stringvars["domainname"]=$properties["Domain Name"];
  		$this->stringvars["localpath"]=$properties["Local Path"];
  		$this->stringvars["cookieprefix"]=$properties["Cookie Prefix"];
  		$this->stringvars["imagepath"]=$properties["Image Upload Path"];
  		$this->stringvars["adminemail"]=$properties["Admin Email Address"];
  		$this->stringvars["emailsig"]=input2html($properties["Email Signature"]);
  		$this->stringvars["datetimeformat"]=$properties["Date Time Format"];
  		$this->stringvars["dateformat"]=$properties["Date Format"];
  		$this->stringvars["thumbnailsize"]=$properties["Thumbnail Size"];
  		$this->stringvars["mobilethumbnailsize"]=$properties["Mobile Thumbnail Size"];
  		
  		$this->vars['submitrow']= new SubmitRow("submit","Submit",true);
  	}

  	// assigns templates
  	function createTemplates()
  	{
    	$this->addTemplate("admin/site/technical.tpl");
  	}
}
?>