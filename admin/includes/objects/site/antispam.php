<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."includes/objects/template.php";
require_once $projectroot."includes/objects/elements.php";
require_once $projectroot."admin/includes/objects/forms.php";


//
//
//
class SiteAntispam extends Template
{

    function SiteAntispam()
    {
        global $projectroot;
        parent::__construct();

        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["postaction"] = "savesite";
        $linkparams["action"] = "sitespam";
        $this->stringvars['actionvars']= makelinkparameters($linkparams);

        $this->stringvars['hiddenvars'] = $this->makehiddenvars(array("postaction" => "savesite"));


        $sql = new SQLSelectStatement(ANTISPAM_TABLE, array('property_name', 'property_value'));
        $variables = $sql->fetch_two_columns();

        // Math CAPTCHA
        $this->vars['usemathcaptcha_yes'] = new RadioButtonForm($this->stringvars['jsid'], "usemathcaptcha", 1, "Yes", $variables['Use Math CAPTCHA'], "right");
        $this->vars['usemathcaptcha_no'] = new RadioButtonForm($this->stringvars['jsid'], "usemathcaptcha", 0, "No", !$variables['Use Math CAPTCHA'], "right");
        $this->vars['mathcaptcha_submitrow']= new SubmitRow("mathcaptcha", "Submit", true);

        // Spam Words
        $this->stringvars['spamwords_subject'] = $variables['Spam Words Subject'];
        $this->stringvars['spamwords_content'] = $variables['Spam Words Content'];

        $this->vars['spamwords_submitrow']= new SubmitRow("spamwords", "Submit", true);
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/site/antispam.tpl");
    }
}


?>
