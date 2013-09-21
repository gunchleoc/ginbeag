<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));

include_once($projectroot."includes/templates/template.php");
include_once($projectroot."includes/templates/page.php");


//
// container for editdata
//
class Showimage extends Template {
    // vars that are simple strings
    var $stringvars=array();
    //var $vars=array();


    function Showimage($page,$image,$item=0,$showhidden=false)
    {
    	global $sid, $_POST;
    	
    	$this->stringvars['sid']=$sid;
    	$this->stringvars['page']=$page;
    	
 		$caption=getcaption($image);

		if($caption)
		{
			$this->vars['pageintro'] = new PageIntro("Viewing Image - ".title2html($caption),"","");
			$this->vars['header']= new PageHeader($page,"Viewing Image - ".title2html($caption));
		}
		else
		{
			$this->vars['header']= new PageHeader($page,"Viewing Image");
			$this->vars['pageintro'] = new PageIntro("Viewing Image","","");
		}
		// function Navigator($page_id,$sistersinnavigator,$depth,$displaytype="page",$showhidden=false) {
		$this->vars['navigator'] = new Navigator($page,false,1,"page",$showhidden);
		
		if(getproperty('Display Banners'))
		{
  			$this->vars['banners'] = new BannerList();
		}

   		$this->vars['editdata']= new ImageEditdata($image);
      	
      	$this->vars['footer'] = new PageFooter();
      	
      	// link to gallery page
      	if($page!=0)
      	{
      		$this->stringvars['returnpage']='index.php?page='.$page.'&sid='.$sid;
      		$this->stringvars['returnpagetitle']="View thumbnails";
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

		elseif($page!=0)
		{
  			// generate item array
  			$items=getgalleryimagefilenames($page, showpermissionrefusedimages($page));
  			$item = array_search($_GET['image'], $items);
  			if(!$item)
  			{
    			$item=0;
  			}
  			$previousitem = $item-1;
  			$nextitem = $item+1;
		}


		if(($page!=0 || $item!=0) && $previousitem >=0)
		{
			$this->stringvars['previous'] = $this->makeitemfields($items);
			$this->stringvars['previousitem'] = $previousitem;
		}
		if(($page!=0 || $item!=0) && $nextitem >=0 && $nextitem  < count($items))
		{
			$this->stringvars['next'] = $this->makeitemfields($items);
			$this->stringvars['nextitem'] = $nextitem;
		}

		// make image
		if(strlen($image)>1)
		{
			$this->stringvars['imagepath'] = getimagelinkpath($image);
			$this->stringvars['simplecaption'] = title2html($caption);
			$this->vars['caption'] = new ImageCaption($image,0,false);

		}
		else
		{
   			$this->stringvars['noimage'] = "No image";
   		}

   		$this->createTemplates();
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


    function ImageEditdata($image)
    {
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
     	
     	$this->stringvars['topofthispage']=getlang("page_topofthispage");
      

      	$this->createTemplates();
    }

    // assigns templates
    function createTemplates()
    {
      	$this->addTemplate("imageeditdata.tpl");
    }
}


?>
