<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprüft wird
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."includes/objects/template.php";
require_once $projectroot."admin/functions/publicusersmod.php";
require_once $projectroot."includes/objects/elements.php";

//
//
//
class SiteIPBanIP extends Template
{

    function SiteIPBanIP($ip)
    {
        parent::__construct();
        
        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["action"] = "siteipban";
        $this->stringvars['actionvars'] = makelinkparameters($linkparams);
        $this->stringvars['hiddenvars'] = $this->makehiddenvars(array("ip" => $ip));
        $this->stringvars['ip']=$ip;
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
class SiteIPBan extends Template
{

    function SiteIPBan()
    {
        parent::__construct();

        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["action"] = "siteipban";
        $this->stringvars['actionvars'] = makelinkparameters($linkparams);
        
        $ips=getalladdbannedipforrestrictedpages();
        $noofips=count($ips);
        if($noofips>0) {
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