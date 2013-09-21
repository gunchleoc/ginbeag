<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

// check legal vars
include($projectroot."admin/includes/legalsitevars.php");

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/functions/usersmod.php");
include_once($projectroot."admin/functions/publicusersmod.php");
include_once($projectroot."admin/includes/adminelements.php");

$sid=$_GET['sid'];
checksession($sid);

$header = new HTMLHeader("Select a User","Webpage Building");
print($header->toHTML());

if(!isadmin($sid))
{
  die('<p class="highlight">You have no permission for this area</p>');
}

$ref=$_GET['ref'];

// print_r($_POST);
// print_r($_GET);


userlistform($ref);

$footer = new HTMLFooter();
print($footer->toHTML());

//
//
//
function userlistform($ref)
{
  global $sid;
  
  $users=getallusers();
?>
<table><tr><td class="bodyline">
<table>
<tr>
  <th class="thHead" colspan="8">Webpage Editors</th>
</tr>
<tr>
  <th class="thHead">Username</th>
  <th class="thHead">E-mail</th>
  <th class="thHead">Contact Page</th>
  <th class="thHead">Contact Function</th>
  <th class="thHead">Active?</th>
  <th class="thHead">User Level</th>
  <th class="thHead">Last Login</th>
  <th class="thHead">Login Retries</th>
</tr>
<?php

  for($i=0; $i<count($users);$i++)
  {

    // get all user values from DB
    $username=getusername($users[$i]);
    $email=getuseremail($users[$i]);
    $iscontact=getiscontact($users[$i]);
    $contactfunction=getcontactfunction($users[$i]);

    $useractive = isactive($users[$i]);
    $userlevel = getuserlevel($users[$i]);
    $lastlogin = getlastlogin($users[$i]);
    $retries = getretries($users[$i]);
?>

<tr>
  <td class="table">
<?php
  if($ref)
  {
    print('<a href="'.$ref.'.php?sid='.$sid.'&userid='.$users[$i].'">'.$username.'</a>');
  }
  else
  {
    print('<span class="highlight">'.$username.'</span>');
    print('<br />');
    print('<a href="usermanagement.php?sid='.$sid.'&userid='.$users[$i].'" class="gensmall">Manage</a>');
    print(' ');
    print('<a href="userpermissions.php?sid='.$sid.'&userid='.$users[$i].'" class="gensmall">Permissions</a>');
    print('<br />&nbsp;');
  }
?>
  </td>
  <td class="gen"><?php print($email); ?></td>
  
  <td class="gen" align="center">
<?php
  if($iscontact) print('Yes');
  else print('&nbsp;');
?>
  </td>
  
  <td class="gen"><?php print($contactfunction); ?></td>

  <td class="gen" align="center">
<?php
  if($useractive) print('Yes');
  else print('&nbsp;');
?>
  </td>

  <td class="gen">
<?php
  if($userlevel==USERLEVEL_USER) print('User');
  elseif($userlevel==USERLEVEL_ADMIN) print('Administrator');
?>
  </td>
  
  <td class="gen"><?php print($lastlogin); ?></td>
  <td class="gen"  align="center"><?php print($retries); ?></td>
  
</tr>
<?php
  }
?>
<tr>
<td class="spacer" colspan="8">
</td>
</tr>
<tr>
<td align="center" colspan="8">
  <input type="button" name="cancel" value="Cancel"
    onClick="self.location.href='<?php
if($ref==="userpermissions") print("userpermissions.php");
elseif($ref==="usermanagement") print("usermanagement.php");
?>?sid=<?php print($sid)?>'" class="liteoption">
</td>
</tr>


</table>
</td></tr></table>
</form>

</table>
</td></tr></table>

<?php



//
// public users
//

  $users=getallpublicusers();
?>
<p align="center">
<table><tr><td class="bodyline">
<table>
<tr>
  <th class="thHead" colspan="3">Users for Restricted Areas</th>
</tr>
<tr>
  <th class="thHead">Username</th>
  <th class="thHead">Active?</th>
  <th class="thHead">Pages</th>
</tr>
<?php

  for($i=0; $i<count($users);$i++)
  {

    // get all user values from DB
    $username=getpublicusername($users[$i]);
    $useractive = ispublicuseractive($users[$i]);
?>

<tr>
  <td class="table">
<?php
  if($ref)
  {
    print('<a href="'.$ref.'.php?sid='.$sid.'&userid='.$users[$i].'&type=public">'.$username.'</a>');
  }
  else
  {
    print('<span class="highlight">'.$username.'</span>');
    print('<br />');
    print('<a href="usermanagement.php?sid='.$sid.'&userid='.$users[$i].'&type=public" class="gensmall">Manage</a>');
    print(' ');
    print('<a href="userpermissions.php?sid='.$sid.'&userid='.$users[$i].'&type=public" class="gensmall">Permissions</a>');
    print('<br />&nbsp;');
  }
?>
  </td>
  <td class="gen" align="center">
<?php
  if($useractive) print('Yes');
  else print('&nbsp;');
?>
  </td>
  <td class="table">
<?php
  $userpages=getpageaccessforpublicuser($users[$i]);
  for($j=0;$j<count($userpages);$j++)
  {
    if($j>0)
    {
      print('<span class="gen"> - </span>');
    }
    print('<a href="'.getprojectrootlinkpath().'admin/pagedisplay.php?page='.$userpages[$j].'&sid='.$sid.'" target="_blank"  class="gen">'.$userpages[$j].": ".getnavtitle($userpages[$j]).'</a>');
  }
  if(!count($userpages))
  {
    print('<div align="center"> &mdash; </div>');
  }
?>
  </td>
</tr>
<?php
  }
?>
<tr>
<td class="spacer" colspan="8">
</td>
</tr>
<tr>
<td align="center" colspan="8">
  <input type="button" name="cancel" value="Cancel"
    onClick="self.location.href='<?php
if($ref==="userpermissions") print("userpermissions.php");
elseif($ref==="usermanagement") print("usermanagement.php");
?>?sid=<?php print($sid)?>'&type=public" class="liteoption">
</td>
</tr>


</table>
</td></tr></table>
</form>

</table>
</td></tr></table>
</p>
<?php
}
?>
