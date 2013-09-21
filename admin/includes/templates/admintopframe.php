<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."functions/pages.php");
include_once($projectroot."includes/templates/template.php");
include_once($projectroot."includes/templates/forms.php");
include_once($projectroot."admin/includes/templates/adminelements.php");

//
// Templating for Admin Navigator
//
class AdminTopFrameLink extends Template {

    function AdminTopFrameLink($page_id,$link,$linktitle,$params="",$target="contents") {

      global $sid;

      $this->stringvars['page']=$page_id;
      $this->stringvars['params']=$params;
      $this->stringvars['link']=getprojectrootlinkpath()."admin/".$link;
      $this->stringvars['linktitle']=$linktitle;
      $this->stringvars['sid']=$sid;
      $this->stringvars['target']=$target;

      $this->createTemplates();
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

  function AdminTopFrame($page_id) {

    global $sid;

    $this->vars['header'] = new HTMLHeader("","Webpage building");
    $this->vars['footer'] = new HTMLFooter();

    if($page_id)
    {
      $this->stringvars['page']=$page_id;
      $this->stringvars['pagetitle']=title2html(getnavtitle($page_id));
      $this->stringvars['publishformactionlink']=getprojectrootlinkpath()."admin/pageedit.php";
      if(ispublished($page_id))
        $this->stringvars['is_published']="is published";
      elseif(ispublishable($page_id))
        $this->stringvars['not_published']="not published";
    }
    $this->stringvars['sid']=$sid;
    $this->stringvars['sitename']=title2html(getproperty("Site Name"));

    if($sid)
    {
      $this->vars['newpagelink']=new AdminTopFrameLink($page_id,"pagenew.php","New Page");
      $this->vars['editpagelink']=new AdminTopFrameLink($page_id,"pageedit.php","Edit Page","&action=edit");
      $this->vars['previewpagelink']=new AdminTopFrameLink($page_id,"pagedisplay.php","Preview Page","","_blank");
      $this->vars['deletepagelink']=new AdminTopFrameLink($page_id,"pageedit.php","Delete Page","&action=delete");
      $this->vars['imageslink']=new AdminTopFrameLink($page_id,"editimagelist.php","Add or Edit Images","","_blank");
      $this->vars['categorieslink']=new AdminTopFrameLink($page_id,"editcategories.php","Edit Categories","","_blank");
      $this->vars['siteadminlink']=new AdminTopFrameLink($page_id,"admin.php","Site","&action=site","_blank");
      $this->vars['profilelink']=new AdminTopFrameLink($page_id,"profile.php","Your Profile");
      $name=getusername(getsiduser($sid));
      $this->vars['logoutlink']=new AdminTopFrameLink($page_id,"admin.php","Logout [".title2html($name)."]","&logout=on","_top");
    }
    else
    {
      $this->vars['registerlink']=new AdminTopFrameLink($page_id,"register.php","Register");
      $this->vars['loginlink']=new AdminTopFrameLink($page_id,"login.php","Login","","_top");
    }

    $this->createTemplates();

  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/admintopframe.tpl");
  }

}

?>
