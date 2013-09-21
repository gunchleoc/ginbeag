<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/functions/usersmod.php");
include_once($projectroot."admin/functions/publicusersmod.php");
include_once($projectroot."functions/email.php");
include_once($projectroot."includes/objects/elements.php");
include_once($projectroot."admin/includes/objects/site/users.php");
include_once($projectroot."includes/functions.php");
include_once($projectroot."admin/includes/objects/adminmain.php");

if(isset($_GET['sid'])) $sid=$_GET['sid'];
else $sid="";
checksession($sid);

if(!isadmin($sid))
{
  die('<p class="highlight">You have no permission for this area</p>');
}

if(isset($_GET['page'])) $page=$_GET['page'];
else $page=0;

$message="";

if(isset($_GET['userid'])) $userid=$_GET['userid'];
elseif(isset($_POST['userid'])) $userid=$_POST['userid'];
else $userid=-1;

// print_r($_POST);
 //print_r($_GET);

if(isset($_POST['searchuser']))
{
  $userid=getuserid(fixquotes($_POST['username']));
}
elseif(isset($_POST['searchpublicuser']))
{
  $userid=getpublicuserid($_POST['username']);
}
if((isset($_POST['searchuser']) || isset($_POST['searchpublicuser'])) && !$userid)
{
  $message='User <i>'.$_POST['username'].'</i> not found.';
}
// public users for restricted areas
elseif(isset($_GET['type']) && $_GET['type']==="public")
{
  if(isset($_POST['profile']) || isset($_GET['profile']))
  {
    if(isset($_POST['pass']))
    {
      $message=changepublicuserpasswordadmin($userid,$_POST['pass'],$_POST['passconfirm'],$sid);
    }
  }
  elseif(isset($_POST['deactivate']))
  {
    deactivatepublicuser($userid);
    $message='User <i>'.getpublicusername($userid).'</i> deactivated.';
  }
  elseif(isset($_POST['activate']))
  {
    activatepublicuser($userid);
    $message='User <i>'.getpublicusername($userid).'</i> activated.';
  }
}
// webpage editors
else
{
  if(isset($_POST['profile']) || isset($_GET['profile']))
  {
    if($_POST['pass'])
    {
      $message.=changeuserpasswordadmin($userid,$_POST['pass'],$_POST['passconfirm'],$sid);
    }
    if($_POST['email'])
    {
      if(emailexists($_POST['email'],$userid))
      {
        $message='E-mail <i>'.$_POST['email'].'</i> already exists!';
      }
      else
      {
        changeuseremail($userid,$_POST['email']);
        $message.=' EMail changed';
      }
    }
  }
  elseif(isset($_POST['contact']) || isset($_GET['contact']))
  {
    $message= 'Changed contact page options';

    if(isset($_POST['iscontact']))
    {
      changeiscontact($userid,1);
    }
    else
    {
      changeiscontact($userid,0);
    }
    changecontactfunction($userid,fixquotes($_POST['contactfunction']));
    print('</p>');
  }
  elseif(isset($_POST['generate']) || isset($_GET['generate']))
  {
    $email=getuseremail($userid);
    $message='Generated new password for <i>'.getusername($userid).'</i>';

    $newpassword=makepassword($userid);

    $message="The Administrator has generated a new password for you.";
    $message.="\r\n\r\nYour new password is";
    $message.="\r\n\r\n".$newpassword;
    $message.="\r\n\r\nYou can logon at ".getprojectrootlinkpath().'admin/login.php';
    $message.="\r\n\r\nPlease go to your profile to change your password after logging in.";
    $subject="Your webpage editing account";
    sendplainemail($subject,$message,$email);
  }
  elseif(isset($_POST['deactivate']))
  {
    $username=getusername($userid);
    deactivateuser($username);
    $message='User <i>'.getusername($userid).'</i> deactivated.';
  }
  elseif(isset($_POST['activate']))
  {
    $username=getusername($userid);
    activateuser($username);
    $message='User <i>'.getusername($userid).'</i> activated.';
  }
}
if($userid>0)
{
  if(isset($_GET['type']) && $_GET['type']==="public" || isset($_POST['searchpublicuser']))
  {
    $contents = new SitePublicUserProfileForm($userid);
  }
  else
  {
    $contents = new SiteAdminUserProfileForm($userid);
  }
}
else
{
	$contents = new SiteSelectUserForm();
}

$content = new AdminMain($page,"siteuserman",$message,$contents);
print($content->toHTML());
$db->closedb();
?>