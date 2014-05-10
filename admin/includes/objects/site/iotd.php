<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprüft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."includes/objects/template.php");
include_once($projectroot."includes/objects/forms.php");
include_once($projectroot."includes/objects/elements.php");
include_once($projectroot."admin/includes/objects/forms.php");

//
//
//
class SiteRandomItems extends Template {

	function SiteRandomItems()
  	{
  		parent::__construct();

		$linkparams["page"] = $this->stringvars['page'];
		$linkparams["postaction"] = "savesite";
		$linkparams["action"] = "siteiotd";
		$this->stringvars['actionvars'] = makelinkparameters($linkparams);

  		$properties=getproperties();
		$this->stringvars['hiddenvars'] = $this->makehiddenvars(array("oldpotdcats" => $properties["Picture of the Day Categories"]));

  		$potdcats=explode(",",$properties["Picture of the Day Categories"]);
  		$potdcatnames=array();
  		if(!count($potdcats))
  		{
    		$potdcatlistoutput=getlang("form_cat_allcats");
  		}
  		else
  		{
    		for($j=0;$j<count($potdcats);$j++)
    		{
				array_push($potdcatnames,getcategoryname($potdcats[$j], CATEGORY_IMAGE));
    		}
    		sort($potdcatnames);
			$potdcatlistoutput=title2html(implode(", ",$potdcatnames));
  		}

		$this->vars['displaypotd_yes'] = new RadioButtonForm($this->stringvars['jsid'], "displaypotd", 1, "Yes", $properties["Display Picture of the Day"], "right");
	    $this->vars['displaypotd_no'] = new RadioButtonForm($this->stringvars['jsid'], "displaypotd", 0, "No", !$properties["Display Picture of the Day"], "right");

        $this->stringvars['potdlist']= $potdcatlistoutput;
        $this->vars['potdcatform']=new CategorySelectionForm(true,"",CATEGORY_IMAGE);

		$this->vars['displayaotd_yes'] = new RadioButtonForm($this->stringvars['jsid'], "displayaotd", 1, "Yes", $properties["Display Article of the Day"], "right");
	    $this->vars['displayaotd_no'] = new RadioButtonForm($this->stringvars['jsid'], "displayaotd", 0, "No", !$properties["Display Article of the Day"], "right");
       
        $this->stringvars['aotdpages']= $properties["Article of the Day Start Pages"];
        
        $this->vars['submitrow']= new SubmitRow("submit","Submit",true);
  	}

  	// assigns templates
  	function createTemplates()
  	{
    	$this->addTemplate("admin/site/iotd.tpl");
  	}
}
?>