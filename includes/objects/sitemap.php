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

  var $pagetype="";

  function Sitemap($showhidden=false)
  {
  	parent::__construct();
    $this->vars['pageintro'] = new PageIntro(getlang("pagetitle_sitemap"),"","");
      
      $roots=getrootpages();
      for($i=0;$i<count($roots);$i++)
      {
        if(displaylinksforpagearray($this->stringvars['sid'],$roots[$i]) || $showhidden)
        {
          $this->listvars['subpages'][]= new ContentNavigatorBranch($roots[$i],"simple","simple","contents",50000000,true,0,"",$showhidden);
        }
      }
  }
  
  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("sitemap.tpl");
  }
}
?>