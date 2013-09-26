<?php
$projectroot=dirname(__FILE__);

// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

//include_once($projectroot."functions/pages.php");
include_once($projectroot."includes/objects/template.php");
//include_once($projectroot."includes/includes.php");
//include_once($projectroot."includes/objects/forms.php");
//include_once($projectroot."functions/images.php");
//include_once($projectroot."admin/includes/objects/images.php");


//
// $page: caller
// $moveid: Page to be moved
//
class MovePageForm extends Template {

  function MovePageForm($page,$moveid)
  {
    parent::__construct($moveid);
    $this->stringvars['moveid']=$moveid;
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/movepageform.tpl");
  }
}

?>