<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));

include_once($projectroot."functions/pages.php");
include_once($projectroot."includes/templates/template.php");
include_once($projectroot."includes/templates/elements.php");
include_once($projectroot."includes/templates/page.php");
include_once($projectroot."includes/includes.php");

//
// a row of images in a gallery page
//
class GalleryImage extends Template {

  function GalleryImage($page,$filename,$factor=2,$showrefused=false,$showhidden=false)
  {
    $this->stringvars['image']=$this->makegalleryimage($page,$filename,$factor,$showrefused,$showhidden);
    $this->vars['caption']=new ImageCaption($filename, $factor);

    $this->createTemplates();

//    print_r($this->vars);
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("galleryimage.tpl");
  }


  function makegalleryimage($page,$filename,$factor=2,$showrefused=false,$showhidden=false)
  {
    global $sid;
    $result="";

    if(imageexists($filename))
    {
      $params='&page='.$page;
      if($sid)
      {
        $params.="&sid=".$sid;
      }
      if($showhidden)
      {
        $image = new Image($filename,$factor,$params);
        $result.=$image->toHTML();
        if(imagepermissionrefused($filename))
        {
          $result.='<div class="highlight">Permission refused for this image;<br />';
          if($showrefused)
          {
            $result.='but shown anyway!</div>';
          }
          else
          {
            $result.='hidden from webpage!</div>';
          }
        }
      }
      elseif(!imagepermissionrefused($filename) || $showrefused)
      {
        $image = new Image($filename,$factor,$params);
        $result.=$image->toHTML();
      }
    }
    else $result='<span class="gen"><i>'.$filename.'</i></span>';
    return $result;
  }
}




//
// a row of images in a gallery page
//
class GalleryRow extends Template {

  function GalleryRow($page,$filenames=array(),$factor=2,$showrefused=false,$showhidden=false)
  {

    for($i=0;$i<count($filenames);$i++)
    {
      $this->listvars['image'][]= new GalleryImage($page,$filenames[$i],$factor,$showrefused,$showhidden);
    }

    $this->createTemplates();

//    print_r($this->listvars);
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("galleryrow.tpl");
  }
}



//
// main class for gallery pages
//
class GalleryPage extends Template {

  function GalleryPage($page,$offset=0,$showrefused=false,$showhidden=false)
  {

    $imagesperpage=6;
    $images=getgalleryimagefilenames($page,$showrefused,$showhidden);
    $noofimages=count($images);
    if(!$offset) $offset=0;

    $this->stringvars['pagetitle']=title2html(getpagetitle($page));
    $this->stringvars['text']=text2html(getgalleryintro($page));

    
    // todo: room for image, need to add admin functions and database entry for that
    
/*    if(strlen($pagecontents['image'])>0 && ($showhidden || !imagepermissionrefused($pagecontents['image'])))
      $this->stringvars['image'] =new Image($pagecontents['image'],2,$showrefused,$showhidden);
*/

    //pagemenu
    $this->vars['pagemenu']= new PageMenu($offset, $imagesperpage, $noofimages,"",$page);
    
    $cols=3;
    $rows=ceil($imagesperpage/$cols);

    for($row=0;$row<$rows;$row++)
    {
      $filenames=array();
      for($col=0;$col<$cols;$col++)
      {
        $position=$row*$cols+$col+$offset;
        if($position<$noofimages)
        {
          $filenames[]=$images[$position];
        }
      }
//      print_r($filenames);
      if(count($filenames))
      {
        $this->listvars['galleryrow'][]= new GalleryRow($page,$filenames,$cols,$showrefused,$showhidden);
      }
    }

    $this->vars['editdata']= new Editdata($page,$showhidden);

    $this->createTemplates();
    
//    print_r($this->vars);
  }
  
  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("gallerypage.tpl");
  }


}

/*print('<br />test');
$object= new GalleryImage(0,"filename",2,true,true);
print($object->toHTML());
print('<br />test');*/
?>
