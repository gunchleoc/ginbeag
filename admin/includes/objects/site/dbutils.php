<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."includes/objects/template.php";
require_once $projectroot."includes/objects/elements.php";


//
//
//
class SiteDBUtilsBackupForm extends Template
{

    function SiteDBUtilsBackupForm()
    {
        parent::__construct();
        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["postaction"] = "backup";
        $linkparams["action"] = "sitedb";
        $this->stringvars['backupactionvars'] = makelinkparameters($linkparams);

        $linkparams["postaction"] = "cache";
        $this->stringvars['cacheactionvars'] = makelinkparameters($linkparams);
    }
    
    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/site/dbutilsbackupform.tpl");
    }
}


//
//
//
class SiteDBUtilsDBDump extends Template
{

    function SiteDBUtilsDBDump($dump)
    {
        parent::__construct();
        $this->stringvars['dump']=$dump;
    }
    
    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/site/dbutilsdbdump.tpl");
    }
}


//
//
//
class SiteDBUtilsTableHeader extends Template
{

    function SiteDBUtilsTableHeader($header)
    {
        parent::__construct();
        $this->stringvars['header']=$header;
    }
    
    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/site/dbutilsshowtableheader.tpl");
    }
}
?>