<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));

include_once($projectroot."includes/objects/template.php");
include_once($projectroot."language/languages.php");



//
//
//
class CategorylistLink extends Template {


    function CategorylistLink($category,$page)
    {
    	parent::__construct();
		$this->stringvars['title']=title2html(getcategoryname($category));
		if(getpagetype($page) == "news")
		{
			$oldestdate=getoldestnewsitemdate($page);
			$newestdate=getnewestnewsitemdate($page);

			if(displaynewestnewsitemfirst($page)) $order="DESC";
			else $order="ASC";

			$link = "?page=".$page."&selectedcat=".$category."&fromday=".$oldestdate["mday"]."&frommonth=".$oldestdate["mon"]."&fromyear=".$oldestdate["year"]."&today=".$newestdate["mday"]."&tomonth=".$newestdate["mon"]."&toyear=".$newestdate["year"]."&order=date&ascdesc=".$order."&filter=Go&sid=".$this->stringvars["sid"];
		}
		else
		{
			$link = "?page=".$page."&selectedcat=".$category."&from=all&to=all&order=title&ascdesc=asc&filter=go&sid=".$this->stringvars["sid"];
		}
		$this->stringvars['link']=$link;
    }

    // assigns templates
    function createTemplates()
    {
      $this->addTemplate("categorylistlink.tpl");
    }
}


//
//
//
class CategorylistLinks extends Template {


    function CategorylistLinks($categories,$page)
    {
    	parent::__construct();
    	
		$noofcategories = count($categories);
		
		if($noofcategories)
		{
		
			for($i=0;$i<count($categories);$i++)
			{
				$this->listvars['catlist'][]=new CategorylistLink($categories[$i],$page);
				$this->stringvars['l_categories']=getlang("categorylist_categories");
			}
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
      $this->addTemplate("categorylistlinks.tpl");
    }
}


//
//
//
class Categorylist extends Template {


    function Categorylist($categories,$printheader=true)
    {
    	parent::__construct();
		$categorynames=getcategorynamessorted($categories);

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
      $this->addTemplate("categorylist.tpl");
    }
}
?>