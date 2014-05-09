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
class SiteIPBanIP extends Template {

	function SiteIPBanIP($ip)
	{
		parent::__construct();
		
		$linkparams["page"] = $this->stringvars['page'];
		$linkparams["action"] = "siteipban";
		$this->stringvars['actionvars'] = makelinkparameters($linkparams);

		$this->stringvars['ip']=$ip;
		$this->stringvars['hiddenvars']='<input type="hidden" name="ip" value="'.$ip.'" />';
	}
	
	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/site/ipbanip.tpl");
	}
}




//
//
//
class SiteIPBan extends Template {

	function SiteIPBan()
	{
		parent::__construct();

		$linkparams["page"] = $this->stringvars['page'];
		$linkparams["action"] = "siteipban";
		$this->stringvars['actionvars'] = makelinkparameters($linkparams);
		
		$ips=getalladdbannedipforrestrictedpages();
		$noofips=count($ips);
		if($noofips>0)
		{
			for($i=0;$i<$noofips;$i++)
			{
				$this->listvars['ips'][] = new SiteIPBanIP($ips[$i]);
			}
		}
		else
		{
			$this->stringvars['noips'] ='No IPs have been banned.';
		}
	}
	
	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/site/ipban.tpl");
	}
}

?>