<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));

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

    function GalleryCaptionedImage($filename,$width,$showhidden=false)
    {
    	parent::__construct();

		// Make the image
      	if(imageexists($filename))
      	{
			$this->vars['image'] = new Image($filename, true, true, array("page" => $this->stringvars['page']), $showhidden);
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
      	$this->addTemplate("images/captionedimage.tpl");
    }
}


//
//
//
class GalleryMobileCaptionedImage extends Template {

    function GalleryMobileCaptionedImage($filename, $showhidden=false)
    {
		global $projectroot;
		parent::__construct();

		// determine image dimensions
		$width=getproperty("Thumbnail Size");

		$filepath=getimagepath($filename);
		$thumbnail = getthumbnail($filename);
		$thumbnailpath=getthumbnailpath($filename, $thumbnail);

		if(ismobile())
		{
			$extension = substr($filename, strrpos($filename,"."), strlen($filename));
			$thumbname = substr($filename, 0, strrpos($filename,".")).'_thn'.$extension;
			$path = $projectroot.getproperty("Image Upload Path").getimagesubpath(basename($filename));

			// make sure a mobile thumbnail exists
			if (extension_loaded('gd') && function_exists('gd_info'))
			{
				if(!file_exists($path."/mobile/".$thumbname))
				{
					include_once($projectroot."functions/imagefiles.php");
					createthumbnail($path, $filename, getproperty("Mobile Thumbnail Size"), true);
				}
			}

			$path = $path."/mobile/".$thumbname;
			if(file_exists($path))
			{
				$thumbnailpath = $path;
				$thumbnail = $thumbname;
			}
		}

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

		$width = $width + IMAGECAPTIONLINEHEIGHT;
		$this->stringvars["width"] = $width;
		$this->stringvars["halign"] = "text-align:left;";

		// make the image
		if(imageexists($filename))
		{
			$this->vars['image'] = new Image($filename, true, true, array("page" => $this->stringvars['page']), $showhidden);
		}
		else $this->stringvars['image']='<i>'.$filename.'</i>';

		$this->vars['caption'] = new ImageCaption($filename);
    }

    // assigns templates
    function createTemplates()
    {
		$this->addTemplate("images/captionedimage.tpl");
    }
}


//
// a row of images in a gallery page
//
class GalleryImage extends Template {

	function GalleryImage($filename,$width=300,$height=350,$showhidden=false)
	{
		parent::__construct();

		$params='&page='.$this->stringvars['page'];
		if($this->stringvars['sid'])
		{
		    $params.="&sid=".$this->stringvars['sid'];
		}
		$this->stringvars["height"]="".$height."px";

		$this->vars['image'] = new GalleryCaptionedImage($filename,$width,$showhidden);

		$filename;
	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("pages/gallery/galleryimage.tpl");
	}

}




//
// main class for gallery pages
//
class GalleryPage extends Template {

	function GalleryPage($offset=0,$showhidden=false)
	{
		global $projectroot;

		parent::__construct();

		$imagesperpage=getproperty("Gallery Images Per Page");
		$images=getgalleryimagefilenames($this->stringvars['page']);
		$noofimages=count($images);
		if(!$offset) $offset=0;

		$pageintro = getpageintro($this->stringvars['page']);
		$this->vars['pageintro'] = new PageIntro(getpagetitle($this->stringvars['page']),$pageintro['introtext'],$pageintro['introimage'],$pageintro['imageautoshrink'], $pageintro['usethumbnail'],$pageintro['imagehalign'],$showhidden);

		//pagemenu
		$this->vars['pagemenu']= new PageMenu($offset, $imagesperpage, $noofimages);

		$startindex = $offset;
		$endindex =($offset+$imagesperpage);

		if(ismobile())
		{
			// create images
			for($i=$startindex;$i<count($images) && $i<$endindex;$i++)
			{
				$this->listvars['galleryimage'][]= new GalleryMobileCaptionedImage($images[$i], $showhidden);
			}
		}
		else
		{

			// determine image dimensions
			$width=getproperty("Thumbnail Size");
			$height=getproperty("Thumbnail Size");
			for($i=$startindex;$i<count($images) && $i<$endindex;$i++)
			{
				$thumbnail = getthumbnail($images[$i]);
				$filepath = getimagepath($images[$i]);
				$thumbnailpath = getthumbnailpath($images[$i], $thumbnail);

				if(thumbnailexists($thumbnail) && file_exists($thumbnailpath) && !is_dir($thumbnailpath))
				{
					$dimensions = getimagedimensions($thumbnailpath);
					if ($width < $dimensions["width"]) $width = $dimensions["width"];
					if ($height < $dimensions["height"]) $height = $dimensions["height"];
				}
				else if(imageexists($images[$i]) && file_exists($filepath) && !is_dir($filepath))
				{
					$dimensions=calculateimagedimensions($images[$i]);
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
			if (!$width) $width=getproperty("Thumbnail Size");
			$width = $width + IMAGECAPTIONLINEHEIGHT;
			if (!$height) $height=getproperty("Thumbnail Size")+150;

			// create images
			for($i=$startindex;$i<count($images) && $i<$endindex;$i++)
			{
				$this->listvars['galleryimage'][]= new GalleryImage($images[$i],$width,$height,$showhidden);
			}
		}
		$this->vars['editdata']= new Editdata($showhidden);
	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("pages/gallery/gallerypage.tpl");
	}
}

?>
