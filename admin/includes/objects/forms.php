<?php
$projectroot=dirname(__FILE__);

// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."functions/pages.php");
include_once($projectroot."includes/objects/template.php");
include_once($projectroot."includes/includes.php");
include_once($projectroot."includes/objects/forms.php");
include_once($projectroot."functions/images.php");


//
// $page: caller
// $moveid: Page to be moved
//
class MovePageForm extends Template {

  function MovePageForm($page,$moveid)
  {
    parent::__construct($moveid);
    $this->stringvars['moveid']=$moveid;
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/movepageform.tpl");
  }
}




//
//
//
class ImagePropertiesForm extends Template {

  function ImagePropertiesForm($page,$image,$imagealign,$header,$submitname,$params="",$anchor="")
  {
    global $articlepage, $offset;
    parent::__construct();
    
    $this->stringvars['actionvars']= "?sid=".$this->stringvars['sid']."&page=".$this->stringvars['page']."&offset=".$offset."&articlepage=".$articlepage.$params."&action=editcontents";
    
    $this->stringvars['header']=$header;
    $this->stringvars['submitname']=$submitname;
    $this->stringvars['imagefilename']=$image;
    $this->stringvars['imagelistpath']=getprojectrootlinkpath()."admin/editimagelist.php?sid=".$this->stringvars['sid']."&page=".$this->stringvars['page'];

    if(strlen($anchor)>0)
      $this->stringvars['anchor']=$anchor;

    if(strlen($image)>0)
    {
      if(imageexists($image))
      {
        $this->vars['image'] = new CaptionedImageAdmin($image,$page,2);
      }
    }
    else
    {
      $this->stringvars['image']="";
    }
    
    if(!$imagealign) $imagealign="left";
    $this->vars['left_align_button']= new RadioButtonForm("imagealign","left","Left",$imagealign==="left","right");
    $this->vars['center_align_button']= new RadioButtonForm("imagealign","center","Center",$imagealign==="center","right");
    $this->vars['right_align_button']= new RadioButtonForm("imagealign","right","Right",$imagealign==="right","right");
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/imagepropertiesform.tpl");
  }
}
?>