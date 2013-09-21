<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."functions/pages.php");
include_once($projectroot."includes/templates/template.php");
include_once($projectroot."includes/includes.php");
//include_once($projectroot."includes/templates/forms.php");
//include_once($projectroot."includes/templates/page.php");


//
//
//
class ShowAllImagesButton extends Template {
  function ShowAllImagesButton($page,$isshowall=true,$noofimages,$imagesperpage)
  {
    global $sid;

    $this->stringvars['sid']=$sid;
    $this->stringvars['page']=$page;
    $this->stringvars['noofimages']=$noofimages;
    
    if($isshowall)
    {
      $this->stringvars['name']="showall";
      $this->stringvars['value']="Show all images (".$noofimages.")";
    }
    else
    {
      $this->stringvars['name']="dontshowall";
      $this->stringvars['value']="Show ".$imagesperpage." images per page";
    }

    $this->createTemplates();
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/showallimagesbutton.tpl");
  }
}


//
//
//
class GalleryImageForm extends Template {
  function GalleryImageForm($page,$imageid,$offset,$pageposition,$noofimages,$showall)
  {
    global $sid;
    
    $this->stringvars['sid']=$sid;
    $this->stringvars['page']=$page;
    $this->stringvars['offset']=$offset;
    $this->stringvars['pageposition']=$pageposition;
    $this->stringvars['noofimages']=$noofimages;
    $this->stringvars['imageid']=$imageid;
    if($showall)
      $this->stringvars['showall']="showall";

    $this->stringvars['imagefilename']=getgalleryimage($imageid);
    $this->vars['image'] = new CaptionedImage($this->stringvars['imagefilename'],2,true);
    $this->stringvars['imagelinkpath']=getimagelinkpath($this->stringvars['imagefilename']);
    
    if(!getthumbnail($image))
      $this->stringvars['no_thumbnail']="This image has no thumbnail";
    
    $this->createTemplates();
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/galleryimageform.tpl");
  }
}


//
//
//
class AddGalleryImageForm extends Template {
  function AddGalleryImageForm($page,$offset,$pageposition,$noofimages,$showall)
  {
    global $sid;

    $this->stringvars['sid']=$sid;
    $this->stringvars['page']=$page;
    $this->stringvars['offset']=$offset;
    $this->stringvars['pageposition']=$pageposition+1;
    $this->stringvars['noofimages']=$noofimages+1;
    if($showall)
      $this->stringvars['showall']="showall";

    $this->createTemplates();
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/addgalleryimageform.tpl");
  }
}


//
//
//
class ReindexGalleryForm extends Template {
  function ReindexGalleryForm($page,$showall)
  {
    global $sid;

    $this->stringvars['sid']=$sid;
    $this->stringvars['page']=$page;
    if($showall)
      $this->stringvars['showall']="showall";

    $this->createTemplates();
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/reindexgalleryform.tpl");
  }
}



//
//
//
class EditGallery extends Template {

  function EditGallery($page,$message="",$offset,$imagesperpage,$showall)
  {
    global $sid;
    
    $this->stringvars['sid']=$sid;
    $this->stringvars['page']=$page;


  	if($showall)
  	{
    	$this->vars['intro']= new EditTextButtons($page,getgalleryintro($page),"Edit Page Intro","gallery",0,0,"&showall=true");
  	}
  	else
  	{
    	$this->vars['intro']= new EditTextButtons($page,getgalleryintro($page),"Edit Page Intro","gallery");
  	}
//todo
/*  $imageform = new ImagePropertiesForm($page,$contents['image'],$contents['imagealign'],$contents['imagevalign'],"Page Intro Image","introimage");
  $this->vars['intro']= new TextWithImageForm("Page Intro",$edittextbuttons,$imageform);
*/

  	$imageids=getgalleryimages($page);

  	$noofimages=count($imageids);
  	if(!$offset) $offset=0;
  	$noofdisplayedimages=$imagesperpage;

  	if ($showall)
  	{
    	$noofdisplayedimages=$noofimages;
  	}
  	$this->vars['showallbutton'] = new ShowAllImagesButton($page,!$showall,$noofimages,$imagesperpage);

  	$this->vars['pagemenu'] = new PageMenu($offset,$noofdisplayedimages,$noofimages,'',$page);
  
  	if($noofimages > 0)
  	{

  		for($i=$offset;$i<($offset+$noofdisplayedimages)&&$i<$noofimages;$i++)
  		{
    		$this->listvars['imageform'][] = new GalleryImageForm($page,$imageids[$i],$offset,$pageposition,$noofimages,$showall);
  		}

  	}
  	
  	else
  	{
  		$this->stringvars['imageform']="";
  		$message .='<p class="highlight">There are no images in this gallery</p>';
  	}

  	$this->vars['addform'] = new AddGalleryImageForm($page,$offset,$pageposition,$noofimages,$showall);

  	$this->vars['reindexform'] = new ReindexGalleryForm($page,$showall);
  
    $this->stringvars['backbuttons']=generalsettingsbuttons($page);
    
    
    $this->vars['header'] = new HTMLHeader("Editing gallery page contents","Webpage Building",title2html(getpagetitle($page)).'<br />'.$message);
    $this->vars['footer']=new HTMLFooter();

    

    $this->createTemplates();
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/editgallery.tpl");
  }
}

?>
