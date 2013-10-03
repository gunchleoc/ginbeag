<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));

include_once($projectroot."functions/pagecontent/articlepages.php");
include_once($projectroot."includes/objects/template.php");
include_once($projectroot."includes/objects/elements.php");
include_once($projectroot."includes/objects/images.php");
include_once($projectroot."includes/includes.php");

//
// Templating for Articlesections
//
class Articlesection extends Template {

    function Articlesection($articlesection_id,$articlepage,$showrefused,$showhidden) {
    
    	parent::__construct();

      $sectioncontents=getarticlesectioncontents($articlesection_id);

      if(strlen($sectioncontents['sectiontitle'])>0)
      {
        $this->stringvars['title'] =title2html($sectioncontents['sectiontitle']);
        $this->stringvars['sectionid'] =$articlesection_id;
      }

      if(strlen($sectioncontents['sectionimage'])>0 && mayshowimage($sectioncontents['sectionimage'],$this->stringvars['page'],$showhidden))
        $this->vars['image'] = new CaptionedImage($sectioncontents['sectionimage'],2,$sectioncontents['imagealign'],$showrefused,$showhidden);
      else $this->stringvars['image']="";

      $this->stringvars['text']=text2html($sectioncontents['text']);

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
	function ArticlePage($articlepage,$showrefused,$showhidden)
  	{
		parent::__construct();

    	$pagecontents=getarticlepagecontents($this->stringvars['page']);
    	$articlesections=getarticlesections($this->stringvars['page'],$articlepage);
    	
    	$this->vars['printviewbutton']= new LinkButton('?sid='.$this->stringvars['sid'].'&printview=on&page='.$this->stringvars['page'],getlang("pagemenu_printview"),"img/printview.png");

    	$this->stringvars['pagetitle']=title2html(getpagetitle($this->stringvars['page']));

    	if(strlen($pagecontents['article_author'])>0)
    	{
      		$this->stringvars['article_author']=title2html($pagecontents['article_author']);
      		$this->stringvars['l_author']=getlang('article_page_author');
    	}

    	$this->stringvars['location']=title2html($pagecontents['location']);
    	$this->stringvars['date']=makearticledate($pagecontents['day'],$pagecontents['month'],$pagecontents['year']);

    	if(strlen($pagecontents['sourcelink'])>0)
      		$this->stringvars['source_link']=$pagecontents['sourcelink'];

    	if(strlen($pagecontents['source'])>0)
    	{
      		$this->stringvars['source']=title2html($pagecontents['source']);
      		$this->stringvars['l_source']=getlang("article_page_source");
     	}


    	$pageintro = getpageintro($this->stringvars['page']);
    	
    	if($articlepage==1)
    		$this->vars['pageintro'] = new PageIntro("",$pageintro['introtext'],$pageintro['introimage'],$pageintro['imagehalign'],$showrefused,$showhidden);
    	
    	$noofarticlepages=numberofarticlepages($this->stringvars['page']);
    
    	// pagemenu
    	if($noofarticlepages>1)
      		$this->vars['pagemenu'] = new Pagemenu($articlepage-1,1,$noofarticlepages,'',$this->stringvars['page']);
      		
		if($pagecontents['use_toc'])
    		$this->vars['toc']=new ArticleTOC();
    	else
    		$this->stringvars['toc']="";
    

    	// get items
    	for($i=0;$i<count($articlesections);$i++)
    	{
      		$this->listvars['articlesection'][] = new Articlesection($articlesections[$i],$articlepage,$showrefused,$showhidden);
    	}

    	$this->vars['editdata']= new Editdata($showhidden);
  	}
  
    // assigns templates
    function createTemplates()
    {
      	$this->addTemplate("articlepage.tpl");
    }
}


//
// Table of Contents
//
class ArticleTOC extends Template {
	function ArticleTOC()
  	{
		parent::__construct();
  		$this->stringvars['l_toc'] =getlang('article_page_toc');
  		
  		$noofarticlepages=numberofarticlepages($this->stringvars['page']);
  		for($i=1;$i<=$noofarticlepages;$i++) {
	  		$articlesections=getarticlesections($this->stringvars['page'],$i);
		    // get items
		    for($j=0;$j<count($articlesections);$j++)
		    {
		    	$sectiontitle = getarticlesectiontitle($articlesections[$j]);
		    	if(strlen($sectiontitle)>0)
		      		$this->listvars['toc'][] = new ArticleTOCItem($articlesections[$j],$sectiontitle,$i-1);
		    }
		}
  	}
  
