<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));

include_once($projectroot."functions/pagecontent/menupages.php");
include_once($projectroot."includes/objects/template.php");
include_once($projectroot."includes/objects/elements.php");
include_once($projectroot."includes/objects/page.php");
include_once($projectroot."includes/includes.php");


//
// main class for menu pages
//
class MenuPage extends Template {

	var $pagetype="";

	function MenuPage($page,$showrefused,$showhidden=false)
	{
	    global $_GET;
	    parent::__construct();
	    
	    $pagecontents=getmenucontents($page);
	    $introtext = getpageintro($page);
	
	    $this->pagetype=getpagetypearray($page);
    
    // todo: room for image, need to add admin functions and database entry for that
//        if(mayshowimage($contents['image'],$page,$showhidden))

		$this->stringvars['actionvars']="?sid=".$this->stringvars['sid']."&page=".$this->stringvars['page'];
	
	    $this->vars['pageintro'] = new PageIntro(title2html(getpagetitle($page)),$introtext,"");
	      
	    if($this->pagetype==="linklistmenu")
	    {
			$children=getchildren($page,"ASC");
			for($i=0;$i<count($children);$i++)
			{
				if(displaylinksforpagearray($this->stringvars['sid'],$children[$i]) || $showhidden)
				{
					$this->listvars['subpages'][]= new ContentNavigatorBranch($children[$i],"simple","simple","contents",$pagecontents['displaydepth']-1,true,0,"",$showhidden);
				}
			}
	
	    }
	    else
	    {
			$children=getchildren($page,"ASC");
			for($i=0;$i<count($children);$i++)
			{
				if(displaylinksforpagearray($this->stringvars['sid'],$children[$i]) || $showhidden)
				{
					$this->listvars['subpages'][]= new ContentNavigatorBranch($children[$i],"simple","bullet","contents",$pagecontents['displaydepth']-1,true,0,"",$showhidden);
				}
			}
	    }
	
	    $this->vars['editdata']= new Editdata($showhidden);
	}
  
	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("menupage.tpl");
	}
}






//
// main class for newspages
//
class ArticleMenuPage extends Template {

	var $pagetype="";

