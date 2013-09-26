<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));

//include_once($projectroot."functions/pages.php");
include_once($projectroot."functions/pagecontent/gallerypages.php");
include_once($projectroot."includes/objects/template.php");
include_once($projectroot."includes/objects/elements.php");
include_once($projectroot."includes/objects/images.php");
include_once($projectroot."includes/objects/page.php");
include_once($projectroot."includes/includes.php");


//
//
//
class GalleryCaptionedImage extends Template {

    function GalleryCaptionedImage($filename,$width, $showrefused=false,$showhidden=false)
    {
		global $projectroot, $_GET;
    	
    	parent::__construct();
      
		// Make the image
      	if(imageexists($filename))
      	{
        	if($showhidden)
        	{
          		$this->vars['image'] = new Image($filename,1,"&page=".$this->stringvars['page'],$showhidden);
          		

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
          		$this->vars['image'] = new Image($filename,1,"&page=".$this->stringvars['page'],$showhidden);
        	}
		}
		else $this->stringvars['image']='<i>'.$filename.'</i>';
      
		// Make the caption
		$this->vars['caption'] = new ImageCaption($filename);
      
		// CS stuff
   		$this->stringvars['halign']="float:left; ";
		$this->stringvars['width'] = "".$width."px";
    }

    // assigns templates
    function createTemplates()
    {
      	$this->addTemplate("captionedimage.tpl");
    }
}


//
// a row of images in a gallery page
//
class GalleryImage extends Template {

	function GalleryImage($filename,$width=300,$height=350,$showrefused=false,$showhidden=false)
	{
		parent::__construct();
		
		$params='&page='.$this->stringvars['page'];
		if($this->stringvars['sid'])
		{
		    $params.="&sid=".$this->stringvars['sid'];
		}
		$this->stringvars["height"]="".$height."px";

		$this->vars['image'] = new GalleryCaptionedImage($filename,$width,$showrefused,$showhidden);
		
		$filename;
	}
	
	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("galleryimage.tpl");
	}

}




//
// main class for gallery pages
//
class GalleryPage extends Template {

	function GalleryPage($offset=0,$showrefused=false,$showhidden=false)
	{
		global $projectroot;
	
		parent::__construct();
		
		$imagesperpage=getproperty("Gallery Images Per Page");
		$images=getgalleryimagefilenames($this->stringvars['page'],$showrefused,$showhidden);
		$noofimages=count($images);
		if(!$offset) $offset=0;
		
		$this->stringvars['pagetitle']=title2html(getpagetitle($this->stringvars['page']));
		$this->stringvars['text']=text2html(getpageintro($this->stringvars['page']));
		
		
		// todo: room for image, need to add admin functions and database entry for that
		
		/*    if(strlen($pagecontents['image'])>0 && ($showhidden || !imagepermissionrefused($pagecontents['image'])))
		  $this->stringvars['image'] =new Image($pagecontents['image'],2,$showrefused,$showhidden);
		*/
		
		//pagemenu
		$this->vars['pagemenu']= new PageMenu($offset, $imagesperpage, $noofimages);
		
		$startindex = $offset;
		$endindex =($offset+$imagesperpage);
		

		// determine image dimensions
		$width=MAXIMAGEDIMENSION;
		$height=MAXIMAGEDIMENSION;
		for($i=$startindex;$i<count($images) && $i<$endindex;$i++)
		{
			$thumbnail = getthumbnail($images[$i]);
		
			$imagedir=$projectroot.getproperty("Image Upload Path");
	      	$filepath=$imagedir.getimagesubpath(basename($images[$i])).'/'.$images[$i];
	      	$thumbnailpath=$imagedir.getimagesubpath(basename($images[$i])).'/'.$thumbnail;
			
			if(thumbnailexists($thumbnail) && file_exists($thumbnailpath) && !is_dir($thumbnailpath))
			{
				$imageproperties=@getimagesize($thumbnailpath);
				if ($width < $imageproperties[0]) $width = $imageproperties[0];
				if ($height < $imageproperties[1]) $height =$imageproperties[1];
			}
			else if(imageexists($images[$i]) && file_exists($filepath) && !is_dir($filepath))
			{
				$dimensions=calculateimagedimensions($images[$i],1);
				if ($width < $dimensions["width"]) $width=$dimensions["width"];
				if ($height < $dimensions["height"]) $height=$dimensions["height"];
			}

			$image=getimage($images[$i]);
			if(strlen($image['caption']))
			{
				$height = $height + IMAGECAPTIONLINEHEIGHT;
				if(strlen($image['caption']) > $width/10) $height = $height + IMAGECAPTIONLINEHEIGHT;
			}
			if(strlen($image['source']))
			{
				$height = $height + IMAGECAPTIONLINEHEIGHT;
				if(strlen($image['source']) > $width/10) $height = $height + IMAGECAPTIONLINEHEIGHT;
			}
			if(strlen($image['copyright']))
			{
				$height = $height + IMAGECAPTIONLINEHEIGHT;
				if(strlen($image['copyright']) > $width/10) $height = $height + IMAGECAPTIONLINEHEIGHT;
			}
			if($image['permission']==PERMISSION_GRANTED) $height = $height + IMAGECAPTIONLINEHEIGHT;
		}
		if (!$width) $width=MAXIMAGEDIMENSION;
		$width = $width + IMAGECAPTIONLINEHEIGHT;
		if (!$height) $height=MAXIMAGEDIMENSION+150;
		
		// create images
		for($i=$startindex;$i<count($images) && $i<$endindex;$i++)
		{
			$this->listvars['galleryimage'][]= new GalleryImage($images[$i],$width,$height,$showrefused,$showhidden);
		} 
		
		$this->vars['editdata']= new Editdata($showhidden);
	}
	
	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("gallerypage.tpl");
	}
}

?>
