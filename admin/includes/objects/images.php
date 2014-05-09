<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."includes/objects/template.php");
include_once($projectroot."includes/objects/images.php");
include_once($projectroot."includes/functions.php");


//
//
//
class CaptionedImageAdmin extends Template {

	function CaptionedImageAdmin($filename, $page)
  	{
    	parent::__construct();
    	
		$this->vars['image']= new CaptionedImage($filename, true, true, "left");
    	$this->stringvars['imagelinkpath']=getimagelinkpath($filename, getimagesubpath(basename($filename)));

		$linkparams["page"] = $page;
		$linkparams["filename"] = $filename;
		$linkparams["filter"] = "Display+Selection";
		$this->stringvars['editimagelink']='<a href="'.getprojectrootlinkpath().'admin/editimagelist.php'.makelinkparameters($linkparams).'" target="_blank">Edit this image</a>';
  	}

  	// assigns templates
  	function createTemplates()
  	{
    	$this->addTemplate("admin/captionedimageadmin.tpl");
  	}
}

?>