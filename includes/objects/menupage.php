<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));

include_once($projectroot."functions/pagecontent/menupages.php");
include_once($projectroot."includes/objects/template.php");
include_once($projectroot."includes/objects/elements.php");
include_once($projectroot."includes/objects/images.php");
include_once($projectroot."includes/objects/page.php");
include_once($projectroot."includes/includes.php");

//
// main class for menu pages
//
class MenuPage extends Template {

	var $pagetype="";

	function MenuPage($page,$showhidden=false)
	{
	    parent::__construct();

	    $pagecontents=getmenucontents($page);

		$pageintro = getpageintro($this->stringvars['page']);
		$this->vars['pageintro'] = new PageIntro(getpagetitle($this->stringvars['page']),$pageintro['introtext'],$pageintro['introimage'],$pageintro['imageautoshrink'], $pageintro['usethumbnail'],$pageintro['imagehalign'],$showhidden);

	    $this->pagetype=getpagetypearray($page);

		$this->stringvars['actionvars'] = makelinkparameters(array("page" => $this->stringvars['page']));

	    if($this->pagetype==="linklistmenu")
	    {
			$children=getchildren($page,"ASC");
			for($i=0;$i<count($children);$i++)
			{
				if(displaylinksforpagearray($children[$i]) || $showhidden)
				{
					$this->listvars['subpages'][]= new MenuNavigatorBranch($children[$i],$pagecontents['displaydepth']-1,0,$showhidden);
				}
			}

	    }
	    else
	    {
			$children=getchildren($page,"ASC");
			for($i=0;$i<count($children);$i++)
			{
				if(displaylinksforpagearray($children[$i]) || $showhidden)
				{
					$this->listvars['subpages'][]= new MenuNavigatorBranch($children[$i],$pagecontents['displaydepth']-1,0,$showhidden);
				}
			}
	    }
	    $this->vars['editdata']= new Editdata($showhidden);
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
class ArticleInfo extends Template {

    function ArticleInfo($page,$article)
    {
    	parent::__construct();

		$contents= getarticlepageoverview($article);

		$articleinfo="";
		if($contents['article_author'])
		{
			$articleinfo.= 'By '.title2html($contents['article_author']);
		}
		if($contents['source'])
		{
			if($articleinfo) $articleinfo.=', ';
			$articleinfo.=title2html($contents['source']);
		}
		$date=makearticledate($contents['day'],$contents['month'],$contents['year']);
		if($date)
		{
			if($articleinfo) $articleinfo.=', ';
			$articleinfo.=$date;
		}

		$this->stringvars['articleinfo']=$articleinfo;
		$this->vars['categorylist']=new CategorylistLinks(getcategoriesforpage($article),$page,CATEGORY_ARTICLE);
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
class ArticleMenuPage extends Template {

	var $pagetype="";

	function ArticleMenuPage($page,$showhidden=false)
	{
		global $_GET;
		parent::__construct();

		$pagecontents=getmenucontents($page);

		$this->pagetype=getpagetypearray($page);

		$linkparams = array("page" => $this->stringvars['page']);
		if(isset($_GET["m"])) $linkparams["m"] = "on";
		$this->stringvars['actionvars'] = makelinkparameters($linkparams);
		$this->stringvars['hiddenvars'] = $this->makehiddenvars($linkparams);

	   $pageintro = getpageintro($this->stringvars['page']);
		$this->vars['pageintro'] = new PageIntro(getpagetitle($this->stringvars['page']),$pageintro['introtext'],$pageintro['introimage'],$pageintro['imageautoshrink'], $pageintro['usethumbnail'],$pageintro['imagehalign'],$showhidden);

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
			if(displaylinksforpagearray($children[$i]) || $showhidden)
			{
				$this->listvars['subpages'][]= new MenuNavigatorBranch($children[$i],$pagecontents['displaydepth']-1,0,$showhidden);
			}
		}

	    $this->vars['editdata']= new Editdata($showhidden);
	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("pages/menu/articlemenupage.tpl");
	}


	//
	//
	//
	function makearticlefilterform($page,$selectedcat="",$from="",$to="",$order="",$ascdesc="",$subpages=false)
	{
		$this->vars["categoryselection"]= new CategorySelectionForm(false,"",CATEGORY_ARTICLE,1,array($selectedcat => $selectedcat));

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

		if($from>$to)
		{
			$this->stringvars['message']=getlang("menu_filter_badyearselection");
		}
		else
		{
			$result=getfilteredarticles($page,$selectedcat,$from,$to,$order,$ascdesc,$showhidden);
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

    function ArticlemenuOrderSelectionForm($order="")
    {
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
      $this->addTemplate("forms/optionform.tpl");
    }
}



//
// Templating for Linklist in Linklistmenu Navigator
//
class MenuLinkListLink extends Template {

    function MenuLinkListLink($link)
    {
		parent::__construct();

		$contents=getlinkcontents($link);

		if(strlen($contents['link'])<=1)
		{
			$this->stringvars['link'] = makelinkparameters(array("page" => $this->stringvars['page']));
		}
		else
		{
			$this->stringvars['link']=$contents['link'];
		}
		$this->stringvars['title']=title2html($contents['title']);

		$text=text2html($contents['description']);
		$paragraphs=explode ('<br />', $text);
		$text=$paragraphs[0];

		if (array_key_exists(1, $paragraphs)) $text.=' <a href="'.makelinkparameters(array("page" => $contents['page_id'])).'#link'.$link.'">[...]</a>';

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
class MenuLinkListBranch extends Template {

    function MenuLinkListBranch($linkids)
    {
		parent::__construct();

		for($i=0;$i<count($linkids);$i++)
		{
			$this->listvars['link'][]=new MenuLinkListLink($linkids[$i]);
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
class MenuNavigatorLink extends Template {

	function MenuNavigatorLink($page, $level=0, $showhidden=false)
	{
		global $_GET;

		parent::__construct();

		// layout parameters
        if($level==0) $this->stringvars['link_class']="contentnavtitle";
        else $this->stringvars['link_class']="contentnavlink";

		$this->stringvars['title']=title2html(getpagetitlearray($page));
        $this->stringvars['linktooltip']=striptitletags(getpagetitlearray($page));

        if($showhidden)
        {
			if(isthisexactpagerestricted($page)) $this->stringvars['title']=$this->stringvars['title'].' (R)';
			if(!ispublished($page)) $this->stringvars['title']='<i>'.$this->stringvars['title'].'</i>';
        }

		$pagetype=getpagetypearray($page);

        if($pagetype==="external")
        {
			$this->stringvars['link']=getexternallink($page);
			if(str_startswith($this->stringvars['link'], getprojectrootlinkpath())
				|| str_startswith($this->stringvars['link'], "?")
				|| str_startswith($this->stringvars['link'], "index.php"))
			{
				$this->stringvars['link_attributes']='';
			}
			else
			{
          		$this->stringvars['link_attributes']=' target="_blank"';
			}
			$this->stringvars['description']="";
        }
        else
        {
			if($pagetype==="article")
			{
				$this->vars['description']=new ArticleInfo($this->stringvars["page"],$page);
			}
			elseif($pagetype==="linklist")
			{
				$linkids=getlinklistitems($page);
				if(count($linkids)>0)
				{
					$this->vars['description']=new MenuLinkListBranch($linkids);
				}
				else
				{
					$this->stringvars['description']="";
				}
			}

			else
			{
				$this->stringvars['description']="";
			}
			if($showhidden) $path=getprojectrootlinkpath()."admin/pagedisplay.php";
			else $path=getprojectrootlinkpath()."index.php";

			$linkparams["page"]=$page;
			if(isset($_GET['m'])) $linkparams["m"] = "on";
			$this->stringvars['link']=$path.makelinkparameters($linkparams);
			$this->stringvars['link_attributes']="";
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
class MenuNavigatorBranch extends Template {

    function MenuNavigatorBranch($page,$depth,$level=0,$showhidden=false)
    {
    	parent::__construct();

        if($level==0) $this->stringvars['wrapper_class'] = "contentnavrootlinkwrapper";
        else $this->stringvars['wrapper_class'] = "contentnavlinkwrapper";

        if(hasaccesssession($page) || $showhidden)
        {
			$this->listvars['link'][]= new MenuNavigatorLink($page, $level,$showhidden);
        }

        $this->stringvars['margin_left']=$level;

        if($depth>0)
        {
			$pages=getchildrenarray($page);
			for($i=0;$i<count($pages);$i++)
			{
				if(displaylinksforpagearray($pages[$i]) || $showhidden)
				{
					$this->listvars['link'][]= new MenuNavigatorBranch($pages[$i], $depth-1, $level+1,$showhidden);
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
