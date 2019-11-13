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

require_once $projectroot."functions/pagecontent/linklistpages.php";
require_once $projectroot."functions/pagecontent/externalpages.php";
require_once $projectroot."functions/pagecontent/menupages.php";
require_once $projectroot."functions/pagecontent/pagecache.php";
require_once $projectroot."functions/pages.php";
require_once $projectroot."functions/referrers.php";
require_once $projectroot."functions/banners.php";
require_once $projectroot."functions/treefunctions.php";
require_once $projectroot."functions/variables.php";
require_once $projectroot."includes/objects/template.php";
require_once $projectroot."functions/images.php";
require_once $projectroot."includes/includes.php";

require_once $projectroot."includes/objects/forms.php";
require_once $projectroot."includes/objects/elements.php";
require_once $projectroot."includes/objects/categories.php";
require_once $projectroot."includes/objects/images.php";

//
// container for editdata
//
class Editdata extends Template
{

    function __construct($contents, $showhidden=false)
    {
        parent::__construct();
        $editdate = formatdatetime($contents['editdate']);

        if ($showhidden) {
            $editor = getdisplayname($contents['editor_id']);
            $this->stringvars['footerlastedited']
                = sprintf(getlang("footer_lasteditedauthor"), $editdate, $editor);
        } else {
            $this->stringvars['footerlastedited']
                = sprintf(getlang("footer_lastedited"), $editdate);
        }

        $permissions = makecopyright($contents);
        if (!empty($permissions)) {
            $this->stringvars['copyright'] = $permissions;
        }
        $this->stringvars['topofthispage'] = getlang("pagemenu_topofthispage");
    }

    // assigns templates
    function createTemplates()
    {
        if (ismobile()) {
            $this->addTemplate("mobile/editdata.tpl");
        } else {
            $this->addTemplate("pages/editdata.tpl");
        }
    }
}


//
// Templating for Banners
//
class Banner extends Template
{

    function __construct($contents)
    {
        global $projectroot;
        parent::__construct();

        if (!empty($contents['header'])) {
            $this->stringvars['header'] = title2html($contents['header']);
        }
        if (empty($contents['code'])) {
            $this->stringvars['link'] = $contents['link'];
            $this->stringvars['image'] = getbannerlinkpath($contents['image']);
            $dimensions = getimagedimensions($projectroot . "img/banners/" . $contents['image']);
            $this->stringvars['width'] = $dimensions["width"];
            $this->stringvars['height'] = $dimensions["height"];
            $this->stringvars['description'] = title2html($contents['description']);
        } else {
            $this->stringvars['complete_banner'] = stripslashes($contents['code']);
        }
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("banner.tpl");
    }
}


//
// Templating for Banners
//
class BannerList extends Template
{

    function __construct()
    {
        parent::__construct();

        $banners = getbanners();
        foreach ($banners as $id => $contents) {
            if (isbannercomplete($contents)) {
                $this->listvars['banner'][] = new Banner($contents);
            }
        }
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("bannerlist.tpl");
    }
}


//
// Templating for Navigator
//
class NavigatorLink extends Template
{

    var $style="";

