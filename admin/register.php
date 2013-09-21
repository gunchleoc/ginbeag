<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

// check legal vars
include($projectroot."admin/includes/legaladminvars.php");

include_once($projectroot."includes/functions.php");
include_once($projectroot."admin/functions/usersmod.php");
include_once($projectroot."functions/email.php");
include_once($projectroot."admin/includes/objects/profile.php");


// all HTTP-vars used in this file
$user="";
if(isset($_POST['user'])) $user=trim($_POST['user']);

$pass="";
if(isset($_POST['pass'])) $pass=trim($_POST['pass']);

$passconf="";
if(isset($_POST['passconfirm'])) $passconf=trim($_POST['passconfirm']);

$email="";
if(isset($_POST['email'])) $email=trim($_POST['email']);

$message="";
$showform=true;

if($user && $pass===$passconf)
{
  if(userexists($user))
  {
    $message='Username already exists!';
  }
  elseif(!$pass)
  {
    $message='Please specify a password!';
  }
  elseif(emailexists($email))
  {
    $message='E-mail <i>'.$email.'</i> already exists!';
    $email="";
  }
  elseif(!$email)
  {
    $message='Please specify an e-mail address!';
    $email="";
  }
  else
  {

    $register=register($user,$pass,$email);

    if($register)
    {
      $message='Registering successful.';
      $message='<br />You will be able to log in as soon as the admin activates your account.';
      sendactivationemail($user,$register);
      $showform=false;
    }
     else
    {
      $message='error';
    }
  }
}
elseif($user && $pass!=$passconf)
{
  $message='Passwords did not match!';
}

$content = new RegisterPage($user, $email,$message,$showform);
print($content->toHTML());
$db->closedb();

//
//
//
function sendactivationemail($username,$activationkey)
{
  $recipient=getproperty("Admin Email Address");
  $message="A new user has registered: ".$username;
  $message.="\r\n\r\n".getprojectrootlinkpath().'admin/activate.php?user='.urlencode($username)."&key=".$activationkey;

  $subject="New web page user account";
  sendplainemail($subject,$message,$recipient);

}
?>
