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
  		
  		$this->stringvars['actionvars']='?page='.$this->stringvars['page'].'&action=siteiotd&postaction=savesite';

  		$properties=getproperties();
  		$this->stringvars['hiddenvars']='<input type="hidden" name="oldpotdcats" value="'.$properties["Picture of the Day Categories"].'">';
  		
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
      			array_push($potdcatnames,getcategoryname($potdcats[$j]));
    		}
    		sort($potdcatnames);
    		$potdcatlistoutput=implode(", ",$potdcatnames);
  		}
  		
  		
        if($properties["Display Picture of the Day"])
        {
        	$this->stringvars['potdon']="true";
        }
        else
        {
        	$this->stringvars['potdoff']="true";
        }
        
        $this->stringvars['potdlist']= $potdcatlistoutput;
        $this->vars['potdcatform']=new CategorySelectionForm(true,"",CATEGORY_IMAGE);

		if($properties["Display Article of the Day"]) $this->stringvars['aotdon']="true";
		else $this->stringvars['aotdoff']= "true";
       
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