<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

// check legal vars
include($projectroot."admin/includes/legaladminvars.php");

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/functions/pagesmod.php");
include_once($projectroot."admin/functions/publicusersmod.php");
include_once($projectroot."admin/functions/categoriesmod.php");
include_once($projectroot."admin/edit/edittext.php");
include_once($projectroot."includes/includes.php");
include_once($projectroot."includes/functions.php");
include_once($projectroot."includes/templates/page.php");
include_once($projectroot."admin/includes/templates/adminforms.php");
include_once($projectroot."admin/includes/templates/adminelements.php");
include_once($projectroot."admin/includes/adminelements.php");
include_once($projectroot."admin/includes/templates/adminpage.php");

$sid=$_GET['sid'];
checksession($sid);

$page=$_GET['page'];

$action="";
if(isset($_GET['action'])) $action=$_GET['action'];
elseif(isset($_POST['action'])) $action=$_POST['action'];

unset($_GET['action']);
unset($_POST['action']);

// print_r($_POST);
// print_r($_GET);



$pagetype=getpagetype($page);


// *************************** actions ************************************** //

if(!$page)
{
  $editpage = new DonePage(0,"No Page Selected","Please select a page first","&action=show","admin.php");
}

// general actions
elseif($action==="edit")
{
  $pagelockmessage = getpagelock($page);
  if(!$pagelockmessage)
  {
    // update external
    if($pagetype==="external")
    {
      if(isset($_POST['changelink']))
      {
        updateexternallink($page,$_POST['link']);
      }
      $editpage = new EditPage($page);
    }
    else
    {
      $editpage = new EditPage($page);
    }
  }
  else
  {
    $editpage = new DonePage($page,"This page is already being edited",$pagelockmessage,"&action=edit&override=on","pageedit.php","Override lock and edit");
  }
}
elseif($action==="rename")
{
  renamepage($page, $_POST['navtitle'], $_POST['title']);
  updateeditdata($page, $sid);
  unlockpage($page);
  $editpage = new DonePage($page,"Renaming page",edittitle2html($_POST['title']));
}
elseif($action==="move")
{
  if(isset($_POST['moveup']))
  {
    $title="Moved page up";
    movepage($page, "up", $_POST['positions']);
  }
  elseif(isset($_POST['movedown']))
  {
    $title="Moved page down";
    movepage($page, "down", $_POST['positions']);
  }
  elseif(isset($_POST['movetop']))
  {
    $title="Moved page to the top";
    movepage($page, "top");
  }
  else
  {
    $title="Moved page to the bottom";
    movepage($page, "bottom");
  }
  updateeditdata(getparent($page), $sid);
  unlockpage($page);
  $editpage = new DonePage($page,$title,title2html(getpagetitle($page)),"&action=edit","admin.php");
}
elseif($action==="findnewparent")
{
  $editpage = new SelectNewParentForm($page);
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
  $editpage = new DonePage($page,'Moved page',$message,"&action=edit","admin.php");
}
elseif($action==="delete")
{
    $editpage = new DeletePageConfirmForm($page);
}
elseif(isset($_POST["executedelete"]))
{
  $parent=getparent($page);
  $deletepage=deletepage($page,$sid);
  $deletepage--;
  unlockpage($page);
  $message=title2html(getpagetitle($page)).'<br />'.$deletepage.' subpages were included in delete.';
  $editpage = new DonePage($parent,'Deleted the following page(s)',$message,"&action=show","admin.php");
}
elseif(isset($_POST["nodelete"]))
{
  unlockpage($page);
  $editpage = new DonePage($page,'Deleting aborted',title2html(getpagetitle($page)),"&action=show","pagedisplay.php");
}
elseif($action==="setpublish")
{
  if(isset($_POST['publish']))
  {
    $title="You published the following page";
    publish($page);
  }
  else
  {
    $title="You hid the following page";
    unpublish($page);
  }
  unlockpage($page);
  $editpage = new DonePage($page,$title,title2html(getpagetitle($page)),"&action=show","admin.php");
}
elseif($action==="setpublishable")
{
  $message="";
  if($_POST['ispublishable']==="public")
  {
    $title="Earmarked a page for publishing";
    makepublishable($page);
  }
  else
  {
    $title="Marked a page as internal";
    if(ispublished($page))
    {
      $message='<br />The page had already been published and has now been removed from public view.';
      unpublish($page);
    }
    hide($page);
  }
  unlockpage($page);
  $editpage = new DonePage($page,$title,title2html(getpagetitle($page)).$message,"&action=edit","admin.php");
}
elseif($action==="setpermissions")
{
  updatecopyright($page,$_POST['copyright'],$_POST['imagecopyright'],$_POST['permission']);
  setshowpermissionrefusedimages($page, $_POST["show"]);
  updateeditdata($page, $sid);
  $editpage = new EditPage($page,"Edited copyright permissions");
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
  }
  $editpage = new EditPage($page,"Edited page restrictions");
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
  $editpage = new EditPage($page,"Edited user access");
}


if(isset($editpage))
{
  print($editpage->toHTML());
}
?>
