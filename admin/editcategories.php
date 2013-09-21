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


if(isset($_GET['sid']))
{
  $sid=$_GET['sid'];
}
else $sid="";
checksession($sid);

if(isset($_GET['page']))
{
  $page=$_GET['page'];
}
else $page="0";

$message="";
$title="";

if(isset($_POST['addsubtext'])) $addsubtext=fixquotes(trim($_POST['addsubtext']));
else $addsubtext="";


if(isset($_POST['editcattext'])) $editcattext=fixquotes(trim(str_replace(chr(160)," ",$_POST['editcattext'])));
else $editcattext="";

//print_r($_GET);
//print_r($_POST);



if(isset($_POST['addsub']))
{
  $title="Added category";

  if(!isset($_POST['selectedcat']))
  {
    $message="Please select a parent category";
  }
  elseif(strlen($addsubtext)<1)
  {
    $message="You cannot create a category that has no name";
  }
  else
  {
    addcategory($_POST['selectedcat'],$addsubtext);
    $message='Added "'.$addsubtext.'" to "'.title2html(getcategoryname($_POST['selectedcat'])).'"';
    unset($_POST['addsubtext']);
  }
}
elseif(isset($_POST['editcat']))
{
  $title="Modified category";
  
  if(!isset($_POST['selectedcat']))
  {
    $message="Please select a category for renaming";
  }
  elseif(strlen($editcattext)<1)
  {
    $message="You cannot enter an empty name";
  }
  elseif(isroot($_POST['selectedcat']))
  {
    $message="You cannot rename the root category";
  }
  else
  {
    $name=title2html(getcategoryname($_POST['selectedcat']));
    renamecategory($_POST['selectedcat'],$editcattext);
    // todo handle encoding somewhere else
    $message='"'.$name.'" renamed to "'.$editcattext.'"';
    unset($_POST['editcattext']);
  }
}
elseif(isset($_POST['delcat']))
{
  $title="Deleting category";
  if(!isset($_POST['selectedcat']))
  {
    $message="Please select a category for deleting";
  }
  if(!isset($_POST['delcatconfirm']))
  {
    $message="Please select 'Confirm delete' when deleting a category";
  }

  elseif(isroot($_POST['selectedcat']))
  {
    $message="You cannot delete the root category";
  }
  elseif(getcategorychildren($_POST['selectedcat']))
  {
    $message="You cannot delete a category that still has subcategories";
  }
  else
  {
    $name=title2html(getcategoryname($_POST['selectedcat']));
    deletecategory($_POST['selectedcat']);
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

  	if(isdescendant($movefrom,$moveto))
  	{
    	$message='You are not allowed to move "'.title2html(getcategoryname($movefrom)).'" to "'.title2html(getcategoryname($moveto)).'".';
  	}
  	else if($movefrom == $moveto)
  	{
  		$message="You can't move a category into itself";
  	}
  	else
  	{

	    $success=movecategory($movefrom,$moveto);
    	if($success)
    	{
      		$message='Moved "'.title2html(getcategoryname($movefrom)).'" to "'.title2html(getcategoryname($moveto)).'".';
    	}
    	else
    	{
      		$message='Failed to move "'.title2html(getcategoryname($movefrom)).'" to "'.title2html(getcategoryname($moveto)).'".';
      	}
    }
  }
}
else
{
  $title="Edit Categories";
}

$content = new AdminMain($page,"editcategories",$message,new AdminCategories($title,$addsubtext, $editcattext));
print($content->toHTML());
$db->closedb();
?>
