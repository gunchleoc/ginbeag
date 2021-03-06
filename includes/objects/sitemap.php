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
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));

require_once $projectroot."functions/pages.php";
require_once $projectroot."includes/objects/template.php";
require_once $projectroot."includes/objects/elements.php";
require_once $projectroot."includes/objects/page.php";
require_once $projectroot."includes/includes.php";

//
// main class for sitemap
//
class Sitemap extends Template
{

    function __construct($showhidden=false)
    {
        parent::__construct();
        $this->vars['pageintro'] = new PageIntro(getlang("pagetitle_sitemap"), "");

        $children = getchildren_with_navinfo(0);
        foreach ($children as $subpageid => $subpageinfo) {
            if (displaylinksforpage($subpageid) || $showhidden) {
                $this->listvars['subpages'][]= new SitemapBranch($subpageid, $subpageinfo, 5, true, 0, "", $showhidden);
            }
        }
        // special links
        if(getproperty("Enable Guestbook")) {
            $this->listvars['subpages'][]=new SitemapBranch(0, array(), 0, true, 0, "guestbook", $showhidden);
        }

        $this->listvars['subpages'][]=new SitemapBranch(0, array(), 0, true, 0, "contact", $showhidden);
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("sitemap.tpl");
    }
}


//
// Templating for Navigator
//
class SitemapLink extends Template
{

    function __construct($page, $pageinfo, $level, $class, $speciallink, $showhidden) {
        parent::__construct();

        $linkparams = array();
        if (ismobile()) {
            $linkparams['m'] = 'on';
        }

        // layout parameters
        $this->stringvars['link_class'] = $class;
        $this->stringvars['title_class'] = '';

        if ($page == 0) {
            // Special pages like contact, guestbook etc
            switch ($speciallink) {
                case 'guestbook':
                    $this->stringvars['linktooltip'] = getlang('navigator_guestbook');
                    $this->stringvars['title'] = getlang('navigator_guestbook');
                    $this->stringvars['link'] = getprojectrootlinkpath() . 'guestbook.php' . makelinkparameters($linkparams);

                break;
                case 'contact':
                    $this->stringvars['linktooltip'] = getlang('navigator_contact');
                    $this->stringvars['title'] = getlang('navigator_contact');
                    $this->stringvars['link'] = getprojectrootlinkpath() . 'contact.php' . makelinkparameters($linkparams);
                break;
                default:
                    $this->stringvars['linktooltip'] = getlang('navigator_notfound');
                    $this->stringvars['title'] = getlang('navigator_notfound');
                    $this->stringvars['link'] = makelinkparameters($linkparams);
            }
            $this->stringvars['link_attributes'] = '';
            $this->stringvars['description'] = '';
        } else {
            // Normal pages
            $this->pagetype = $pageinfo['pagetype'];

            $this->stringvars['title'] = title2html($pageinfo['title_page']);
            $this->stringvars['linktooltip'] = striptitletags($pageinfo['title_page']);
            $this->stringvars['description'] = '';
            $this->stringvars['title_class'] = '';

            if ($showhidden) {
                if (isthisexactpagerestricted($page)) {
                    $this->stringvars['title'] .= ' (R)';
                }
                if (!ispublished($page)) {
                    $this->stringvars['title'] = '<i>' . $this->stringvars['title'] . '</i>';
                }
            }

            if ($this->pagetype === 'external') {
                $this->stringvars['link'] = getexternallink($page);
                if (str_startswith($this->stringvars['link'], getprojectrootlinkpath())
                    || str_startswith($this->stringvars['link'], '?')
                    || str_startswith($this->stringvars['link'], 'index.php')
                ) {
                    $this->stringvars['link_attributes']='';
                } else {
                    $this->stringvars['link_attributes']=' target="_blank"';
                }
            } else {
                if ($showhidden) {
                    $path = getprojectrootlinkpath() . 'admin/pagedisplay.php';
                } else {
                    $path = getprojectrootlinkpath() . 'index.php';
                }
                $linkparams['page'] = $page;
                $this->stringvars['link'] = $path . makelinkparameters($linkparams);
                $this->stringvars['link_attributes'] = '';
            }
        }
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("navigator/navigatorlink.tpl");
    }
}



//
// Templating for Navigator
// iterate over branch and create links
//
class SitemapBranch extends Template
{

    function __construct($page, $pageinfo, $depth, $startwithroot, $level, $speciallink, $showhidden) {
        parent::__construct();

        if ($startwithroot && $level==0) {
            $class = "contentnavtitle";
            $this->stringvars['wrapper_class'] = "contentnavrootlinkwrapper";
        } else {
            $class = "contentnavlink";
            $this->stringvars['wrapper_class'] = "contentnavlinkwrapper";
        }

        if (hasaccesssession($page) || $showhidden) {
            $this->listvars['link'][] = new SitemapLink($page, $pageinfo, $level, $class, $speciallink, $showhidden);
        }

        $this->stringvars['margin_left'] = $level;

        if ($depth > 0) {
            $children = getchildren_with_navinfo($page);
            foreach ($children as $subpageid => $subpageinfo) {
                if (displaylinksforpage($subpageid) || $showhidden) {
                    $this->listvars['link'][]= new SitemapBranch($subpageid, $subpageinfo, $depth-1, $startwithroot, $level+1, $speciallink, $showhidden);
                }
            }
        }
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("navigator/navigatorbranch.tpl");
    }
}
?>
