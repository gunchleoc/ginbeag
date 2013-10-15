<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"objects"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."includes/functions.php");
include_once($projectroot."includes/objects/template.php");
include_once($projectroot."includes/objects/forms.php");
include_once($projectroot."admin/includes/objects/images.php");


//
// Templating for Section Images
//
class ImageEditor extends Template {

    function ImageEditor($page, $elementid, $elementtype,$contents)
    {
    	parent::__construct($page.'-'.$elementid);
		$this->stringvars['javascript']="&nbsp;".prepareJavaScript($this->stringvars['jsid'], "admin/includes/javascript/messageboxes.js");
		$this->stringvars['javascript'].=prepareJavaScript($this->stringvars['jsid'], "admin/includes/javascript/imageeditor.js");
    	
    	$imagealign="left";
    	$this->stringvars['image']="";
    	
    	if($elementtype==="pageintro")
    	{
    		$this->stringvars['image']=$contents["image"];
    		$imagealign = $contents["halign"];
    		$this->stringvars['title']="Synopsis";
    	}
    	elseif($elementtype==="articlesection" || $elementtype==="newsitemsection")
    	{
    		$this->stringvars['image']=$contents['sectionimage'];
    		$imagealign = $contents['imagealign'];
    		$this->stringvars['title']="Section";
    	}
    	elseif($elementtype==="link")
    	{
    		$this->stringvars['image']=$contents['image'];
    		$imagealign = "";
    		$this->stringvars['title']="Link";
    	}
    
		$this->stringvars['elementtype']=$elementtype;
		$this->stringvars['imagelistpath']=getprojectrootlinkpath()."admin/editimagelist.php?page=".$this->stringvars['page'];
		$this->vars['filenamepane'] = new ImageEditorFilenamePane($page,$elementid, $this->stringvars['image'],$elementtype);

		if($elementtype=="link")
		{
			$this->stringvars['propertiespane'] ="";
			$this->vars['imagepane'] = new ImageEditorImagePane($page,$this->stringvars['image']);
		}
		elseif($this->stringvars['image'])
		{
			$this->vars['propertiespane'] = new ImageEditorPropertiesPane($page,$elementid, $imagealign);
			$this->vars['imagepane'] = new ImageEditorImagePane($page,$this->stringvars['image']);
		}
		else
		{
			$this->stringvars['propertiespane'] ="";
			$this->stringvars['imagepane'] = "";
		}
    }

    // assigns templates
    function createTemplates()
    {
		$this->addTemplate("admin/imageeditor.tpl");
    }
}


//
// Templating for assigning an image to a section
//
class ImageEditorFilenamePane extends Template {

    function ImageEditorFilenamePane($page,$elementid, $image, $elementtype)
    {
    	parent::__construct($page.'-'.$elementid);
    
		$this->stringvars['image']=$image;
		$this->stringvars['elementtype']=$elementtype;
		$this->stringvars['imagefilename']=$image;
		if($this->stringvars['image']) $this->stringvars['submitname']="Add / Change Image";
		else $this->stringvars['submitname']="Remove Image";
    }

    // assigns templates
    function createTemplates()
    {
		$this->addTemplate("admin/imageeditorfilenamepane.tpl");
    }
}



//
// Templating for Image alignment within a section.
// Planned feature: scale image yes/no, use thumbnail yes/no
//
class ImageEditorPropertiesPane extends Template {

    function ImageEditorPropertiesPane($page,$elementid, $imagealign)
    {
    	parent::__construct($page.'-'.$elementid);
    	
		$this->stringvars['submitname'] ="Save image alignment";

		if(!$imagealign) $imagealign="left";
		$this->vars['left_align_button']= new RadioButtonForm($this->stringvars["jsid"],"imagealign","left","Left",$imagealign==="left","right");
		$this->vars['center_align_button']= new RadioButtonForm($this->stringvars["jsid"],"imagealign","center","Center",$imagealign==="center","right");
		$this->vars['right_align_button']= new RadioButtonForm($this->stringvars["jsid"],"imagealign","right","Right",$imagealign==="right","right");
    }

    // assigns templates
    function createTemplates()
    {
		$this->addTemplate("admin/imageeditorpropertiespane.tpl");
    }
}




//
// Templating for showing the image in the form
//
class ImageEditorImagePane extends Template {

    function ImageEditorImagePane($page,$image)
    {
    	parent::__construct();
    	$this->stringvars['image']="";
    	
		if(strlen($image)>0 && imageexists($image))
			$this->vars['image'] = new CaptionedImageAdmin($image,$page,2);
    }

    // assigns templates
    function createTemplates()
    {
		$this->addTemplate("admin/imageeditorimagepane.tpl");
    }
}

?>