<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));
include_once($projectroot."includes/functions.php");

//
//
//
function locationbutton($buttonname, $location)
{
  $result='<input type="button" name="location" value="'.$buttonname.'" onClick="self.location.href=';
  $result.="'".$location."'".'" class="liteoption">';
  return $result;
}

// *************************** page edit ************************************ //


//
//
//
function editcontentsbuttons($page,$title)
{
  $result='<table><tr><td>';
  $button = new DoneButton($page,"",'',$title,"liteoption");
  $result.=$button->toHTML();
  $result.='</td><td>&nbsp;</td><td>';
  $button = new DoneButton($page,"&action=edit",getprojectrootlinkpath().'admin/pageedit.php',"General settings","liteoption");
  $result.=$button->toHTML();
  $result.='</td><td>&nbsp;</td><td>';
  $button = new DoneButton($page,"&action=show",getprojectrootlinkpath().'admin/admin.php');
  $result.=$button->toHTML();
  $result.='</td></tr></table>';
  return $result;
}


//
//
//
function generalsettingsbuttons($page)
{
  $result='<table><tr><td>';
  $button = new DoneButton($page,"&action=edit",getprojectrootlinkpath().'admin/pageedit.php',"General settings","liteoption");
  $result.=$button->toHTML();
  $result.='</td><td>&nbsp;</td><td>';
  $button = new DoneButton($page,"&action=show",getprojectrootlinkpath().'admin/admin.php');
  $result.=$button->toHTML();
  $result.='</td></tr></table>';
  return $result;
}

?>
