<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."includes/objects/template.php");
include_once($projectroot."includes/objects/elements.php");
include_once($projectroot."includes/objects/forms.php");


//
// Temmplating for Categories Admin
//
class CategoryMoveForm extends Template {
	function CategoryMoveForm()
	{
		parent::__construct();
		
		$this->vars['fromform']=new CategorySelectionForm(false,"",15,array(),false,"movefrom","Select a category to move:");
		$this->vars['toform']=new CategorySelectionForm(false,"",15,array(),false,"moveto","Select destination:");
		$this->stringvars['actionvars']= "?page=".$this->stringvars['page']."&action=movecat";
		$this->vars['submitrow']=new SubmitRow("movecat","Move");
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
		parent::__construct();
		
		$this->stringvars['addsubtext']=$addsubtext;
		$this->stringvars['editcattext']=$editcattext;
		$this->stringvars['actionvars']= "?page=".$this->stringvars['page']."&action=editcat";
		$this->vars['categoryselection']=new CategorySelectionForm(false,"",15,array(),"assignSelectValue(this, editcattext)","selectedcat","Select a category for editing:");
		$this->vars['deleteconfirm']= new CheckboxForm("delcatconfirm","Delete selected","Confirm delete",false,"right");
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

	function AdminCategories($title,$addsubtext, $editcattext)
	{
		parent::__construct();
		
		$this->vars['categorymoveform']= new CategoryMoveForm();
		$this->vars['editcategoryform']= new EditCategoryForm($addsubtext, $editcattext);
		$this->stringvars['linktarget']= "admin.php?page=".$this->stringvars['page'];
	}
	
	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/admincategories.tpl");
	}
}
?>