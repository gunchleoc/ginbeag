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
		$this->vars['pageintro'] = new PageIntro(utf8_decode(getlang("pagetitle_sitemap")),"","");
		  
		$roots=getrootpages();
		for($i=0;$i<count($roots);$i++)
		{
			if(displaylinksforpagearray($this->stringvars['sid'],$roots[$i]) || $showhidden)
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
// todo remove parameter $isroot
//
class SitemapLink extends Template {

	function SitemapLink($page, $level=0, $class="navtitle", $speciallink="" ,$showhidden=false) {

		global $_GET;
      
		parent::__construct();
		
		if(isset($_GET['sid'])) $sid=$_GET['sid'];
		else $sid="";
		
		$linkparams="?sid=".$sid;
		if(isset($_GET['m'])) $linkparams.="&m=on";
		
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
				$this->stringvars['link']=getprojectrootlinkpath()."guestbook.php".$linkparams;
				$this->stringvars['link_attributes']='';
				$this->stringvars['description']="";
			}
			elseif($speciallink==="contact")
			{
				$this->stringvars['linktooltip']=getlang("navigator_contact");
				$this->stringvars['title']=getlang("navigator_contact");
				$this->stringvars['link']=getprojectrootlinkpath()."contact.php".$linkparams;
				$this->stringvars['link_attributes']='';
				$this->stringvars['description']="";
			}
			else
			{
				$this->stringvars['linktooltip']=getlang("navigator_notfound");
				$this->stringvars['title']=getlang("navigator_notfound");
				$this->stringvars['link']=$linkparams;
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
				$this->stringvars['link']=$path.$linkparams.'&page='.$page;
				$this->stringvars['link_attributes']="";
			}
		} 
	}

    // assigns templates
    function createTemplates()
    {
		$this->addTemplate("navigatorlink.tpl");
    }
}



//
// Templating for Navigator
// iterate over branch and create links
//
class SitemapBranch extends Template {

	function SitemapBranch($page,$depth,$startwithroot=false,$level=0,$speciallink="",$showhidden=false)
    {
		global $_GET;
    	
    	parent::__construct();
    	
    	if(isset($_GET['sid'])) $sid=$_GET['sid'];
    	else $sid="";
    	
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
        

        if(hasaccesssession($sid, $page) || $showhidden)
        {
        	$this->listvars['link'][]= new SitemapLink($page, $level, $class,$speciallink, $showhidden);
        }

        $this->stringvars['margin_left']=$level;

        if($depth>0)
        {
			$pages=getchildrenarray($page);
			for($i=0;$i<count($pages);$i++)
			{
				if(displaylinksforpagearray($sid,$pages[$i]) || $showhidden)
				{
					$this->listvars['link'][]= new SitemapBranch($pages[$i], $depth-1, $startwithroot, $level+1,$speciallink, $showhidden);
				}
			}
        }
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("navigatorbranch.tpl");
    }
}
?>