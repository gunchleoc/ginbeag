<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

// check legal vars
include($projectroot."admin/includes/legaladminvars.php");

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/functions/pagesmod.php");
include_once($projectroot."admin/functions/pagecontent/externalpagesmod.php");
include_once($projectroot."admin/functions/publicusersmod.php");
include_once($projectroot."admin/functions/categoriesmod.php");
include_once($projectroot."includes/objects/page.php");
include_once($projectroot."admin/includes/objects/forms.php");
include_once($projectroot."admin/includes/objects/page.php");
include_once($projectroot."includes/functions.php");
include_once($projectroot."admin/includes/objects/adminmain.php");

/*
print("Post: ");
print_r($_POST);
print("<br />Get: ");
print_r($_GET);
*/

checksession();

if(isset($_GET['page'])) $page=$_GET['page'];
else $page=0;

if(isset($_GET['action'])) $action=$_GET['action'];
else $action="";

unset($_GET['action']);

// *************************** actions ************************************** //

if(!$page)
{
	$editpage = noPageSelectedNotice();
	$message = "Please select a page first";
	$error = true;
}
else
{
	$message = getpagelock($page);
	$error = false;
	if(!$message)
	{
		// general actions
		if($action === "edit")
		{
			// update external
			if(getpagetype($page) === "external")
			{
				if(isset($_POST['changelink']))
				{
					updateexternallink($page,$_POST['link']);
				}
			}
		}
		elseif($action === "rename")
		{
			renamepage($page, fixquotes($_POST['navtitle']), fixquotes($_POST['title']));
			updateeditdata($page);
			unlockpage($page);
			$message = "Renamed page to:<br /> <em>".edittitle2html($_POST['navtitle'])."</br />".edittitle2html($_POST['title'])."</em>";
			$editpage = editedRedirect($page, "Renamed page");
		}
		elseif($action === "move")
		{
			if(isset($_GET['moveup']))
			{
				$title = "Moved page up";
				movepage($page, "up", $_GET['positions']);
				$message="Moved the page <em>".title2html(getpagetitle($page))."</em> up ".$_GET['positions']." place(s)";
			}
			elseif(isset($_GET['movedown']))
			{
				$title = "Moved page down";
				movepage($page, "down", $_GET['positions']);
				$message="Moved the page <em>".title2html(getpagetitle($page))."</em> down".$_GET['positions']." place(s)";
			}
			elseif(isset($_GET['movetop']))
			{
				$title = "Moved page to the top";
				movepage($page, "top");
				$message="Moved the page <em>".title2html(getpagetitle($page))."</em> to the top";
			}
			else
			{
				$title = "Moved page to the bottom";
				movepage($page, "bottom");
				$message="Moved the page <em>".title2html(getpagetitle($page))."</em> to the bottom";
			}
			updateeditdata(getparent($page));
			unlockpage($page);
			$editpage = editedRedirect($page, $title);
		}
		elseif($action === "findnewparent")
		{
			$editpage = new SelectNewParentForm();
		}
		elseif($action === "newparent")
		{
			$newparent = $_POST['parentnode'];
			$message='Moved page <i>'.title2html(getpagetitle($page)).'</i> to <i>';
			if($newparent)
			{
				$message .= title2html(getpagetitle($newparent));
			}
			else
			{
				$message .= 'Site Root';
			}
			$message .= "</i><br />".movetonewparentpage($page,$newparent);
			updateeditdata($newparent);
			unlockpage($page);
			$editpage = editedRedirect($page,"Moved page to a new parent page");
		}
		elseif($action === "publish")
		{
		    publish($page);
		    unlockpage($page);
		    $message = "You published the following page: ".title2html(getpagetitle($page));
			$editpage = editedRedirect($page,"Published a page");
		}
		elseif($action === "unpublish")
		{
		    unpublish($page);
		    unlockpage($page);
		    $message = "You removed the following page from public view: ".title2html(getpagetitle($page));
			$editpage = editedRedirect($page,"Hid a page");
		}
		elseif($action === "setpublishable")
		{
			$message="";
			if($_POST['ispublishable'] === "public")
			{
				makepublishable($page);
				$message = "Earmarked <em>".title2html(getpagetitle($page))."</em> for publishing";
				$title = "Earmarked a page for publishing";
			}
			else
			{
			    $message = "Marked <em>".title2html(getpagetitle($page))."</em> as internal";
			    $title = "Marked a page as internal";
				if(ispublished($page))
				{
					$message .= '<br />The page had already been published and has now been removed from public view.';
					unpublish($page);
				}
				hide($page);
			}
			unlockpage($page);
			$editpage = editedRedirect($page, $title);
		}
		elseif($action === "setpermissions")
		{
			updatecopyright($page, fixquotes($_POST['copyright']), fixquotes($_POST['imagecopyright']), $_POST['permission']);
			updateeditdata($page);
			$message="Edited copyright permissions";
		}
		// access restriction
		elseif($action === "restrictaccess")
		{
			if($_POST["restrict"])
			{
				restrictaccess($page);
			}
			else
			{
				removeaccessrestriction($page);
			}
			$message="Edited page restrictions";
		}
		elseif($action === "restrictaccessusers")
		{
			if(isset($_POST["addpublicusers"]))
			{
				addpageaccess($_POST["selectusers"],$page);
			}
			else
			{
				removepageaccess($_POST["selectusers"],$page);
			}
			$message = "Edited user access";
		}

	}
	else
	{
		$editpage = new pageBeingEditedNotice($message);
	}
}

if(!isset($editpage)) $editpage = new EditPage($page);
$content = new AdminMain($page, "edit", new AdminMessage($message, $error), $editpage);
print($content->toHTML());

$db->closedb();
?>
