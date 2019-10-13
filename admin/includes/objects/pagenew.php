<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."admin/includes/objects/forms.php";
require_once $projectroot."functions/pages.php";
require_once $projectroot."includes/objects/template.php";



//
//
//
class NewPageForm extends Template
{

    function NewPageForm($parentpage,$title="",$navtitle="",$ispublishable=false,$isrootchecked=false)
    {
        parent::__construct();

        $this->stringvars['actionvars']= makelinkparameters(array("page" => $this->stringvars['page']));

        $this->stringvars['page']=$parentpage;
        $this->stringvars['parentname']=title2html(getpagetitle($parentpage)).' ('.getpagetype($parentpage).')';
        $this->stringvars['pagetitle']=$title;
        $this->stringvars['navtitle']=$navtitle;

        $this->vars['rootcheckedform']= new CheckboxForm("root", "root", "Create main page:", $isrootchecked);
        $this->vars['is_publishable_yes']= new RadioButtonForm("", "ispublishable", "public", "Public page", $ispublishable);
        $this->vars['is_publishable_no']= new RadioButtonForm("", "ispublishable", "internal", "Internal page", !$ispublishable);


        $pagetypes=getpagetypes();
        $keys=array_keys($pagetypes);

        for($i=0;$i<count($keys);$i++)
        {
            $short=$keys[$i];
            $values[]=$short;
            $descriptions[]=$short.': '.input2html($pagetypes[$short]);
        }
        $this->vars['typeselection']= new OptionForm($keys[0], $values, $descriptions, "type", "Page type: ", 1);
        $this->vars['submitrow'] = new SubmitRow("create", "Create", true, true, "admin.php".$this->stringvars['actionvars'], $this->stringvars["jsid"]);
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/newpageform.tpl");
    }
}
?>
