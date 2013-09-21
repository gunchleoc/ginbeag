<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));

include_once($projectroot."functions/pages.php");
include_once($projectroot."includes/templates/template.php");
include_once($projectroot."includes/templates/elements.php");
include_once($projectroot."includes/templates/page.php");
include_once($projectroot."includes/includes.php");

//
// main class for sitemap
//
class Sitemap extends Template {

  var $pagetype="";

  function Sitemap($showhidden=false)
  {
    $this->vars['pageintro'] = new PageIntro(getlang("pagetitle_sitemap"),"","");
      
      $roots=getrootpages();
      for($i=0;$i<count($roots);$i++)
      {
        if(displaylinksforpagearray($roots[$i]) || $showhidden)
        {
          $this->listvars['subpages'][]= new NavigatorBranch($roots[$i],"simple","simple","contents",50000000,true,0,"",$showhidden);
        }
      }
    $this->createTemplates();
    
//    print_r($this->vars);
  }
  
  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("sitemap.tpl");
  }
}
?>
