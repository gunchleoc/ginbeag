<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));

include_once($projectroot."functions/pagecontent/gallerypages.php");
include_once($projectroot."includes/objects/template.php");
include_once($projectroot."includes/objects/page.php");


//
// Showimage main class
//
class Showimage extends Template {

    function Showimage($page,$image,$item=0,$showhidden=false)
    {
		global $_POST, $_GET;
    	parent::__construct();
    	
    	/*if($page && !$showhidden)
		{
			if(ispagerestrictedarray($page))
			{
				//checkpublicsession($page);
			}
		}
    	*/
    	$this->stringvars['site_description']=title2html(getproperty("Site Description"));
    	
    	$pagetitle=utf8_decode(getlang("image_viewing"));
    	
 		$caption=getcaption($image);

		if($caption)
		{
			if(strlen($caption)>30) $caption = substr($caption,0,30)."...";
			$caption = utf8_decode(title2html($caption));
			$pagetitle = $pagetitle." - ".$caption;
		}
		$this->vars['pageintro'] = new PageIntro($pagetitle,"");
		$this->vars['header']= new PageHeader($this->stringvars['page'],$pagetitle);

		$this->vars['navigator'] = new Navigator($this->stringvars['page'],false,1,"page",$showhidden);
		
		if(getproperty('Display Banners'))
		{
  			$this->vars['banners'] = new BannerList();
		}

   		$this->vars['editdata']= new ImageEditdata($image, $showhidden);
      	
      	$this->vars['footer'] = new PageFooter();
      	
      	// link to gallery page
      	if($this->stringvars['page']!=0)
      	{
      		if($showhidden)
				$this->stringvars['returnpage']='pagedisplay.php'.makelinkparameters(array("page" => $this->stringvars['page']));
      		else
				$this->stringvars['returnpage']='index.php'.makelinkparameters(array("page" => $this->stringvars['page']));
      		$this->stringvars['returnpagetitle']=getlang("image_viewthumbnails");
      	}
      	
      	
      	// collect items for navigation through images
      	
  		$previousitem = -1;
  		$nextitem = -1;      	

		if($item!=0)
		{
  			// generate item array from http_post_vars
  			$items=array();
  			for($i=0;isset($_POST[$i]);$i++)
  			{
    			$items[$i] =$_POST[$i];
  			}
  			$previousitem = $item-1;
  			$nextitem = $item+1;
		}

		elseif($this->stringvars['page']!=0)
		{
			// generate item array
			$items=getgalleryimagefilenames($this->stringvars['page']);
			$item = 0;
			if(isset($_GET['image']))
			{
				$item = array_search($_GET['image'], $items);
			}
			$previousitem = $item-1;
			$nextitem = $item+1;
		}


		$linkparams = array();
		$linkparams["page"] = $this->stringvars['page'];

		if(($this->stringvars['page']!=0 || $item!=0) && $previousitem >=0)
		{
			$this->stringvars['previous'] = $this->makeitemfields($items);

			$linkparams["item"] = $previousitem;
			$this->stringvars['previousitem'] = makelinkparameters($linkparams);
		}
		if(($this->stringvars['page']!=0 || $item!=0) && $nextitem >=0 && $nextitem  < count($items))
		{
			$this->stringvars['next'] = $this->makeitemfields($items);

			$linkparams["item"] = $nextitem;
			$this->stringvars['nextitem'] = makelinkparameters($linkparams);
		}

		// make image
		if(strlen($image)>1)
		{
			$this->stringvars['imagepath'] = getimagelinkpath($image,getimagesubpath(basename($image)));
			$dimensions = getimagedimensions(getimagepath($image));
			$this->stringvars['width'] = $dimensions["width"];
			$this->stringvars['height'] = $dimensions["height"];
			$this->stringvars['simplecaption'] = title2html($caption);
			$this->vars['caption'] = new ImageCaption($image, $showhidden);

		}
		else
		{
   			$this->stringvars['noimage'] = "No image";
   		}
 	}
  

	//
	// generates hidden fields from item array for form
	//
	function makeitemfields($items)
	{
		$result="";
  		for($i=0; $i<count($items);$i++)
  		{
    		$result.='<input type="hidden" name="'.$i.'" value="'.$items[$i].'" />';
  		}
  		return $result;
	}

    // assigns templates
    function createTemplates()
    {
		$this->addTemplate("showimage.tpl");
    }
}


//
// container for editdata
//
class ImageEditdata extends Template {

    function ImageEditdata($image, $showhidden=false)
    {
    	parent::__construct();
    	$editdate= getuploaddate($image);
      	$editor=  getusername(getuploader($image));
      	
      	if($showhidden)
      	{
      		$this->stringvars['footerlastedited']=sprintf(getlang("footer_imageuploadedauthor"),formatdatetime($editdate),$editor);
      	}
      	else
      	{
			$this->stringvars['footerlastedited']=sprintf(getlang("footer_imageuploaded"),formatdatetime($editdate));
      	}
     	
     	$this->stringvars['topofthispage']=getlang("pagemenu_topofthispage");
    }

    // assigns templates
    function createTemplates()
    {
      	$this->addTemplate("imageeditdata.tpl");
    }
}


?>
