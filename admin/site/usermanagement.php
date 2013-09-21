<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

// check legal vars
include($projectroot."admin/includes/legalsitevars.php");

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/functions/usersmod.php");
include_once($projectroot."admin/functions/publicusersmod.php");
include_once($projectroot."admin/includes/adminelements.php");
include_once($projectroot."functions/email.php");

$sid=$_GET['sid'];
checksession($sid);

$header = new HTMLHeader("Manage users","Webpage Building");
print($header->toHTML());

if(!isadmin($sid))
{
  die('<p class="highlight">You have no permission for this area</p>');
}

if(isset($_GET['userid'])) $userid=$_GET['userid'];
elseif(isset($_POST['userid'])) $userid=$_POST['userid'];
else $userid=-1;

// print_r($_POST);
// print_r($_GET);

if(isset($_POST['searchuser']))
{
  $userid=getuserid($_POST['username']);
}
elseif(isset($_POST['searchpublicuser']))
{
  $userid=getpublicuserid($_POST['username']);
}
if((isset($_POST['searchuser']) || isset($_POST['searchpublicuser'])) && !$userid)
{
  print('<p class="highlight">User <i>'.$_POST['username'].'</i> not found.</p>');
}
// public users for restricted areas
elseif(isset($_GET['type']) && $_GET['type']==="public")
{
  if(isset($_POST['profile']) || isset($_GET['profile']))
  {
    if(isset($_POST['pass']))
    {
      print('<p class="highlight">'.changepublicuserpasswordadmin($userid,$_POST['pass'],$_POST['passconfirm'],$sid).'</p>');
    }
  }
  elseif(isset($_POST['deactivate']))
  {
    deactivatepublicuser($userid);
    print('<p class="highlight">User <i>'.getpublicusername($userid).'</i> deactivated.</p>');
  }
  elseif(isset($_POST['activate']))
  {
    activatepublicuser($userid);
    print('<p class="highlight">User <i>'.getpublicusername($userid).'</i> activated.</p>');
  }
}
// webpage editors
else
{
  if(isset($_POST['profile']) || isset($_GET['profile']))
  {
    if($_POST['pass'])
    {
      print('<p class="highlight">'.changeuserpasswordadmin($userid,$_POST['pass'],$_POST['passconfirm'],$sid).'</p>');
    }
    if($_POST['email'])
    {
      if(emailexists($_POST['email'],$userid))
      {
        print('<span class="gen">E-mail</span> <span class="highlight">'.$_POST['email'].'</span> <span class="gen">already exists!</span>');
      }
      else
      {
        changeuseremail($userid,$_POST['email']);
      }
    }
  }
  elseif(isset($_POST['contact']) || isset($_GET['contact']))
  {
    print ('<p class="highlight">Changed contact page options</p>');

    if(isset($_POST['iscontact']))
    {
      changeiscontact($userid,1);
    }
    else
    {
      changeiscontact($userid,0);
    }
    changecontactfunction($userid,$_POST['contactfunction']);
    print('</p>');
  }
  elseif(isset($_POST['generate']) || isset($_GET['generate']))
  {
    $email=getuseremail($userid);
    print ('<p class="gen">Generated new password for '.getusername($userid));
    print ('<br />The user has been notified per e-mail to '.$email.'.</p>');

    $newpassword=makepassword($userid);

    $message="The Administrator has generated a new password for you.";
    $message.="\r\n\r\nYour new password is";
    $message.="\r\n\r\n".$newpassword;
    $message.="\r\n\r\nYou can logon at ".getprojectrootlinkpath().'admin/login.php';
    $message.="\r\n\r\nPlease go to your profile to change your password after logging in.";
    $subject="Your webpage editing account";
    sendplainemail($subject,$message,$email,"en");
  }
  elseif(isset($_POST['deactivate']))
  {
    $username=getusername($userid);
    deactivateuser($username);
    print('<p class="highlight">User <i>'.getusername($userid).'</i> deactivated.</p>');
  }
  elseif(isset($_POST['activate']))
  {
    $username=getusername($userid);
    activateuser($username);
    print('<p class="highlight">User <i>'.getusername($userid).'</i> activated.</p>');
  }
}
if($userid>0)
{
  if(isset($_GET['type']) && $_GET['type']==="public" || isset($_POST['searchpublicuser']))
  {
    publicuserprofileform($userid);
  }
  else
  {
    adminprofileform($userid);
  }
}
else
{
  selectuserform();
}

$footer = new HTMLFooter();
print($footer->toHTML());

//
//
//
function selectuserform()
{
  global $sid;
?>

<form name="profile" action="?sid=<?php print($sid)?>" method="post">
<table><tr><td class="bodyline">
<table><tr>
<th class="thHead" colspan="2" width="100%">Search for Username</th>
</tr>
<tr>
<tr>
<td class="table"><span class="gen">Username:</span><br /><span class="gensmall">Please enter the full username</span></td>
<td class="table"><input type="text" name="username" size="20" maxlength="25" value="" /></td>
</tr>
<tr>
<td class="spacer" colspan="2">
</td>
</tr>
<tr>
<td class="table" colspan="2">
<p><input type="submit" name="searchuser" value="Webpage Editors" class="mainoption">
&nbsp;&nbsp;&nbsp;<input type="submit" name="searchpublicuser" value="Users for Restriced Areas" class="mainoption">
</td>
</tr>
</table>
</td></tr></table>
<br /><a href="userlist.php?sid=<?php print($sid);?>&ref=usermanagement" class="gen">Select user from list</a>
</form>

<hr>
<form name="createpublicform" action="createpublicuser.php?sid=<?php print($sid)?>" method="post">
<p><input type="submit" name="createuser" value="Create User for Restricted Areas" class="mainoption">
</form>


<?php
}

