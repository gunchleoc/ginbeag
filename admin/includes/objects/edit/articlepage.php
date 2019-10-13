<?php
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



//
//
//
class ArticlePageButton extends Template
{
    function ArticlePageButton($articlepage)
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
    function EditArticle($page)
    {
        parent::__construct($page, array(0 => "includes/javascript/jcaret.js"), array(0 => "admin/includes/javascript/editarticle.js"));
        $this->stringvars['javascript']=$this->getScripts();
        $this->stringvars['hiddenvars'] = $this->makehiddenvars();

        $contents=getarticlepagecontents($page);

        $this->vars['synopsiseditor'] = new Editor($page, 0, "pageintro", "Synopsis Text");
        $this->vars['imageeditor'] = new ImageEditor($page, 0, "pageintro", getpageintro($page));

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
    function ArticleSectionForm($articlepage,$articlesection,$moveup="move section up",$movedown="move section down")
    {
        parent::__construct($articlesection, array(), array(0 => "admin/includes/javascript/editarticlepage.js"));
        $this->stringvars['javascript']=$this->getScripts();

        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["articlepage"] = $articlepage;
        $linkparams["articlesection"] = $articlesection;
        $linkparams["action"] = "editcontents";
        $this->stringvars['actionvars']= makelinkparameters($linkparams)."#section".$articlesection;

        $this->stringvars['hiddenvars'] = $this->makehiddenvars(array("articlesection" => $articlesection));

        $contents=getarticlesectioncontents($articlesection);

        $this->stringvars['articlesection']=$articlesection;

        $this->stringvars['moveup']=$moveup;
        $this->stringvars['movedown']=$movedown;


        if(strlen($contents['sectiontitle'])>0) {
            $this->stringvars['sectionheader']=title2html($contents['sectiontitle']);
        } else {
            $this->stringvars['sectionheader']="Section ID ".$articlesection;
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
    function EditArticlePage($articlepage)
    {
        parent::__construct($articlepage, array(0 => "includes/javascript/jcaret.js"));
        $this->stringvars['javascript']=$this->getScripts();

        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["articlepage"] = $articlepage;
        $linkparams["action"] = "editcontents";
        $this->stringvars['actionvars'] = makelinkparameters($linkparams);

        $this->stringvars['articlepage']=$articlepage;

        $articlesections=getarticlesections($this->stringvars['page'], $articlepage);

        $numberofarticlepages=numberofarticlepages($this->stringvars['page']);
        $this->vars['pagemenu']= new PageMenu($articlepage-1, 1, $numberofarticlepages, array("action" => "editcontents"));

        if($numberofarticlepages==$articlepage) {
            $this->stringvars['deletepage']="Delete This Page";
        }

        for($i=0;$i<count($articlesections);$i++)
        {
            if($i==0 && $articlepage>1) {
                $moveup="move section to previous page";
            } else {
                $moveup="move section up";
            }

            if(getarticlesectionnumber($articlesections[$i])==getlastarticlesection($this->stringvars['page'], $articlepage)) {
                $movedown="move section to next page";
            } else {
                $movedown="move section down";
            }

            $this->listvars['articlesectionform'][] = new ArticleSectionForm($articlepage, $articlesections[$i], $moveup, $movedown);
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
    function DeleteArticleSectionConfirm($articlepage,$articlesection)
    {
        parent::__construct();

        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["articlepage"] = $articlepage;
        $linkparams["articlesection"] = $articlesection;
        $linkparams["action"] = "editcontents";
        $this->stringvars['actionvars']= makelinkparameters($linkparams);

        $this->vars['section'] = new Articlesection($articlesection, $articlepage, true, true);
        $this->vars['confirmbuttons'] = new CancelConfirmButtons($this->stringvars['actionvars'], "confirmdeletesection", "nodeletesection");
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/edit/deletearticlesectionconfirm.tpl");
    }
}
?>