    // assigns templates
    function createTemplates()
    {
      $this->addTemplate("articletoc.tpl");
    }
}


//
// Table of Contents
//
class ArticleTOCItem extends Template {
	function ArticleTOCItem($sectionid,$title,$offset)
  	{
		parent::__construct();
		$this->stringvars['link']="?sid=".$this->stringvars['sid']."&page=".$this->stringvars['page']."&offset=".$offset."#section".$sectionid;
    	//$this->stringvars['sectionid'] =$sectionid;
    	$this->stringvars['title'] =title2html($title);
    	//$this->stringvars['offset'] =$offset;
  	}
  
    // assigns templates
    function createTemplates()
    {
      $this->addTemplate("articletocitem.tpl");
    }
}





//
// Templating for Articlesections
//
class ArticlesectionPrintview extends Template {

	function ArticlesectionPrintview($articlesection) {
		parent::__construct();
	
		$sectioncontents=getarticlesectioncontents($articlesection);

		if(strlen($sectioncontents['sectiontitle'])>0)
        	$this->stringvars['title'] =title2html($sectioncontents['sectiontitle']);

      	$this->stringvars['image']="";
      	
      	if(strlen($sectioncontents['sectionimage'])>0 && mayshowimage($sectioncontents['sectionimage'],$this->stringvars['page'],false))
        	$this->vars['image'] = new CaptionedImage($sectioncontents['sectionimage'],2,$sectioncontents['imagealign'],false,false);
      	else $this->stringvars['image']="";

      	$this->stringvars['text']=text2html($sectioncontents['text']);
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
  	function ArticlePagePrintview()
  	{
    	parent::__construct();


    	$pagecontents=getarticlepagecontents($this->stringvars['page']);
    	$articlesections=getallarticlesections($this->stringvars['page']);
    

    	$this->stringvars['pagetitle']=title2html(getpagetitle($this->stringvars['page']));

    	if(strlen($pagecontents['article_author'])>0)
    	{
      		$this->stringvars['article_author']=title2html($pagecontents['article_author']);
      		$this->stringvars['l_author']=getlang('article_page_author');
    	}

    	$this->stringvars['location']=title2html($pagecontents['location']);
    	$this->stringvars['date']=makearticledate($pagecontents['day'],$pagecontents['month'],$pagecontents['year']);

    	if(strlen($pagecontents['sourcelink'])>0)
      		$this->stringvars['source_link']=$pagecontents['sourcelink'];

    	if(strlen($pagecontents['source'])>0)
    	{
      		$this->stringvars['source']=title2html($pagecontents['source']);
      		$this->stringvars['l_source']=getlang("article_page_source");
     	}


    	$this->stringvars['text']=text2html(getpageintrotext($this->stringvars['page']));
    	
   	
    	$synopsisimage=getpageintroimage($this->stringvars['page']);
    	
    	if(strlen($synopsisimage)>0 && mayshowimage($synopsisimage,$this->stringvars['page'],false))
      		$this->vars['image'] = new CaptionedImage($synopsisimage,2,getpageintrohalign($this->stringvars['page']),false,false);

    	else $this->stringvars['image']="";
    	
		if($pagecontents['use_toc'])
    		$this->vars['toc']=new ArticleTOC();
    	else
    		$this->stringvars['toc']="";


	    // get items
	    for($i=0;$i<count($articlesections);$i++)
	    {
	      	$this->listvars['articlesection'][] = new ArticlesectionPrintview($articlesections[$i]);
	    }

    	$this->vars['editdata']= new Editdata();

	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("articlepage.tpl");
	}
}

?>
