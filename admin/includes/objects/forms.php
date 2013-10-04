<?php
$projectroot=dirname(__FILE__);

// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

//include_once($projectroot."functions/pages.php");
include_once($projectroot."includes/objects/template.php");
//include_once($projectroot."includes/includes.php");
//include_once($projectroot."includes/objects/forms.php");
//include_once($projectroot."functions/images.php");
//include_once($projectroot."admin/includes/objects/images.php");


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
class DoneButton extends Template {

	function DoneButton($page,$params="&action=edit",$link="pageedit.php",$buttontext="Done",$class="mainoption")
	{
		parent::__construct();
		$this->stringvars['link']=$link."?sid=".$this->stringvars['sid']."&page=".$page.$params;
		$this->stringvars['buttontext']=$buttontext;
		$this->stringvars['class']=$class;
		
		if(str_endswith($link,"admin.php"))
			$this->stringvars['target']="_top";
		else
			$this->stringvars['target']="_self";
	}
	
	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/donebutton.tpl");
	}
}


//
//
//
class DonePage extends Template {

	function DonePage($title,$params="&action=edit",$link="pageedit.php",$buttontext="Done")
	{
		parent::__construct();
		$this->vars['donebutton'] = new DoneButton($this->stringvars['page'],$params,$link,$buttontext);
		$this->stringvars['title'] =$title;
	}
	
	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/donepage.tpl");
	}
}


//
//
//
class DoneRedirect extends Template {

	function DoneRedirect($page,$title,$params="&action=edit",$link="pageedit.php",$buttontext="Done")
	{
		parent::__construct();
		
		$this->vars['donebutton'] =new DoneButton($page,$params,$link,$buttontext,"mainoption");
		$this->stringvars['url'] =$link.'?sid='.$this->stringvars['sid'].'&page='.$page.$params;
		$this->stringvars['title'] =$title;
	}
	
	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/donepage.tpl");
	}
}



/**** Buttons for navigator when editing a page *******************************************/


//
//
//
class EditPageIntroSettingsButton extends Template {

	function EditPageIntroSettingsButton()
	{
		parent::__construct();
	
		$pagetype = getpagetype($this->stringvars['page']);
		if($pagetype==="article")
		{
		    $this->stringvars['buttontext']="Edit synopsis, source info & categories ...";
		}
		elseif($pagetype==="menu" || $pagetype==="articlemenu")
		{
		    $this->stringvars['buttontext']="Edit synopsis & navigation options ...";
		}
		elseif($pagetype==="news")
		{
		    $this->stringvars['buttontext']="Edit synopsis, rss & page order, or create archive ...";
		}
		else
		{
		    $this->stringvars['buttontext']="Edit synopsis ...";
		}
		$this->stringvars['action']=getprojectrootlinkpath().'admin/edit/pageintrosettingsedit.php';
	}
	
	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/editpageintrosettingsbutton.tpl");
	}
}


//
//
//
class EditPageContentsButton extends Template {

	function EditPageContentsButton()
	{
		parent::__construct();
		
		$pagetype = getpagetype($this->stringvars['page']);
		if($pagetype==="article")
		{
		    $this->stringvars['action']=getprojectrootlinkpath().'admin/edit/articleedit.php';
		    $this->stringvars['title']="Edit sections ...";
		}
		elseif($pagetype==="gallery")
		{
		    $this->stringvars['action']=getprojectrootlinkpath().'admin/edit/galleryedit.php';
		    $this->stringvars['title']="Edit images ...";
		}
		elseif($pagetype==="linklist")
		{
		    $this->stringvars['action']=getprojectrootlinkpath().'admin/edit/linklistedit.php';
		    $this->stringvars['title']="Edit links ...";
		}
		elseif($pagetype==="menu" || $pagetype==="articlemenu" || $pagetype==="linklistmenu")
		{
		    $this->stringvars['action']=getprojectrootlinkpath().'admin/edit/menuedit.php';
		    $this->stringvars['title']="Edit order of subpages ...";
		}
		elseif($pagetype==="news")
		{
		    $this->stringvars['action']=getprojectrootlinkpath().'admin/edit/newsedit.php';
		    $this->stringvars['title']="Edit newsitems ...";
		}
		else
		{
		    $this->stringvars['action']="pageedit.php";
		    $this->stringvars['title']="Edit page elements ...";
		}
	}
	
	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/editpagecontentsbutton.tpl");
	}
}


//
//
//
class GeneralSettingsButton extends Template {

	function GeneralSettingsButton()
  	{
    	parent::__construct();
    	
    	$this->vars['button']= new DoneButton($this->stringvars['page'],"&action=edit",getprojectrootlinkpath().'admin/pageedit.php',"General settings","liteoption");
  	}

  	// assigns templates
  	function createTemplates()
  	{
    	$this->addTemplate("admin/generalsettingsbutton.tpl");
  	}
}



//
// firstbutton, secondbutton need to be of type Template
// otherwise, a stringvar is used
//
class PageEditNavigationButtons extends Template {

	function PageEditNavigationButtons($firstbutton, $secondbutton)
  	{
    	parent::__construct();
    	
    	if($firstbutton instanceof Template)
    		$this->vars['firstbutton']= $firstbutton;
    	else
    		$this->stringvars['firstbutton']= $firstbutton;
    	
    	if($secondbutton instanceof Template)
    		$this->vars['secondbutton']= $secondbutton;
    	else
    		$this->stringvars['secondbutton']= $secondbutton;
    	
    	$this->vars['donebutton']= new DoneButton($this->stringvars['page'],"&action=show&unlock=on",getprojectrootlinkpath().'admin/admin.php');
  	}

  	// assigns templates
  	function createTemplates()
  	{
    	$this->addTemplate("admin/pageeditnavigationbuttons.tpl");
  	}
}



/**** Button row for subitting changes *******************************************/


//
//
//
class SubmitRow extends Template {

	function SubmitRow($submitname="submit",$submitlabel="Submit",$showreset=false,$showcancel=false,$cancellocation="",$jsid="")
  	{
  		parent::__construct($jsid);
  		
    	$this->stringvars['submit']=$submitname;
    	$this->stringvars['submitlabel']=$submitlabel;
    	if($showreset)
    		$this->stringvars['show_reset']="reset";

    	if($showcancel)
    	{
    		$this->stringvars['show_cancel']="cancel";

	    	if(strlen($cancellocation)>0)
	    		$this->stringvars['cancellocation']=$cancellocation;
	    	else 
	    		$this->stringvars['no_cancellocation']="true";
    	}
  	}

  	// assigns templates
  	function createTemplates()
  	{
    	$this->addTemplate("admin/submitrow.tpl");
  	}
}

?>