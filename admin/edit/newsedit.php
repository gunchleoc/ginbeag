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
include_once($projectroot."includes/templates/newspage.php");
include_once($projectroot."admin/includes/templates/adminelements.php");
include_once($projectroot."admin/includes/templates/adminnewspage.php");

$sid=$_GET['sid'];
checksession($sid);

$page=$_GET['page'];

if(isset($_POST['item']))
{
	$offset=getnewsitemoffset($page,1,$_POST['item'],true);
}
elseif(isset($_GET['offset']))
{
	$offset=$_GET['offset'];
}
else $offset=0;

//print_r($_POST);
//print_r($_GET);

// *************************** actions ************************************** //

// page content actions

$pagelockmessage = getpagelock($page);
if(!$pagelockmessage)
{
	// update news
	// add a newsitem
	if(isset($_POST['addnewsitem']))
	{
		addnewsitem($page,$sid);
		updateeditdata($page, $sid);
		$offset=0;
		editnewsitempageforms($page, "Added news item");
	}
	// permissions
	elseif(isset($_POST['setpermissions']))
	{
		updatenewsitemcopyright($_GET['newsitem'],$_POST['copyright'],$_POST['imagecopyright'],$_POST['permission']);
		updateeditdata($page, $sid);
		editnewsitempageforms($page, "Permissions updated");
	}
	// title
	elseif(isset($_POST['edittitle']))
	{
		updatenewsitemtitle($_GET['newsitem'],$_POST['title']);
		updateeditdata($page, $sid);
		editnewsitempageforms($page, "Changed title");
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
		editnewsitempageforms($page,$message);
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
		editnewsitempageforms($page, $message);
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
		editnewsitempageforms($page,$message);
	}
	// source
	elseif(isset($_POST['newsitemsource']))
	{
		updatenewsitemsource($_GET['newsitem'],$_POST['source'],$_POST['sourcelink'],$_POST['location'],$_POST['contributor']);
		updateeditdata($page, $sid);
		editnewsitempageforms($page, "Changed source info");
	}
	// date
	elseif(isset($_POST['fakethedate']))
	{
		$message="";
		if(strlen($_POST['year'])!=4)
		{
			$message="Please enter a 4-digit year!</p>";
		}
		else
		{
			$message="Date for Newsitem set";
			fakethedate($_GET['newsitem'],$_POST['day'],$_POST['month'],$_POST['year'],$_POST['hours'],$_POST['minutes'],$_POST['seconds']);
			updateeditdata($page, $sid);
		}
		editnewsitempageforms($page, $message);
	}
	// categories
	elseif(isset($_POST['removecat']))
	{
		$selectedcats=$_POST['selectedcat'];
		removenewsitemcategories($_GET['newsitem'],$selectedcats);
		editnewsitempageforms($page, "Removed categories from newsitem");
	}
	elseif(isset($_POST['addcat']))
	{
		$selectedcats=$_POST['selectedcat'];
		addnewsitemcategories($_GET['newsitem'],$selectedcats);
		editnewsitempageforms($page, "Added new categories for newsitem");
	}
	// sections
	elseif(isset($_POST['addsection']))
	{
		addnewsitemsection($_GET['newsitem'],$_GET['newsitemsection']);
		updateeditdata($page, $sid);
		editnewsitempageforms($page, "Added section to newsitem");
	}
	elseif(isset($_POST['addquotedsection']))
	{
		addnewsitemsection($_GET['newsitem'],$_GET['newsitemsection'],true);
		updateeditdata($page, $sid);
		editnewsitempageforms($page, "Added quoted section to newsitem on page");
	}
	elseif(isset($_POST['editsectiontitle']))
	{
		updatenewsitemsectionttitle($_GET['newsitemsection'],$_POST['sectiontitle']);
		updateeditdata($page, $sid);
		editnewsitempageforms($page, "Changed a section title");
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
		$imagevalign=$_POST['imagevalign'];
		updatenewsitemsectionimage($_GET['newsitemsection'],$imagefilename,$imagealign,$imagevalign);
		updateeditdata($page, $sid);
		$message.="<br />Updated Section Image";
		editnewsitempageforms($page, $message);
	}
	// section
	elseif(isset($_POST['deletesection']))
	{
		deletenewsitemsectionconfirm($page,$_GET['newsitem'],$_GET['newsitemsection']);
	}
	elseif(isset($_POST['confirmdeletesection']))
	{
		deletenewsitemsection($_GET['newsitem'],$_GET['newsitemsection']);
		updateeditdata($page, $sid);
		editnewsitempageforms($page, "Section deleted");
	}
	elseif(isset($_POST['nodeletesection']))
	{
		editnewsitempageforms($page, "Deleting of section aborted");
	}
	// searching
	elseif(isset($_POST['search']) && isset($_POST['title']) && strlen($_POST['title'])>0)
	{
		newsitemsearchresults($page,$_POST['title']);
	}
	//archiving
	elseif(isset($_POST['archivenewsitems']))
	{
		archivenewsitemsform($page);
	}
	elseif(isset($_POST['doarchivenewsitems']))
	{
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
		archivenewsitemsform($page, $message);
		updateeditdata($page, $sid);
	}
	// deleting
	elseif(isset($_POST['deleteitem']))
	{
		deletenewsitemconfirm($page,$_GET['newsitem']);
	}
	elseif(isset($_POST['confirmdeleteitem']))
	{
		deletenewsitem($_GET['newsitem']);
		updateeditdata($page, $sid);
		editnewsitempageforms($page, "Newsitem deleted");
	}
	// publishing
	elseif(isset($_POST['publish']))
	{
		publishnewsitem($_GET['newsitem']);
		updateeditdata($page, $sid);
		editnewsitempageforms($page, "Newsitem published");
	}
	elseif(isset($_POST['unpublish']))
	{
		unpublishnewsitem($_GET['newsitem']);
		updateeditdata($page, $sid);
		editnewsitempageforms($page, "Newsitem hidden");
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
		editnewsitempageforms($page, $message);
	}
	// display order
	elseif(isset($_POST['setdisplayorder']))
	{
		setdisplaynewestnewsitemfirst($page, $_POST['displayorder']);
		updateeditdata($page, $sid);
		editnewsitempageforms($page);
	}
	else
	{
		editnewsitempageforms($page);
	}
}
// locked page
else
{
	$editpage = new DonePage($page,"This page is already being edited",$pagelockmessage,"&action=editcontents&override=on","newsedit.php","Override lock and edit");
	print($editpage->toHTML());
}

// *************************** newsitem ************************************* //

//
//
//
function editnewsitempageforms($page, $message="")
{
	global $sid,$offset;
	$editnewsitemforms = new EditNewsItemForms($page, $offset, $message);
	print($editnewsitemforms->toHTML());
}

//
//
//
function newsitemsearchresults($page,$searchtitle)
{
	$searchresults = new NewsItemSearchResults($page,$searchtitle);
	print($searchresults->toHTML());
}

//
//
//
function archivenewsitemsform($page, $message="")
{
	$form = new ArchiveNewsItemsForm($page, $message);
	print($form->toHTML());
}


//
//
//
function deletenewsitemconfirm($page,$newsitem_id)
{
	$confirm = new DeleteNewsItemConfirm($page,$newsitem_id);
	print($confirm->toHTML());
}

//
//
//
function deletenewsitemsectionconfirm($page,$newsitem_id,$newsitemsection_id)
{
	$confirm = new DeleteNewsItemSectionConfirm($page,$newsitem_id,$newsitemsection_id);
	print($confirm->toHTML());
}

?>
