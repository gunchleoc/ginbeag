<?php
/**
 * An Gineadair Beag is a content management system to run websites with.
 *
 * PHP Version 7
 *
 * Copyright (C) 2005-2019 GunChleoc
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 * @category Ginbeag
 * @package  Ginbeag
 * @author   gunchleoc <fios@foramnagaidhlig.net>
 * @license  https://www.gnu.org/licenses/agpl-3.0.en.html GNU AGPL
 * @link     https://github.com/gunchleoc/ginbeag/
 */

$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."functions/pages.php";
require_once $projectroot."includes/objects/template.php";
require_once $projectroot."includes/objects/forms.php";
require_once $projectroot."admin/includes/objects/forms.php";
require_once $projectroot."includes/objects/elements.php";


//
// Templating for Site Admin Navigator
//
class SiteAdminNavigatorLink extends Template
{

    function __construct($linktitle,$action="")
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
class SiteAdminNavigatorCategory extends Template
{

    function __construct($header,$links)
    {
        parent::__construct();

        // layout parameters
        if(strlen($header)>0) {
            $this->stringvars['header']=$header;
        }

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
class SiteAdminNavigatorHeader extends Template
{

    function __construct()
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
class SiteAdminNavigator extends Template
{

    function __construct()
    {
        parent::__construct();

        $links=array();
        $links[]= new SiteAdminNavigatorLink("Site Statistics", "sitestats");
        $this->listvars['category'][]= new SiteAdminNavigatorCategory("Site", $links);


        $links=array();
        if(isadmin()) {
            $links[]= new SiteAdminNavigatorLink("Page Types", "sitepagetype");
        }
        $links[]= new SiteAdminNavigatorLink("Restricted Pages", "sitepagerestrict");
        $this->listvars['category'][]= new SiteAdminNavigatorCategory("Pages", $links);

        $links=array();
        if(isadmin()) {
            $links[]= new SiteAdminNavigatorLink("Site Layout", "sitelayout");
            $links[]= new SiteAdminNavigatorLink("Items of the Day", "siteiotd");
            $links[]= new SiteAdminNavigatorLink("Contact & Guestbook", "siteguest");
            $links[]= new SiteAdminNavigatorLink("Site Policy", "sitepolicy");
            $links[]= new SiteAdminNavigatorLink("Banners", "sitebanner");
            $this->listvars['category'][]= new SiteAdminNavigatorCategory("Features &amp; Layout", $links);

            $links=array();
            $links[]= new SiteAdminNavigatorLink("Technical Setup", "sitetech");
            $links[]= new SiteAdminNavigatorLink("Database Utilities", "sitedb");
            $links[]= new SiteAdminNavigatorLink("Rebuild Indices", "siteind");
            $this->listvars['category'][]= new SiteAdminNavigatorCategory("Technical", $links);

            $links=array();
            $links[]= new SiteAdminNavigatorLink("Anti-Spam", "sitespam");
            $links[]= new SiteAdminNavigatorLink("Blocked Sites", "sitereferrers");
            $this->listvars['category'][]= new SiteAdminNavigatorCategory("Protection", $links);

            $links=array();
            $links[]= new SiteAdminNavigatorLink("User Management", "siteuserman");
            $links[]= new SiteAdminNavigatorLink("User Permissions", "siteuserperm");
            $links[]= new SiteAdminNavigatorLink("List Users", "siteuserlist");
            $links[]= new SiteAdminNavigatorLink("IP Ban", "siteipban");
        }
        $links[]= new SiteAdminNavigatorLink("Who's Online", "siteonline");
        $this->listvars['category'][]= new SiteAdminNavigatorCategory("Users", $links);
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/site/adminnavigator.tpl");
    }
}

?>