	function ArticleMenuPage($page,$showrefused,$showhidden=false)
	{
		global $_GET;
		parent::__construct();
		
		$pagecontents=getmenucontents($page);
		$introtext = getpageintro($page);
		
		$this->pagetype=getpagetypearray($page);
	    
	    // todo: room for image, need to add admin functions and database entry for that
	//        if(mayshowimage($contents['image'],$page,$showhidden))
	
		$this->stringvars['actionvars']="?sid=".$this->stringvars['sid']."&page=".$this->stringvars['page'];
		
		$this->vars['pageintro'] = new PageIntro(title2html(getpagetitle($page)),$introtext,"");
		
		$this->stringvars['l_displayoptions']=getlang("menu_filter_displayoptions");
		$this->stringvars['l_categories']=getlang("menu_filter_categories");
		$this->stringvars['l_from']=getlang("menu_filter_from");
		$this->stringvars['l_to']=getlang("menu_filter_to");
		$this->stringvars['l_go']=getlang("menu_filter_go");
		$this->stringvars['l_orderby']=getlang("menu_filter_orderby");
	
		$filter=isset($_GET['filter']);
		if($filter)
		{
			$selectedcat=$_GET['selectedcat'];
			$from=$_GET['from'];
			$to=$_GET['to'];
			$order=$_GET['order'];
			$ascdesc=$_GET['ascdesc'];
			if (isset($_GET['subpages'])) $subpages=$_GET['subpages'];
			else $subpages=false;
			
			$this->makearticlefilterform($page,$selectedcat,$from,$to,$order,$ascdesc,$subpages);
			$children=$this->getfilteredarticles($page,$showhidden);
			$this->stringvars['l_showall']=getlang("article_filter_showall");
		}
		else
		{
			$this->makearticlefilterform($page);
			$children=getchildren($page,"ASC");
		}
		for($i=0;$i<count($children);$i++)
		{
			if(displaylinksforpagearray($this->stringvars['sid'],$children[$i]) || $showhidden)
			{
				$this->listvars['subpages'][]= new ContentNavigatorBranch($children[$i],"simple","bullet","contents",$pagecontents['displaydepth']-1,true,0,"",$showhidden);
			}
		}
	
	    $this->vars['editdata']= new Editdata($showhidden);
	}
	  
	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("articlemenupage.tpl");
	}


	//
	//
	//
	function makearticlefilterform($page,$selectedcat="",$from="",$to="",$order="",$ascdesc="",$subpages=false)
	{
	
		$this->stringvars["page"]= $page;
		$this->vars["categoryselection"]= new CategorySelectionForm(false,"",1,array($selectedcat => $selectedcat));
		
		$this->stringvars["hiddenvars"]= '<input type="hidden" name="page" value="'.$this->stringvars["page"].'" />';
		$this->stringvars["hiddenvars"].= '<input type="hidden" name="sid" value="'.$this->stringvars["sid"].'" />';
		
		$this->stringvars["l_timespan"]= getlang("menu_filter_timespan");
		$allyears=getallarticleyears();
		if($allyears[0]=="0000") array_shift($allyears);
		$values[0]="all";
		$descriptions[0]=getlang("article_filter_allyears");
		for($i=0;$i<count($allyears);$i++)
		{
			$values[$i+1]=$allyears[$i];
			$descriptions[$i+1]=$allyears[$i];
		}
		$this->vars["from_year"]=new OptionForm($from,$values,$descriptions,"from",getlang("menu_filter_from"));
		$this->vars["to_year"]=new OptionForm($to,$values,$descriptions,"to",getlang("menu_filter_to"));
		
		$this->vars["order"]= new ArticlemenuOrderSelectionForm($order);
		$this->vars["ascdesc"]= new AscDescSelectionForm($ascdesc!=="desc");
		$this->vars["include_subpages"]= new CheckboxForm("subpages","1",getlang("menu_filter_subpages"),$subpages,"right");
	}


	//
	//
	//
	function getfilteredarticles($page,$showhidden)
	{
		global $_GET;
		$result=array();
		
		$this->stringvars['search_result']="search result";
		
		$this->stringvars['l_clearsearch']=getlang("menu_filter_clearsearch");
		
		$selectedcat=$_GET['selectedcat'];
		$from=$_GET['from'];
		$to=$_GET['to'];
		$order=$_GET['order'];
		$ascdesc=$_GET['ascdesc'];
		if (isset($_GET['subpages'])) $subpages=$_GET['subpages'];
		else $subpages=false;
		
		if($from>$to)
		{
			$this->stringvars['message']=getlang("menu_filter_badyearselection");
		}
		else
		{
			$result=getfilteredarticles($page,$selectedcat,$from,$to,$order,$ascdesc,$subpages,$showhidden);
			if(!count($result))
			{
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
class ArticlemenuOrderSelectionForm  extends Template {
    var $stringvars=array("optionform_name" => "",
                          "optionform_attributes" => "",
                          "optionform_size" => "1");
    var $listvars=array("option" => array());

    function ArticlemenuOrderSelectionForm($order="") {
    
    	parent::__construct();
    	
        $this->stringvars['optionform_name'] = "order";
        $this->stringvars['optionform_label'] = getlang("menu_filter_property");
        $this->stringvars['optionform_id'] ="order";

        $this->listvars['option'][]= new OptionFormOption("title",$order==="title",getlang("article_filter_title"));
        $this->listvars['option'][]= new OptionFormOption("author",$order==="author",getlang("article_filter_author"));
        $this->listvars['option'][]= new OptionFormOption("date",$order==="date",getlang("article_filter_date"));
        $this->listvars['option'][]= new OptionFormOption("source",$order==="source",getlang("article_filter_source"));
        $this->listvars['option'][]= new OptionFormOption("editdate",$order==="editdate",getlang("article_filter_changes"));
    }

    // assigns templates
    function createTemplates()
    {
      $this->addTemplate("optionform.tpl");
    }
}


?>
