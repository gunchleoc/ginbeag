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

require_once $projectroot."functions/pagecontent/articlepages.php";
require_once $projectroot."includes/objects/template.php";
require_once $projectroot."includes/objects/elements.php";
require_once $projectroot."includes/objects/images.php";
require_once $projectroot."includes/objects/page.php";
require_once $projectroot."includes/includes.php";

//
// Templating for Articlesections
//
class Articlesection extends Template
{

    function __construct($articlesection, $sectioncontents, $showhidden)
    {
        parent::__construct();

        if(strlen($sectioncontents['sectiontitle'])>0) {
            $this->stringvars['title'] =title2html($sectioncontents['sectiontitle']);
            $this->stringvars['sectionid'] =$articlesection;
            if (!Page::has_metadata('title')) {
                Page::set_metadata('title', $sectioncontents['sectiontitle']);
            }
        }

        if (!empty($sectioncontents['image_filename'])) {
            $this->vars['image'] = new CaptionedImage($sectioncontents, array("page" => $this->stringvars['page']), $showhidden);
            if (!Page::has_metadata('image')) {
                Page::set_metadata('image', $sectioncontents['image_filename']);
            }
        } else {
            if (!Page::has_metadata('image')) {
                Page::set_metadata('image', extract_image_from_text($sectioncontents['text']));
            }
            $this->stringvars['image']="";
        }

        if (!Page::has_metadata('description')) {
            Page::set_metadata('description', $sectioncontents['text']);
        }
        $this->stringvars['text']=text2html($sectioncontents['text']);
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("pages/article/articlesection.tpl");
    }
}



//
// main class for newspages
//
class ArticlePage extends Template
{
    function __construct($articlepage, $introcontents, $showhidden)
    {
        parent::__construct();

        $pagecontents=getarticlepagecontents($this->stringvars['page']);

        $linkparams["printview"]="on";
        $linkparams["page"]=$this->stringvars['page'];
        if(ismobile()) {
            $linkparams["m"] = "on";
            $this->stringvars['printviewbutton'] ='<a href="'.makelinkparameters($linkparams).'" title="'.getlang("pagemenu_printview").'" class="buttonlink">'.getlang("pagemenu_printview_short").'</a>';
        }
        else
        {
            $this->vars['printviewbutton']= new LinkButton(makelinkparameters($linkparams), getlang("pagemenu_printview"), "img/printview.png");
        }

        $this->stringvars['pagetitle'] = title2html($introcontents['title_page']);

        if(strlen($pagecontents['article_author'])>0) {
            $this->stringvars['article_author']=title2html($pagecontents['article_author']);
            $this->stringvars['l_author']=getlang('article_page_author');
        }

        if(strlen($pagecontents['location'])>0) {
            $this->stringvars['location']=title2html($pagecontents['location']);
        }

        $this->stringvars['date']=makearticledate($pagecontents['day'], $pagecontents['month'], $pagecontents['year']);

        if(strlen($pagecontents['sourcelink'])>0) {
              $this->stringvars['source_link']=$pagecontents['sourcelink'];
        }

        if(strlen($pagecontents['source'])>0) {
            $this->stringvars['source']=title2html($pagecontents['source']);
            $this->stringvars['l_source']=getlang("article_page_source");
        }

        if($articlepage==1) {
            $this->vars['pageintro'] = new PageIntro("", $introcontents['introtext'], "introtext", $introcontents, $showhidden);
        } else { $this->stringvars['pageintro'] = "";
        }

        // pagemenu
        if ($pagecontents['numberofpages'] > 1) {
            $this->vars['pagemenu'] = new Pagemenu($articlepage-1, 1, $pagecontents['numberofpages']);
        }

        if ($pagecontents['use_toc']) {
            $this->vars['toc']=new ArticleTOC($pagecontents['numberofpages']);
        } else {
            $this->stringvars['toc']="";
        }

        // get sections
        $articlesections=getarticlesections($this->stringvars['page'], $articlepage);
        foreach ($articlesections as $sectionid => $sectioncontents) {
            $this->listvars['articlesection'][] = new Articlesection($sectionid, $sectioncontents, $showhidden);
        }

        $this->vars['editdata']= new Editdata($introcontents, $showhidden);
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("pages/article/articlepage.tpl");
    }
}


