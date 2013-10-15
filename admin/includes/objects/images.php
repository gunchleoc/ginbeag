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

	function CaptionedImageAdmin($filename,$page,$factor=1)
  	{
    	parent::__construct();
    	
    	$this->vars['image']= new CaptionedImage($filename,$factor,"left", true,true);
    	$this->stringvars['imagelinkpath']=getimagelinkpath($filename, getimagesubpath(basename($filename)));
    	$this->stringvars['editimagelink']='<a href="'.getprojectrootlinkpath().'admin/editimagelist.php?page='.$page.'&filename='.$filename.'&caption=&source=&copyright=&uploader=0&filter=Display+Selection" target="_blank">Edit this image</a>';
  	}

  	// assigns templates
  	function createTemplates()
  	{
    	$this->addTemplate("admin/captionedimageadmin.tpl");
  	}
}

?>