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

if(isset($_GET['sid'])) $sid=$_GET['sid'];
else $sid="";
checksession($sid);

if(isset($_GET['page'])) $page=$_GET['page'];
else $page=0;

if(isset($_GET['action'])) $action=$_GET['action'];
else $action="";

unset($_GET['action']);

$message="";

$pagetype=getpagetype($page);


// *************************** actions ************************************** //

if(!$page)
{
  $editpage = new DonePage("No Page Selected","&action=show","admin.php","Admin home");
  $message="Please select a page first";
}

// general actions
elseif($action==="edit")
{
  $message = getpagelock($page);
  if(!$message)
  {
    // update external
    if($pagetype==="external")
    {
      if(isset($_POST['changelink']))
      {
        updateexternallink($page,$_POST['link']);
      }
    }
  }
  else
  {
    $editpage = new DonePage("This page is already being edited","&action=show","admin.php","View this page");
  }
}
elseif($action==="rename")
{
  renamepage($page, fixquotes($_POST['navtitle']), fixquotes($_POST['title']));
  updateeditdata($page, $sid);
  unlockpage($page);
  $message="Renamed page to:<br /> <em>".edittitle2html($_POST['navtitle'])."</br />".edittitle2html($_POST['title'])."</em>";
  $editpage = new DoneRedirect($page,"Renamed page","&action=edit","","Edit this page");
}
elseif($action==="move")
{
  if(isset($_GET['moveup']))
  {
    $title="Moved page up";
    movepage($page, "up", $_GET['positions']);
    $message="Moved the page <em>".title2html(getpagetitle($page))."</em> up ".$_GET['positions']." place(s)";
  }
  elseif(isset($_GET['movedown']))
  {
    $title="Moved page down";
    movepage($page, "down", $_GET['positions']);
    $message="Moved the page <em>".title2html(getpagetitle($page))."</em> down".$_GET['positions']." place(s)";
  }
  elseif(isset($_GET['movetop']))
  {
    $title="Moved page to the top";
    movepage($page, "top");
    $message="Moved the page <em>".title2html(getpagetitle($page))."</em> to the top";
  }
  else
  {
    $title="Moved page to the bottom";
    movepage($page, "bottom");
    $message="Moved the page <em>".title2html(getpagetitle($page))."</em> to the bottom";
  }
  updateeditdata(getparent($page), $sid);
  unlockpage($page);
  
  $editpage = new DoneRedirect($page,$title,"&action=edit","","Edit this page");
}
elseif($action==="findnewparent")
{
  $editpage = new SelectNewParentForm();
}
elseif($action==="newparent")
{
  $newparent=$_POST['parentnode'];
  $message='Moved page <i>'.title2html(getpagetitle($page)).'</i> to <i>';
  if($newparent)
  {
    $message.=title2html(getpagetitle($newparent));
  }
  else
  {
    $message.='Site Root';
  }
  $message.="</i>";
  $message.="<br />".movetonewparentpage($page,$newparent);
  updateeditdata($newparent, $sid);
  unlockpage($page);
  $editpage = new DoneRedirect($page,"Moved page to a new parent page","&action=edit","","Edit this page");
}
elseif($action==="publish")
{
    publish($page);
    unlockpage($page);
    $message="You published the following page: ".title2html(getpagetitle($page));
 	$editpage = new DoneRedirect($page,"Published a page","&action=edit","","Edit this page");
}
elseif($action==="unpublish")
{
    unpublish($page);
    unlockpage($page);
    $message="You removed the following page from public view: ".title2html(getpagetitle($page));
	$editpage = new DoneRedirect($page,"Hid a page","&action=edit","","Edit this page");
}
elseif($action==="setpublishable")
{
	$message="";
	if($_POST['ispublishable']==="public")
	{
		makepublishable($page);
		$message="Earmarked <em>".title2html(getpagetitle($page))."</em> for publishing";
		$title="Earmarked a page for publishing";
	}
	else
	{
	    $message="Marked <em>".title2html(getpagetitle($page))."</em> as internal";
	    $title="Marked a page as internal";
		if(ispublished($page))
		{
		  $message.='<br />The page had already been published and has now been removed from public view.';
		  unpublish($page);
		}
		hide($page);
	}
	unlockpage($page);
	$editpage = new DoneRedirect($page,$title,"&action=edit","","Edit this page");
}
elseif($action==="setpermissions")
{
  updatecopyright($page,fixquotes($_POST['copyright']),fixquotes($_POST['imagecopyright']),$_POST['permission']);
  // todo if access restricted
  if (ispagerestricted($page)) setshowpermissionrefusedimages($page, $_POST["show"]);
  updateeditdata($page, $sid);
  $message="Edited copyright permissions";
}
// access restriction
elseif($action==="restrictaccess")
{
  if($_POST["restrict"])
  {
    restrictaccess($page);
  }
  else
  {
    removeaccessrestriction($page);
    setshowpermissionrefusedimages($page, 0);
  }
  $message="Edited page restrictions";
}
elseif($action==="restrictaccessusers")
{
  if(isset($_POST["addpublicusers"]))
  {
    addpageaccess($_POST["selectusers"],$page);
  }
  else
  {
    removepageaccess($_POST["selectusers"],$page);
  }
  $message="Edited user access";
}

if(!isset($editpage)) $editpage = new EditPage($page);
$content = new AdminMain($page,"edit",$message,$editpage);
print($content->toHTML());

$db->closedb();
?>