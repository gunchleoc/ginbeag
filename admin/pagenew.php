<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

// check legal vars
include($projectroot."admin/includes/legaladminvars.php");

include_once($projectroot."admin/functions/pagescreate.php");
include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/includes/objects/pagenew.php");
include_once($projectroot."admin/includes/objects/forms.php");
include_once($projectroot."includes/functions.php");
include_once($projectroot."admin/includes/objects/adminmain.php");

if(isset($_GET['sid'])) $sid=$_GET['sid'];
else $sid="";
checksession($sid);

if(isset($_GET['page'])) $page=$_GET['page'];
else $page=0;

$message="";

//print_r($_POST);
//print_r($_GET);

if(isset($_POST['create']))
{
  $title=fixquotes($_POST['title']);
  $navtitle=fixquotes($_POST['navtitle']);
  $type=$_POST['type'];
  $root=isset($_POST['root']);
  $ispublishable=$_POST['ispublishable'];

  if($title && $navtitle)
  {
    if($ispublishable==="public")
    {
      $ispublishable=1;
    }
    else
    {
      $ispublishable=0;
    }
    if($root || !$page): $parent=0; else: $parent=$page;endif;
    $parentpagetype=getpagetype($parent);

    $createpage=islegalparentpage($type, $parent);

    if($createpage)
    {
      $userid=getsiduser($sid);
      $page=createpage($parent, $title, $navtitle, $type,$userid,$ispublishable);
      $allpages=getmultiplefields(PAGES_TABLE, "page_id","1", $fields, $orderby="parent_id, position_navigator");
      if($parent)
      {
        $title=getpagetitle($parent);
        $message="Created a new page under page: <em>".title2html($title)."</em> (".getpagetype($parent).")";
      }
      else $message="Created a new page as a main page";
      
      $redirect = new DoneRedirect($page,"Created a new page","&action=show","admin.php","Edit this page");
      $content = new AdminMain($parent,"show",$message,$redirect);
      print($content->toHTML());
    }
    else
    {
      $message.='<i>'.ucfirst($type).'</i> pages can only be created inside the following types of pages:';
      $keys=array_keys(getlegalparentpagetypes($type));
      for($i=0;$i<count($keys);$i++)
      {
        if($keys[$i]!="root")
        {
          $message.=" <i>".$keys[$i]."</i>";
        }
      }
      if (array_search ("root",$keys) || $keys[0] === "root")
      {
      		$message.=", or as a main page";
      }
      if($parentpagetype)
      {
        $message.='.<br />You tried to add a <i>'.$type.'</i> page to a <i>'.$parentpagetype.'</i> page.';
      }
      else
      {
        $message.='</i>.<br />You tried to add a <i>'.$type.'</i> page as a main page.';
      }
    }
  } // title && navtitle
  else
  {
  	$message.="Please specify the new page's title (long and short)";
  }
  $content = new AdminMain($page,"pagenew",$message,new NewPageForm($page,$title,$navtitle,$ispublishable,isset($_POST['root'])));
	  print($content->toHTML());  
}
else
{
    $content = new AdminMain($page,"pagenew",$message,new NewPageForm($page,"","",true,false));
	print($content->toHTML());  
}
$db->closedb();
?>