    function __construct($page,$style="simple", $level=0, $speciallink="" ,$showhidden=false)
    {
        global $_GET;
        $this->style=$style;
        if($level==0) { $class="navtitle";
        } else { $class="navlink";
        }

        // layout parameters
        $this->stringvars['link_class']=$class;
        $this->stringvars['title_class']="";

        parent::__construct();

        $linkparams = array();
        if(ismobile()) { $linkparams["m"] = "on";
        }

        // for special pages like, contact, guestbook etc
        if($page==0) {
            if($speciallink==="guestbook") {
                $this->stringvars['linktooltip']=getlang("navigator_guestbook");
                $this->stringvars['title']=getlang("navigator_guestbook");
                $this->stringvars['link']=getprojectrootlinkpath()."guestbook.php".makelinkparameters($linkparams);
                $this->stringvars['link_attributes']='';
                if(basename($_SERVER['PHP_SELF'])==="guestbook.php") {
                    $this->stringvars['title_class']="navhighlight";
                }
            }
            elseif($speciallink==="contact") {
                $this->stringvars['linktooltip']=getlang("navigator_contact");
                $this->stringvars['title']=getlang("navigator_contact");
                $this->stringvars['link']=getprojectrootlinkpath()."contact.php".makelinkparameters($linkparams);
                $this->stringvars['link_attributes']='';
                if(basename($_SERVER['PHP_SELF'])==="contact.php") {
                    $this->stringvars['title_class']="navhighlight";
                }
            }
            elseif($speciallink==="sitemap") {
                $this->stringvars['linktooltip']=getlang("navigator_sitemap");
                $this->stringvars['title']=getlang("navigator_sitemap");
                $linkparams["page"] = "0";
                $linkparams["sitemap"] = "on";
                $this->stringvars['link']=getprojectrootlinkpath()."index.php".makelinkparameters($linkparams);
                $this->stringvars['link_attributes']='';
                if(isset($_GET['sitemap'])) {
                    $this->stringvars['title_class']="navhighlight";
                }
            }
            elseif($speciallink==="home") {
                $this->stringvars['linktooltip']=getlang("navigator_home");
                $this->stringvars['title']=getlang("navigator_home");
                $this->stringvars['link']=getprojectrootlinkpath().makelinkparameters($linkparams);
                $this->stringvars['link_attributes']='';
            }
            else
            {
                $this->stringvars['linktooltip']=getlang("navigator_notfound");
                $this->stringvars['title']=getlang("navigator_notfound");
                $this->stringvars['link']=$linkparams;
                $this->stringvars['link_class']=$class;
                $this->stringvars['link_attributes']='';
            }
        }
        // for normal pages
        else
        {
            $this->pagetype=getpagetype($page);

            $this->stringvars['linktooltip']=striptitletags(getpagetitle($page));

            if($this->style=="splashpage") { $this->stringvars['title']= title2html(str_replace(" ", "&nbsp;", getnavtitle($page)));
            } else { $this->stringvars['title']=title2html(getnavtitle($page));
            }

            if($showhidden) {
                if(isthisexactpagerestricted($page)) { $this->stringvars['title']=$this->stringvars['title'].' (R)';
                }
                if(!ispublished($page)) { $this->stringvars['title']='<i>'.$this->stringvars['title'].'</i>';
                }
            }

            if($this->pagetype==="external") {
                $this->stringvars['link']=getexternallink($page);
                if(str_startswith($this->stringvars['link'], getprojectrootlinkpath())
                    || str_startswith($this->stringvars['link'], "?")
                    || str_startswith($this->stringvars['link'], "index.php")
                ) {
                    $this->stringvars['link_attributes']='';
                }
                else
                {
                    $this->stringvars['link_attributes']=' target="_blank"';
                }
            }
            else
            {
                if($showhidden) { $path=getprojectrootlinkpath()."admin/pagedisplay.php";
                } else { $path=getprojectrootlinkpath()."index.php";
                }
                $linkparams["page"] = $page;
                $this->stringvars['link']=$path.makelinkparameters($linkparams);
                $this->stringvars['link_attributes']="";
            }

            if(isset($_GET['page']) && $_GET['page']==$page) { $this->stringvars['title_class']="navhighlight";
            } else { $this->stringvars['title_class']="";
            }
        }

        //$this->stringvars['title']= "XXXXXXXXXXX test".$level." ------ ".$this->stringvars['title'];
    }

    // assigns templates
    function createTemplates()
    {
        if($this->style=="splashpage") {
            if(ismobile()) { $this->addTemplate("mobile/navigatorlinksplashpage.tpl");
            } else { $this->addTemplate("navigator/navigatorlinksplashpage.tpl");
            }
        }
        elseif($this->style=="printview") {
            $this->addTemplate("navigator/navigatorlinkprintview.tpl");
        } elseif($this->stringvars['title_class']=="navhighlight") {
            $this->addTemplate("navigator/navigatornolink.tpl");
        } else {
            $this->addTemplate("navigator/navigatorlink.tpl");
        }
    }
}

//
// Templating for Navigator
// iterate over branch and create links
//
class NavigatorBranch extends Template
{

    var $style="";

    function __construct($page,$style="simple",$depth,$level=0,$speciallink="",$showhidden=false)
    {
        $this->style=$style;
        parent::__construct();

        if($level==0) { $this->stringvars['wrapper_class'] = "navrootlinkwrapper";
        } else { $this->stringvars['wrapper_class'] = "navlinkwrapper";
        }

        if(hasaccesssession($page) || $showhidden) {
            $this->listvars['link'][]= new NavigatorLink($page, $style, $level, $speciallink, $showhidden);
        }

        $this->stringvars['margin_left']=$level;

        if($depth>0) {
            $pages=getchildren($page);
            for($i=0;$i<count($pages);$i++)
            {
                if(displaylinksforpage($pages[$i]) || $showhidden) {
                    $this->listvars['link'][]= new NavigatorBranch($pages[$i], $style, $depth-1, $level+1, $speciallink, $showhidden);
                }
            }
        }
    }

    // assigns templates
    function createTemplates()
    {
        if($this->style=="splashpage") {
            if(ismobile()) { $this->addTemplate("mobile/navigatorbranchsplashpage.tpl");
            } else { $this->addTemplate("navigator/navigatorbranchsplashpage.tpl");
            }
        }
        elseif($this->style=="printview") {
            $this->addTemplate("navigator/navigatorbranchprintview.tpl");
        } else {
            $this->addTemplate("navigator/navigatorbranch.tpl");
        }
    }
}




//
// Templating for Navigator
// todo remove global GET?
//
class Navigator extends Template
{

    var $displaytype;

