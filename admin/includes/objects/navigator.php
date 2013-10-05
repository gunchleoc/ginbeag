<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."functions/pages.php");
include_once($projectroot."functions/treefunctions.php");
include_once($projectroot."includes/objects/template.php");
include_once($projectroot."includes/objects/forms.php");
include_once($projectroot."admin/includes/objects/forms.php");
include_once($projectroot."includes/objects/elements.php");


//
// Templating for Admin Navigator
//
class AdminNavigatorLink extends Template {

    function AdminNavigatorLink($page,$level=0,$class="navtitle",$isroot=false)
    {
		global $_GET;

		parent::__construct();

		$this->stringvars['page']=$page;

		// layout parameters
		$this->stringvars['margin_left']=$level;
		$this->stringvars['margin_top']=1;
		$this->stringvars['link_class']=$class;
		$this->stringvars['title_class']="";
		
		if($isroot)
			$this->stringvars['is_root']="is_root";
		else
			$this->stringvars['no_root']="no_root";
        
		// data
		$this->stringvars['pagetype']=getpagetypearray($page);
		$this->stringvars['title']=title2html(getnavtitlearray($page));
		
		if(isthisexactpagerestricted($page)) $this->stringvars['title']=$this->stringvars['title'].' (R)';
		if(!ispublished($page)) $this->stringvars['title']='<i>'.$this->stringvars['title'].'</i>';
		
		$this->stringvars['description']="";
		
		$this->stringvars['link']=getprojectrootlinkpath().'admin/admin.php?page='.$page.'&sid='.$this->stringvars['sid'];
		$this->stringvars['link_attributes']=' target="_top"';
		
		if(isset($_GET['page']) && $_GET['page']===$page)
			$this->stringvars['title_class']="navhighlight";
		else
			$this->stringvars['title_class']="";
	}

    // assigns templates
    function createTemplates()
    {
		$this->addTemplate("admin/adminnavigatorlink.tpl");
    }
}

//
// Templating for Admin Navigator
// iterate over branch and create links
//
class AdminNavigatorBranch extends Template {

    function AdminNavigatorBranch($page_id,$depth,$level=0)
    {
		parent::__construct();
        if($level==0)
			$class="navtitle";
        else
			$class="navlink";
        
        $isroot=false;
        if(isrootpagearray($page_id))
          $isroot=true;

        $this->listvars['link'][]= new AdminNavigatorLink($page_id,$level,$class,$isroot);

        if($depth>0)
        {
			$pageids=getchildrenarray($page_id);
			for($i=0;$i<count($pageids);$i++)
			{
				$this->listvars['link'][]= new AdminNavigatorBranch($pageids[$i],$depth-1,$level+1);
			}
        }
    }

    // assigns templates
    function createTemplates()
    {
		$this->addTemplate("admin/adminnavigatorbranch.tpl");
    }
}

//
// Templating for Admin Navigator in left frame
//
class AdminNavigator extends Template {

	function AdminNavigator($page_id)
	{
	    global $_GET;
	    
	    parent::__construct();
    
		// navigator
		if($page_id==0 || !pageexists($page_id))
		{
			$roots=getrootpages();
			while(count($roots))
			{
				$currentroot=array_shift($roots);
				$this->listvars['link'][]=new AdminNavigatorBranch($currentroot,0,0);
			}
		}
		else
		{
			if(isrootpagearray($page_id))
			{
				$roots=getrootpages();
				$currentroot=array_shift($roots);
				$navposition=getnavpositionarray($page_id);
				// display upper root pages
				while(getnavpositionarray($currentroot)<$navposition)
				{
					$this->listvars['link'][]=new AdminNavigatorBranch($currentroot,0,0);
					$currentroot=array_shift($roots);
				}
				// display root page
				$this->listvars['link'][]=new AdminNavigatorBranch($page_id,1,0);
			}
			else
			{
				// get parent chain
				$parentpages=array();
				$level=0;
				$currentpage=$page_id;
				while(!isrootpagearray($currentpage))
				{
					$parent= getparentarray($currentpage);
					array_push($parentpages,$parent);
					$currentpage=$parent;
					$level++;
				}
				$parentroot=array_pop($parentpages);
				$roots=getrootpages();
				$currentroot=array_shift($roots);
				$parentrootnavposition=getnavpositionarray($parentroot);
				// display upper root pages
				while(getnavpositionarray($currentroot)<$parentrootnavposition)
				{
					$this->listvars['link'][]=new AdminNavigatorBranch($currentroot,0,0);
					$currentroot=array_shift($roots);
				}
				$this->listvars['link'][]=new AdminNavigatorBranch($currentroot,0,0);

				// display parent chain
				$navdepth=count($parentpages); // for closing table tags
				for($i=0;$i<$navdepth;$i++)
				{
					$parentpage=array_pop($parentpages);
					$this->listvars['link'][]=new AdminNavigatorBranch($parentpage,0,$i+1);
				}
				// display page
				// get sisters then display 1 level only.
				$sisterids=getsisters($page_id);
				$currentsister=array_shift($sisterids);
				$pagenavposition=getnavpositionarray($page_id);
				// display upper sister pages
				while(getnavpositionarray($currentsister)<$pagenavposition)
				{
					$this->listvars['link'][]=new AdminNavigatorBranch($currentsister,0,$level);
					$currentsister=array_shift($sisterids);
				}
				// display page
				$this->listvars['link'][]=new AdminNavigatorBranch($page_id,1,$level);

				// display lower sister pages
				while(count($sisterids))
				{
					$currentsister=array_shift($sisterids);
					$this->listvars['link'][]=new AdminNavigatorBranch($currentsister,0,$level);
				}
			}
			// display lower root pages
			while(count($roots))
			{
				$currentroot=array_shift($roots);
				$this->listvars['link'][]=new AdminNavigatorBranch($currentroot,0,0);
			}
		}
	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/adminnavigator.tpl");
	}
}



//
// Complete list of pages for Admin Navigator
// iterate over branch and create links
//
class AdminNavigatorHeader extends Template {

    function AdminNavigatorHeader()
    {
		parent::__construct();
		
		$this->vars['jumptopageform']= new JumpToPageForm(getprojectrootlinkpath()."admin/admin.php",array(),"left","_top");
		$this->stringvars['pagelistlink']= getprojectrootlinkpath()."admin/includes/pagelist.php?sid=".$this->stringvars['sid'].'&page='.$this->stringvars['page'];
		
    }

    // assigns templates
    function createTemplates()
    {
      $this->addTemplate("admin/adminnavigatorheader.tpl");
    }
}


//
// Complete list of pages for Admin Navigator
// iterate over branch and create links
//
class PageList extends Template {

    function PageList()
    {
		parent::__construct();
		
		$roots=getrootpages();
		for($i=0;$i<count($roots);$i++)
		{
			$this->listvars['navigator'][]=new AdminNavigatorBranch($roots[$i],50,0);
		}
		
    }

    // assigns templates
    function createTemplates()
    {
      $this->addTemplate("admin/pagelist.tpl");
    }
}
?>