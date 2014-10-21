<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));

include_once($projectroot."includes/objects/template.php");
include_once($projectroot."includes/functions.php");
include_once($projectroot."language/languages.php");

//
//
//
class CategorylistLink extends Template {


	 function CategorylistLink($category, $name, $page)
	 {
		parent::__construct();
		$this->stringvars['title']=title2html($name);
		$this->stringvars['title'] = str_replace(" ", "&nbsp;", $this->stringvars['title']);
		if(getpagetype($page) == "news")
		{
			$oldestdate=getoldestnewsitemdate($page);
			$newestdate=getnewestnewsitemdate($page);

			if(displaynewestnewsitemfirst($page)) $order="DESC";
			else $order="ASC";

			$linkparams["page"] = $this->stringvars['page'];
			$linkparams["selectedcat"] = $category;
			$linkparams["fromday"] = $oldestdate["mday"];
			$linkparams["frommonth"] = $oldestdate["mon"];
			$linkparams["fromyear"] = $oldestdate["year"];
			$linkparams["today"] = $newestdate["mday"];
			$linkparams["tomonth"] = $newestdate["mon"];
			$linkparams["toyear"] = $newestdate["year"];
			$linkparams["order"] = "date";
			$linkparams["ascdesc"] = $order;
			$linkparams["filter"] = "Go";
			if(ismobile()) $linkparams["m"] = "on";
			$this->stringvars['link'] = makelinkparameters($linkparams);
		}
		else
		{
			$linkparams["page"] = $this->stringvars['page'];
			$linkparams["selectedcat"] = $category;
			$linkparams["from"] = "all";
			$linkparams["to"] = "all";
			$linkparams["order"] = "title";
			$linkparams["ascdesc"] = "asc";
			$linkparams["filter"] = "Go";
			if(ismobile()) $linkparams["m"] = "on";
			$this->stringvars['link'] = makelinkparameters($linkparams);
		}
	}

	// assigns templates
	function createTemplates()
	{
		if(ismobile())
			$this->addTemplate("mobile/categorylistlink.tpl");
		else
			$this->addTemplate("categories/categorylistlink.tpl");
	}
}


//
//
//
class CategorylistLinks extends Template {

    function CategorylistLinks($categories,$page, $cattype)
    {
    	parent::__construct();

		$noofcategories = count($categories);

		if($noofcategories)
		{
			$cats_with_names = array();

			for($i=0;$i<count($categories);$i++)
			{
				$cats_with_names[$categories[$i]] = getcategoryname($categories[$i], $cattype);
			}
			natcasesort($cats_with_names);
			$keys = array_keys($cats_with_names);
			while($key = current($keys))
			{
				$this->listvars['catlist'][]=new CategorylistLink($key, $cats_with_names[$key], $page);
				next($keys);
			}
			$this->stringvars['l_categories']=getlang("categorylist_categories");
		}
  		else
  		{
  			$this->stringvars['catlist']="";
  			$this->stringvars['l_categories']="";
  		}
    }

    // assigns templates
    function createTemplates()
    {
		$this->addTemplate("categories/categorylistlinks.tpl");
    }
}


//
//
//
class Categorylist extends Template {


    function Categorylist($categories, $cattype, $printheader=true)
    {
    	parent::__construct();
		$categorynames=getcategorynamessorted($categories, $cattype);

  		$catlistoutput=implode(", ",$categorynames);
  		if($printheader)
  		{
  			$this->stringvars['header']=getlang("categorylist_categories");
  		}

  		if($catlistoutput)
  		{
    		$this->stringvars['catlist']=title2html($catlistoutput);
  		}
  		elseif($printheader) $this->stringvars['catlist']=getlang("categorylist_none");
  		else $this->stringvars['catlist']="";
    }

    // assigns templates
    function createTemplates()
    {
		$this->addTemplate("categories/categorylist.tpl");
    }
}
?>
