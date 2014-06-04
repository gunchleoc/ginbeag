<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));

include_once($projectroot."functions/pages.php");
include_once($projectroot."includes/objects/template.php");
include_once($projectroot."includes/objects/elements.php");
include_once($projectroot."includes/objects/page.php");
include_once($projectroot."includes/includes.php");

//
// main class for sitemap
//
class Sitemap extends Template {

	function Sitemap($showhidden=false)
	{
		parent::__construct();
		$this->vars['pageintro'] = new PageIntro(utf8_decode(getlang("pagetitle_sitemap")),"");

		$roots=getrootpages();
		for($i=0;$i<count($roots);$i++)
		{
			if(displaylinksforpagearray($roots[$i]) || $showhidden)
			{
				$this->listvars['subpages'][]= new SitemapBranch($roots[$i],5,true,0,"",$showhidden);
			}
		}
		// special links
		if(getproperty("Enable Guestbook"))
			$this->listvars['subpages'][]=new SitemapBranch(0,0,true,0,"guestbook",$showhidden);

		$this->listvars['subpages'][]=new SitemapBranch(0,0,true,0,"contact",$showhidden);
	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("sitemap.tpl");
	}
}


//
// Templating for Navigator
//
class SitemapLink extends Template {

	function SitemapLink($page, $level=0, $class="navtitle", $speciallink="" ,$showhidden=false)
	{
		parent::__construct();

		$linkparams = array();
		if(ismobile()) $linkparams["m"] = "on";

		// layout parameters
		$this->stringvars['link_class']=$class;
		$this->stringvars['title_class']="";

		// for special pages like, contact, guestbook etc
		if($page==0)
		{
			if($speciallink==="guestbook")
			{
				$this->stringvars['linktooltip']=getlang("navigator_guestbook");
				$this->stringvars['title']=getlang("navigator_guestbook");
				$this->stringvars['link']=getprojectrootlinkpath()."guestbook.php".makelinkparameters($linkparams);
				$this->stringvars['link_attributes']='';
				$this->stringvars['description']="";
			}
			elseif($speciallink==="contact")
			{
				$this->stringvars['linktooltip']=getlang("navigator_contact");
				$this->stringvars['title']=getlang("navigator_contact");
				$this->stringvars['link']=getprojectrootlinkpath()."contact.php".makelinkparameters($linkparams);
				$this->stringvars['link_attributes']='';
				$this->stringvars['description']="";
			}
			else
			{
				$this->stringvars['linktooltip']=getlang("navigator_notfound");
				$this->stringvars['title']=getlang("navigator_notfound");
				$this->stringvars['link']=makelinkparameters($linkparams);
				$this->stringvars['link_class']=$class;
				$this->stringvars['link_attributes']='';
				$this->stringvars['description']="";
			}
		}
		// for normal pages
		else
		{
			$this->pagetype=getpagetypearray($page);

			$this->stringvars['title']=title2html(getpagetitlearray($page));
			$this->stringvars['linktooltip']=striptitletags(getpagetitlearray($page));
			$this->stringvars['description']="";
			$this->stringvars['title_class']="";

			if($showhidden)
			{
				if(isthisexactpagerestricted($page)) $this->stringvars['title']=$this->stringvars['title'].' (R)';
				if(!ispublished($page)) $this->stringvars['title']='<i>'.$this->stringvars['title'].'</i>';
			}

			if($this->pagetype==="external")
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
			}
			else
			{
				if($showhidden) $path=getprojectrootlinkpath()."admin/pagedisplay.php";
				else $path=getprojectrootlinkpath()."index.php";
				$linkparams["page"] = $page;
				$this->stringvars['link']=$path.makelinkparameters($linkparams);
				$this->stringvars['link_attributes']="";
			}
		}
	}

    // assigns templates
    function createTemplates()
    {
		$this->addTemplate("navigator/navigatorlink.tpl");
    }
}



//
// Templating for Navigator
// iterate over branch and create links
//
class SitemapBranch extends Template {

	function SitemapBranch($page,$depth,$startwithroot=false,$level=0,$speciallink="",$showhidden=false)
    {
    	parent::__construct();

        if($startwithroot && $level==0)
        {
			$class="contentnavtitle";
			$this->stringvars['wrapper_class'] = "contentnavrootlinkwrapper";
        }
        else
        {
			$class="contentnavlink";
			$this->stringvars['wrapper_class'] = "contentnavlinkwrapper";
        }


        if(hasaccesssession($page) || $showhidden)
        {
        	$this->listvars['link'][]= new SitemapLink($page, $level, $class,$speciallink, $showhidden);
        }

        $this->stringvars['margin_left']=$level;

        if($depth>0)
        {
			$pages=getchildrenarray($page);
			for($i=0;$i<count($pages);$i++)
			{
				if(displaylinksforpagearray($pages[$i]) || $showhidden)
				{
					$this->listvars['link'][]= new SitemapBranch($pages[$i], $depth-1, $startwithroot, $level+1,$speciallink, $showhidden);
				}
			}
        }
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("navigator/navigatorbranch.tpl");
    }
}
?>
