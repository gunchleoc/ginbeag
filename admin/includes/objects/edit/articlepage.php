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

require_once $projectroot."functions/pagecontent/articlepages.php";
require_once $projectroot."includes/objects/articlepage.php";
require_once $projectroot."includes/objects/template.php";
require_once $projectroot."includes/objects/categories.php";
require_once $projectroot."admin/includes/objects/editor.php";
require_once $projectroot."admin/includes/objects/imageeditor.php";
require_once $projectroot."functions/phpcompatibility.php";
require_once $projectroot."admin/functions/pagesmod.php";

//
//
//
class ArticlePageButton extends Template
{
    function __construct($articlepage)
    {
        parent::__construct();
        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["articlepage"] = $this->stringvars['articlepage'];
        $linkparams["action"] = "editcontents";
        $this->stringvars['actionvars']= makelinkparameters($linkparams);
        $this->stringvars['articlepage']=$articlepage;
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/edit/articlepagebutton.tpl");
    }
}


//
//
//
class EditArticle extends Template
{
    function __construct($page)
    {
        parent::__construct($page, array(), array('admin/includes/javascript/editarticle.js'));
        $this->stringvars['javascript']=$this->getScripts();
        $this->stringvars['hiddenvars'] = $this->makehiddenvars();

        $contents=getarticlepagecontents($page);

        $this->vars['synopsiseditor'] = new Editor($page, 0, "pageintro", "Synopsis Text");
        $this->vars['imageeditor'] = new ImageEditor($page, 0, "pageintro", getpageintroimage($page));

        $this->stringvars['author']= input2html($contents['article_author']);
        $this->stringvars['location']= input2html($contents['location']);
        $this->stringvars['source']= input2html($contents['source']);
        $this->stringvars['sourcelink']= $contents['sourcelink'];
        $this->vars['dayform']= new DayOptionForm($contents['day'], true, $this->stringvars['jsid']);
        $this->vars['monthform']= new MonthOptionForm($contents['month'], true, $this->stringvars['jsid']);
        $this->stringvars['year']=$contents['year'];

        $this->vars['toc_yes'] = new RadioButtonForm($this->stringvars['jsid'], "toc", "yes", "Yes", $contents['use_toc'], "right");
        $this->vars['toc_no'] = new RadioButtonForm($this->stringvars['jsid'], "toc", "no", "No", !$contents['use_toc'], "right");

        $this->vars['categorylist']=new Categorylist(getcategoriesforpage($page), CATEGORY_ARTICLE);
        $this->vars['categoryselection']= new CategorySelectionForm(true, $this->stringvars['jsid'], CATEGORY_ARTICLE);
        $this->vars['navigationbuttons']= new PageEditNavigationButtons(new GeneralSettingsButton(), new EditPageContentsButton());
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/edit/editarticle.tpl");
    }
}



//
//
//
class ArticleSectionForm extends Template
{
    function __construct($articlepage, $articlesection, $contents, $moveup, $movedown) {
        parent::__construct($articlesection, array(), array(0 => "admin/includes/javascript/editarticlepage.js"));
        $this->stringvars['javascript']=$this->getScripts();

        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["articlepage"] = $articlepage;
        $linkparams["articlesection"] = $articlesection;
        $linkparams["action"] = "editcontents";
        $this->stringvars['actionvars']= makelinkparameters($linkparams)."#section".$articlesection;

        $this->stringvars['hiddenvars'] = $this->makehiddenvars(array("articlesection" => $articlesection));

        $this->stringvars['articlesection']=$articlesection;

        $this->stringvars['moveup']=$moveup;
        $this->stringvars['movedown']=$movedown;


        if (!empty($contents['sectiontitle'])) {
            $this->stringvars['sectionheader'] = title2html($contents['sectiontitle']);
        } else {
            $this->stringvars['sectionheader'] = "Section ID $articlesection";
        }

        $this->stringvars['sectiontitle']=input2html($contents['sectiontitle']);

        $this->vars['sectioneditor'] = new Editor($this->stringvars['page'], $articlesection, "articlesection", "Section Text");

        $this->vars['imageeditor'] = new ImageEditor($this->stringvars['page'], $articlesection, "articlesection", $contents);
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/edit/articlesectionform.tpl");
    }
}



//
//
//
class EditArticlePage extends Template
{
    function __construct($articlepage)
    {
        parent::__construct($articlepage);
        $this->stringvars['javascript']=$this->getScripts();

        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["articlepage"] = $articlepage;
        $linkparams["action"] = "editcontents";
        $this->stringvars['actionvars'] = makelinkparameters($linkparams);

        $this->stringvars['articlepage']=$articlepage;


        $numberofarticlepages=numberofarticlepages($this->stringvars['page']);
        $this->vars['pagemenu']= new PageMenu($articlepage-1, 1, $numberofarticlepages, array("action" => "editcontents"));

        if($numberofarticlepages==$articlepage) {
            $this->stringvars['deletepage']="Delete This Page";
        }

        $articlesections=getarticlesections($this->stringvars['page'], $articlepage);

        $keys = array_keys($articlesections);
        $count = count($keys);
        for ($i = 0; $i < $count; $i++) {
            $sectioncontents = $articlesections[$keys[$i]];
            if ($i == 0 && $articlepage > 1) {
                $moveup="move section to previous page";
                $isfirst = false;
            } else {
                $moveup="move section up";
            }

            if ($i == $count - 1) {
                $movedown="move section to next page";
            } else {
                $movedown="move section down";
            }

            $this->listvars['articlesectionform'][] = new ArticleSectionForm($articlepage, $keys[$i], $sectioncontents, $moveup, $movedown);
        }

        $this->vars['navigationbuttons']= new PageEditNavigationButtons(new GeneralSettingsButton(), new EditPageIntroSettingsButton());
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/edit/editarticlepage.tpl");
    }
}



//
//
//
class DeleteArticleSectionConfirm extends Template
{
    function __construct($articlepage,$articlesection)
    {
        parent::__construct();

        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["articlepage"] = $articlepage;
        $linkparams["articlesection"] = $articlesection;
        $linkparams["action"] = "editcontents";
        $this->stringvars['actionvars']= makelinkparameters($linkparams);
        $this->vars['section'] = new Articlesection($articlesection, getarticlesectioncontents($articlesection), true);
        $this->vars['confirmbuttons'] = new CancelConfirmButtons($this->stringvars['actionvars'], "confirmdeletesection", "nodeletesection");
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/edit/deletearticlesectionconfirm.tpl");
    }
}
?>