    function __construct($page,$sistersinnavigator,$depth,$displaytype="page",$showhidden=false)
    {
        $this->displaytype=$displaytype;
        parent::__construct();

        $linkparams = "";
        if(ismobile()) {
            $linkparams = makelinkparameters(array("m" => "on"));
        }

        if($displaytype=="splashpage") {

            $linksonsplashpage=explode(",", getproperty('Links on Splash Page'));
            if(!getproperty('Show All Links on Splash Page') && $linksonsplashpage[0]) {
                $roots=$linksonsplashpage;
            }
            else
            {
                $roots=getrootpages();
            }
            while(count($roots))
            {
                $currentroot=array_shift($roots);
                if(displaylinksforpage($currentroot) || $showhidden) {
                    $this->listvars['link'][]=new NavigatorBranch($currentroot, $displaytype, 0, 0, "", $showhidden);
                }
            }
        }
        elseif($displaytype=="printview") {
            $this->stringvars['sitename']=title2html(getproperty("Site Name"));
            $this->stringvars['home_link']=getprojectrootlinkpath().'index.php'.$linkparams;

            // get parent chain
            $parentpages=array();
            $level=0;
            $currentpage=$page;
            while(!isrootpage($currentpage))
            {
                $parent = getparent($currentpage);
                array_push($parentpages, $parent);
                $currentpage=$parent;
                $level++;
            }
            // display parent chain
            $navdepth=count($parentpages); // for closing table tags
            for($i=0;$i<$navdepth;$i++)
            {
                $parentpage=array_pop($parentpages);
                $this->listvars['link'][]=new NavigatorBranch($parentpage, "printview", 0, $i+1, "", $showhidden);
            }
            // display page
            $this->listvars['link'][]=new NavigatorBranch($page, "printview", $depth, 0, "", $showhidden);
        }
        else
        {
            $style="simple";
            $this->stringvars['home_link']=getprojectrootlinkpath().'index.php'.$linkparams;
            $this->stringvars['l_home']=getlang("navigator_home");

            // navigator
            if($page==0 || !ispageknown($page)) {
                $roots=getrootpages();
                while(count($roots))
                {
                    $currentroot=array_shift($roots);
                    if(displaylinksforpage($currentroot) || $showhidden) {
                        $this->listvars['link'][]=new NavigatorBranch($currentroot, $style, 0, 0, "", $showhidden);
                    }
                }
            }
            else
            {

                if(isrootpage($page)) {
                    $roots=getrootpages();
                    $currentroot=array_shift($roots);
                    $navposition=getnavposition($page);
                    // display upper root pages
                    while(getnavposition($currentroot)<$navposition)
                    {
                        if(displaylinksforpage($currentroot) || $showhidden) {
                            $this->listvars['link'][]=new NavigatorBranch($currentroot, $style, 0, 0, "", $showhidden);
                        }
                        $currentroot=array_shift($roots);
                    }
                    // display root page
                    $this->listvars['link'][]=new NavigatorBranch($page, $style, $depth, 0, "", $showhidden);
                }
                else
                {
                    // get parent chain
                    $parentpages=array();
                    $level=0;
                    $currentpage=$page;
                    while(!isrootpage($currentpage))
                    {
                        $parent = getparent($currentpage);
                        array_push($parentpages, $parent);
                        $currentpage=$parent;
                        $level++;
                    }
                    $parentroot=array_pop($parentpages);
                    $roots=getrootpages();
                    $currentroot=array_shift($roots);
                    $parentrootnavposition=getnavposition($parentroot);
                    // display upper root pages
                    while(getnavposition($currentroot)<$parentrootnavposition)
                    {
                        if(displaylinksforpage($currentroot) || $showhidden) {
                            $this->listvars['link'][]=new NavigatorBranch($currentroot, $style, 0, 0, "", $showhidden);
                        }
                        $currentroot=array_shift($roots);
                    }
                    if(displaylinksforpage($currentroot) || $showhidden) {
                        $this->listvars['link'][]=new NavigatorBranch($currentroot, $style, 0, 0, "", $showhidden);
                    }

                    // display parent chain
                    $navdepth=count($parentpages); // for closing table tags
                    for($i=0;$i<$navdepth;$i++)
                    {
                        $parentpage=array_pop($parentpages);
                        $this->listvars['link'][]=new NavigatorBranch($parentpage, $style, 0, $i+1, "", $showhidden);
                    }
                    // display page
                    if($sistersinnavigator) {
                        // get sisters then display 1 level only.
                        $sisterids=getsisters($page);
                        $currentsister=array_shift($sisterids);
                        $pagenavposition=getnavposition($page);
                        // display upper sister pages
                        while(getnavposition($currentsister)<$pagenavposition)
                        {
                            if(displaylinksforpage($currentsister) || $showhidden) {
                                $this->listvars['link'][]=new NavigatorBranch($currentsister, $style, 0, $level, "", $showhidden);
                            }
                            $currentsister=array_shift($sisterids);
                        }
                        // display page
                        $this->listvars['link'][]=new NavigatorBranch($page, $style, $depth, $level, "", $showhidden);

                        // display lower sister pages
                        while(count($sisterids))
                        {
                            $currentsister=array_shift($sisterids);
                            if(displaylinksforpage($currentsister) || $showhidden) {
                                $this->listvars['link'][]=new NavigatorBranch($currentsister, $style, 0, $level, "", $showhidden);
                            }
                        }
                    }
                    else
                    {
                        $this->listvars['link'][]=new NavigatorBranch($page, $style, $depth, 0, "", $showhidden);
                    }
                }
                // display lower root pages
                while(count($roots))
                {
                    $currentroot=array_shift($roots);
                    if(displaylinksforpage($currentroot) || $showhidden) {
                        $this->listvars['link'][]=new NavigatorBranch($currentroot, $style, 0, 0, "", $showhidden);
                    }
                }
            }
            // special links
            if(getproperty("Enable Guestbook")) {
                $this->listvars['link'][]=new NavigatorBranch(0, $style, 0, 0, "guestbook", $showhidden);
            }

            $this->listvars['link'][]=new NavigatorBranch(0, $style, 0, 0, "contact", $showhidden);
            $this->listvars['link'][]=new NavigatorBranch(0, $style, 0, 0, "sitemap", $showhidden);
        }
    }

