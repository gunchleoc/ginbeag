<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

// check legal vars
include($projectroot."admin/includes/legaladminvars.php");

include_once($projectroot."includes/includes.php");
include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/functions/categoriesmod.php");
include_once($projectroot."admin/includes/templates/admincategories.php");


$sid=$_GET['sid'];
checksession($sid);

if(isset($_POST['addsubtext'])) $addsubtext=trim($_POST['addsubtext']);
else $addsubtext="";

if(isset($_POST['editcattext'])) $editcattext=trim(str_replace(chr(160)," ",$_POST['editcattext']));
else $editcattext="";

//print_r($_GET);
//print_r($_POST);

$message="";
$title="";


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
    $message='"'.title2html($addsubtext).'" to "'.title2html(getcategoryname($_POST['selectedcat'])).'"';
    unset($addsubtext);
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
    $message='"'.$name.'" renamed to "'.utf8_decode(title2html($editcattext)).'"';
    unset($editcattext);
  }
}
elseif(isset($_POST['delcat']))
{
  $title="Deleting category";
  if(!isset($_POST['selectedcat']))
  {
    $message="Please select a category for deleting";
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
  $movefrom=$_POST['movefrom'];
  $moveto=$_POST['moveto'];

  $title="Moving Category";
  if(!isset($_POST['movefrom']))
  {
    $message='<p class="highlight">Please select a category to move</p>';
  }
  elseif(!isset($_POST['moveto']))
  {
    $message='<p class="highlight">Please select a destination category to move to</p>';
  }
  elseif(isdescendant($movefrom,$moveto))
  {
    $message='<p class="highlight">You are not allowed to move "'.title2html(getcategoryname($movefrom)).'" to "'.title2html(getcategoryname($moveto)).'".</p>';
  }
  else
  {
    $success=movecategory($movefrom,$moveto);
    if($success)
    {
      $message='<p class="highlight">Moved "'.title2html(getcategoryname($movefrom)).'" to "'.title2html(getcategoryname($moveto)).'".</p>';
    }
    else
    {
      $message='<p class="highlight">Failed to move "'.title2html(getcategoryname($movefrom)).'" to "'.title2html(getcategoryname($moveto)).'".</p>';
    }
  }
}
else
{
  $title="Edit Categories";
}
$content = new AdminCategories($title,$message,$addsubtext, $editcattext);
print($content->toHTML())
?>
