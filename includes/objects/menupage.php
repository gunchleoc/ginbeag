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

require_once $projectroot."functions/pagecontent/menupages.php";
require_once $projectroot."includes/objects/template.php";
require_once $projectroot."includes/objects/elements.php";
require_once $projectroot."includes/objects/images.php";
require_once $projectroot."includes/objects/page.php";
require_once $projectroot."includes/functions.php";
require_once $projectroot."includes/includes.php";

//
// main class for menu pages
//
class MenuPage extends Template
{

    var $pagetype="";

    function __construct($page, $introcontents, $showhidden=false)
    {
        parent::__construct();

        $pagecontents=getmenucontents($page);

        $this->vars['pageintro'] = new PageIntro($introcontents['title_page'], $introcontents['introtext'], "introtext", $introcontents, $showhidden);

        $this->pagetype = $introcontents['pagetype'];

        $this->stringvars['actionvars'] = makelinkparameters(array("page" => $this->stringvars['page']));

        $children = getchildren_with_navinfo($page);
        $displaydepth = $this->pagetype === "linklistmenu" ?
            (isset($pagecontents['displaydepth']) ? $pagecontents['displaydepth'] - 1 : 1) :
            $pagecontents['displaydepth'] - 1;

        $subpagepreviewdata = getpagepreviewdata(array_keys($children));
        foreach ($children as $subpageid => $subpageinfo) {
            if (displaylinksforpage($subpageid) || $showhidden) {
                $this->listvars['subpages'][]= new MenuNavigatorBranch($subpageid, $subpageinfo, $subpagepreviewdata, $displaydepth, 0, $showhidden);
            }
        }

        $this->vars['editdata']= new Editdata($introcontents,$showhidden);
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("pages/menu/menupage.tpl");
    }
}




//
// Templating for Navigator
//
class ArticleInfo extends Template
{
    function __construct($page, $article, $pageinfo, $contents)
    {
        parent::__construct();

        $articleinfo="";
        if($contents['article_author']) {
            $articleinfo.= 'By '.title2html($contents['article_author']);
        }
        if($contents['source']) {
            if($articleinfo) { $articleinfo.=', ';
            }
            $articleinfo.=title2html($contents['source']);
        }
        $date=makearticledate($contents['day'], $contents['month'], $contents['year']);
        if($date) {
            if($articleinfo) { $articleinfo.=', ';
            }
            $articleinfo.=$date;
        }

        $this->stringvars['articleinfo']=$articleinfo;
        $this->vars['categorylist']=new CategorylistLinks(getcategoriesforpage($article), $page, CATEGORY_ARTICLE);
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("pages/menu/articleinfo.tpl");
    }
}



//
// main class for newspages
//
class ArticleMenuPage extends Template
{

    var $pagetype="";

    function __construct($page, $introcontents, $showhidden=false)
    {
        global $_GET;
        parent::__construct();

        $pagecontents=getmenucontents($page);

        $this->pagetype = $introcontents['pagetype'];

        $linkparams = array("page" => $this->stringvars['page']);
        if(ismobile()) { $linkparams["m"] = "on";
        }
        $this->stringvars['actionvars'] = makelinkparameters($linkparams);
        $this->stringvars['hiddenvars'] = $this->makehiddenvars($linkparams);

        $this->vars['pageintro'] = new PageIntro($introcontents['title_page'], $introcontents['introtext'], "introtext", $introcontents, $showhidden);

        $this->stringvars['l_displayoptions']=getlang("menu_filter_displayoptions");
        $this->stringvars['l_categories']=getlang("menu_filter_categories");
        $this->stringvars['l_from']=getlang("menu_filter_from");
        $this->stringvars['l_to']=getlang("menu_filter_to");
        $this->stringvars['l_go']=getlang("menu_filter_go");
        $this->stringvars['l_orderby']=getlang("menu_filter_orderby");

        $filter=isset($_GET['filter']);
        if($filter) {
            $selectedcat=$_GET['selectedcat'];
            $from=$_GET['from'];
            $to=$_GET['to'];
            $order=$_GET['order'];
            $ascdesc=$_GET['ascdesc'];
            if (isset($_GET['subpages'])) { $subpages=$_GET['subpages'];
            } else { $subpages=false;
            }

            $this->makearticlefilterform($page, $selectedcat, $from, $to, $order, $ascdesc, $subpages);
            $children = $this->getfilteredarticles($page, $showhidden);
            $this->stringvars['l_showall']=getlang("article_filter_showall");
        }
        else
        {
            $this->makearticlefilterform($page);
            $children=getchildren_with_navinfo($page);
        }
        $subpagepreviewdata = getpagepreviewdata(array_keys($children));
        foreach ($children as $subpageid => $subpageinfo) {
            if (displaylinksforpage($subpageid) || $showhidden) {
                $this->listvars['subpages'][] = new MenuNavigatorBranch($subpageid, $subpageinfo, $subpagepreviewdata, $pagecontents['displaydepth']-1, 0, $showhidden);
            }
        }

        $this->vars['editdata']= new Editdata($introcontents, $showhidden);
    }

