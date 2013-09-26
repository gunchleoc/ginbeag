<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

//include_once($projectroot."functions/pages.php");
include_once($projectroot."includes/objects/template.php");
include_once($projectroot."includes/functions.php");
//include_once($projectroot."includes/objects/elements.php");
//include_once($projectroot."includes/objects/forms.php");

//
//
//
class DoneButton extends Template {

  function DoneButton($params="&action=edit",$link="pageedit.php",$buttontext="Done",$class="mainoption")
  {
    parent::__construct();
    $this->stringvars['link']=$link."?sid=".$this->stringvars['sid']."&page=".$this->stringvars['page'].$params;
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
    $this->vars['donebutton'] = new DoneButton($params,$link,$buttontext);
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
  	
  	$this->vars['donebutton'] =new DoneButton($params,$link,$buttontext,"mainoption");
  	$this->stringvars['url'] =$link.'?sid='.$this->stringvars['sid'].'&page='.$page.$params;
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
class EditContentsButtons extends Template {

	function EditContentsButtons($title)
  	{
  		parent::__construct();
    	$this->vars['titlebutton']= new DoneButton("",'',$title,"liteoption");
    	$this->vars['generalsettingsbutton']= new DoneButton("&action=edit",getprojectrootlinkpath().'admin/pageedit.php',"General settings","liteoption");
    	$this->vars['donebutton']= new DoneButton("&action=show&unlock=on",getprojectrootlinkpath().'admin/admin.php');
  	}

  	// assigns templates
  	function createTemplates()
  	{
    	$this->addTemplate("admin/editcontentsbuttons.tpl");
  	}
}


//
//
//
class GeneralSettingsButtons extends Template {

	function GeneralSettingsButtons()
  	{
    	parent::__construct();
    	
    	$this->vars['generalsettingsbutton']= new DoneButton("&action=edit",getprojectrootlinkpath().'admin/pageedit.php',"General settings","liteoption");
    	$this->vars['donebutton']= new DoneButton("&action=show&unlock=on",getprojectrootlinkpath().'admin/admin.php');
  	}

  	// assigns templates
  	function createTemplates()
  	{
    	$this->addTemplate("admin/edit/generalsettingsbuttons.tpl");
  	}
}


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