<?php
$projectroot=dirname(__FILE__);

// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."functions/pages.php");
include_once($projectroot."includes/templates/template.php");
include_once($projectroot."includes/includes.php");
include_once($projectroot."includes/templates/forms.php");


//
// $page: caller
// $moveid: Page to be moved
//
class MovePageForm extends Template {

  function MovePageForm($page,$moveid)
  {
    global $sid;
    $this->stringvars['sid']=$sid;
    $this->stringvars['page']=$page;
    $this->stringvars['moveid']=$moveid;

    $this->createTemplates();
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

  function ImagePropertiesForm($page,$image,$imagealign,$imagevalign,$header,$submitname,$params="",$anchor="")
  {
    global $sid, $articlepage, $offset;
    $this->stringvars['sid']=$sid;
    $this->stringvars['page']=$page;
    $this->stringvars['articlepage']=$articlepage;
    $this->stringvars['offset']=$offset;
    $this->stringvars['header']=$header;
    $this->stringvars['submitname']=$submitname;
    $this->stringvars['params']=$params;
    $this->stringvars['imagefilename']=$image;
    $this->stringvars['imagelistpath']=getprojectrootlinkpath()."admin/editimagelist.php";

    if(strlen($anchor)>0)
      $this->stringvars['anchor']=$anchor;

    if(strlen($image)>0)
    {
      if(imageexists($image))
      {
        $this->vars['image'] = new CaptionedImage($image,2,true);
        $this->stringvars['imagepath']=getimagelinkpath($image);
      }
      else
      {
        $this->stringvars['no_imagepath']="Image does not exist!";
      }
    }
    else
    {
      $this->stringvars['no_image']="no image";
    }
    
    $this->stringvars['right_align_checked']="";
    $this->stringvars['center_align_checked']="";
    $this->stringvars['left_align_checked']="";
    $this->stringvars['bottom_valign_checked']="";
    $this->stringvars['middle_valign_checked']="";
    $this->stringvars['top_valign_checked']="";

    if($imagealign==="right")
      $this->stringvars['right_align_checked']="checked";
    elseif($imagealign==="center")
      $this->stringvars['center_align_checked']="checked";
    else
      $this->stringvars['left_align_checked']="checked";
      
    if($imagevalign==="bottom")
      $this->stringvars['bottom_valign_checked']="checked";
    elseif($imagevalign==="middle")
      $this->stringvars['middle_valign_checked']="checked";
    else
      $this->stringvars['top_valign_checked']="checked";

    $this->createTemplates();
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/imagepropertiesform.tpl");
  }
}


//
//
//
class NewsitemImagePropertiesForm extends Template {

  function NewsitemImagePropertiesForm($page,$image,$newsitem,$imageid,$offset=0)
  {
    global $sid, $offset;
    $this->stringvars['sid']=$sid;
    $this->stringvars['page']=$page;
    $this->stringvars['newsitem']=$newsitem;
    $this->stringvars['offset']=$offset;
    $this->stringvars['imageid']=$imageid;
    $this->stringvars['imagelistpath']=getprojectrootlinkpath()."admin/editimagelist.php";

    $this->stringvars['imagefilename']=$image;

    if(strlen($image)>0)
    {
      if(imageexists($image))
      {
        $this->vars['image'] = new CaptionedImage($image,2,true);
        $this->stringvars['imagepath']=getimagelinkpath($image);
      }
      else
      {
        $this->stringvars['no_imagepath']="Image does not exist!";
      }
    }
    else
    {
      $this->stringvars['no_image']="no image";
    }

    $this->createTemplates();
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/newsitemimagepropertiesform.tpl");
  }
}

//
// $page: caller
// $moveid: Page to be moved
//
class EditPageContentsForm extends Template {

