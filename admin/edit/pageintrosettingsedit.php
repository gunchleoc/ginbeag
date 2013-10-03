<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/functions/pagesmod.php");
include_once($projectroot."admin/includes/objects/adminmain.php");

//print("Post: ");
//print_r($_POST);
//print("<br/Get: ");
//print_r($_GET);


if(isset($_GET['sid'])) $sid=$_GET['sid'];
else $sid="";
checksession($sid);

if(isset($_GET['page'])) $page=$_GET['page'];
else $page=0;

$message="";

// *************************** actions ************************************** //

// page content actions

$message = getpagelock($page);
if(!$message)
{
	$pagetype = getpagetype($page);
	if($pagetype==="article")
	{
		include_once($projectroot."admin/includes/objects/edit/articlepage.php");
		$editpage = new EditArticle($page);
	}
	elseif($pagetype==="menu" || $pagetype==="articlemenu")
	{
		include_once($projectroot."admin/includes/objects/edit/menupage.php");
		$editpage = new EditMenu($page);
	}
	elseif($pagetype==="news")
	{
		include_once($projectroot."admin/includes/objects/edit/newspage.php");
		//archiving
		if(isset($_POST['archivenewsitems']))
		{
			$editpage = new ArchiveNewsItemsForm();
		}
		elseif(isset($_POST['doarchivenewsitems']))
		{
			include_once($projectroot."admin/functions/pagescreate.php");
			$message="";
			$dateok=true;
			if($_POST['year']==$_POST['oldestyear'])
			{
				if($_POST['month']<$_POST['oldestmonth'])
				{
					$dateok=false;
				}
				elseif($_POST['month']==$_POST['oldestmonth'])
				{
					if($_POST['day']<$_POST['oldestday'])
					{
						$dateok=false;
					}
				}
			}
			if(!$dateok)
			{
				$message="The selected date must not be older than the start date!";
			}
			elseif(!checkdate ($_POST['month'],$_POST['day'],$_POST['year']))
			{
				$message="The selected date does not exist!";
			}
			else
			{
				// do the archiving
				$moveditems=archivenewsitems($page,$_POST['day'],$_POST['month'],$_POST['year'],$sid);
				if($moveditems>0)
				{
					$message="Moved ".$moveditems." newsitem(s) to new page.";
				}
				else
				{
					$message="No newsitems to move.";
				}
			}
			$editpage = new ArchiveNewsItemsForm();
			updateeditdata($page, $sid);
		} // doarchivenewsitems
		// rss
		elseif(isset($_POST['rssfeed']))
		{
			$message="";
			if(isset($_POST['enablerss']))
			{
				addrssfeed($page);
				$message = "RSS enabled for this newspage";
			}
			elseif(isset($_POST['disablerss']))
			{
				removerssfeed($page);
				$message = "RSS disabled for this newspage";
			}
			updateeditdata($page, $sid);
			$editpage = new EditNews($page);
		}
		// display order
		elseif(isset($_POST['setdisplayorder']))
		{
			setdisplaynewestnewsitemfirst($page, $_POST['displayorder']);
			updateeditdata($page, $sid);
			$editpage = new EditNews($page);
		}
		else
		{
			$editpage = new EditNews($page);
		}

	}
	else
	{
		include_once($projectroot."admin/includes/objects/edit/pageintro.php");
		$editpage = new EditPageIntro($page);
	}
	
	$content = new AdminMain($page,"pageintrosettingsedit",$message,$editpage);
	print($content->toHTML());
}
// locked page
else
{
	$editpage = new DonePage("This page is already being edited","&action=show","admin.php","View this page");
	print($editpage->toHTML());
}

$db->closedb();
?>