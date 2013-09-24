<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));

include_once($projectroot."includes/objects/template.php");


//
// a general header
//
class HTMLHeader extends Template {


    function __construct($title,$headertitle,$message="", $redirecturl="",$urltext="If the page does not load, use this link", $isredirect=false,$stylesheet="main.css",$scriptpaths=array())
    {
    	parent::__construct();
      $this->stringvars['stylesheet']=getCSSPath($stylesheet);
      $this->stringvars['site_name']=title2html(getproperty("Site Name"));
      $this->stringvars['header_title']=$headertitle;
      if(strlen($title)>0)
        $this->stringvars['title']=$title;
      if(strlen($message)>0)
        $this->stringvars['message']=$message;
      if(strlen($isredirect)>0)
        $this->stringvars['is_redirect']="redirect";

      if(strlen($redirecturl)>0)
        $this->stringvars['url']=$redirecturl;

      $this->stringvars['url_text']=$urltext;
      
      //if(strlen($scriptpath)>0)
      	//$this->stringvars['scriptpath']=$scriptpath;
      	
      if (count($scriptpaths))
      {
      	$this->stringvars['script']="";
      	for($i=0;$i<count($scriptpaths);$i++)
      	{
      		$this->stringvars['script'].='<script type="text/javascript" src="'.getprojectrootlinkpath().$scriptpaths[$i].'"></script>';
      	}
      }
      //flush(); // partial browser rendering
    }

    // assigns templates
    function createTemplates()
    {
       $this->addTemplate("htmlheader.tpl");
    }
}

//
// a general footer
//
class HTMLFooter extends Template {


    function HTMLFooter()
    {
    	parent::__construct();
    }

    // assigns templates
    function createTemplates()
    {
       $this->addTemplate("htmlfooter.tpl");
    }
}


//
// formerly makeimagelink
//
class Image extends Template {
    var $templates=array();

    // vars that are simple strings
    var $stringvars=array();


    function Image($filename,$factor=1,$params="")
    {
      global $projectroot;
      
      parent::__construct();

      $image="";
      $alttext=title2html(getcaption($filename));
      if(!$alttext) $alttext = $filename;
      $thumbnail=getthumbnail($filename);
      $imagedir=$projectroot.getproperty("Image Upload Path");
      $filename=$imagedir.getimagesubpath(basename($filename)).'/'.$filename;
      if(file_exists($filename))
      {
        if(!is_dir($filename))
        {
          $dimensions=calculateimagedimensions($filename,$factor);
          $width=$dimensions["width"];
          $height=$dimensions["height"];

          if($thumbnail && file_exists($imagedir.getimagesubpath(basename($filename)).'/'.$thumbnail))
          {
            //$thumbnail=$imagedir.getimagesubpath(basename($filename)).'/'.$thumbnail;
            $image='<a href="'.getprojectrootlinkpath().'showimage.php?image='.basename($filename).'&sid='.$this->stringvars['sid'].$params.'"><img src="'.getimagelinkpath($thumbnail,getimagesubpath(basename($filename))).'" alt="'.$alttext.'" title="'.$alttext.'" border="0"></a>';
          }
          else
          {
            $image='<a href="'.getprojectrootlinkpath().'showimage.php?image='.basename($filename).'&sid='.$this->stringvars['sid'].$params.'"><img src="'.getimagelinkpath($filename,getimagesubpath(basename($filename))).'" width="'.$width.'" height="'.$height.'" title="'.$alttext.'" alt="'.$alttext.'" border="0"></a>';
          }
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
// formerly makeimage
//
class CaptionedImage extends Template {
	var $templates=array();

    // vars that are simple strings
    var $stringvars=array();


    function CaptionedImage($filename,$factor=1,$halign="left", $showrefused=false,$showhidden=false)
    {
    	global $projectroot, $_GET;
    	
    	parent::__construct();
      
      	$this->stringvars['caption']="";
      	$this->stringvars['width']=MAXIMAGEDIMENSION+100;
      	$this->stringvars['halign']=$halign;
      	
      	if ($halign == "right")
      	{
      		$this->stringvars['halign']="float:right; ";
      	}
      	elseif ($halign == "left")
      	{
      		$this->stringvars['halign']="float:left; ";
      	}
      	
      	

      	if(imageexists($filename))
      	{
        	if($showhidden)
        	{
          		$this->vars['image'] = new Image($filename,$factor,"&page=".$this->stringvars['page']);
          		

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
          		$this->vars['image'] = new Image($filename,$factor,"&page=".$this->stringvars['page']);
        	}
      }
      else $this->stringvars['image']='<i>'.$filename.'</i>';

	  
	  $imagedir=$projectroot.getproperty("Image Upload Path");
	  
      if(file_exists($imagedir.getimagesubpath(basename($filename)).'/'.$filename))
      {
      		$thumbnail=getthumbnail($filename);
        	if(!is_dir($imagedir.getimagesubpath(basename($filename)).'/'.$filename))
        	{
          		if($thumbnail && file_exists($imagedir.getimagesubpath(basename($thumbnail)).'/'.$thumbnail))
          		{
            		$imageproperties=@getimagesize($filename);
            		$width=$imageproperties[0];
            		$this->vars['caption'] = new ImageCaption($filename);
          		}
         		else
          		{
            		$dimensions=calculateimagedimensions($filename,$factor);
            		$width=$dimensions["width"];
            		$this->vars['caption'] = new ImageCaption($filename);
          		}
        	}
      }
      else $this->stringvars['width'] = "200px"; // todo replace by configured standard thumbnail width
      
      // todo this is only a workaround
      if($this->stringvars['width'] == "0px")
      	$this->stringvars['width'] = "200px";
    }

    // assigns templates
    function createTemplates()
    {
      	$this->addTemplate("captionedimage.tpl");
    }
}


//
// todo: Check width - tamplate captionedImage
//
class ImageCaption extends Template {

    function ImageCaption($filename)
    {
      global $projectroot;
      parent::__construct();
      $result="";

      $captionfontsize=10;

      $image=getimage($filename);

      $caption=$image['caption'];
      $source=$image['source'];
      $sourcelink=$image['sourcelink'];
      $copyright=$image['copyright'];
      $permission=$image['permission'];

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

//
// container for a highlighted message
//
class Message extends Template {
    // vars that are simple strings
    var $stringvars=array();


    function Message($message)
    {
    	parent::__construct();
       $this->stringvars['message']=$message;
    }

    // assigns templates
    function createTemplates()
    {
      $this->addTemplate("message.tpl");
    }
}

?>