//
// Table of Contents
//
class ArticleTOC extends Template
{
    function __construct($noofarticlepages)
    {
        parent::__construct();
        $this->stringvars['l_toc'] =getlang('article_page_toc');

        for ($i = 0; $i < $noofarticlepages; $i++) {
            // get items
            $allsectioncontents = getarticlesections($this->stringvars['page'], $i + 1);
            foreach ($allsectioncontents as $id => $contents) {
                $sectiontitle = $contents['sectiontitle'];
                if (!empty($sectiontitle)) {
                    $this->listvars['toc'][] = new ArticleTOCItem($id, $sectiontitle, $i);
                }
            }
        }
        if (empty($this->listvars['toc'])) {
            $this->stringvars['toc'] = "";
        }
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("pages/article/articletoc.tpl");
    }
}


//
// Table of Contents
//
class ArticleTOCItem extends Template
{
    function __construct($sectionid,$title,$offset)
    {
        parent::__construct();

        $linkparams["page"]=$this->stringvars['page'];
        $linkparams["offset"]=$offset;
        $this->stringvars['link']=makelinkparameters($linkparams)."#section".$sectionid;

        $this->stringvars['title'] =title2html($title);
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("pages/article/articletocitem.tpl");
    }
}





//
// Templating for Articlesections
//
class ArticlesectionPrintview extends Template
{

    function __construct($sectioncontents) {
        parent::__construct();

        if(strlen($sectioncontents['sectiontitle'])>0) {
            $this->stringvars['title'] =title2html($sectioncontents['sectiontitle']);
        }

        $this->stringvars['image']="";

        if (!empty($sectioncontents['image_filename'])) {
            $this->vars['image'] = new CaptionedImage($sectioncontents, array("page" => $this->stringvars['page']), false);
        } else {
            $this->stringvars['image']="";
        }

          $this->stringvars['text']=text2html($sectioncontents['text']);
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("pages/article/articlesection.tpl");
    }
}



//
// main class for newspages
//
class ArticlePagePrintview extends Template
{
    function __construct($introcontents)
    {
        parent::__construct();

        $this->vars['pageintro'] = new PageIntro("", $introcontents['introtext'], "introtext", $introcontents, false);

        $pagecontents=getarticlepagecontents($this->stringvars['page']);

        $this->stringvars['pagetitle']=title2html($introcontents['title_page']);

        if(strlen($pagecontents['article_author'])>0) {
            $this->stringvars['article_author']=title2html($pagecontents['article_author']);
            $this->stringvars['l_author']=getlang('article_page_author');
        }

        if (!empty($pagecontents['location'])) {
            $this->stringvars['location']=title2html($pagecontents['location']);
        }
        $this->stringvars['date']=makearticledate($pagecontents['day'], $pagecontents['month'], $pagecontents['year']);

        if(strlen($pagecontents['sourcelink'])>0) {
              $this->stringvars['source_link']=$pagecontents['sourcelink'];
        }

        if(strlen($pagecontents['source'])>0) {
            $this->stringvars['source']=title2html($pagecontents['source']);
            $this->stringvars['l_source']=getlang("article_page_source");
        }

        if($pagecontents['use_toc']) {
            $this->vars['toc']=new ArticleTOC($pagecontents['numberofpages']);
        } else {
            $this->stringvars['toc']="";
        }


        // get items
        $articlesections=getallarticlesections($this->stringvars['page']);
        foreach ($articlesections as $sectioncontents) {
            $this->listvars['articlesection'][] = new ArticlesectionPrintview($sectioncontents);
        }

        $this->vars['editdata']= new Editdata($introcontents);

    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("pages/article/articlepage.tpl");
    }
}

?>