  function EditPageContentsForm($page)
  {
    global $sid;
    $this->stringvars['sid']=$sid;
    $this->stringvars['page']=$page;

    $pagetype = getpagetype($page);
    if($pagetype==="article")
    {
        $this->stringvars['action']=getprojectrootlinkpath().'admin/edit/articleedit.php';
    }
    elseif($pagetype==="gallery")
    {
        $this->stringvars['action']=getprojectrootlinkpath().'admin/edit/galleryedit.php';
    }
    elseif($pagetype==="linklist")
    {
        $this->stringvars['action']=getprojectrootlinkpath().'admin/edit/linklistedit.php';
    }
    elseif($pagetype==="menu" || $pagetype==="articlemenu" || $pagetype==="linklistmenu")
    {
        $this->stringvars['action']=getprojectrootlinkpath().'admin/edit/menuedit.php';
    }
    elseif($pagetype==="news")
    {
        $this->stringvars['action']=getprojectrootlinkpath().'admin/edit/newsedit.php';
    }
    else
    {
        $this->stringvars['action']="pageedit.php";
    }

    $this->createTemplates();
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/editpagecontentsform.tpl");
  }
}



//
//
//
class EditTextButtons extends Template {

  function EditTextButtons($page,$text,$buttontitle,$elementtype,$item=0,$articlepage=0,$params="",$anchor="")
  {
    global $sid, $offset;

    $location='?sid='.$sid;
    $location.='&page='.$page.'&item='.$item;
    if($offset) $location.='&offset='.$offset;
    if($articlepage)
    {
      $location.='&articlepage='.$articlepage;
    }
    else
    {
      $location.='&action=editcontents';
    }
    $location.=$params;
  

    $this->stringvars['sid']=$sid;
    $this->stringvars['text']=text2html($text);
    $this->stringvars['buttontitle']=$buttontitle;
    $this->stringvars['location']=$location;
    $this->stringvars['elementtype']=$elementtype;
    $this->stringvars['params']=$params;
    $this->stringvars['item']=$item;
    $this->stringvars['page']=$page;
    $this->stringvars['offset']=$offset;
    $this->stringvars['edittextlink']=getprojectrootlinkpath()."admin/edit/edittext.php";
    
    if(strlen($anchor)>0)
      $this->stringvars['anchor']=$anchor;

    $this->createTemplates();
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/edittextbuttons.tpl");
  }
}



//
//
//
class AdminLoginForm extends Template {


    function AdminLoginForm($username)
    {
      global $_GET;
      $this->stringvars['params']=makelinkparameters($_GET);
      $this->stringvars['username']=title2html($username);

      $this->createTemplates();
    }

    // assigns templates
    function createTemplates()
    {
       $this->addTemplate("admin/adminloginform.tpl");
    }
}


//
//
//
class ForgotEmailForm extends Template {


    function ForgotEmailForm($username)
    {
      $this->stringvars['username']=title2html($username);

      $this->createTemplates();
    }

    // assigns templates
    function createTemplates()
    {
       $this->addTemplate("admin/forgotemailform.tpl");
    }
}



//
//
//
class ForgotPasswordForm extends Template {


    function ForgotPasswordForm($username)
    {
      $this->stringvars['username']=title2html($username);

      $this->createTemplates();
    }

    // assigns templates
    function createTemplates()
    {
       $this->addTemplate("admin/forgotpasswordform.tpl");
    }
}




//
// used in Site properties
//
class LocationButton extends Template {


    function LocationButton($buttonname,$location,$isparent=false,$class="mainoption")
    {
      $this->stringvars['value']=$buttonname;
      $this->stringvars['location']=$location;
      $this->stringvars['class']=$class;
      if($isparent)
        $this->stringvars['target']="parent.location.href";
      else
      {
        $this->stringvars['target']="self.location.href";
      }

      $this->createTemplates();
    }

    // assigns templates
    function createTemplates()
    {
       $this->addTemplate("admin/locationbutton.tpl");
    }
}




//
// $text must be of type EditTextButtons
// $image must be an image form
//
class TextWithImageForm extends Template {
  function TextWithImageForm($title,$edittextbuttons,$imageform)
  {
    $this->stringvars['introtitle']=title2html($title);
    $this->vars['edittext']=$edittextbuttons;
    $this->vars['imageform']=$imageform;

    $this->createTemplates();

  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/textwithimageform.tpl");
  }
}

?>
