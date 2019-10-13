<?php
$projectroot=dirname(__FILE__);

// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."includes/objects/template.php";

class AdminMessage extends Template
{

    function __construct($message, $iserror)
    {
        parent::__construct("adminmessage");
        if($iserror) { $this->stringvars["error"] = "true";
        } else { $this->stringvars["error"] = "false";
        }
        $this->stringvars["alertmessage"] = addslashes(strip_tags($message));
        
        if(strlen($message) > 0) {
            $this->stringvars['javascript']=$this->getScripts();
            $this->stringvars["message"] = addslashes($message);
        }
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/adminmessage.tpl");
    }
}
?>
