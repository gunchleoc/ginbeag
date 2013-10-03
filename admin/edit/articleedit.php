<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/functions/pagecontent/articlepagesmod.php");
include_once($projectroot."admin/includes/objects/edit/articlepage.php");
include_once($projectroot."includes/objects/elements.php");
include_once($projectroot."admin/includes/objects/adminmain.php");

if(isset($_GET['sid'])) $sid=$_GET['sid'];
else $sid="";
checksession($sid);

if(isset($_GET['page'])) $page=$_GET['page'];
else $page=0;

if(isset($_GET['offset'])) $articlepage=$_GET['offset']+1;
else if(isset($_GET['articlepage'])) $articlepage=$_GET['articlepage'];
else $articlepage=1;

if(isset($_GET['articlesection'])) $articlesection=$_GET['articlesection'];
else $articlesection=0;

$message="";

//print_r($_POST);
//print_r($_GET);

// *************************** actions ************************************** //

// page content actions

$message = getpagelock($page);
if(!$message)
{
  if(isset($_POST['addarticlepage']))
  {
    $lastpage=numberofarticlepages($page);
    if(getlastarticlesection($page,$lastpage))
    {
      addarticlepage($page);
      $editpage = new EditArticlePage($lastpage+1);
    }
    else
    {
      $editpage = new EditArticlePage($lastpage);
      $message="You cannot add a page after an empty page";
    }
  }
  elseif(isset($_POST['addarticlesection']))
  {
    addarticlesection($page,$articlepage);
    $editpage = new EditArticlePage($articlepage);
    $message="Added section";
  }
  elseif(isset($_POST['deletesection']))
  {
    $editpage = new DeleteArticleSectionConfirm($articlepage, $articlesection);
  }
  elseif(isset($_POST['confirmdeletesection']))
  {
    deletearticlesection($articlesection);
    updateeditdata($page, $sid);
    $editpage = new EditArticlePage($articlepage);
    $message="Deleted section";
  }
  elseif(isset($_POST['nodeletesection']))
  {
    $editpage = new EditArticlePage($articlepage);
    $message="Deleting aborted";
  }
  elseif(isset($_POST['deletelastarticlepage']))
  {
		$noofpages=numberofarticlepages($page);
		if($noofpages>1 )
		{
			if(!getlastarticlesection($page,$noofpages))
			{
				deletelastarticlepage($page);
				updateeditdata($page, $sid);
				$editpage = new EditArticlePage($articlepage-1);
				$message = 'Deleted page #'.$articlepage.' of this article';
			}
			else
			{
				$editpage = new EditArticlePage($articlepage);
				$message="Could not delete page because there are still some sections in it";
			}
		}
		else
		{
			$editpage = new EditArticlePage(1);
			$message="Could not delete page because there is only 1 page left";
		}
  }
  elseif(isset($_POST['movesectionup']))
  {
    $articlepage=movearticlesection($_GET['articlesection'],$_GET['articlepage'], "up");
    updateeditdata($page, $sid);
    $editpage = new EditArticlePage($articlepage);
    $message="Moved section up";
  }
  elseif(isset($_POST['movesectiondown']))
  {
    $articlepage=movearticlesection($_GET['articlesection'],$_GET['articlepage'], "down");
    updateeditdata($page, $sid);
    $editpage = new EditArticlePage($articlepage);
    $message="Moved section down";
  }
  $editpage = new EditArticlePage($articlepage);
}
else
{
	$editpage = new DonePage("This page is already being edited","&action=show","admin.php","View this page");
	print($editpage->toHTML());
}
$content = new AdminMain($page,"editcontents",$message,$editpage);
print($content->toHTML());
?>