    // assigns templates
    function createTemplates()
    {
        if($this->displaytype==="splashpage") {
            if(ismobile()) { $this->addTemplate("mobile/navigatorsplashpage.tpl");
            } else { $this->addTemplate("navigator/navigatorsplashpage.tpl");
            }
        }
        elseif($this->displaytype==="printview") {
            $this->addTemplate("navigator/navigatorprintview.tpl");
        } else {
            $this->addTemplate("navigator/navigator.tpl");
        }
    }
}





//
// Templating for Pictuer & Article of the Day
//
class ItemsOfTheDay extends Template
{

    function __construct($showhidden=false)
    {
        parent::__construct();

        if(getproperty('Display Picture of the Day')) {
            $potd=getpictureoftheday();
            if($potd) {
                $this->vars['potd_image']= new Image($potd, array('imageautoshrink' => true, 'usethumbnail' => true), array(), $showhidden);
                $this->stringvars['l_potd']=getlang("navigator_potd");
            }
        }
        if(getproperty('Display Article of the Day')) {
            $aotd=getarticleoftheday();
            if($aotd) {
                $linkparams = array();
                $linkparams["page"] = $aotd;
                if(ismobile()) {
                    $linkparams["m"] = "on";
                }
                $this->stringvars['aotd_link']=getprojectrootlinkpath().'index.php'.makelinkparameters($linkparams);
                $this->stringvars['l_aotd']=getlang("navigator_aotd");
            }
        }
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("pages/itemsoftheday.tpl");
    }
}


//
// intro/synopsis for all pages
//
class PageIntro extends Template
{

    function __construct($title, $text, $class = "introtext", $imagedata = array(), $showhidden=false)
    {
        parent::__construct();
        $this->stringvars['pagetitle']=title2html($title);
        $this->stringvars['text']=text2html($text);
        $this->stringvars['class']=$class;
        if (!empty($imagedata) && !empty($imagedata['image_filename'])) {
            $this->vars['image'] = new CaptionedImage($imagedata, array("page" => $this->stringvars['page']), $showhidden);
            if (!Page::has_metadata('image')) {
                Page::set_metadata('image', $imagedata['image_filename']);
            }
        } elseif (!Page::has_metadata('image')) {
            Page::set_metadata('image', extract_image_from_text($text));
        }
        if (!Page::has_metadata('description')) {
            Page::set_metadata('description', $text);
        }
        if (!Page::has_metadata('title')) {
            Page::set_metadata('title', $title);
        }
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("pages/pageintro.tpl");
    }
}


//
// page header for all pages
// message should be an object of type Message or a string
//
class PageHeader extends Template
{

    var $displaytype;

    function __construct($page, $title, $browsertitle, $meta_content = "", $displaytype = "page")
    {
        global $projectroot, $_GET, $_SERVER;
        $this->displaytype=$displaytype;
        parent::__construct();

        $linkparams = array("page" => $this->stringvars['page']);
        $linkparams["logout"] = "on";
        if(ismobile()) { $linkparams["m"] = "on";
        }

        $this->stringvars['logoutlink'] = makelinkparameters($linkparams);

        $linkparams = $_GET;
        if(ismobile()) {
            unset($linkparams["m"]);
            $this->stringvars['displaytypelink'] = "index.php".makelinkparameters($linkparams);
            $this->stringvars['l_displaytypelink'] = getlang("header_desktopstyle");

            if($displaytype != "splashpage") {
                $this->stringvars['l_showmenu'] = getlang("header_showmenu");
                $this->stringvars['l_hidemenu'] = getlang("header_hidemenu");
            }
        }
        else
        {
            $linkparams["m"] = "on";
            $this->stringvars['displaytypelink'] = makelinkparameters($linkparams);
            $this->stringvars['l_displaytypelink'] = getlang("header_mobilestyle");
        }


        $this->stringvars['meta_content'] = $meta_content;

        if(ismobile()) { $this->stringvars['stylesheet']= getCSSPath("mobile.css");
        } else { $this->stringvars['stylesheet']= getCSSPath("main.css");
        }

        $this->stringvars['stylesheetcolors']= getCSSPath("colors.css");
        $this->stringvars['sitename']=title2html(getproperty("Site Name"));
        $this->stringvars['browsertitle']=striptitletags($browsertitle);
        $this->stringvars['title']=title2html($title);

        if($displaytype!="splashpage") {
            $this->stringvars['site_description']=title2html(getproperty("Site Description"));
        } elseif(getproperty("Display Site Description on Splash Page")) {
            $this->stringvars['site_description']=title2html(getproperty("Site Description"));
        }

        $image=getproperty("Left Header Image");
        if(strlen($image)>0) {
            $this->stringvars['left_image']=getprojectrootlinkpath().'img/'.$image;
            $dimensions = getimagedimensions($projectroot."img/".$image);
            $this->stringvars['left_width'] = $dimensions["width"];
            $this->stringvars['left_height'] = $dimensions["height"];
        }

        $image=getproperty("Right Header Image");
        if(strlen($image)>0) {
            $this->stringvars['right_image']=getprojectrootlinkpath().'img/'.$image;
            $dimensions = getimagedimensions($projectroot."img/".$image);
            $this->stringvars['right_width'] = $dimensions["width"];
            $this->stringvars['right_height'] = $dimensions["height"];
        }

        $linkparams = array();
        if(ismobile()) { $linkparams["m"] = "on";
        }

        $link=getproperty("Left Header Link");
        if(strlen($link)>0) {
            $this->stringvars['left_link']=getprojectrootlinkpath().$link.makelinkparameters($linkparams);
        }

        $link=getproperty("Right Header Link");
        if(strlen($link)>0) {
            $this->stringvars['right_link']=getprojectrootlinkpath().$link.makelinkparameters($linkparams);
        }

        if(ispublicloggedin()) {
            $this->stringvars['logged_in']="logged in";
        }
    }

