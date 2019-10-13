<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprüft wird
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."includes/objects/template.php";
require_once $projectroot."admin/includes/objects/forms.php";
require_once $projectroot."admin/includes/objects/editor.php";
require_once $projectroot."admin/includes/objects/imageeditor.php";


//
//
//
class EditPageIntro extends Template
{
    function EditPageIntro($page)
    {
        parent::__construct($page, array(0 => "includes/javascript/jcaret.js"));
        $this->stringvars['javascript']=$this->getScripts();

        $this->vars['intro']= new Editor($page, 0, "pageintro", "Synopsis");
        $this->vars['imageeditor'] = new ImageEditor($page, 0, "pageintro", getpageintro($page));
        $this->vars['navigationbuttons']= new PageEditNavigationButtons(new GeneralSettingsButton(), new EditPageContentsButton());
    }
    
    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/edit/editpageintro.tpl");
    }
}

?>