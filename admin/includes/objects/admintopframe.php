<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."functions/pages.php");
include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."includes/objects/template.php");
include_once($projectroot."includes/objects/forms.php");
include_once($projectroot."admin/includes/objects/forms.php");
include_once($projectroot."includes/objects/elements.php");
include_once($projectroot."admin/includes/actions.php");

//
// Templating for Admin Navigator
//
class AdminTopFrameLink extends Template {

    function AdminTopFrameLink($link,$linktitle,$params="",$target="") {

      parent::__construct();
      
      $this->stringvars['link']=getprojectrootlinkpath()."admin/".$link."?sid=".$this->stringvars['sid']."&page=".$this->stringvars['page'].$params;
      $this->stringvars['linktitle']=$linktitle;
      $this->stringvars['target']=$target;
    }

    // assigns templates
    function createTemplates()
    {
      $this->addTemplate("admin/admintopframelink.tpl");
    }
}


//
// Templating for Admin Navigator in left frame
//
class AdminTopFrame extends Template {

	function AdminTopFrame($page,$action) {

    	parent::__construct();

    	$this->stringvars['sitename']=title2html(getproperty("Site Name"));

    	if(isloggedin($this->stringvars['sid']))
    	{

	    	if($page)
    		{
      			$this->stringvars['pagetitle']=title2html(getnavtitle($page));
	     		$this->stringvars['publishformactionlink']=getprojectrootlinkpath()."admin/pageedit.php";
      			if(ispublished($page))
      			{
      				$this->vars['publishlink']=new AdminTopFrameLink("pageedit.php","Hide Page","&action=unpublish");
      				$this->stringvars['published']="(published)";
      			}
      			elseif(ispublishable($page))
      				$this->vars['publishlink']=new AdminTopFrameLink("pageedit.php","Publish Page","&action=publish");
    		}
    		else
    		{
    			$this->stringvars['pagetitle']="No page selected";
    		}
    		if($action == "pagenew") $this->stringvars['newpagelink']="New Page";
		    else $this->vars['newpagelink']=new AdminTopFrameLink("pagenew.php","New Page");

		    if($action == "edit" || $action == "editcontents" || $action == "editpageintro")
      		{
		    	$this->vars['donelink']=new AdminTopFrameLink("admin.php","Done","&action=show&unlock=on");
		    	$this->stringvars['editpagelink']="Edit Page";
		    }
		    elseif($this->stringvars['page'])
		    {
		    	$pagetype=getpagetype($page);
		    	if($pagetype==="article")
			    {
			        $this->vars['editpagelink']=new AdminTopFrameLink("edit/articleedit.php","Edit Page","");
			    }
			    elseif($pagetype==="gallery")
			    {
			        $this->vars['editpagelink']=new AdminTopFrameLink("edit/galleryedit.php","Edit Page","");
			    }
			    elseif($pagetype==="linklist")
			    {
			        $this->vars['editpagelink']=new AdminTopFrameLink("edit/linklistedit.php","Edit Page","");
			    }
			    elseif($pagetype==="menu" || $pagetype==="articlemenu")
			    {
			        $this->vars['editpagelink']=new AdminTopFrameLink("edit/menuedit.php","Edit Page","");
			    }
			    elseif($pagetype==="news")
			    {
			        $this->vars['editpagelink']=new AdminTopFrameLink("edit/newsedit.php","Edit Page","");
			    }
			    else
			    {
			        $this->vars['editpagelink']=new AdminTopFrameLink("pageedit.php","Edit Page","&action=edit");
			    }
		    }
		    $this->vars['previewpagelink']=new AdminTopFrameLink("pagedisplay.php","Preview Page","","_blank");
		    
		    if($action == "pagedelete") $this->stringvars['deletepagelink']="Delete Page";
		    elseif($this->stringvars['page']) $this->vars['deletepagelink']=new AdminTopFrameLink("pagedelete.php","Delete Page","&action=delete");
		    $this->vars['imageslink']=new AdminTopFrameLink("editimagelist.php","Images","","_blank");
		    
		    if($action == "editcategories") $this->stringvars['categorieslink']="Categories";
		    else $this->vars['categorieslink']=new AdminTopFrameLink("editcategories.php","Categories");
		    
		    if(issiteaction($action))
		    {
		    	$this->stringvars['siteadminlink']="Site";
		    	$this->vars['returnpageeditinglink']=new AdminTopFrameLink("admin.php","Return to Page Editing");
		    	$this->stringvars['showsitelinks']="on";
		    }
		    else
		    {
		    	$this->vars['siteadminlink']=new AdminTopFrameLink("admin.php","Site","&action=site");
		    	$this->stringvars['showeditlinks']="on";
		    }
		    $profilelinktitle="Profile [".title2html(getusername(getsiduser($this->stringvars['sid'])))."]";
		    if($action == "profile") $this->stringvars['profilelink']=$profilelinktitle;
		    else $this->vars['profilelink']=new AdminTopFrameLink("profile.php",$profilelinktitle);
		    
		    $this->vars['logoutlink']=new AdminTopFrameLink("admin.php","Logout","&logout=on","_top");
		    $this->stringvars['onlineusers']=implode(", ", getloggedinusers());
    	}
    	else
    	{
      		$this->vars['registerlink']=new AdminTopFrameLink("register.php","Register");
      		$this->vars['loginlink']=new AdminTopFrameLink("login.php","Login","","_top");
    	}
  	}

  	// assigns templates
  	function createTemplates()
  	{
    	$this->addTemplate("admin/admintopframe.tpl");
  	}

}

?>