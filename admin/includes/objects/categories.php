<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."includes/objects/template.php");
include_once($projectroot."includes/objects/elements.php");
include_once($projectroot."includes/objects/forms.php");


//
// Templating for Categories Admin
// TODO split for category types
//
class CategoryMoveForm extends Template {
	function CategoryMoveForm($cattype)
	{
		parent::__construct();
		
		$this->vars['fromform']=new CategorySelectionForm(false,"",$cattype,15,array(),false,"movefrom","Select a category to move:");
		$this->vars['toform']=new CategorySelectionForm(false,"",$cattype,15,array(),false,"moveto","Select destination:");
		$this->stringvars['actionvars']= "?page=".$this->stringvars['page']."&action=movecat&cattype=".$cattype;
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

	function EditCategoryForm($addsubtext, $editcattext, $cattype)
	{
		parent::__construct();
		
		$this->stringvars['addsubtext']=$addsubtext;
		$this->stringvars['editcattext']=$editcattext;
		$this->stringvars['actionvars']= "?page=".$this->stringvars['page']."&action=editcat&cattype=".$cattype;
		$this->vars['categoryselection']=new CategorySelectionForm(false,"",$cattype,15,array(),"assignSelectValue(this, editcattext)","selectedcat","Select a category for editing:");
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

	function AdminCategories($title,$addsubtext, $editcattext, $cattype)
	{
		parent::__construct();
		
		$this->vars['categorymoveform']= new CategoryMoveForm($cattype);
		$this->vars['editcategoryform']= new EditCategoryForm($addsubtext, $editcattext, $cattype);
		$this->stringvars['linktarget']= "admin.php?page=".$this->stringvars['page'];
		$this->stringvars['actionvars']= "?page=".$this->stringvars['page']."&action=selectcattype";
		$this->vars['cattypeformimage']= new RadioButtonForm($this->stringvars["jsid"], "cattype", CATEGORY_IMAGE, "Image", $cattype == CATEGORY_IMAGE, "right");
		$this->vars['cattypeformpage']= new RadioButtonForm($this->stringvars["jsid"], "cattype", CATEGORY_ARTICLE, "Page", $cattype != CATEGORY_IMAGE, "right");
		if($cattype == CATEGORY_IMAGE)
		{
			$this->stringvars['edittitle']= "Edit an Image Category";
			$this->stringvars['movetitle']= "Move an Image Category";
		}
		else
		{
			$this->stringvars['edittitle']= "Edit a Page Category";
			$this->stringvars['movetitle']= "Move a Page Category";
		}
	}
	
	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/admincategories.tpl");
	}
}
?>