<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprüft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."includes/objects/template.php");
include_once($projectroot."includes/objects/elements.php");
include_once($projectroot."admin/includes/objects/forms.php");

//
//
//
class SiteTechnical extends Template {

	function SiteTechnical()
  	{
  		parent::__construct();

		$linkparams["page"] = $this->stringvars['page'];
		$linkparams["postaction"] = "savesite";
		$linkparams["action"] = "sitetech";
		$this->stringvars['actionvars'] = makelinkparameters($linkparams);

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
		$this->stringvars["imagesperpage"]=$properties['Imagelist Images Per Page'];
  		
  		$this->vars['submitrow']= new SubmitRow("submit","Submit",true);
  	}

  	// assigns templates
  	function createTemplates()
  	{
    	$this->addTemplate("admin/site/technical.tpl");
  	}
}
?>