<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));

//include_once($projectroot."functions/pages.php");
include_once($projectroot."functions/pagecontent/gallerypages.php");
include_once($projectroot."includes/objects/template.php");
include_once($projectroot."includes/objects/elements.php");
include_once($projectroot."includes/objects/page.php");
include_once($projectroot."includes/includes.php");

//
// a row of images in a gallery page
//
class GalleryImage extends Template {

  function GalleryImage($filename,$width=200,$showrefused=false,$showhidden=false)
  {
  	parent::__construct();
    
    $params='&page='.$this->stringvars['page'];
    if($this->stringvars['sid'])
    {
        $params.="&sid=".$this->stringvars['sid'];
    }
    $thumbnail=getthumbnail($filename);
    
    //$this->vars['image'] = new CaptionedImage($filename,1,'vertical-align:bottom;',$showrefused,$showhidden);
    $this->vars['image'] = new CaptionedImage($filename,1,'',$showrefused,$showhidden);
   
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
    
    for($i=$startindex;$i<count($images) && $i<$endindex;$i++)
    {
      $this->listvars['galleryimage'][]= new GalleryImage($images[$i],200,$showrefused,$showhidden);
    }    
    

    $this->vars['editdata']= new Editdata($showhidden);
    
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
