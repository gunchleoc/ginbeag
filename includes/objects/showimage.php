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
    // vars that are simple strings
    var $stringvars=array();
    //var $vars=array();


    function Showimage($image,$item=0,$showhidden=false)
    {
    	global $_POST;
    	parent::__construct();
    	
    	$this->stringvars['site_description']=title2html(getproperty("Site Description"));
    	
 		$caption=getcaption($image);
 		if(strlen($caption)>30) $caption = substr($caption,0,30)."...";

		if($caption)
		{
			//todo why encoding?
			$this->vars['pageintro'] = new PageIntro(getlang("image_viewing")." - ".title2html($caption),"","");
			$this->vars['header']= new PageHeader($this->stringvars['page'],utf8_decode(getlang("image_viewing"))." - ".utf8_decode(title2html($caption)));
		}
		else
		{
			//todo why encoding?
			$this->vars['header']= new PageHeader($this->stringvars['page'],utf8_decode(getlang("image_viewing")));
			$this->vars['pageintro'] = new PageIntro(getlang("image_viewing"),"","");
		}
		// function Navigator($this->stringvars['page'],$sistersinnavigator,$depth,$displaytype="page",$showhidden=false) {
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
      			$this->stringvars['returnpage']='pagedisplay.php?page='.$this->stringvars['page'].'&sid='.$this->stringvars['sid'];
      		else
      			$this->stringvars['returnpage']='index.php?page='.$this->stringvars['page'].'&sid='.$this->stringvars['sid'];
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
  			$items=getgalleryimagefilenames($this->stringvars['page'], showpermissionrefusedimages($this->stringvars['page']));
  			$item = array_search($_GET['image'], $items);
  			if(!$item)
  			{
    			$item=0;
  			}
  			$previousitem = $item-1;
  			$nextitem = $item+1;
		}


		if(($this->stringvars['page']!=0 || $item!=0) && $previousitem >=0)
		{
			$this->stringvars['previous'] = $this->makeitemfields($items);
			$this->stringvars['previousitem'] = "?item=".$previousitem."&page=".$this->stringvars['page'].'&sid='.$this->stringvars['sid'];
		}
		if(($this->stringvars['page']!=0 || $item!=0) && $nextitem >=0 && $nextitem  < count($items))
		{
			$this->stringvars['next'] = $this->makeitemfields($items);
			$this->stringvars['nextitem'] = "?item=".$nextitem."&page=".$this->stringvars['page'].'&sid='.$this->stringvars['sid'];
		}

		// make image
		if(strlen($image)>1)
		{
			$this->stringvars['imagepath'] = getimagelinkpath($image,getimagesubpath(basename($image)));
			$this->stringvars['simplecaption'] = title2html($caption);
			$this->vars['caption'] = new ImageCaption($image);

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
    // vars that are simple strings
    var $stringvars=array();


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
