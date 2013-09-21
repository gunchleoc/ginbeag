<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

// check legal vars
include($projectroot."admin/includes/legaladminvars.php");

include_once($projectroot."admin/functions/usersmod.php");
include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/includes/templates/adminprofile.php");

$sid=$_GET['sid'];
checksession($sid);


// HTTP-vars
if(isset($_POST['oldpass'])) $oldpass=trim($_POST['oldpass']);
else $oldpass="";

if(isset($_POST['pass'])) $pass=trim($_POST['pass']);
else $pass="";

if(isset($_POST['passconfirm'])) $passconf=trim($_POST['passconfirm']);
else $passconf="";

if(isset($_POST['email'])) $email=trim($_POST['email']);
else $email="";


$userid=getsiduser($sid);
$message="";

if(isset($_POST['contact']))
{
  $message='Changed contact page options';

  if(isset($_POST['iscontact']))
  {
    changeiscontact($userid,1);
  }
  else
  {
    changeiscontact($userid,0);
  }
  changecontactfunction($userid,$_POST['contactfunction']);
}
else
{
  if($pass)
  {
    $message=changeuserpassword($userid,$oldpass,$pass,$passconf).' ';
  }
  if($email)
  {
    if(emailexists($email,$userid))
    {
      $message.='E-mail <i>'.$email.'</i> already exists!';
    }
    else
    {
      changeuseremail($userid,$email);
    }
  }
}

$content = new ProfilePage($userid);
print($content->toHTML());

?>
