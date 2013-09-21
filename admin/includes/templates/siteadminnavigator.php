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
// Templating for Site Admin Navigator
//
class SiteAdminNavigatorLink extends Template {

    function SiteAdminNavigatorLink($link,$linktitle,$params="",$target="contents") {

      global $sid;

      $this->stringvars['link']=getprojectrootlinkpath()."admin/".$link;
      $this->stringvars['target']=$target;
      $this->stringvars['linktitle']=$linktitle;
      $this->stringvars['params']=$params;
      $this->stringvars['sid']=$sid;

      $this->createTemplates();
    }

    // assigns templates
    function createTemplates()
    {
      $this->addTemplate("admin/siteadminnavigatorlink.tpl");
    }
}



//
// Templating for Site Admin Navigator
// links must be an array of type SiteAdminNavigatorLink
//
class SiteAdminNavigatorCategory extends Template {

    function SiteAdminNavigatorCategory($header,$links) {

      // layout parameters
      if(strlen($header)>0)
        $this->stringvars['header']=$header;
        
      for($i=0;$i<count($links);$i++)
      {
        $this->listvars['link'][]=$links[$i];
      }

      $this->createTemplates();
    }

    // assigns templates
    function createTemplates()
    {
      $this->addTemplate("admin/siteadminnavigatorcategory.tpl");
    }
}



//
// Templating for Site Admin Navigator
//
class SiteAdminNavigator extends Template {

    function SiteAdminNavigator($page) {

      global $sid;

      $this->vars['header'] = new HTMLHeader("","Webpage Building");

      $links=array();
      $links[]= new SiteAdminNavigatorLink("site/monthlystats.php","Monthly Stats");
      $links[]= new SiteAdminNavigatorLink("admin.php","Return to Page Editing","&page=".$page,"_top");
      $this->listvars['category'][]= new SiteAdminNavigatorCategory("Site",$links);

      $links=array();
      $links[]= new SiteAdminNavigatorLink("site/copyrightpermissions.php","Copyright Permissions");
      if(isadmin($sid))
      {
        $links[]= new SiteAdminNavigatorLink("site/referrers.php","Referrers");
      }
      $this->listvars['category'][]= new SiteAdminNavigatorCategory("Copyright",$links);

      $links=array();
      if(isadmin($sid))
      {
        $links[]= new SiteAdminNavigatorLink("site/pagetypes.php","Page Types","&action=site");
      }
      $links[]= new SiteAdminNavigatorLink("site/restrictedpages.php","Restricted Pages");
      // todo reactivate when this actually works
      //$links[]= new SiteAdminNavigatorLink("site/checkexternallinks.php","Check external links");
      //$links[]= new SiteAdminNavigatorLink("site/checkinternallinks.php","Check internal links");
      $this->listvars['category'][]= new SiteAdminNavigatorCategory("Pages",$links);


      if(isadmin($sid))
      {
        $links=array();
        $links[]= new SiteAdminNavigatorLink("site/sitelayout.php","Site Layout","&action=site");
        $links[]= new SiteAdminNavigatorLink("site/sitefeatures.php","Features","&action=site");
        $links[]= new SiteAdminNavigatorLink("site/antispam.php","Anti-Spam","&action=site");
        $links[]= new SiteAdminNavigatorLink("site/guestbookadmin.php","Guestbook");
        $links[]= new SiteAdminNavigatorLink("site/sitepolicy.php","Site Policy","&action=site");
        $links[]= new SiteAdminNavigatorLink("site/bannersadmin.php","Banners","&action=site");
        $this->listvars['category'][]= new SiteAdminNavigatorCategory("Features &amp; Layout",$links);


        $links=array();
        $links[]= new SiteAdminNavigatorLink("site/siteproperties.php","Technical Setup","&action=site");
        $links[]= new SiteAdminNavigatorLink("site/dbutils.php","Database Utilities");
        $links[]= new SiteAdminNavigatorLink("site/rebuild.php","Rebuild Indices");
        $this->listvars['category'][]= new SiteAdminNavigatorCategory("Technical",$links);

        $links=array();
        $links[]= new SiteAdminNavigatorLink("site/usermanagement.php","User Management");
        $links[]= new SiteAdminNavigatorLink("site/userpermissions.php","User Permissions");
        $links[]= new SiteAdminNavigatorLink("site/userlist.php","List Users");
        $links[]= new SiteAdminNavigatorLink("site/ipban.php","IP Ban");
        $links[]= new SiteAdminNavigatorLink("site/whosonline.php","Who's Online");
        $this->listvars['category'][]= new SiteAdminNavigatorCategory("Users",$links);

      }
      
      // who's online
      $loggedinusers=getloggedinusers();
      $userlist="";
      for($i=0;$i<count($loggedinusers);$i++)
      {
        $userlist.= $loggedinusers[$i].", ";
      }
      $this->stringvars['onlinelist']=substr($userlist,0,strlen($userlist)-2);
      
      
      $this->vars['footer'] = new HTMLFooter();

      $this->createTemplates();
    }

    // assigns templates
    function createTemplates()
    {
      $this->addTemplate("admin/siteadminnavigator.tpl");
    }
}

?>