    // assigns templates
    function createTemplates()
    {
        if($this->displaytype=="splashpage") {
            if(ismobile()) { $this->addTemplate("mobile/splashpageheader.tpl");
            } else { $this->addTemplate("pages/splashpageheader.tpl");
            }
        }
        elseif(ismobile()) {
            $this->addTemplate("mobile/pageheader.tpl");
        } else {
            $this->addTemplate("pages/pageheader.tpl");
        }
    }
}


//
// page footer for all pages
//
class PageFooter extends Template
{

    function __construct()
    {
        parent::__construct();
        if(getproperty("Display Site Policy")) {
            $linkparams = array("page" => 0, "sitepolicy" => "on");
            if(ismobile()) { $linkparams["m"] = "on";
            }
            $this->stringvars['site_policy_link']=getprojectrootlinkpath().'index.php'.makelinkparameters($linkparams);
            $title=getproperty("Site Policy Title");
            if(strlen($title)>0) {
                $this->stringvars['site_policy_title']=title2html($title);
            }
        }

        $this->stringvars['footer_message']=text2html(getproperty("Footer Message"));
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("pages/pagefooter.tpl");
    }
}


//
// page footer for all pages
//
class Page extends Template
{

    private $displaytype;

    // Associative array of metadata
    static $metadata = array();

    function __construct($displaytype="page",$showhidden=false)
    {
        global $_SERVER, $_GET, $projectroot;

        $this->displaytype=$displaytype;

        parent::__construct();


        if (isset($_SERVER['HTTP_REFERER']) && isreferrerblocked($_SERVER['HTTP_REFERER'])) {
            // todo: simple header class
            $this->stringvars['header']="<html><head></head><body>";
            $this->stringvars['navigator']="";
            $this->stringvars['banners']="";
            $this->vars['message']=new Message("Sorry, this link to our page was not authorized.");
            $this->stringvars['contents']="";
        } else {
            if(!$showhidden) {
                if(ispagerestricted($this->stringvars['page'])) {
                    checkpublicsession($this->stringvars['page']);
                }
                updatepagestats($this->stringvars['page']);
            }
            $pagecontents = getpagecontents($this->stringvars['page']);

            // contents
            if(isset($_GET['newsitem'])) {
                include_once $projectroot."includes/objects/newspage.php";
                $this->vars['contents'] = new Newsitempage($_GET['newsitem'], $this->stringvars['page'], 0, false);
            } else {
                $this->makecontents($this->stringvars['page'], $pagecontents, $showhidden);
            }

            // header
            $this->makeheader($this->stringvars['page'], $pagecontents, $showhidden);

            // banners
            if (getproperty('Display Banners')) {
                $this->vars['banners']=new BannerList();
            } else {
                $this->stringvars['banners']="";
            }

            // navigator
            if ($pagecontents['pagetype'] === "menu" || $pagecontents['pagetype'] === "articlemenu" || $pagecontents['pagetype'] == "linklistmenu") {
                $displaysisters=getsisters($this->stringvars['page']);
                $navigatordepth=getmenunavigatordepth($this->stringvars['page']);
            } else {
                $displaysisters=1;
                $navigatordepth=2;
            }
            $this->vars['navigator'] = new Navigator($this->stringvars['page'], $displaysisters, $navigatordepth-1, $displaytype, $showhidden);
        }

        // area labels for screen readers
        $this->stringvars['l_navigator']=getlang("title_navigator");
        $this->stringvars['l_content']=getlang("title_content");

        // footer
        $this->vars['footer']= new PageFooter();
    }

