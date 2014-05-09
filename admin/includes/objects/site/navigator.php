<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."functions/pages.php");
include_once($projectroot."includes/objects/template.php");
include_once($projectroot."includes/objects/forms.php");
include_once($projectroot."admin/includes/objects/forms.php");
include_once($projectroot."includes/objects/elements.php");


//
// Templating for Site Admin Navigator
//
class SiteAdminNavigatorLink extends Template {

    function SiteAdminNavigatorLink($linktitle,$action="")
    {
		parent::__construct();

		$linkparams["page"] = $this->stringvars['page'];
		$linkparams["action"] = $action;

		$this->stringvars['link']=getprojectrootlinkpath().'admin/admin.php'.makelinkparameters($linkparams);
		$this->stringvars['linktitle']=$linktitle;
    }

    // assigns templates
    function createTemplates()
    {
		$this->addTemplate("admin/site/adminnavigatorlink.tpl");
    }
}



//
// Templating for Site Admin Navigator
// links must be an array of type SiteAdminNavigatorLink
//
class SiteAdminNavigatorCategory extends Template {

    function SiteAdminNavigatorCategory($header,$links)
    {
    	parent::__construct();

		// layout parameters
		if(strlen($header)>0)
		$this->stringvars['header']=$header;
		
		for($i=0;$i<count($links);$i++)
		{
			$this->listvars['link'][]=$links[$i];
		}
    }

    // assigns templates
    function createTemplates()
    {
		$this->addTemplate("admin/site/adminnavigatorcategory.tpl");
    }
}


//
// Templating for Site Admin Navigator
// Header text above the navigation links
//
class SiteAdminNavigatorHeader extends Template {

    function SiteAdminNavigatorHeader()
    {
    	parent::__construct();
	}
    // assigns templates
    function createTemplates()
    {
		$this->addTemplate("admin/site/adminnavigatorheader.tpl");
    }
}



//
// Templating for Site Admin Navigator
//
class SiteAdminNavigator extends Template {

	function SiteAdminNavigator()
	{
		parent::__construct();
		
		$links=array();
		$links[]= new SiteAdminNavigatorLink("Site Statistics","sitestats");
		$this->listvars['category'][]= new SiteAdminNavigatorCategory("Site",$links);
		
		
		$links=array();
		if(isadmin())
		{
			$links[]= new SiteAdminNavigatorLink("Page Types","sitepagetype");
		}
		$links[]= new SiteAdminNavigatorLink("Restricted Pages","sitepagerestrict");
		$this->listvars['category'][]= new SiteAdminNavigatorCategory("Pages",$links);
		
		$links=array();
		if(isadmin())
		{
			$links[]= new SiteAdminNavigatorLink("Site Layout","sitelayout");
			$links[]= new SiteAdminNavigatorLink("Items of the Day","siteiotd");
			$links[]= new SiteAdminNavigatorLink("Guestbook","siteguest");
			$links[]= new SiteAdminNavigatorLink("Site Policy","sitepolicy");
			$links[]= new SiteAdminNavigatorLink("Banners","sitebanner");
			$this->listvars['category'][]= new SiteAdminNavigatorCategory("Features &amp; Layout",$links);
			
			$links=array();
			$links[]= new SiteAdminNavigatorLink("Technical Setup","sitetech");
			$links[]= new SiteAdminNavigatorLink("Database Utilities","sitedb");
			$links[]= new SiteAdminNavigatorLink("Rebuild Indices","siteind");
			$this->listvars['category'][]= new SiteAdminNavigatorCategory("Technical",$links);

			$links=array();
			$links[]= new SiteAdminNavigatorLink("Anti-Spam","sitespam");
			$links[]= new SiteAdminNavigatorLink("Blocked Sites","sitereferrers");
			$this->listvars['category'][]= new SiteAdminNavigatorCategory("Protection",$links);

			$links=array();
			$links[]= new SiteAdminNavigatorLink("User Management","siteuserman");
			$links[]= new SiteAdminNavigatorLink("User Permissions","siteuserperm");
			$links[]= new SiteAdminNavigatorLink("List Users","siteuserlist");
			$links[]= new SiteAdminNavigatorLink("IP Ban","siteipban");
		}
		$links[]= new SiteAdminNavigatorLink("Who's Online","siteonline");
		$this->listvars['category'][]= new SiteAdminNavigatorCategory("Users",$links);
	}

    // assigns templates
    function createTemplates()
    {
		$this->addTemplate("admin/site/adminnavigator.tpl");
    }
}

?>