<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

// check legal vars
include($projectroot."admin/includes/legalsitevars.php");

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/functions/publicusersmod.php");
include_once($projectroot."admin/includes/adminelements.php");
include_once($projectroot."includes/templates/elements.php");

$sid=$_GET['sid'];
checksession($sid);

$header = new HTMLHeader("Create User for Restriced Areas","Webpage Building");
print($header->toHTML());

if(!isadmin($sid))
{
  die('<p class="highlight">You have no permission for this area</p>');
}

// print_r($_POST);
// print_r($_GET);

if(isset($_POST['user'])) $user=trim($_POST['user']);
else $user="";

if(isset($_POST['pass'])) $pass=$_POST['pass'];
else $pass="";

if(isset($_POST['passconfirm'])) $passconf=$_POST['passconfirm'];
else $passconf="";


if($user && $pass===$passconf)
{
  if(publicuserexists($user))
  {
    print('<p class="highlight">Username already exists!</p>');
    createuserform($user);
  }
  elseif(!$pass)
  {
    print('<p class="highlight">Please specify a password!</p>');
    createuserform($user);
  }
  else
  {
    $register=addpublicuser($user,$pass);
    
    if($register)
    {
?>
<div class="gen">Created user successfully.
<br />&nbsp;<br />
<a href="createpublicuser.php?sid=<?php print($sid);?>">Create
 another user</a>
&nbsp;<a href="usermanagement.php?sid=<?php print($sid);?>&userid=<?php print($register);?>&type=public">Manage this user</a>
</div>
<?php
    }
    else
    {
      print('<p class="highlight">error</p>');
      createuserform($user);
    }
  }
}
elseif($user && $pass!=$passconf)
{
  print('<p class="highlight">Passwords did not match!</p>');
  createuserform($user);
}
else
{
  createuserform();
}
?>
</span>
</body>
</html>

<?php

function createuserform($user="")
{
?>
<form name="register" method="post">
<table><tr><td class="bodyline">
<table><tr>
<th class="thHead" colspan="2">Create User</th>
</tr>
<tr>
<td class="table"><span class="gen">Username:</span></td>
<td class="table"><input type="text" name="user" size="20" maxlength="25" value="<?php print(input2html($user)); ?>" /></td>
</tr>
<tr>
<td class="table"><span class="gen">Password:</span></td>
<td class="table"><input type="password" name="pass" size="20" maxlength="32" /></td>
</tr>
<tr>
<td class="table"><span class="gen">Confirm Password:</span></td>
<td class="table"><input type="password" name="passconfirm" size="20" maxlength="32" /></td>
</tr>
</table>
</td></tr></table>
<p><input type="submit" name="createuser" value="Create User" class="mainoption">
&nbsp;&nbsp;<input type="reset" value="Cancel" class="liteoption">
</form>

<?php
}
?>
