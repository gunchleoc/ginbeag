<?php
$projectroot=dirname(__FILE__)."/";

// check legal vars
include_once($projectroot."includes/legalvars.php");

include_once($projectroot."includes/includes.php");
include_once($projectroot."functions/publicsessions.php");
include_once($projectroot."functions/publicusers.php");
include_once($projectroot."includes/templates/elements.php");
include_once($projectroot."includes/templates/forms.php");

//print_r($_GET);
//print_r($_POST);

if(isset($_POST['user']))
{
  $user=trim($_POST['user']);
  $userid=getpublicuserid($user);

  if(!$userid)
  {
    $header = new HTMLHeader("Login","Login","Wrong username or password");
    $loginform = new LoginForm($user);
  }
  elseif(ispublicuseractive($userid))
  {
    $login=publiclogin($user,trim($_POST['pass']));
    if(array_key_exists('sid',$login))
    {
      $_GET['sid']= $login['sid'];
      $contenturl='index.php'.makelinkparameters($_GET,true);
      $header = new HTMLHeader("Login","Login",$login['message'],$contenturl,"Enter",true);
    }
    else
    {
      $header = new HTMLHeader("Login","Login",$login['message']);
      $loginform = new LoginForm($user);
    }
  }
  else
  {
    $header = new HTMLHeader("Login","Login","Your account has been deactivated");
    $loginform = new LoginForm("");
  }
}
else
{
  $header = new HTMLHeader("Login","Login");
  $loginform = new LoginForm("");
}

print($header->toHTML());
print($loginform->toHTML());

$footer = new HTMLFooter();
print($footer->toHTML());

?>