    // assigns templates
    function createTemplates()
    {
        if(ismobile()) { $this->addTemplate("mobile/articlemenupage.tpl");
        } else { $this->addTemplate("pages/menu/articlemenupage.tpl");
        }
    }


    //
    //
    //
    function makearticlefilterform($page,$selectedcat="",$from="",$to="",$order="",$ascdesc="",$subpages=false)
    {
        $this->vars["categoryselection"]= new CategorySelectionForm(false, "", CATEGORY_ARTICLE, 1, array($selectedcat => $selectedcat));

        $this->stringvars["l_timespan"]= getlang("menu_filter_timespan");
        $allyears=getallarticleyears();
        if($allyears[0]=="0000") { array_shift($allyears);
        }
        $values[0]="all";
        $descriptions[0]=getlang("article_filter_allyears");
        for($i=0;$i<count($allyears);$i++)
        {
            $values[$i+1]=$allyears[$i];
            $descriptions[$i+1]=$allyears[$i];
        }
        $this->vars["from_year"]=new OptionForm($from, $values, $descriptions, "from", getlang("menu_filter_from"));
        $this->vars["to_year"]=new OptionForm($to, $values, $descriptions, "to", getlang("menu_filter_to"));

        $this->vars["order"]= new ArticlemenuOrderSelectionForm($order);
        $this->vars["ascdesc"]= new AscDescSelectionForm($ascdesc!=="desc");
    }


    //
    //
    //
    function getfilteredarticles($page,$showhidden)
    {
        global $_GET;
        $result=array();

        $this->stringvars['l_clearsearch']=getlang("menu_filter_clearsearch");

        $selectedcat=$_GET['selectedcat'];
        $from=$_GET['from'];
        $to=$_GET['to'];
        $order=$_GET['order'];
        $ascdesc=$_GET['ascdesc'];

        if($from>$to) {
            $this->stringvars['message']=getlang("menu_filter_badyearselection");
        }
        else
        {
            $result=getfilteredarticles($page, $selectedcat, $from, $to, $order, $ascdesc, $showhidden);
            if(!count($result)) {
                $this->stringvars['message']=getlang("menu_filter_nomatch");
            }
            else
            {
                $this->stringvars['message']=getlang("menu_filter_result");
            }
        }
        return $result;
    }
}



//
// name may contain white spaces. They will be stripped for the form name,
// but left intact for display
//
class ArticlemenuOrderSelectionForm  extends Template
{

    function __construct($order="")
    {
        parent::__construct();

        $this->stringvars['optionform_name'] = "order";
        $this->stringvars['optionform_label'] = getlang("menu_filter_property");
        $this->stringvars['optionform_id'] ="order";

        $this->listvars['option'][]= new OptionFormOption("title", $order==="title", getlang("article_filter_title"));
        $this->listvars['option'][]= new OptionFormOption("author", $order==="author", getlang("article_filter_author"));
        $this->listvars['option'][]= new OptionFormOption("date", $order==="date", getlang("article_filter_date"));
        $this->listvars['option'][]= new OptionFormOption("source", $order==="source", getlang("article_filter_source"));
        $this->listvars['option'][]= new OptionFormOption("editdate", $order==="editdate", getlang("article_filter_changes"));
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("forms/optionform.tpl");
    }
}



//
// Templating for Linklist in Linklistmenu Navigator
//
class MenuLinkListLink extends Template
{

