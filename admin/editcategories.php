<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

// check legal vars
include($projectroot."admin/includes/legaladminvars.php");

include_once($projectroot."includes/includes.php");
include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/functions/categoriesmod.php");
include_once($projectroot."admin/includes/objects/categories.php");
include_once($projectroot."admin/includes/objects/adminmain.php");
include_once($projectroot."includes/functions.php");

checksession();


//print_r($_GET);
//print_r($_POST);


if(isset($_GET['page']))
{
	$page=$_GET['page'];
}
else $page="0";

$message="";
$title="";

if(isset($_POST['addsubtext'])) $addsubtext=fixquotes(trim($_POST['addsubtext']));
else $addsubtext="";

if(isset($_POST['editcattext'])) $editcattext=fixquotes(trim($_POST['editcattext']));
else $editcattext="";

if(isset($_POST['selectedcat'])) $selectedcat=$_POST['selectedcat'];
else $selectedcat=-1;

if(isset($_POST['cattype'])) $cattype=$_POST['cattype'];
elseif(isset($_GET['cattype'])) $cattype=$_GET['cattype'];
else $cattype=CATEGORY_ARTICLE;


if(isset($_POST['addsub']))
{
	$title="Added category";
	
	if($selectedcat<0)
	{
		$message="Please select a parent category";
	}
	elseif(strlen($addsubtext)<1)
	{
		$message="You cannot create a category that has no name";
	}
	else
	{
		addcategory($selectedcat, $addsubtext, $cattype);
		$message='Added "'.$addsubtext.'" to "'.title2html(getcategoryname($selectedcat, $cattype)).'"';
	}
}
elseif(isset($_POST['editcat']))
{
	$title="Modified category";
	
	if($selectedcat<0)
	{
		$message="Please select a category for renaming";
	}
	elseif(strlen($editcattext)<1)
	{
		$message="You cannot enter an empty name";
	}
	elseif(isroot($selectedcat, $cattype))
	{
		$message="You cannot rename the root category";
	}
	else
	{
		$name=title2html(getcategoryname($selectedcat, $cattype));
		renamecategory($selectedcat, $editcattext, $cattype);
		$message='"'.$name.'" renamed to "'.$editcattext.'"';
	}
}
elseif(isset($_POST['delcat']))
{
	$title="Deleting category";
	if($selectedcat<0)
	{
		$message="Please select a category for deleting";
	}
	if(!isset($_POST['delcatconfirm']))
	{
		$message="Please select 'Confirm delete' when deleting a category";
	}
	elseif(isroot($selectedcat, $cattype))
	{
		$message="You cannot delete the root category";
	}
	elseif(getcategorychildren($selectedcat, $cattype))
	{
		$message="You cannot delete a category that still has subcategories";
	}
	else
	{
		$name=title2html(getcategoryname($selectedcat, $cattype));
		deletecategory($selectedcat, $cattype);
		$message='Deleted "'.$name.'"';
	}
}
elseif(isset($_POST['movecat']))
{
	$title="Moving Category";
	if(!isset($_POST['movefrom']))
	{
		$message='Please select a category to move';
	}
	elseif(!isset($_POST['moveto']))
	{
		$message='Please select a destination category to move to';
	}
	else
	{
		$movefrom=$_POST['movefrom'];
		$moveto=$_POST['moveto'];
		
		if(isdescendant($movefrom, $moveto, $cattype))
		{
			$message='You are not allowed to move "'.title2html(getcategoryname($movefrom, $cattype)).'" to "'.title2html(getcategoryname($moveto, $cattype)).'".';
		}
		else if($movefrom == $moveto)
		{
			$message="You can't move a category into itself";
		}
		else
		{
		    $success=movecategory($movefrom, $moveto, $cattype);
	    	if($success)
	    	{
				$message='Moved "'.title2html(getcategoryname($movefrom, $cattype)).'" to "'.title2html(getcategoryname($moveto, $cattype)).'".';
	    	}
	    	else
	    	{
				$message='Failed to move "'.title2html(getcategoryname($movefrom, $cattype)).'" to "'.title2html(getcategoryname($moveto, $cattype)).'".';
	      	}
    	}
	}
}
else
{
	$title="Edit Categories";
}

$content = new AdminMain($page,"editcategories",$message,new AdminCategories($title,$addsubtext, $editcattext, $cattype));
print($content->toHTML());
$db->closedb();
?>
