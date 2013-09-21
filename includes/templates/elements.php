<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));

include_once($projectroot."includes/templates/template.php");


//
// a general header
//
class HTMLHeader extends Template {


    function HTMLHeader($title,$headertitle,$message="", $redirecturl="",$urltext="If the page does not load, use this link", $isredirect=false,$stylesheet="page.css")
    {
      $this->stringvars['stylesheet']=getprojectrootlinkpath().$stylesheet;
      $this->stringvars['site_name']=title2html(getproperty("Site Name"));
      $this->stringvars['header_title']=title2html($headertitle);
      if(strlen($title)>0)
        $this->stringvars['title']=title2html($title);
      if(strlen($message)>0)
        $this->stringvars['message']=$message;
      if(strlen($isredirect)>0)
        $this->stringvars['is_redirect']="redirect";

      if(strlen($redirecturl)>0)
        $this->stringvars['url']=$redirecturl;

      $this->stringvars['url_text']=title2html($urltext);

      $this->createTemplates();
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
      $this->createTemplates();
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

      $image="";
      $alttext=title2html(getcaption($filename));
      if(!$alttext) $alttext = $filename;
      $thumbnail=getthumbnail($filename);
      $imagedir=$projectroot.getproperty("Image Upload Path");
      $filename=$imagedir.'/'.$filename;
      if(file_exists($filename))
      {
        if(!is_dir($filename))
        {
          $dimensions=calculateimagedimensions($filename,$factor);
          $width=$dimensions["width"];
          $height=$dimensions["height"];

          if($thumbnail && file_exists($imagedir.'/'.$thumbnail))
          {
            $thumbnail=$imagedir.'/'.$thumbnail;
            $image='<a href="'.getprojectrootlinkpath().'showimage.php?image='.basename($filename).$params.'"><img src="'.getimagelinkpath($thumbnail).'" alt="'.$alttext.'" border="0"></a>';
          }
          else
          {
            $image='<a href="'.getprojectrootlinkpath().'showimage.php?image='.basename($filename).$params.'"><img src="'.getimagelinkpath($filename).'" width="'.$width.'" height="'.$height.'" alt="'.$alttext.'" border="0"></a>';
          }
        }
      }
      else
      {
        $image='<span class="gensmall">Image <i>'.basename($filename).'</i></span>';
      }
       $this->stringvars['image']=$image;
       $this->createTemplates();
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


    function CaptionedImage($filename,$factor=1,$showrefused=false,$showhidden=false)
    {
      global $projectroot;
      
      $this->stringvars['caption']="";
      $this->stringvars['width']=0;

      if(imageexists($filename))
      {
        if($showhidden)
        {
          $this->vars['image'] = new Image($filename,$factor);

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
          // todo      $this->stringvars['caption'].=
        }
        elseif(!imagepermissionrefused($filename) || $showrefused)
        {
          $this->vars['image'] = new Image($filename,$factor);
          // todo      $this->stringvars['caption'].=
        }
      }
      else $this->stringvars['image']='<span class="gen"><i>'.$filename.'</i></span>';

      if(file_exists($filename))
      {
        if(!is_dir($filename))
        {
          if($thumbnail && file_exists($imagedir.'/'.$thumbnail))
          {
            $imageproperties=@getimagesize($filename);
            $width=$imageproperties[0];
            $this->stringvars['width'] = $width;
            $this->vars['caption'] = new ImageCaption($filename, $factor,true);
          }
          else
          {
            $dimensions=calculateimagedimensions($filename,$factor);
            $width=$dimensions["width"];
            $this->stringvars['width'] = $width;
            $this->vars['caption'] = new ImageCaption($filename, $factor,false);
          }
        }
      }
       $this->stringvars['image']=$image;
       $this->createTemplates();
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

    function ImageCaption($filename, $factor=1,$calcfromthumbnail=true)
    {
      global $projectroot;
      $result="";

      $captionfontsize=10;

      $image=getimage($filename);

      $caption=$image['caption'];
      $source=$image['source'];
      $sourcelink=$image['sourcelink'];
      $copyright=$image['copyright'];
      $permission=$image['permission'];

      // get image width

     $imagedir=$projectroot.getproperty("Image Upload Path");
      if(hasthumbnail($filename) && $calcfromthumbnail)
      {
        $filetocheck=$imagedir.'/'.getthumbnail($filename);
      }
      else
      {
        $filetocheck=$imagedir.'/'.$filename;
      }
      if(file_exists($filetocheck) && !is_dir($filetocheck))
      {
        $dimensions=calculateimagedimensions($filetocheck,$factor);
      }
      $width=$dimensions["width"];

      if($width<250)
      {
        $limit=floor(21/7*$width/$captionfontsize);
      }
      else
      {
        $limit=floor(5/3*$width/$captionfontsize);
      }

      // reformat caption if too long for image width
/*      if($width>0 && $width<$captionfontsize*strlen($caption)*3/5)
      {
        $temparray=array();
        $pos=strrpos(substr($caption,0,$limit)," ");
        while(strlen($caption)>$limit && $pos>0)
        {
          array_push($temparray,substr($caption,0,$pos));
          $caption=substr($caption,$pos);
          $pos=strrpos(substr($caption,0,$limit)," ");
        }
        array_push($temparray,$caption);
        $temparray=array_map("title2html", $temparray);
        $caption=implode('<br />',$temparray);
      }
      else*/
      {
        $caption=title2html($caption);
      }
      // reformat source
/*      if($width>0 && $width<$captionfontsize*strlen($source)*3/5)
      {
        $temparray=array();
        $pos=strrpos(substr($source,0,$limit-6)," ");
        while(strlen($source)>$limit && $pos>0)
        {
          array_push($temparray,substr($source,0,$pos));
          $source=substr($source,$pos);
          $pos=strrpos(substr($source,0,$limit)," ");
        }
        array_push($temparray,$source);
        $temparray=array_map("title2html", $temparray);
        $source=implode('<br />',$temparray);
      }
      else*/
      {
        $source=title2html($source);
      }
      // reformat copyright
/*      if($width>0 && $width<$captionfontsize*strlen($copyright)*3/5)
      {
        $temparray=array();
        $pos=strrpos(substr($copyright,0,$limit-2)," ");
        while(strlen($copyright)>$limit && $pos>0)
        {
          array_push($temparray,substr($copyright,0,$pos));
          $copyright=substr($copyright,$pos);
          $pos=strrpos(substr($copyright,0,$limit)," ");
        }
        array_push($temparray,$copyright);
        $temparray=array_map("title2html", $temparray);
        $copyright=implode('<br />',$temparray);
      }
      else*/
      {
        $copyright=title2html($copyright);
      }
      // now assemble it
      if($caption) $result.='<br>'.$caption;
      if($source)
      {
        if($caption)
        {
          $result.='. ';
        }
        $result.='<br>'.getlang("image_image");
        if($sourcelink)
        {
          $result.='<a href="'.$sourcelink.'" target="_blank">';
        }
        $result.=$source;
        if($sourcelink)
        {
          $result.='</a>';
        }
      }
      if($copyright)
      {
        if($caption || $source)
        {
          $result.='. ';
        }
        $result.='<br>&copy; '.$copyright.'.';
      }
      if($permission==PERMISSION_GRANTED) $result.=getlang("image_bypermission");

      $this->stringvars['caption']=$result;
      $this->createTemplates();
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
       $this->stringvars['message']=$message;
       $this->createTemplates();
    }

    // assigns templates
    function createTemplates()
    {
      $this->addTemplate("message.tpl");
    }
}

?>
