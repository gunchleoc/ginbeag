<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."functions/pages.php");
include_once($projectroot."includes/templates/template.php");
include_once($projectroot."includes/includes.php");
include_once($projectroot."includes/templates/forms.php");
include_once($projectroot."admin/includes/templates/adminforms.php");
//include_once($projectroot."includes/templates/page.php");


//
//
//
class EditMenuLevelsForm extends Template {
  function EditMenuLevelsForm($page,$sistersinnavigator,$pagelevel,$navigatorlevel)
  {
    global $sid;
    
    $this->stringvars['sid']=$sid;
    $this->stringvars['page']=$page;
    if($sistersinnavigator)
      $this->stringvars['sistersinnavigator']= " checked";
    else
      $this->stringvars['sistersinnavigator']= "";

    $this->vars['pagelevelsform']= new NumberOptionForm($pagelevel,1,10,false,"pagelevels","pagelevels");
    $this->vars['navigatorlevelsform']=new NumberOptionForm($navigatorlevel,1,10,false,"navlevels","navlevels");

    
    $this->createTemplates();
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/menulevelsform.tpl");
  }
}


//
//
//
class MenuMovePageForm extends Template {
  function MenuMovePageForm ($title,$movepageform)
  {
    $this->stringvars['title']=title2html($title);
    $this->vars['movepageform']= $movepageform;
    $this->createTemplates();
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/menumovepageform.tpl");
  }
}


//
//
//
class EditMenu extends Template {
  function EditMenu($page,$message="")
  {
    global $sid;
    
    $this->stringvars['sid']=$sid;
    $this->stringvars['page']=$page;

    $this->vars['header'] = new HTMLHeader("Editing menu page contents","Webpage Building",title2html(getpagetitle($page)).'<br />'.$message);
    $this->vars['footer']=new HTMLFooter();
    
    $contents=getmenucontents($page);
//    $this->vars['intro']= new EditTextButtons($page,$contents['introtext'],"Edit Page Intro","menu");
    
//    $this->vars['intro']= new EditTextButtons($page,$contents['introtext'],"Edit Page Intro","menu",0,0,"");
    
    $this->vars['intro']= new EditTextButtons($page,$contents['introtext'],"Edit Page Intro","menu");

/*    $image=getlinklistimage($page);
    $this->stringvars['imagefilename']=$image;
    $this->stringvars['image']=new CaptionedImage($this->stringvars['imagefilename'],2,true);
    $this->stringvars['imagelinkpath']=getimagelinkpath($this->stringvars['imagefilename']);*/

    $this->vars['menulevelsform'] = new EditMenuLevelsForm($page,$contents['sistersinnavigator'],$contents['displaydepth'],$contents['navigatordepth']);

    $subpageids=getallsubpageids($page);
    $titles_navigator=getallsubpagenavtitles($page);
    
    if(count($subpageids)>0) $this->stringvars['hassubpages']="true";
    
    for($i=0;$i<count($subpageids);$i++)
   	{
   		$this->listvars['movepageform'][] = new MenuMovePageForm ($titles_navigator[$i],new MovePageForm($page,$subpageids[$i]));
    }

    $this->stringvars['backbuttons']=generalsettingsbuttons($page);

    $this->createTemplates();
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/editmenu.tpl");
  }
}

?>
