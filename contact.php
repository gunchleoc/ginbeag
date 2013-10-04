<?php
$projectroot=dirname(__FILE__)."/";

// check legal vars
include_once($projectroot."includes/legalvars.php");
include_once($projectroot."functions/email.php");
include_once($projectroot."includes/objects/contact.php");


$email="";
$subject="";
$messagetext="";
$sendcopy="";
$userid="";
$emailinfo="";
$errormessage="";
$sendmail=false;


if(isset($_GET['user']))
{
	$recipient=getuseremail($_GET['user']);
	$userid=getuserid($_GET['user']);
}
elseif(isset($_POST['userid']))
{
	$userid=$_POST['userid'];
	if($userid==0)
		$recipient=getproperty("Admin Email Address");
	else
		$recipient=getuseremail($userid);
}
else
{
	$recipient=getproperty("Admin Email Address");
	$userid=0;
}


// check data and send e-mail
if(isset($_POST[$emailvariables['E-Mail Address Variable']['property_value']]))
{
	// get vars
	$email=trim($_POST[$emailvariables['E-Mail Address Variable']['property_value']]);
  
//  print("test".$email);
	$subject=trim($_POST[$emailvariables['Subject Line Variable']['property_value']]);
  	$messagetext=trim(stripslashes($_POST[$emailvariables['Message Text Variable']['property_value']]));
  	$sendcopy=isset($_POST['sendcopy']) && $_POST['sendcopy'];

  	$errormessage=emailerror($email,$subject,$messagetext,$sendcopy);

  	if($errormessage=="")
  	{
    	$sendmail=true;
    	sendemail($email,$subject,$messagetext,$sendcopy,$recipient);
  	}
}

$contactpage = new ContactPage($email,$subject,$messagetext, $sendcopy, $userid, $errormessage, $sendmail);
print($contactpage->toHTML());


$db->closedb();
?>