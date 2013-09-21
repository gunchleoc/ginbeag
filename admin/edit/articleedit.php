<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/functions/pagesmod.php");
include_once($projectroot."admin/functions/categoriesmod.php");
include_once($projectroot."admin/edit/edittext.php");
include_once($projectroot."includes/includes.php");
include_once($projectroot."includes/functions.php");
include_once($projectroot."admin/includes/adminelements.php");
include_once($projectroot."admin/includes/templates/adminforms.php");
include_once($projectroot."admin/includes/templates/adminelements.php");
include_once($projectroot."admin/includes/templates/adminarticlepage.php");
$sid=$_GET['sid'];
checksession($sid);

$page=$_GET['page'];

$articlepage=0;
if(isset($_GET['articlepage'])) $articlepage=$_GET['articlepage'];
elseif(isset($_POST['articlepage'])) $articlepage=$_POST['articlepage'];

$articlesection=0;
if(isset($_GET['articlesection'])) $articlesection=$_GET['articlesection'];
if(isset($_POST['articlesection'])) $articlesection=$_POST['articlesection'];

$offset=0;
if(isset($_GET['offset']))
{
  $articlepage=$_GET['offset']+1;
  $offset=$_GET['offset'];
}
$message="";

//print_r($_POST);
//print_r($_GET);

// *************************** actions ************************************** //

// page content actions

$pagelockmessage = getpagelock($page);
if(!$pagelockmessage)
{
  if(isset($_POST['articlesource']))
  {
    $author=$_POST['author'];
    $location=$_POST['location'];
    $day=$_POST['day'];
    $month=$_POST['month'];
    $year=$_POST['year'];
    $source=$_POST['source'];
    $sourcelink=$_POST['sourcelink'];
    updatearticlesource($page,$author,$location,$day,$month,$year,$source,$sourcelink);
    updateeditdata($page, $sid);
    editarticleforms($page,"Updated Source");
  }
  elseif(isset($_POST['articlesynopsisimage']))
  {
    $imagefilename=trim($_POST['imagefilename']);
    $imagealign=$_POST['imagealign'];
    $imagevalign=$_POST['imagevalign'];
    updatearticlesynopsisimage($page,$imagefilename,$imagealign,$imagevalign);
    updateeditdata($page, $sid);
    editarticleforms($page,"Updated Synopsis Image");
  }
  elseif(isset($_POST['removecat']))
  {
    $selectedcats=$_POST['selectedcat'];
    removepagecategories($page,$selectedcats);
    editarticleforms($page,"Removed categories from page");
  }
  elseif(isset($_POST['addcat']))
  {
    $selectedcats=$_POST['selectedcat'];
    addpagecategories($page,$selectedcats);
    editarticleforms($page,"Added new categories for page");
  }
  elseif(isset($_POST['addarticlepage']))
  {
    $lastpage=numberofarticlepages($page);
    if(getlastarticlesection($page,$lastpage))
    {
      addarticlepage($page);
      editarticleforms($page);
    }
    else
    {
      editarticleforms($page,"You cannot add a page after an empty page");
    }
  }
  elseif(isset($_POST['addarticlesection']))
  {
    addarticlesection($page,$articlepage);
    editarticlepageforms($page,$articlepage,"Added section");
  }
  elseif(isset($_POST['editsectiontitle']))
  {
    $sectiontitle=$_POST['sectiontitle'];
    updatearticlesectiontitle($articlesection,$sectiontitle);
    updateeditdata($page, $sid);
    editarticlepageforms($page,$articlepage,"Updated Section Title");
  }
  elseif(isset($_POST['editsectionimage']))
  {
    $imagefilename=trim($_POST['imagefilename']);
    $imagealign=$_POST['imagealign'];
    $imagevalign=$_POST['imagevalign'];
    updatearticlesectionimage($articlesection,$imagefilename,$imagealign,$imagevalign);
    updateeditdata($page, $sid);
    editarticlepageforms($page,$articlepage,"Updated Section Image");
  }
  elseif(isset($_POST['deletesection']))
  {
    deletesectionconfirm($page, $articlepage, $articlesection);
  }
  elseif(isset($_POST['confirmdeletesection']))
  {
    deletearticlesection($articlesection);
    updateeditdata($page, $sid);
    editarticlepageforms($page,$articlepage,"Deleted section");
  }
  elseif(isset($_POST['nodeletesection']))
  {
    editarticlepageforms($page,$articlepage,"Deleting aborted");
  }
  elseif(isset($_POST['deletelastarticlepage']))
  {
    $noofpages=numberofarticlepages($page);
    if(!getlastarticlesection($page,$noofpages))
    {
      deletelastarticlepage($page);
      updateeditdata($page, $sid);
      if($noofpages>1)
      {
        editarticlepageforms($page,$articlepage-1,'Deleted page '.$articlepage);
      }
      else
      {
        editarticleforms($page,'Deleted page '.$articlepage);
      }
    }
    else
    {
      editarticlepageforms($page,$articlepage,"Could not delete page because there are still some sections in it");
    }
  }
  elseif(isset($_POST['movesectionup']))
  {
    $articlepage=movearticlesection($_GET['articlesection'],$_GET['articlepage'], "up");
    updateeditdata($page, $sid);
    editarticlepageforms($page,$articlepage,"Moved section up");
  }
  elseif(isset($_POST['movesectiondown']))
  {
    $articlepage=movearticlesection($_GET['articlesection'],$_GET['articlepage'], "down");
    updateeditdata($page, $sid);
    editarticlepageforms($page,$articlepage,"Moved section down");
  }
  // default for section view
  elseif($articlepage || $articlesection)
  {
    editarticlepageforms($page,$articlepage);
  }
  else
  {
    editarticleforms($page);
  }
}
else
{
  $editpage = new DonePage($page,"This page is already being edited",$pagelockmessage,"&action=editcontents&override=on","articleedit.php","Override lock and edit");
  print($editpage->toHTML());
}

// *************************** article ************************************** //

//
//
//
function editarticleforms($page,$message="")
{
  $contents = new EditArticle($page,$message);
  print($contents->toHTML());
}

//
//
//
function editarticlepageforms($page, $articlepage,$message="")
{
  $contents = new EditArticlePage($page,$articlepage,$message);
  print($contents->toHTML());
}

//
//
//
function deletesectionconfirm($page, $articlepage, $articlesection_id)
{
  $content = new DeleteArticleSectionConfirm($page,$articlepage,$articlesection_id);
  print($content->toHTML());
}

?>
