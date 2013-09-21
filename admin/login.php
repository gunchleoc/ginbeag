<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

// check legal vars
include($projectroot."admin/includes/legaladminvars.php");

include_once($projectroot."includes/includes.php");
include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/functions/usersmod.php");
include_once($projectroot."functions/email.php");
include_once($projectroot."admin/includes/templates/adminforms.php");


//print_r($_GET);
//print_r($_POST);

$header = new HTMLHeader("Webpage building login","Webpage building login");

if(!isset($_GET["referrer"]) && isset($_SERVER["HTTP_REFERER"]))
{
  $referrer=substr($_SERVER["HTTP_REFERER"],strpos($_SERVER["HTTP_REFERER"],"admin"));
  $referrer=substr($referrer,0,strpos($referrer,".php"));
  $referrer=substr($referrer,strpos($referrer,"/")+1);
  $_GET["referrer"]=$referrer;
  
  $action=substr($_SERVER["HTTP_REFERER"],strpos($_SERVER["HTTP_REFERER"],"action"));
  if(strpos($action,"http://")<0)
  {
    $action=substr($action,strpos($action,"="));
    $action=substr($action,0,strpos($action,"&"));
    $_GET["action"]=$action;
  }
  elseif(!isset($_GET["action"]) && strpos($referrer,"ite/")>0)
  {
    $_GET["action"]="site";
  }
  if(isset($_GET["action"])&& $_GET["action"]==="site")
  {
    $_GET["contents"]=substr($referrer,strpos($referrer,"/")+1);
  }
  

  $params=substr($_SERVER["HTTP_REFERER"],strpos($_SERVER["HTTP_REFERER"],"?"));
  if(strpos($params,"http://")<0)
  {
    $_GET["params"]=$params;
  }
}

if(isset($_POST['requestemail']))
{
  $header = new HTMLHeader("Request a new password","Webpage building password","A request has been sent to the admin.");
  $username=trim($_POST['user']);
  $message=$username." requests a new password";
  $subject="Webpage editing password request";
  $recipient=getproperty("Admin Email Address");
  sendplainemail($subject,$message,$recipient,"en");
}
elseif(isset($_GET['superforgetful']))
{
  $header = new HTMLHeader("Request a new password","Webpage building password","Please specify your username. A request will be sent to the admin.");
  $form = new ForgotEmailForm(trim($_GET['user']));
}
elseif(isset($_GET['forgetful']))
{
  $header = new HTMLHeader("Request a new password","Webpage building password","Please fill out this form to receive a new password. The new password will be sent to you by e-mail. You have to use the e-mail address stated in your profile.");
  $form = new ForgotPasswordForm(trim($_GET['user']));
}
elseif(isset($_POST['requestpassword']))
{
  $username=trim($_POST['user']);
  $email=trim($_POST['email']);
  $userid=getuserid($username);
  $useremail=getuseremail($userid);
  if($useremail!==$email)
  {
    $header = new HTMLHeader("Request a new password","Webpage building password","Wrong username or e-mail!<br />Please fill out this form to receive a new password. The new password will be sent to you by e-mail. You have to use the e-mail address stated in your profile.");
    $form = new ForgotPasswordForm(trim($_GET['user']));
  }
  else
  {
    $header = new HTMLHeader("Request a new password","Webpage building password","You have been sent an e-mail with the new password.");

    $newpassword=makepassword($userid);
    $message="Your new password is";
    $message.="\r\n\r\n".$newpassword;
    $message.="\r\n\r\nYou can logon at ".getprojectrootlinkpath().'admin/login.php';
    $message.="\r\n\r\nPlease go to your profile to change your password after logging in.";
    $subject="Your webpage editing account";
    sendplainemail($subject,$message,$useremail,"en");
    
  }
}
elseif(isset($_POST['user']))
{
  $user= trim($_POST['user']);
  $userid= getuserid($user);

  if(!$userid)
  {
    $header = new HTMLHeader("Webpage building login","Webpage building login","Wrong username or password");
    $form = new AdminLoginForm($user);
  }
  elseif(isactive($userid))
  {
    $login=login($user,trim($_POST['pass']));
    if(array_key_exists('sid',$login))
    {
      if($_GET["referrer"]==="editimagelist" ||
         $_GET["referrer"]==="profile" ||
         $_GET["referrer"]==="editcategories")
      {
        $contenturl=$_GET["referrer"].'.php';
        unset($_GET["referrer"]);
        $contenturl.=makelinkparameters($_GET).'&sid='.$login['sid'];
      }
      else
      {
        unset($_GET["referrer"]);
        $_GET['sid']= $login['sid'];
        $contenturl='admin.php'.makelinkparameters($_GET, true);
      }
      $header = new HTMLHeader("Webpage building login","Webpage building login","Login Successful",$contenturl,"Enter",true);
    }
    else
    {
      $header = new HTMLHeader("Webpage building login","Webpage building login",$login['message']);
      $form = new AdminLoginForm($user);
    }
  }
  else
  {
    $header = new HTMLHeader("Webpage building login","Webpage building login","Your account has been deactivated");
    $form = new AdminLoginForm("");
  }
}
else
{
  $header = new HTMLHeader("Webpage building login","Webpage building login");
  $form = new AdminLoginForm("");
}

print($header->toHTML());

if(isset($form))
    print($form->toHTML());

$footer = new HTMLFooter();
print($footer->toHTML());
?>
