<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."functions/pagecontent/menupages.php");
include_once($projectroot."includes/objects/template.php");
include_once($projectroot."admin/includes/objects/elements.php");
include_once($projectroot."admin/includes/objects/forms.php");
include_once($projectroot."admin/includes/objects/editor.php");


//
//
//
class EditMenuLevelsForm extends Template {
  function EditMenuLevelsForm($page,$sistersinnavigator,$pagelevel,$navigatorlevel)
  {
    parent::__construct($page);
    
    $this->stringvars['javascript']="&nbsp;".prepareJavaScript($this->stringvars['jsid'], "admin/includes/javascript/messageboxes.js");
   	$this->stringvars['javascript'].=prepareJavaScript($this->stringvars['jsid'], "admin/includes/javascript/editmenu.js");

    $this->vars['pagelevelsform']= new NumberOptionForm($pagelevel,1,10,false,$this->stringvars['jsid'],"pagelevels","pagelevels");
    $this->vars['navigatorlevelsform']=new NumberOptionForm($navigatorlevel,1,10,false,$this->stringvars['jsid'],"navlevels","navlevels");
    
    $this->vars["sistersinnavigator"]= new CheckboxForm("sisters","1","List items in same level",$sistersinnavigator,"right");
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/edit/menulevelsform.tpl");
  }
}


//
//
//
class MenuMovePageForm extends Template {
  function MenuMovePageForm ($page,$position,$noofelements,$title,$jsid,$movepageform)
  {
  	parent::__construct($jsid);
  	$this->stringvars['page']=$page;
    $this->stringvars['title']=title2html($title);
    $this->stringvars['position']=$position;
    $this->stringvars['noofelements']=$noofelements;
    $this->vars['movepageform']= $movepageform;
    
    $this->stringvars['javascript']="&nbsp;".prepareJavaScript($this->stringvars['jsid'], "admin/includes/javascript/messageboxes.js");
   	$this->stringvars['javascript'].=prepareJavaScript($this->stringvars['jsid'], "admin/includes/javascript/editmenumovepage.js");
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/edit/menumovepageform.tpl");
  }
}

//
//
//
class MenuMovePageFormContainer extends Template {
  function MenuMovePageFormContainer ($page,$subpageids)
  {
  	parent::__construct();

    $titles_navigator=getallsubpagenavtitles($page);
   
    for($i=0;$i<count($subpageids);$i++)
   	{
   		$this->listvars['movepageform'][] = new MenuMovePageForm ($page,$i,count($subpageids),$titles_navigator[$i],$subpageids[$i],new MovePageForm($page,$subpageids[$i]));
    }
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/edit/menumovepageformcontainer.tpl");
  }
}



//
//
//
class EditMenu extends Template {
  function EditMenu($page)
  {
   		parent::__construct($page,array(0=>"includes/javascript/jquery.js", 1=>"includes/javascript/jcaret.js"));
  		
  		$this->stringvars['javascript']="&nbsp;".prepareJavaScript($this->stringvars['jsid'], "admin/includes/javascript/messageboxes.js");

    $this->vars['intro']= new Editor($page,0,"menu","Page Intro");
    
    $contents=getmenucontents($page);

/*    $image=getpageintro($page);
    $this->stringvars['imagefilename']=$image;
    $this->stringvars['image']=new CaptionedImage($this->stringvars['imagefilename'],2,"left",true,true);
    $this->stringvars['imagelinkpath']=getimagelinkpath($this->stringvars['imagefilename'],getimagesubpath(basename($this->stringvars['imagefilename'])));*/

    $this->vars['menulevelsform'] = new EditMenuLevelsForm($page,$contents['sistersinnavigator'],$contents['displaydepth'],$contents['navigatordepth']);

    $subpageids=getallsubpageids($page);

    if(count($subpageids)>0)
    {
    	$this->stringvars['hassubpages']="true";
    	$this->vars['movepageform'] = new MenuMovePageFormContainer ($page,$subpageids);
    }


    $this->vars['backbuttons']=new GeneralSettingsButtons();
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/edit/editmenu.tpl");
  }
}

?>