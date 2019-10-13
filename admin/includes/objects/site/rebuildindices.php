<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprüft wird
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."includes/objects/template.php";
require_once $projectroot."includes/objects/elements.php";

//
//
//
class SiteRebuildIndices extends Template
{

    function SiteRebuildIndices($message="")
    {
        parent::__construct();

        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["postaction"] = "restrictedpages";
        $linkparams["action"] = "siteind";
        $this->stringvars['actionvars'] = makelinkparameters($linkparams);

        if(strlen($message) > 0) { $this->stringvars['message'] = $message;
        }
    }
    
    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/site/rebuildindices.tpl");
    }
}

?>