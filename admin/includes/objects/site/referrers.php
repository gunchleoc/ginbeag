<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprüft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."includes/objects/template.php");
include_once($projectroot."admin/functions/referrersmod.php");
include_once($projectroot."includes/objects/elements.php");

//
//
//
class SiteReferrers extends Template {

	function SiteReferrers()
	{
		parent::__construct();
		
		$this->stringvars['actionvars']='?sid='.$this->stringvars['sid'].'&page='.$this->stringvars['page'].'&action=sitereferrers';
		
		$blockedrefs=getblockedreferrers();
		
		$noofrefs =count($blockedrefs);
		
		if($noofrefs>0)
		{
			for($i=0;$i<$noofrefs;$i++)
			{
				$this->listvars['blockedreferrer'][] = new SiteReferrer($blockedrefs[$i]);
			}
		}
	}
	
	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/site/referrers.tpl");
	}
}


//
//
//
class SiteReferrer extends Template {

  function SiteReferrer($referrer)
  {
  	parent::__construct();
  	
  	$this->stringvars['actionvars']='?sid='.$this->stringvars['sid'].'&page='.$this->stringvars['page'].'&action=sitereferrers';
  	
  	$this->stringvars["referrer"]=$referrer;
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/site/referrer.tpl");
  }
}



//
//
//
class SiteReferrerUnblockForm extends Template {

  function SiteReferrerUnblockForm($referrer)
  {
  	parent::__construct();
  	
  	$this->stringvars['actionvars']='?sid='.$this->stringvars['sid'].'&page='.$this->stringvars['page'].'&action=sitereferrers';
  	
  	$this->stringvars["referrer"]=$referrer;
  	
  	$this->vars["submitrow"]=new SubmitRow("confirmunblock","Yes, please unblock",false,true);
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/site/referrerunblockform.tpl");
  }
}
?>