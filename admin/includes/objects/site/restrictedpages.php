<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprüft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."includes/objects/template.php");
include_once($projectroot."admin/functions/publicusersmod.php");
include_once($projectroot."includes/objects/elements.php");


//
//
//
class SiteRestrictedPages extends Template {

	function SiteRestrictedPages()
  	{
  		parent::__construct();
  	
  		$pages=getrestrictedpages();
  		if(count($pages))
  		{
  			$this->stringvars["hasrestrictedpages"]="true";
		    for($i=0;$i<count($pages);$i++)
    		{
    			$this->listvars['restrictedpages'][]= new SiteRestrictedPage($pages[$i]);
    		}

  		}
  		else
  		{
  			$this->stringvars["norestrictedpages"]="true";
  		}
  	}

  	// assigns templates
  	function createTemplates()
  	{
    	$this->addTemplate("admin/site/restrictedpages.tpl");
  	}
}


//
//
//
class SiteRestrictedPage extends Template {

	function SiteRestrictedPage($page)
  	{
  		parent::__construct();
  	
  		$this->stringvars["linktopage"]=getprojectrootlinkpath()."admin/admin.php?page=".$page;
      	
      	$this->stringvars["page"]=$page;
      	$this->stringvars["pagetype"]=getpagetype($page);
      	$this->stringvars["pagetitle"]=text2html(getpagetitle($page));
      	
      	$accessusers=getallpublicuserswithaccessforpage($page);
		if(count($accessusers)==0)
    	{
      		$this->stringvars["accessuserlist"]='<p align="center">&mdash;</p>';
    	}
    	else
    	{
    		$this->stringvars["accessuserlist"]="";
      		for($j=0;$j<count($accessusers);$j++)
      		{
        		if($j>0) $this->stringvars["accessuserlist"].=' - ';
        		$this->stringvars["accessuserlist"].='<a href="?userid='.$accessusers[$j].'&type=public&action=siteuserperm&page='.$this->stringvars['page'].'">'.getpublicusername($accessusers[$j]).'</a>';
      		}
      	}
  	}

  	// assigns templates
  	function createTemplates()
  	{
    	$this->addTemplate("admin/site/restrictedpage.tpl");
  	}
}

?>