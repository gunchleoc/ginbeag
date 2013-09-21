<?php
$projectroot=dirname(__FILE__);

// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."functions/pages.php");
include_once($projectroot."includes/templates/template.php");
include_once($projectroot."includes/includes.php");
include_once($projectroot."includes/templates/forms.php");
include_once($projectroot."includes/templates/page.php");
include_once($projectroot."includes/templates/articlepage.php");


//
//
//
class ArticlePageButton extends Template {
  function ArticlePageButton($page,$articlepage)
  {
    global $sid;

    $this->stringvars['sid']=$sid;
    $this->stringvars['page']=$page;
    $this->stringvars['articlepage']=$articlepage;

    $this->createTemplates();
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/articlepagebutton.tpl");
  }
}


//
//
//
class EditArticle extends Template {
  function EditArticle($page,$message="")
  {
    global $sid;
    
    $this->stringvars['sid']=$sid;
    $this->stringvars['page']=$page;

    $this->vars['header'] = new HTMLHeader("Editing article page contents","Webpage Building",title2html(getpagetitle($page)).'<br />'.$message);
    $this->vars['footer']=new HTMLFooter();

    $contents=getarticlepagecontents($page);

    $this->stringvars['author']= input2html($contents['article_author']);
    $this->stringvars['location']= input2html($contents['location']);
    $this->stringvars['source']= input2html($contents['source']);
    $this->stringvars['sourcelink']= $contents['sourcelink'];
    $this->vars['dayform']= new DayOptionForm($contents['day'],true);
    $this->vars['monthform']= new MonthOptionForm($contents['month'],true);
    $this->stringvars['year']=$contents['year'];

    $numberofpages=numberofarticlepages($page);

    $edittextbuttons = new EditTextButtons($page,$contents['synopsis'],"Edit Synopsis","articlesynopsis");
    $imageform = new ImagePropertiesForm($page,$contents['synopsisimage'],$contents['imagealign'],$contents['imagevalign'],"Synopsis","articlesynopsisimage");
    $this->vars['intro']= new TextWithImageForm("Synopsis",$edittextbuttons,$imageform);

    for($i=1;$i<=$numberofpages;$i++)
    {
     $this->listvars['articlepagebutton'][]= new ArticlePageButton($page,$i);
    }
    
    $this->stringvars['categorylist']=makecategorylist(getcategoriesforpage($page));
    $this->vars['categoryselection']= new CategorySelectionForm(true);
    $this->stringvars['backbuttons']=generalsettingsbuttons($page);

    $this->createTemplates();

  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/editarticle.tpl");
  }
}



//
//
//
class ArticleSectionForm extends Template {
  function ArticleSectionForm($page,$articlepage,$articlesection,$moveup="move section up",$movedown="move section down")
  {
    global $sid;
    
    $contents=getarticlesectioncontents($articlesection);

    $this->stringvars['sid']=$sid;
    $this->stringvars['page']=$page;
    $this->stringvars['articlepage']=$articlepage;
    $this->stringvars['articlesection']=$articlesection;
    
    $this->stringvars['moveup']=$moveup;
    $this->stringvars['movedown']=$movedown;


    if(strlen($contents['sectiontitle'])>0)
      $this->stringvars['sectionheader']=title2html($contents['sectiontitle']);
    else
      $this->stringvars['sectionheader']="Section ID ".$articlesection;
      
    $this->stringvars['sectiontitle']=title2html($contents['sectiontitle']);
    $edittextbuttons = new EditTextButtons($page,$contents['text'],"Edit Text","articlesection",$articlesection,$articlepage,"",'section'.$articlesection);
    $imageform = new ImagePropertiesForm($page,$contents['sectionimage'],$contents['imagealign'],$contents['imagevalign'],"Section","editsectionimage",'&articlesection='.$articlesections[$i].'&articlepage='.$articlepage,'section'.$articlesection);
    $this->vars['sectioncontents']= new TextWithImageForm("Synopsis",$edittextbuttons,$imageform);

    $this->createTemplates();

  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/articlesectionform.tpl");
  }
}



//
//
//
class EditArticlePage extends Template {
  function EditArticlePage($page,$articlepage,$message="")
  {
    global $sid;

    $this->stringvars['sid']=$sid;
    $this->stringvars['page']=$page;
    $this->stringvars['articlepage']=$articlepage;

    $this->vars['header'] = new HTMLHeader('Editing page '.$articlepage.' of article',"Webpage Building",title2html(getpagetitle($page)).'<br />'.$message);
    $this->vars['footer']=new HTMLFooter();

    $articlesections=getarticlesections($page,$articlepage);

    $numberofarticlepages=numberofarticlepages($page);
    $this->vars['pagemenu']= new PageMenu($articlepage-1,1,$numberofarticlepages,'action=editcontents',$page);

    if($numberofarticlepages==$articlepage)
    {
      $this->stringvars['deletepage']="Delete This Page";
    }

    for($i=0;$i<count($articlesections);$i++)
    {
      if($i==0 && $articlepage>1)
        $moveup="move section to previous page";
      else
        $moveup="move section up";

      if(getarticlesectionnumber($articlesections[$i])==getlastarticlesection($page,$articlepage))
        $movedown="move section to next page";
      else
        $movedown="move section down";

      $this->listvars['articlesectionform'][] = new ArticleSectionForm($page,$articlepage,$articlesections[$i],$moveup,$movedown);
    }
    
    $this->stringvars['backbuttons']=editcontentsbuttons($page,"Back");

    $this->createTemplates();
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/editarticlepage.tpl");
  }
}



//
//
//
class DeleteArticleSectionConfirm extends Template {
  function DeleteArticleSectionConfirm($page,$articlepage,$articlesection)
  {
    global $sid;

    $this->stringvars['sid']=$sid;
    $this->stringvars['page']=$page;
    $this->stringvars['articlepage']=$articlepage;
    $this->stringvars['articlesection']=$articlesection;

    $this->vars['header'] = new HTMLHeader("Deleting article section","Webpage Building",title2html(getpagetitle($page)));
    $this->vars['footer']=new HTMLFooter();
    
    $this->vars['section'] = new Articlesection($articlesection,$page,$articlepage,true,true);

    $this->createTemplates();
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/deletearticlesectionconfirm.tpl");
  }
}
?>