    static function set_metadata($key, $value) {
        if (!empty($value)) {
            Page::$metadata[$key] = $value;
        }
    }
    static function has_metadata($key) {
        return isset(Page::$metadata[$key]);
    }

    //
    //
    //
    function makeheader($page, $pagecontents, $showhidden)
    {
        global $_GET, $projectroot;
        $title = "";
        $meta_title = "";
        $meta_content = "";

        if(!$showhidden) {
            if(ispagerestricted($page)) {
                checkpublicsession($page);
            }
            $meta_sitename = getproperty("Site Name");
            $meta_type = "website";
            $meta_description = "";

            if ($pagecontents['ispublished']) {
                $title = getmaintitle($pagecontents);
                $meta_title = $pagecontents['title_navigator'];
                $meta_type = "article";
            } elseif($this->displaytype=="splashpage") {
                $meta_description .= getproperty("Site Description")." ";
                $sql = new SQLSelectStatement(SPECIALTEXTS_TABLE, 'text', array('id'), array('splashpage1'), 's');
                $meta_description .= $sql->fetch_value() . " ";
                $sql = new SQLSelectStatement(SPECIALTEXTS_TABLE, 'text', array('id'), array('splashpage2'), 's');
                $meta_description .= $sql->fetch_value();
                $imagefile = $image=getproperty("Splash Page Image");
                $meta_title = $title;
            }

            elseif(isset($_GET["sitepolicy"])) {
                $title=getproperty("Site Policy Title");
                $meta_title = $title;
            } elseif(isset($_GET["sitemap"])) {
                $title=getlang("pagetitle_sitemap");
                $meta_title = $title;
            } else {
                $title=getlang("error_pagenotfound");
            }

            // Facebook
            if (Page::has_metadata('title')) {
                $meta_title .= ' - ' . striptitletags(Page::$metadata['title']);
                $meta_title = str_replace('"', "'", $meta_title);
            }
            $meta_content .= "\n    " . '<meta property="og:title" content="' . $meta_title . '" />';

            if (Page::has_metadata('description')) {
                $meta_description = substr(striptitletags(Page::$metadata['description']), 0, 300);
                $meta_description = str_replace('"', "'", $meta_description);
                // Facebook
                $meta_content .= "\n    " . '<meta property="og:description" content="' . $meta_description . '" />';
                // Google
                $keywords = "";
                if ($page > 0) {
                    $keywords .= title2html(implode(', ', getcategoriesforpage($page))) . ', ';
                }
                $keywords .= title2html(getproperty('Google Keywords'));
                if ($keywords) {
                    $meta_content .= "\n    " . '<meta name="keywords" content="'.$keywords.'">';
                    $meta_content .= "\n    " . '<meta name="description" content="'.$meta_description.' - '.$keywords.'" />';
                }
                else
                {
                    $meta_content .= "\n    " . '<meta name="description" content='.$meta_description.'" />';
                }
            }

            // Facebook
            if (Page::has_metadata('image')) {
                $imagefile = Page::$metadata['image'];
            } else {
                $imageurl = getproperty("Left Header Image");
                if (empty($imageurl)) {
                    $imageurl=getproperty("Right Header Image");
                }
                if (!empty($imageurl)) {
                    $imagefile = getprojectrootlinkpath() . 'img/' . $imageurl;
                }
            }
            if (!empty($imagefile)) {
                $imagedata = getimage(basename($imagefile));
                if (!empty($imagedata)) {
                    $imagefile = getimagelinkpath($imagedata['image_filename'], $imagedata['path']);
                }
                $meta_content .= "\n    " . '<meta property="og:image" content="' . $imagefile . '" />';
            }

            // Facebook
            $meta_content .= "\n    " . '<meta property="og:site_name" content="'.$meta_sitename.'" />';
            $meta_content .= "\n    " . '<meta property="og:type" content="'.$meta_type.'" />';

            $meta_url=getprojectrootlinkpath().'index.php'.makelinkparameters($_GET, false);
            $meta_content .= "\n    " . '<meta property="og:url" content="'.$meta_url.'" />';

            // Google
            $meta_content .= "\n    " . '<link rel="canonical" href="'.$meta_url.'" />';

            // JQuery is only needed for admin functions
            if ($showhidden) {
                $meta_content
                    .= "\n    " . '<script type="application/javascript" src="'
                    . getprojectrootlinkpath()
                    . 'includes/javascript/jquery.js"></script>';
            }
        }
        else
        {
            if(@strlen($page<1) || $page<0) {
                $title ="Welcome to the webpage editing panel";
            }
            else
            {
                $title="Displaying ".$pagecontents['pagetype']." page#".$page." - ".$pagecontents['title_navigator'];

                if($pagecontents['pagetype']==="external") {
                    $url=getexternallink($page);
                } else {
                    $url=getprojectrootlinkpath()."index.php".makelinkparameters($_GET);
                }

                $this->vars['message'] = new AdminPageDisplayMessage();
            }
        }

        $this->vars['header'] = new PageHeader($page, $title, $meta_title, $meta_content, $this->displaytype);
    }

