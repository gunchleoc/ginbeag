<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

//include_once($projectroot."functions/categories.php");
include_once($projectroot."includes/templates/template.php");
//include_once($projectroot."includes/includes.php");
include_once($projectroot."includes/templates/forms.php");


//
// Temmplating for Categories Admin
//
class CategoryMoveForm extends Template {
  function CategoryMoveForm()
  {
    global $sid;
    
    $this->stringvars['sid']=$sid;

    $this->vars['fromform']=new CategorySelectionForm(false,15,array(),false,"movefrom");
    $this->vars['toform']=new CategorySelectionForm(false,15,array(),false,"moveto");
    $this->createTemplates();

  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/categorymoveform.tpl");
  }
}


//
// Temmplating for Categories Admin
//
class EditCategoryForm extends Template {

  function EditCategoryForm($addsubtext, $editcattext)
  {
    global $sid;

    $this->stringvars['sid']=$sid;
    $this->stringvars['addsubtext']=input2html($addsubtext);
    $this->stringvars['editcattext']=input2html($editcattext);
    
    $this->vars['categoryselection']=new CategorySelectionForm(false,15,array(),"assignSelectValue(this, editcattext)");

    $this->createTemplates();

  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/editcategoryform.tpl");
  }
}




//
// Temmplating for Categories Admin
//
class AdminCategories extends Template {

  function AdminCategories($title,$message,$addsubtext, $editcattext)
  {
    global $sid;

    $this->stringvars['sid']=$sid;
    $this->vars['categorymoveform']= new CategoryMoveForm();
    $this->vars['editcategoryform']= new EditCategoryForm($addsubtext, $editcattext);
    
    $this->vars['header'] = new HTMLHeader($title,"Edit Categories",$message);
    $this->vars['footer'] = new HTMLFooter();

    $this->createTemplates();

  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/admincategories.tpl");
  }
}
?>
