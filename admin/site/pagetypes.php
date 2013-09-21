<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

// check legal vars
include($projectroot."admin/includes/legalsitevars.php");

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/functions/usersmod.php");
include_once($projectroot."admin/includes/adminelements.php");

$sid=$_GET['sid'];
checksession($sid);

$header = new HTMLHeader("Page Types","Webpage Building");
print($header->toHTML());

if(!isadmin($sid))
{
  die('<p class="highlight">You have no permission for this area</p>');
}

// print_r($_POST);
// print_r($_GET);

if(isset($_POST['pagetypesettings']))
{
  $allowroot=0;
  if(isset($_POST['allowroot']))
  {
    $allowroot=1;
  }
  $allowsimplemenu=0;
  if(isset($_POST['allowsimplemenu']))
  {
    $allowsimplemenu=1;
  }
  updaterestrictions($_POST['pagetype'],$allowroot,$allowsimplemenu);
  print('<p class="highlight">Changed settings for <i>'.$_POST['pagetype'].'</i>.</p>');
}

pagetypeform();

$footer = new HTMLFooter();
print($footer->toHTML());

//
//
//
function pagetypeform()
{
  global $sid;
  $pagetypes=getpagetypes();
  $keys=array_keys($pagetypes);
//  print_r($pagetypes);
?>

<table><tr><td class="bodyline">
<table>
<tr>
  <th class="thHead" colspan="2">Pagetype</th>
  <th class="thHead" colspan="3">Allowed Parent Page Types</th>
  <th class="thHead">&nbsp;</th>
</tr>
<tr>
  <th class="thHead">Name</th>
  <th class="thHead">Description</th>
  <th class="thHead">As Main Page</th>
  <th class="thHead">Simple Menu</th>
  <th class="thHead">Same Type</th>
  <th class="thHead">&nbsp;</th>
</tr>
<?php
  for($i=0;$i<count($keys);$i++)
  {
    $pagetype=$keys[$i];
    $restrictions=getrestrictions($pagetype);
?>
<form action="?sid=<?php print($sid)?>" method="post">
<tr>
<input type="hidden" name="pagetype" value="<?php print($pagetype);?>"></td>
  <td class="gen"><?php print($pagetype);?></td>
  <td class="gen"><i><?php print($pagetypes[$pagetype]);?></i></td>
  <td class="gen" align="center"><input type="checkbox" name="allowroot" <?php if($restrictions["allowroot"]) print("checked");?>></td>
  <td class="gen" align="center"><input type="checkbox" name="allowsimplemenu" <?php if($restrictions["allowsimplemenu"]) print("checked");?>></td>
  <td class="gen" align="center"><?php if($restrictions["allowself"]) print("yes");?></td>
  <td class="table"><input type="submit" name="pagetypesettings" value="Save Changes" class="liteoption"></td>
</tr>
</form>
<?php
  }
?>
</table>
</td></tr></table>
<?php
}
?>
