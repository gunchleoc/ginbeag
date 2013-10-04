<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));

include_once($projectroot."includes/objects/template.php");

//
// Image with thumbhail & link to showimage.php
//
class Image extends Template {

    function Image($filename,$factor=1,$params="",$showhidden=false)
    {
		global $projectroot;
      
		parent::__construct();
		
		$image="";
		$alttext=title2html(getcaption($filename));
		if(!$alttext) $alttext = $filename;
		$thumbnail=getthumbnail($filename);
		$imagedir=$projectroot.getproperty("Image Upload Path");
		$filename=$imagedir.getimagesubpath(basename($filename)).'/'.$filename;
		if(file_exists($filename) && !is_dir($filename))
		{
			$dimensions=calculateimagedimensions($filename,$factor);
			$width=$dimensions["width"];
			$height=$dimensions["height"];

			if($thumbnail && file_exists($imagedir.getimagesubpath(basename($filename)).'/'.$thumbnail))
			{
				//$thumbnail=$imagedir.getimagesubpath(basename($filename)).'/'.$thumbnail;
				if($showhidden)
					$image='<a href="'.getprojectrootlinkpath().'admin/showimage.php?image='.basename($filename).'&sid='.$this->stringvars['sid'].$params.'"><img src="'.getimagelinkpath($thumbnail,getimagesubpath(basename($filename))).'" alt="'.$alttext.'" title="'.$alttext.'" border="0"></a>';
				else
					$image='<a href="'.getprojectrootlinkpath().'showimage.php?image='.basename($filename).'&sid='.$this->stringvars['sid'].$params.'"><img src="'.getimagelinkpath($thumbnail,getimagesubpath(basename($filename))).'" alt="'.$alttext.'" title="'.$alttext.'" border="0"></a>';
			}
			else
			{
				if($showhidden)
					$image='<a href="'.getprojectrootlinkpath().'admin/showimage.php?image='.basename($filename).'&sid='.$this->stringvars['sid'].$params.'"><img src="'.getimagelinkpath($filename,getimagesubpath(basename($filename))).'" width="'.$width.'" height="'.$height.'" title="'.$alttext.'" alt="'.$alttext.'" border="0"></a>';
				else
					$image='<a href="'.getprojectrootlinkpath().'showimage.php?image='.basename($filename).'&sid='.$this->stringvars['sid'].$params.'"><img src="'.getimagelinkpath($filename,getimagesubpath(basename($filename))).'" width="'.$width.'" height="'.$height.'" title="'.$alttext.'" alt="'.$alttext.'" border="0"></a>';
			}
		}
		else
		{
			$image='<span class="smalltext">Image <i>'.basename($filename).'</i></span>';
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

    function CaptionedImage($filename,$factor=1,$halign="left", $showrefused=false,$showhidden=false)
    {
    	global $projectroot, $_GET;
    	
    	parent::__construct();
      	
      	// CSS stuff
      	
      	if ($halign == "right") $this->stringvars['halign']="float:right; ";
      	elseif ($halign == "left") $this->stringvars['halign']="float:left; ";
      	else $this->stringvars['halign']=$halign;
      	
		// determine image dimensions
		$width=MAXIMAGEDIMENSION;

		$thumbnail = getthumbnail($filename);
	
		$imagedir=$projectroot.getproperty("Image Upload Path");
      	$filepath=$imagedir.getimagesubpath(basename($filename)).'/'.$filename;
      	$thumbnailpath=$imagedir.getimagesubpath(basename($filename)).'/'.$thumbnail;
		
		if(thumbnailexists($thumbnail) && file_exists($thumbnailpath) && !is_dir($thumbnailpath))
		{
			$imageproperties=@getimagesize($thumbnailpath);
			$width = $imageproperties[0];
		}
		else if(imageexists($filename) && file_exists($filepath) && !is_dir($filepath))
		{
			$dimensions=calculateimagedimensions($filename,1);
			$width=$dimensions["width"];
		}

		if (!$width) $width=MAXIMAGEDIMENSION;
		$width = $width + IMAGECAPTIONLINEHEIGHT;
		$this->stringvars["width"]=$width;

		// make the image
      	if(imageexists($filename))
      	{
        	if($showhidden)
        	{
          		$this->vars['image'] = new Image($filename,$factor,"&page=".$this->stringvars['page'],$showhidden);
          		

          		if(imagepermissionrefused($filename))
          		{
            		$this->stringvars['caption'].='<div class="highlight">Permission refused for this image;<br />';
            		if($showrefused)
            		{
              			$this->stringvars['caption'].='but shown anyway!</div>';
            		}
            		else
            		{
              			$this->stringvars['caption'].='hidden from webpage!</div>';
            		}
          		}
        	}
        	elseif(!imagepermissionrefused($filename) || $showrefused)
        	{
          		$this->vars['image'] = new Image($filename,$factor,"&page=".$this->stringvars['page'],$showhidden);
        	}
      }
      else $this->stringvars['image']='<i>'.$filename.'</i>';
      
      $this->vars['caption'] = new ImageCaption($filename);

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

    function ImageCaption($filename)
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
