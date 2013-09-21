<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/functions/pagesmod.php");
include_once($projectroot."admin/functions/categoriesmod.php");
include_once($projectroot."admin/includes/objects/edit/newspage.php");
include_once($projectroot."includes/objects/elements.php");
include_once($projectroot."includes/functions.php");
include_once($projectroot."admin/includes/objects/adminmain.php");

if(isset($_GET['sid'])) $sid=$_GET['sid'];
else $sid="";
checksession($sid);

if(isset($_GET['page'])) $page=$_GET['page'];
else $page=0;

if(isset($_POST['item'])) $offset=getnewsitemoffset($page,1,$_POST['item'],true);
elseif(isset($_GET['offset'])) $offset=$_GET['offset'];
else $offset=0;

if(isset($_GET['articlepage'])) $articlepage=$_GET['articlepage'];
else $articlepage=0;

if(isset($_GET['articlesection'])) $articlesection=$_GET['articlesection'];
else $articlesection=0;

$message="";

//print("Post: ");
//print_r($_POST);
//print("<br/Get: ");
//print_r($_GET);

// *************************** actions ************************************** //

// page content actions

$message = getpagelock($page);
if(!$message)
{
	// update news
	// add a newsitem
	if(isset($_POST['addnewsitem']))
	{
		addnewsitem($page,$sid);
		updateeditdata($page, $sid);
		$offset=0;
		$editpage = new EditNewsItemForms($page,$offset);
		$message="Added news item";
	}
	// synopsis
	elseif(isset($_POST['addnewsitemsynopsisimage']))
	{
		$message="";
		$filename=trim($_POST['filename']);
		if(imageexists($filename))
		{
			addnewsitemsynopsisimage($_GET['newsitem'], $filename);
			updateeditdata($page, $sid);
			$message="Added synopsis image";
		}
		else
		{
			$message='Failed to add synopsis image. The image <i>'.$_POST['filename'].'</i> does not exist!';
		}
		$editpage = new EditNewsItemForms($page,$offset);
	}
	elseif(isset($_POST['editnewsitemsynopsisimage']))
	{
		$message="";
		if(imageexists($_POST['imagefilename']))
		{
			$message="Edited synopsis image";
			editnewsitemsynopsisimage($_GET['imageid'],$_POST['imagefilename']);
			updateeditdata($page, $sid);
		}
		else
		{
			$message="Failed to edit synopsis image. The image <i>".text2html($_POST['imagefilename'])."</i> does not exist!";
		}
		$editpage = new EditNewsItemForms($page,$offset);
	}
	elseif(isset($_POST['removenewsitemsynopsisimage']))
	{
		$message="";
		if(isset($_POST['removeconfirm']))
		{
			$message="Removed a synopsis image";
			removenewsitemsynopsisimage($_GET['imageid']);
		}
		else
		{
			$message="Failed to remove image. Please confirm when removing an image.";
		}
		updateeditdata($page, $sid);
		$editpage = new EditNewsItemForms($page,$offset);
	}
	// sections
	elseif(isset($_POST['addsection']))
	{
		addnewsitemsection($_GET['newsitem'],$_GET['newsitemsection']);
		updateeditdata($page, $sid);
		$editpage = new EditNewsItemForms($page,$offset);
		$message="Added section to newsitem";
	}
	elseif(isset($_POST['addquotedsection']))
	{
		addnewsitemsection($_GET['newsitem'],$_GET['newsitemsection'],true);
		updateeditdata($page, $sid);
		$editpage = new EditNewsItemForms($page,$offset);
		$message="Added quoted section to newsitem on page";
	}
	elseif(isset($_POST['changeimage']))
	{
		$message="";
		$imagefilename=trim($_POST['imagefilename']);

		if(!(imageexists($imagefilename) || strlen($imagefilename)==0))
		{
			$message="The image <i>".text2html($imagefilename)."</i> does not exist! Reverting to old image.";
			$imagefilename=getnewsitemsectionimage($_GET['newsitemsection']);
		}

		$imagealign=$_POST['imagealign'];
		updatenewsitemsectionimage($_GET['newsitemsection'],$imagefilename,$imagealign);
		updateeditdata($page, $sid);
		$message.="<br />Updated Section Image";
		$editpage = new EditNewsItemForms($page,$offset);
	}
	// section
	elseif(isset($_POST['deletesection']))
	{
		$editpage = new DeleteNewsItemSectionConfirm($_GET['newsitem'],$_GET['newsitemsection']);
	}
	elseif(isset($_POST['confirmdeletesection']))
	{
		deletenewsitemsection($_GET['newsitem'],$_GET['newsitemsection']);
		updateeditdata($page, $sid);
		$editpage = new EditNewsItemForms($page,$offset);
		$message="Section deleted";
	}
	elseif(isset($_POST['nodeletesection']))
	{
		$editpage = new EditNewsItemForms($page,$offset);
		$message="Deleting of section aborted";
	}
	// searching
	elseif(isset($_POST['search']) && isset($_POST['title']) && strlen($_POST['title'])>0)
	{
		$editpage = new NewsItemSearchResults(fixquotes($_POST['title']));
	}
	//archiving
	elseif(isset($_POST['archivenewsitems']))
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
	// deleting
	elseif(isset($_POST['deleteitem']))
	{
		$editpage = new DeleteNewsItemConfirm($_GET['newsitem']);
	}
	elseif(isset($_POST['confirmdeleteitem']))
	{
		deletenewsitem($_GET['newsitem']);
		updateeditdata($page, $sid);
		$editpage = new EditNewsItemForms($page,$offset);
		$message="Newsitem deleted";
	}
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
		$editpage = new EditNewsItemForms($page,$offset);
	}
	// display order
	elseif(isset($_POST['setdisplayorder']))
	{
		setdisplaynewestnewsitemfirst($page, $_POST['displayorder']);
		updateeditdata($page, $sid);
		$editpage = new EditNewsItemForms($page,$offset);
	}
	else
	{
		$editpage = new EditNewsItemForms($page,$offset);
	}

	$content = new AdminMain($page,"editcontents",$message,$editpage);
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