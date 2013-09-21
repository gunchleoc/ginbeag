<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

// check legal vars
include($projectroot."admin/includes/legaladminvars.php");

include_once($projectroot."admin/functions/pagesmod.php");
include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/includes/templates/adminpage.php");
include_once($projectroot."admin/includes/templates/adminelements.php");

$sid=$_GET['sid'];
checksession($sid);

$page=$_GET['page'];

//print_r($_POST);
//print_r($_GET);

if(isset($_POST['create']))
{
  $title=$_POST['title'];
  $navtitle=$_POST['navtitle'];
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
      $message="";
      if($parent)
      {
        $title=getpagetitle($parent);
        $message=getpagetype($parent).': '.title2html($title);
      }
      else $message ="Site Root";
      
      $header = new HTMLHeader("Created new page under page:","Webpage Building",$message);
      print($header->toHTML());
      $donebutton = new DoneButton($page,"&action=show","admin.php");
      print($donebutton->toHTML());
      $footer = new HTMLFooter();
      print($footer->toHTML());
    }
    else
    {
      $warning='<i>'.ucfirst($type).'</i> pages can only be created inside the following types of pages:';
      $keys=array_keys(getlegalparentpagetypes($type));
      for($i=0;$i<count($keys);$i++)
      {
        if($keys[$i]==="root")
        {
          $warning.=", or as a main page";
        }
        else
        {
          $warning.=" <i>".$keys[$i]."</i>";
        }
      }
      if($parentpagetype)
      {
        $warning.='.<br />You tried to add a <i>'.$type.'</i> page to a <i>'.$parentpagetype.'</i> page.';
      }
      else
      {
        $warning.='</i>.<br />You tried to add a <i>'.$type.'</i> page as a main page.';
      }

      newpage($page,$warning,$title,$navtitle,$ispublishable,isset($_POST['root']));
    }
  }
  else
  {
    newpage($page,"Please specify the new page's title (long and short)",$title,$navtitle,$ispublishable,isset($_POST['root']));
  }
}
else
{
  newpage($page);
}

//
//
//
function newpage($parentpage,$warning="",$newtitle="",$newnavtitle="",$ispublishable=false,$isrootchecked=false)
{
  if($parentpage)
  {
    $message=getpagetype($parentpage).': '.title2html(getpagetitle($parentpage)).'<br />';
  }
  if($warning)
  {
    $message.='<br />'.$warning;
  }
  
  $header = new HTMLHeader("Creating new page under page:","Webpage Building",$message);
  print($header->toHTML());
  $form = new NewPageForm($parentpage,$newtitle,$newnavtitle,$ispublishable,$isrootchecked);
  print($form->toHTML());
  $footer = new HTMLFooter();
  print($footer->toHTML());
}
?>
