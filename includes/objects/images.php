<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));

include_once($projectroot."includes/objects/template.php");

//
// Image with thumbhail & link to showimage.php
//
class Image extends Template {

    function Image($filename, $imageautoshrink, $usethumbnail, $params = array(), $showhidden=false)
    {
		global $projectroot;
      
		parent::__construct();
		
		$params["image"] = $filename;

		$image="";
		$alttext=title2html(getcaption($filename));
		if(!$alttext) $alttext = $filename;
		$thumbnail=getthumbnail($filename);
		$filepath=getimagepath($filename);
		$thumbnailpath = getthumbnailpath($filename, $thumbnail);
		if(file_exists($filepath) && !is_dir($filepath))
		{
			if($usethumbnail && $thumbnail && file_exists($thumbnailpath))
			{
				$dimensions=getimagedimensions($thumbnailpath);
				if($showhidden)
					$image='<a href="'.getprojectrootlinkpath().'admin/showimage.php'.makelinkparameters($params).'"><img src="'.getimagelinkpath($thumbnail,getimagesubpath($filename)).'" width="'.$dimensions["width"].'" height="'.$dimensions["height"].'" alt="'.$alttext.'" title="'.$alttext.'" border="0"></a>';
				else
					$image='<a href="'.getprojectrootlinkpath().'showimage.php'.makelinkparameters($params).'"><img src="'.getimagelinkpath($thumbnail,getimagesubpath($filename)).'" width="'.$dimensions["width"].'" height="'.$dimensions["height"].'" alt="'.$alttext.'" title="'.$alttext.'" border="0"></a>';
			}
			else
			{
				$dimensions=calculateimagedimensions($filepath, $imageautoshrink);
				if($showhidden)
					$image='<a href="'.getprojectrootlinkpath().'admin/showimage.php'.makelinkparameters($params).'"><img src="'.getimagelinkpath($filename,getimagesubpath($filename)).'" width="'.$dimensions["width"].'" height="'.$dimensions["height"].'" title="'.$alttext.'" alt="'.$alttext.'" border="0"></a>';
				else
					$image='<a href="'.getprojectrootlinkpath().'showimage.php'.makelinkparameters($params).'"><img src="'.getimagelinkpath($filepath,getimagesubpath($filename)).'" width="'.$dimensions["width"].'" height="'.$dimensions["height"].'" title="'.$alttext.'" alt="'.$alttext.'" border="0"></a>';
			}
		}
		else
		{
			$image='<span class="smalltext">Image <i>'.$filename.'</i></span>';
		}
		$this->stringvars['image']=$image;
	}
	
	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("image.tpl");
	}
}


//
// Image with caption used in most page types
//
class CaptionedImage extends Template {

    function CaptionedImage($filename, $imageautoshrink, $usethumbnail, $halign="left", $linkparams=array(), $showhidden=false)
    {
    	parent::__construct();

      	// CSS stuff
      	
      	if ($halign == "right")
      	{
      		$this->stringvars['halign']="float:right; ";
      	}
      	elseif ($halign == "left")
      	{
      		$this->stringvars['halign']="float:left; ";
      	}
      	elseif ($halign == "center")
      	{
      		$this->stringvars['halign']="";
      		$this->stringvars['center']="center";
      	}
      	else
      	{
      		$this->stringvars['halign']=$halign;
      	}
      	
		// determine image dimensions
		$width=getproperty("Thumbnail Size");

		$filepath=getimagepath($filename);
		$thumbnail = getthumbnail($filename);
		$thumbnailpath=getthumbnailpath($filename, $thumbnail);
		
		if($usethumbnail)
		{
			if(thumbnailexists($thumbnail) && file_exists($thumbnailpath) && !is_dir($thumbnailpath))
			{
				$dimensions = getimagedimensions($thumbnailpath);
				$width = $dimensions["width"];

			}
			else if(imageexists($filename) && file_exists($filepath) && !is_dir($filepath))
			{
				$dimensions = getimagedimensions($filepath);
				$width = $dimensions["width"];

			}
		}
		else if(imageexists($filename) && file_exists($filepath) && !is_dir($filepath))
		{
			$dimensions=calculateimagedimensions($filepath, $imageautoshrink);
			$width=$dimensions["width"];
		}

		$width = $width + IMAGECAPTIONLINEHEIGHT;
		$this->stringvars["width"] = $width;

		// make the image
      	if(imageexists($filename))
      	{
			$this->vars['image'] = new Image($filename, $imageautoshrink, $usethumbnail, $linkparams, $showhidden);
		}
		else $this->stringvars['image']='<i>'.$filename.'</i>';

		$this->vars['caption'] = new ImageCaption($filename, $showhidden);

    }

    // assigns templates
    function createTemplates()
    {
      	$this->addTemplate("captionedimage.tpl");
    }
}


//
// Formats an image caption with source etc.
//
class ImageCaption extends Template {

    function ImageCaption($filename, $showhidden=false)
    {
		global $projectroot;
		parent::__construct();
		$result="";
		
		$captionfontsize=10;
		
		$image=getimage($filename);
		
		if(array_key_exists("caption",$image)) $caption=$image['caption'];
		else $caption="";

		if(array_key_exists("source",$image)) $source=$image['source'];
		else $source="";

		if(array_key_exists("sourcelink",$image)) $sourcelink=$image['sourcelink'];
		else $sourcelink="";

		if(array_key_exists("copyright",$image)) $copyright=$image['copyright'];
		else $copyright="";

		if(array_key_exists("permission",$image)) $permission=$image['permission'];
		else $permission=NO_PERMISSION;
		
		$caption=title2html($caption);
		$source=title2html($source);
		$copyright=title2html($copyright);

		// now assemble it
		if($caption)
		{
			$captiontitle=$caption;
			if(strlen($caption) > 50)
				$caption = substr($caption,0,50)."...";
			$result.='<span title="'.$captiontitle.'">'.$caption.'</span>';
		}
		if($source)
		{
			$sourcetitle=$source;
			if(strlen($source) > 50)
				$source = substr($source,0,50)."...";
			if($caption)
			{
				$result.='<br>';
			}
			$result.='<span title="'.getlang("image_image").$sourcetitle.'">'.getlang("image_image");
			if($sourcelink)
			{
				$result.='<a href="'.$sourcelink.'" title="'.$sourcetitle.'" target="_blank">';
			}
			$result.=$source;
			if($sourcelink)
			{
				$result.='</a>';
			}
			$result.='</span>';
		}
		if($copyright)
		{
			$copyrighttitle=$copyright;
			if(strlen($copyright) > 50)
			$copyright = substr($copyright,0,50)."...";
			
			if($caption || $source)
			{
				$result.='.<br>';
			}
			$result.='<span title="&copy; '.$copyrighttitle.'">&copy; '.$copyright.'.</span>';
		}
		if($permission==PERMISSION_GRANTED) $result.=getlang("image_bypermission");
		
		$this->stringvars['caption']=$result;
    }

    // assigns templates
    function createTemplates()
    {
		$this->addTemplate("imagecaption.tpl");
    }
}

?>