//
//
//
function adminprofileform($userid)
{
  global $sid;

  // get all user values from DB
  $username=getusername($userid);
  $email=getuseremail($userid);
  $iscontact=getiscontact($userid);
  $contactfunction=getcontactfunction($userid);
?>
<p class="sectiontitle">Edit profile for: <?php print($username)?></p>

<form name="profile" action="?sid=<?php print($sid)?>&profile=change" method="post">
<table><tr><td class="bodyline">
<table><tr>
<th class="thHead" colspan="2">Password & E-mail</th>
</tr>
<tr>
<td class="table"><span class="gen">New Password:</span></td>
<td class="table"><input type="password" name="pass" size="20" maxlength="32" /></td>
</tr>
<tr>
<td class="table"><span class="gen">Confirm New Password:</span></td>
<td class="table"><input type="password" name="passconfirm" maxlength="32" size="20" /></td>
</tr>
<tr>
<td class="table"><span class="gen">E-mail address:</span></td>
<td class="table"><input type="text" name="email" size="20" maxlength="255" value="<?php print($email)?>" /></td>
</tr>
<tr>
<td class="spacer" colspan="2">
</td>
</tr>
<tr>
<td class="table" align="center" colspan="2">
<input type="hidden" name="userid" value="<?php print($userid)?>" />
<input type="submit" name="profile" value="Change Profile" class="mainoption">
<input type="reset" value="Reset Profile" class="liteoption">
</td>
</tr>


</table>
</td></tr></table>

</form>


<form name="generatepasswordform" action="?sid=<?php print($sid)?>&generate=generate" method="post">
<input type="hidden" name="userid" value="<?php print($userid)?>" />
<p><input type="submit" name="generate" value="Auto-generate new password" class="mainoption">
</form>


<hr>

<form name="contactsettingsform" action="?sid=<?php print($sid)?>&contact=contact" method="post">
<table><tr><td class="bodyline">
<table><tr>
<th class="thHead" colspan="2">Contact page options</th>
</tr>
<tr>
<td class="table"><span class="gen">Display me on the contact page:</span></td>
<td class="table"><span class="gen">
  <input type="checkbox" name="iscontact" value="Is Contact" class="gen" <?php
if($iscontact) print("checked");
?>/></span>
</td>
</tr>
<tr>
<td class="table"><span class="gen">Responsible for:</span></td>
<td class="table"><input type="text" name="contactfunction" size="20" maxlength="50" value="<?php print(input2html($contactfunction))?>" /></td>
</tr>


<tr>
<td class="spacer" colspan="2">
</td>
</tr>
<tr>
<td class="table" align="center" colspan="2">
<input type="hidden" name="userid" value="<?php print($userid)?>" />
<input type="submit" name="contact" value="Submit Changes" class="mainoption">
<input type="reset" value="Reset" class="liteoption">
</td>
</tr>


</table>
</td></tr></table>
</form>

<hr>

<form name="activate" action="?sid=<?php print($sid)?>" method="post">
<input type="hidden" name="userid" value="<?php print($userid)?>" />
<?php
  if(isactive($userid))
  {
    print('<input type="submit" name="deactivate" value="Deactivate User" class="mainoption">');
  }
  else
  {
    print('<input type="submit" name="activate" value="Activate User" class="mainoption">');
  }
?>
</form>

<?php
}

//
//
//
function publicuserprofileform($userid)
{
  global $sid;

  // get all user values from DB
  $username=getpublicusername($userid);
?>
<p class="sectiontitle">User for Restriced Areas - Edit profile for: <?php print($username)?></p>

<form name="profile" action="?sid=<?php print($sid)?>&profile=change&type=public" method="post">
<table><tr><td class="bodyline">
<table><tr>
<th class="thHead" colspan="2">Password</th>
</tr>
<tr>
<td class="table"><span class="gen">New Password:</span></td>
<td class="table"><input type="password" name="pass" size="20" maxlength="32" /></td>
</tr>
<tr>
<td class="table"><span class="gen">Confirm New Password:</span></td>
<td class="table"><input type="password" name="passconfirm" maxlength="32" size="20" /></td>
</tr>
<tr>
<td class="spacer" colspan="2">
</td>
</tr>
<tr>
<td class="table" align="center" colspan="2">
<input type="hidden" name="userid" value="<?php print($userid)?>" />
<input type="submit" name="profile" value="Change Profile" class="mainoption">
<input type="reset" value="Reset Profile" class="liteoption">
</td>
</tr>

</table>
</td></tr></table>
</form>

<hr>

<form name="activatepublic" action="?sid=<?php print($sid)?>&type=public" method="post">
<input type="hidden" name="userid" value="<?php print($userid)?>" />
<?php
  if(ispublicuseractive($userid))
  {
    print('<input type="submit" name="deactivate" value="Deactivate User" class="mainoption">');
  }
  else
  {
    print('<input type="submit" name="activate" value="Activate User" class="mainoption">');
  }
?>
</form>

<?php
}


?>
