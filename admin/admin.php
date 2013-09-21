<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

// check legal vars
include($projectroot."admin/includes/legaladminvars.php");

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/functions/pagesmod.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<meta http-equiv="Content-Type"	content="text/html;	charset=utf-8">
	<meta http-equiv="Content-Style-Type" content="text/css">
  <link rel="stylesheet" href="../../page.css" type="text/css">
	<title>
    <?php print(title2html(getproperty("Site Name")).' - ');?>
    Webpage building
  </title>
</head>

<?php
if(isset($_GET['sid']))
{
  $sid=$_GET['sid'];
}
else $sid="";

if(isset($_GET['jumppage']))
{
  $_GET['page']=$_GET['jumppage'];
  unset($_GET['jumppage']);
}

if(isset($_GET['page']))
{
  $page=trim($_GET['page']);
}
else $page=0;

if(isset($_GET['action']))
{
  $action=$_GET['action'];
}
else $action="";
unset($_GET['action']);
unset($_POST['action']);

if(isset($_GET['unlock'])) unlockpage($page);
unset($_GET['unlock']);

if(isset($_GET['logout']))
{
  logout($sid);
  unlockuserpages($sid);
  unset($sid);
  unset($_GET);
}
?>
<frameset rows="25%,75%">
  <frame src="includes/navigator.php?sid=<?php
   print($sid);
   print('&page=');
   print($page);
   if(isset($_GET['action']))
   {
     print('&action=');
     print($_GET['action']);
   }
   ?>" name="navigator">
<?php
if($action=="site")
{
?>
 <!-- Dadurch ergeben sich zwei Framefenster, deren Inhalt hier bestimmt wird -->
  <frameset cols="20%,80%">
     <!-- Dadurch ergeben sich zwei Framefenster, deren Inhalt hier bestimmt wird -->
    <frame src="includes/adminnavigator.php?sid=<?php
    print($sid);
    print("&page=".$page);
    ?>" name="tree">
<?php
}
else
{
?>
 <!-- Dadurch ergeben sich zwei Framefenster, deren Inhalt hier bestimmt wird -->
  <frameset cols="25%,75%">
     <!-- Dadurch ergeben sich zwei Framefenster, deren Inhalt hier bestimmt wird -->
    <frame src="includes/pagetree.php?sid=<?php
    print($sid);
    print('&page=');
    print($page);
    ?>" name="tree">
<?php
}
if($action==="news")
{
?>
    <frame src="edit/newsedit.php?sid=<?php
     print($sid);
     print('&page=');
     print($page);
     if(isset($_GET['offset']))
     {
       print('&offset=');
       print($_GET['offset']);
     }
     ?>" name="contents">
<?php
}
elseif($action==="edit")
{
?>
    <frame src="pageedit.php?sid=<?php
     print($sid);
     print('&page=');
     print($page);
     if(isset($_GET['articlepage']))
     {
       print('&articlepage=');
       print($_GET['articlepage']);
     }
     print('&action=');
     print($action);
     ?>" name="contents">
<?php
}
elseif($action==="editcontents")
{
  $pagetype = getpagetype($page);
  if($pagetype==="article")
    $script=getprojectrootlinkpath().'admin/edit/articleedit.php';
  elseif($pagetype==="gallery")
    $script=getprojectrootlinkpath().'admin/edit/galleryedit.php';
  elseif($pagetype==="linklist")
    $script=getprojectrootlinkpath().'admin/edit/linklistedit.php';
  elseif($pagetype==="menu" || $pagetype==="articlemenu" || $pagetype==="linklistmenu")
    $script=getprojectrootlinkpath().'admin/edit/menuedit.php';
  elseif($pagetype==="news")
    $script=getprojectrootlinkpath().'admin/edit/newsedit.php';
  else
    $script="pageedit.php";
?>
    <frame src="<?php
     print($script);
     print('?sid=');
     print($sid);
     print('&page=');
     print($page);
     if(isset($_GET['articlepage']))
     {
       print('&articlepage=');
       print($_GET['articlepage']);
     }
     print('&action=');
     print($action);
     ?>" name="contents">
<?php
}
elseif($action==="site")
{
  if(isset($_GET["contents"]))
  {
    $script="site/".$_GET["contents"].".php";
  }
  else
  {
    $script="site/monthlystats.php";
  }
  ?>
    <frame src="<?php
     print($script);
     print('?sid=');
     print($sid);
     print('&action=');
     print($action);
     ?>" name="contents">
<?php
}
else
{
?>
    <frame src="pagedisplay.php?sid=<?php
     print($sid);
     print('&page=');
     print($page);
     if(isset($_GET['articlepage']))
     {
       print('&articlepage=');
       print($_GET['articlepage']);
     }
     print('&action=');
     print($action);
     ?>" name="contents">
<?php
}
?>
  </frameset>
</frameset>
