<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));

include_once($projectroot."functions/pages.php");
include_once($projectroot."includes/templates/template.php");
include_once($projectroot."includes/templates/elements.php");
include_once($projectroot."includes/includes.php");

//
// Templating for Articlesections
//
class Articlesection extends Template {

    function Articlesection($articlesection_id,$page,$articlepage,$showrefused,$showhidden) {

      $sectioncontents=getarticlesectioncontents($articlesection_id);

      if(strlen($sectioncontents['sectiontitle'])>0)
        $this->stringvars['title'] =title2html($sectioncontents['sectiontitle']);

      if(strlen($sectioncontents['sectionimage'])>0 && mayshowimage($sectioncontents['sectionimage'],$page,$showhidden))
        $this->vars['image'] = new CaptionedImage($sectioncontents['sectionimage'],2,$showrefused,$showhidden);
      else $this->stringvars['no_image']="no_image";

      if($sectioncontents['imagealign']==="right")
        $this->stringvars['image_align_right'] ="right";
      elseif($sectioncontents['imagealign']==="center")
        $this->stringvars['image_align_center'] ="center";
      else
        $this->stringvars['image_align_left'] ="left";

      if($sectioncontents['imagevalign']==="bottom")
        $this->stringvars['image_valign_bottom'] ="bottom";
      else
        $this->stringvars['image_valign_top'] ="top";

      $this->stringvars['image_align'] =$sectioncontents['imagealign'];
      $this->stringvars['image_valign'] =$sectioncontents['imagevalign'];
      $this->stringvars['text']=text2html($sectioncontents['text']);

      $this->createTemplates();
    }

    // assigns templates
    function createTemplates()
    {
      $this->addTemplate("articlesection.tpl");
    }
}



//
// main class for newspages
//
class ArticlePage extends Template {
  function ArticlePage($page,$articlepage,$showrefused,$showhidden)
  {
    global $sid;

    $pagecontents=getarticlepagecontents($page);
    $articlesections=getarticlesections($page,$articlepage);
    
    $this->vars['printviewbutton']= new PrintviewButton();

    $this->stringvars['pagetitle']=title2html(getpagetitle($page));

    if(strlen($pagecontents['article_author'])>0)
      $this->stringvars['article_author']=title2html($pagecontents['article_author']);

    $this->stringvars['location']=title2html($pagecontents['location']);
    $this->stringvars['date']=makearticledate($pagecontents['day'],$pagecontents['month'],$pagecontents['year']);

    if(strlen($pagecontents['sourcelink'])>0)
      $this->stringvars['source_link']=$pagecontents['sourcelink'];

    if(strlen($pagecontents['source'])>0)
    {
      $this->stringvars['source']=title2html($pagecontents['source']);
      $this->stringvars['l_source']=getlang("article_page_source");
     }

    if(strlen($pagecontents['synopsis'])>0 && $articlepage==1)
      $this->stringvars['text']=text2html($pagecontents['synopsis']);
    else $this->stringvars['text']="";

    if($articlepage==1 && strlen($pagecontents['synopsisimage'])>0 && mayshowimage($pagecontents['synopsisimage'],$page,$showhidden))
      $this->vars['image'] = new CaptionedImage($pagecontents['synopsisimage'],2,$showrefused,$showhidden);

    else $this->stringvars['no_image']="no_image";

    if($pagecontents['imagealign']==="right")
      $this->stringvars['image_align_right'] ="right";
    elseif($pagecontents['imagealign']==="center")
      $this->stringvars['image_align_center'] ="center";
    else
      $this->stringvars['image_align_left'] ="left";

    if($pagecontents['imagevalign']==="bottom")
      $this->stringvars['image_valign_bottom'] ="bottom";
    else
      $this->stringvars['image_valign_top'] ="top";

    $this->stringvars['image_align'] =$pagecontents['imagealign'];
    $this->stringvars['image_valign'] =$pagecontents['imagevalign'];


    $noofarticlepages=numberofarticlepages($page);
    
    // pagemenu
    if($noofarticlepages>1)
    {
      $this->vars['pagemenu'] =
      new Pagemenu($articlepage-1,1,$noofarticlepages,'',$page);
    }

    // get items
    for($i=0;$i<count($articlesections);$i++)
    {
      $this->listvars['articlesection'][] = new Articlesection($articlesections[$i],$page,$articlepage,$showrefused,$showhidden);
    }

    $this->vars['editdata']= new Editdata($page,$showhidden);

    $this->createTemplates();
    
//    print_r($this->stringvars);
  }
  
    // assigns templates
    function createTemplates()
    {
      $this->addTemplate("articlepage.tpl");
    }
}


//
// Templating for Articlesections
//
class ArticlesectionPrintview extends Template {

    function ArticlesectionPrintview($articlesection_id) {

      $sectioncontents=getarticlesectioncontents($articlesection_id);

      if(strlen($sectioncontents['sectiontitle'])>0)
        $this->stringvars['title'] =title2html($sectioncontents['sectiontitle']);

      $this->stringvars['no_image']="no_image";

      $this->stringvars['text']=text2html($sectioncontents['text']);

      $this->createTemplates();
    }

    // assigns templates
    function createTemplates()
    {
      $this->addTemplate("articlesection.tpl");
    }
}



//
// main class for newspages
//
class ArticlePagePrintview extends Template {
  function ArticlePagePrintview($page)
  {
    global $sid;


    $pagecontents=getarticlepagecontents($page);
    $articlesections=getallarticlesections($page);
    
    $this->stringvars['printviewbutton']= "";

    $this->stringvars['pagetitle']=title2html(getpagetitle($page));

    if(strlen($pagecontents['article_author'])>0)
      $this->stringvars['article_author']=title2html($pagecontents['article_author']);

    $this->stringvars['location']=title2html($pagecontents['location']);
    $this->stringvars['date']=makearticledate($pagecontents['day'],$pagecontents['month'],$pagecontents['year']);

    if(strlen($pagecontents['sourcelink'])>0)
      $this->stringvars['source_link']=$pagecontents['sourcelink'];

    if(strlen($pagecontents['source'])>0)
      $this->stringvars['source']=title2html($pagecontents['source']);

    $this->stringvars['text']=text2html($pagecontents['synopsis']);

    $this->stringvars['no_image']="no_image";

    // get items
    for($i=0;$i<count($articlesections);$i++)
    {
      $this->listvars['articlesection'][] = new ArticlesectionPrintview($articlesections[$i]);
    }

    $this->vars['editdata']= new Editdata($page,false);

    $this->createTemplates();
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("articlepage.tpl");
  }
}

?>