    //
    //
    //
    function makecontents($page, $pagecontents, $showhidden)
    {
        global $_GET, $offset, $projectroot;

        // init
        if(isset($_GET['articlepage'])) {
            $articlepage=$_GET['articlepage'];
        } elseif(isset($_GET['offset'])) {
            $articlepage=$_GET['offset']+1;
        } elseif(!isset($_GET['articlepage']) || @strlen($_GET['articlepage'])<1) {
            $articlepage=1;
        } else { $articlepage=0;
        }

        if($this->displaytype=="splashpage") {
            $contents="";
            if(getproperty("Splash Page Font")==="italic") { $contents.='<i>';
            } elseif(getproperty("Splash Page Font")==="bold") { $contents.='<b>';
            }
            $sql = new SQLSelectStatement(SPECIALTEXTS_TABLE, 'text', array('id'), array('splashpage1'), 's');
            $text = $sql->fetch_value();

            if(strlen($text)>0) {
                $contents.='<p>'.$text.'</p><p>&nbsp;</p>';
            }
            $image=getproperty("Splash Page Image");
            if(strlen($image)>0) {
                $contents.='<p><img src="'.getprojectrootlinkpath().'img/'.$image.'" border="0" /></p><p>&nbsp;</p>';
            }
            $sql = new SQLSelectStatement(SPECIALTEXTS_TABLE, 'text', array('id'), array('splashpage2'), 's');
            $text = $sql->fetch_value();
            if(strlen($text)>0) {
                $contents.='<p>'.$text.'</p>';
            }
            if(getproperty("Splash Page Font")==="italic") { $contents.='</i>';
            } elseif(getproperty("Splash Page Font")==="bold") { $contents.='</b>';
            }
            $contents = text2html($contents);
            $this->stringvars['contents']=$contents;

            // bottom links
            $this->listvars['bottomlink'][]=new NavigatorBranch(0, $this->displaytype, 0, 0, "sitemap", $showhidden);
            if(getproperty("Enable Guestbook")) {
                $this->listvars['bottomlink'][]=new NavigatorBranch(0, $this->displaytype, 0, 0, "guestbook", $showhidden);
            }
            $this->listvars['bottomlink'][]=new NavigatorBranch(0, $this->displaytype, 0, 0, "contact", $showhidden);
            $this->vars["itemsoftheday"] = new ItemsOfTheDay($showhidden);
        }

        // reroute to guide for webpage editors
        elseif($showhidden && @strlen($page<1) || $page<0) {
            $messagetext='<table border="0" cellpadding="10" cellspacing="0" width="100%">';
            $messagetext.='<tr><td><p class="gen">Please check the <a href="http://www.noclockthing.de/minicms" target="_blank">';
            $messagetext.='Guide</a> to find your way around.</p>';
            $messagetext.='<p class="gen">This site needs JavaScript for some editing functions and cookies to keep the editing session.</p>';
            $messagetext.='<p class="highlight">Since login sessions can always be lost,';
            $messagetext.=' it can\'t hurt to copy the texts you\'re editing to your computer\'s clipboard';
            $messagetext.=' before pressing any buttons.</p>';
            $messagetext.='<p class="gen">Please stay away from the Technical Setup in the Administration section, unless you know what you\'re doing ;)</p>';
            $messagetext.='<p class="gen">Please log out when you leave</p></td></tr></table>';
            $this->stringvars['contents']=$messagetext;
        } else {
            // create page content
            if ($showhidden || ispublished($page)) {
                $offset = getintvariable('offset', $_GET);

                /*
                if(!$showhidden && !DEBUG)
                {
                $cached_page = getcachedpage($this->stringvars['page'], makelinkparameters($_GET));
                if($cached_page != "")
                {
                $this->stringvars['contents'] = $cached_page;
                }
                }
                */
                if (!isset($this->stringvars['contents'])) {
                    switch($pagecontents['pagetype']) {
                        case "article":
                            include_once $projectroot."includes/objects/articlepage.php";
                            $this->vars['contents'] = new ArticlePage($articlepage, $pagecontents, $showhidden);
                            break;
                        case "articlemenu":
                            include_once $projectroot."includes/objects/menupage.php";
                            $this->vars['contents'] = new ArticleMenuPage($page, $pagecontents, $showhidden);
                            break;
                        case "menu":
                        case "linklistmenu":
                            include_once $projectroot."includes/objects/menupage.php";
                            $this->vars['contents'] = new MenuPage($page, $pagecontents, $showhidden);
                            break;
                        case "external":
                            $this->stringvars['contents'] ='<a href="'.getexternallink($page).'" target="_blank">External page</a>';
                            break;
                        case "gallery":
                            include_once $projectroot."includes/objects/gallerypage.php";
                            $this->vars['contents'] = new GalleryPage($pagecontents, $offset, $showhidden);
                            break;
                        case "linklist":
                        include_once $projectroot."includes/objects/linklistpage.php";
                        $this->vars['contents']  = new LinklistPage($pagecontents, $showhidden);
                            break;
                        case "news":
                        include_once $projectroot."includes/objects/newspage.php";
                        $this->vars['contents']  = new NewsPage($page, $pagecontents, $offset, $showhidden);
                            break;
                    }

                    /*
                    if(!$showhidden)
                    {
                    makecachedpage($this->stringvars['page'], makelinkparameters($_GET), $this->vars['contents']->toHTML());
                    }
                    * */
                }
            }
            elseif(isset($_GET["sitepolicy"])) {
                $sql = new SQLSelectStatement(SPECIALTEXTS_TABLE, 'text', array('id'), array('sitepolicy'), 's');
                $this->vars['contents']  = new PageIntro(title2html(getproperty("Site Policy Title")), $sql->fetch_value(), "sectiontext");
            }
            elseif(isset($_GET["sitemap"])) {
                include_once $projectroot."includes/objects/sitemap.php";
                $this->vars['contents']  = new Sitemap($showhidden);
            }
            else
            {
                $this->vars['contents']  = new PageIntro(getlang("error_pagenotfound"), sprintf(getlang("error_pagenonotfound"), $page), "highlight");
            }
        }
    }

