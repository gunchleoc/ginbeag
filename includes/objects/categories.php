<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));

include_once($projectroot."includes/objects/template.php");
include_once($projectroot."language/languages.php");

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
    		$this->stringvars['catlistoutput']=title2html($catlistoutput);
  		}
  		elseif($printheader) $this->stringvars['catlistoutput']=getlang("categorylist_none");
  		else $this->stringvars['catlistoutput']="";
    }

    // assigns templates
    function createTemplates()
    {
      $this->addTemplate("categorylist.tpl");
    }
}
?>