    function __construct($link, $contents)
    {
        parent::__construct();

        if(strlen($contents['link'])<=1) {
            $this->stringvars['link'] = makelinkparameters(array("page" => $this->stringvars['page']));
        }
        else
        {
            $this->stringvars['link']=$contents['link'];
        }
        $this->stringvars['title']=title2html($contents['title']);

        $text=text2html($contents['description']);
        $paragraphs=explode('<br />', $text);
        $text=$paragraphs[0];

        if (array_key_exists(1, $paragraphs)) { $text.=' <a href="'.makelinkparameters(array("page" => $contents['page_id'])).'#link'.$link.'">[...]</a>';
        }

        $this->stringvars['description']=$text;
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("pages/menu/menulinklistlink.tpl");
    }
}

//
// Templating for Linklist in Linklistmenu Navigator
//
class MenuLinkListBranch extends Template
{

    function __construct($links)
    {
        parent::__construct();

        foreach ($links as $id => $contents) {
            $this->listvars['link'][] = new MenuLinkListLink($id, $contents);
        }
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("pages/menu/menulinklistbranch.tpl");
    }
}



//
// Templating for Navigator
//
class MenuNavigatorLink extends Template
{

    function __construct($page, $pageinfo, $pagepreviewdata, $level, $showhidden) {
        parent::__construct();

        // layout parameters
        $this->stringvars['link_class'] = $level == 0 ?
            'contentnavtitle' :
            'contentnavlink';

        $this->stringvars['title']=title2html($pageinfo['title_page']);
        $this->stringvars['linktooltip']=striptitletags($pageinfo['title_page']);

        if ($showhidden) {
            if (isthisexactpagerestricted($page)) {
                $this->stringvars['title'] .= ' (R)';
            }
            if (!ispublished($page)) {
                $this->stringvars['title'] = '<i>' . $this->stringvars['title'] . '</i>';
            }
        }

        $pagetype = $pageinfo['pagetype'];

        if ($pagetype === "external") {
            $this->stringvars['link'] = getexternallink($page);
            if (str_startswith($this->stringvars['link'], getprojectrootlinkpath())
                || str_startswith($this->stringvars['link'], '?')
                || str_startswith($this->stringvars['link'], 'index.php')
            ) {
                $this->stringvars['link_attributes'] = '';
            } else {
                $this->stringvars['link_attributes'] = ' target="_blank"';
            }
            $this->stringvars['description'] = '';
        } else {
            if ($pagetype === "article") {
                $this->vars['description'] = new ArticleInfo($this->stringvars['page'], $page, $pageinfo, $pagepreviewdata[$page]);
            } elseif ($pagetype === "linklist") {
                $links = getlinklistitems($page);
                if (!empty($links)) {
                    $this->vars['description'] = new MenuLinkListBranch($links);
                } else {
                    $this->stringvars['description'] = '';
                }
            } else {
                $this->stringvars['description'] = '';
            }
            if ($showhidden) {
                $path=getprojectrootlinkpath() . 'admin/pagedisplay.php';
            } else {
                $path=getprojectrootlinkpath() . 'index.php';
            }

            $linkparams['page'] = $page;
            if (ismobile()) {
                $linkparams['m'] = 'on';
            }
            $this->stringvars['link'] = $path . makelinkparameters($linkparams);
            $this->stringvars['link_attributes'] = '';
        }
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("pages/menu/menunavigatorlink.tpl");
    }
}



//
// Templating for Navigator
// iterate over branch and create links
//
class MenuNavigatorBranch extends Template
{
    function __construct($page, $pageinfo, $pagepreviewdata, $depth, $level, $showhidden) {
        parent::__construct();

        $this->stringvars['wrapper_class'] = $level == 0 ?
            'contentnavrootlinkwrapper' :
            'contentnavlinkwrapper';

        if (hasaccesssession($page) || $showhidden) {
            $this->listvars['link'][] = new MenuNavigatorLink($page, $pageinfo, $pagepreviewdata, $level, $showhidden);
        }

        $this->stringvars['margin_left']=$level;

        if ($depth>0) {
            $children = getchildren_with_navinfo($page);
            $subpagepreviewdata = getpagepreviewdata(array_keys($children));
            foreach ($children as $subpageid => $subpageinfo) {
                if (displaylinksforpage($subpageid) || $showhidden) {
                    $this->listvars['link'][] = new MenuNavigatorBranch($subpageid, $subpageinfo, $subpagepreviewdata, $depth-1, $level+1, $showhidden);
                }
            }
        }
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("pages/menu/menunavigatorbranch.tpl");
    }
}
?>