    // assigns templates
    function createTemplates()
    {
        if($this->displaytype=="splashpage") {
            if(ismobile()) { $this->addTemplate("mobile/splashpage.tpl");
            } else { $this->addTemplate("pages/splashpage.tpl");
            }
        }
        else {
            $this->addTemplate("pages/page.tpl");
        }
    }
}




//
//
//
class Printview extends Template
{

    function __construct($showhidden=false)
    {
        global $_SERVER, $_GET;
        parent::__construct();

        $pagecontents = getpagecontents($this->stringvars['page']);

        // header
        $this->makeheader($pagecontents);

        if(isset($_SERVER['HTTP_REFERER']) && isreferrerblocked($_SERVER['HTTP_REFERER'])) {
            // todo: simple header class
            $this->stringvars['header']="<html><head></head><body>";
            $this->stringvars['navigator']="";
            $this->stringvars['banners']="";
            $this->vars['message']=new Message("Sorry, this link to our page was not authorized.");
            $this->stringvars['contents']="";
        } else {
            if(!$showhidden) {
                if(ispagerestricted($this->stringvars['page'])) {
                    checkpublicsession($this->stringvars['page']);
                }
            }

            // contents
            $this->makecontents($pagecontents, $showhidden);

            // navigator
            $this->vars['navigator'] = new Navigator($this->stringvars['page'], 0, 0, "printview", false);
        }

        $this->stringvars['url']=getprojectrootlinkpath().makelinkparameters(array("page" => $this->stringvars['page']));
    }

    //
    //
    //
    function makeheader($pagecontents)
    {
        $title="";
        if(ispagerestricted($this->stringvars['page'])) {
            checkpublicsession($this->stringvars['page']);
        }
        if(ispublished($this->stringvars['page'])) {
            $title = $pagecontents['title_navigator'];
        }
        else
        {
            $title="Page not found";
        }
        $this->stringvars['site_name']=title2html(getproperty("Site Name"));
        $this->stringvars['header_title']=striptitletags($title);
        $this->stringvars['title'] =  title2html(getmaintitle($pagecontents));
        $this->stringvars['stylesheet'] = getCSSPath("printview.css");
    }

    //
    //
    //
    function makecontents($pagecontents)
    {
        global $projectroot, $_GET;

        if (ispublished($this->stringvars['page'])) {
            switch ($pagecontents['pagetype']) {
                case "article":
                    include_once $projectroot."includes/objects/articlepage.php";
                    $this->vars['contents'] = new ArticlePagePrintview($pagecontents);
                break;
                case "linklist":
                    include_once $projectroot."includes/objects/linklistpage.php";
                    $this->vars['contents']  = new LinklistPagePrintview($pagecontents, false);
                break;
                case "news":
                    include_once $projectroot."includes/objects/newspage.php";
                    $this->vars['contents']  = new Newsitem($_GET['newsitem'], getnewsitemcontents($_GET['newsitem']), false, false);
                break;
            }
        } else {
            $this->vars['contents'] = new PageIntro("Page not found", "Could not find page ".$this->stringvars['page'].".", "highlight");
        }
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("printview.tpl");
    }
}


//
// container for editdata
//
class AdminPageDisplayMessage extends Template
{

    function __construct()
    {
        global $_GET;
        parent::__construct();

        if(isset($_GET["show"])) { unset($_GET["show"]);
        }

        if(getpagetype($this->stringvars['page'])==="external") {
             $this->stringvars['publiclink']=getexternallink($this->stringvars['page']);
        }
        else
        {
            $this->stringvars['publiclink']=getprojectrootlinkpath()."index.php".makelinkparameters($_GET);
        }

        $this->stringvars['navtitle']= title2html(getnavtitle($this->stringvars['page']));
        $this->stringvars['editlink']=getprojectrootlinkpath()."admin/pageedit.php".makelinkparameters($_GET).'&page='.$this->stringvars['page'].'&action=edit';

        if(ispagerestricted($this->stringvars['page'])) {
            $this->stringvars['isrestricted']="true";
        }
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/pagedisplaymessage.tpl");
    }
}

?>
