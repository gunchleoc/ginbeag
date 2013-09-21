<?php
$projectroot=dirname(__FILE__)."/";

include_once($projectroot."functions/db.php");
include_once($projectroot."includes/functions.php");


// anti bot nonsense links
// ********************************* achtung - bot secure ist server-spezifisch!
$testpath = "/".getproperty("Local Path");
if(getproperty("Local Path") == "") $testpath = "";

if(!((isset($_SERVER["ORIG_PATH_TRANSLATED"]) && $_SERVER["ORIG_PATH_TRANSLATED"] == $projectroot."guestbook.php") ||
	$_SERVER["PHP_SELF"] == $testpath."/guestbook.php"))
{
//	print("test: ".$_SERVER["PHP_SELF"]);
	header("HTTP/1.0 404 Not Found");
	print("HTTP 404: Sorry, but this page does not exist.");
	exit;
}

// check legal vars
include_once($projectroot."includes/legalvars.php");

include_once($projectroot."functions/email.php");
include_once($projectroot."functions/guestbook.php");
include_once($projectroot."includes/objects/page.php");
include_once($projectroot."includes/objects/forms.php");
include_once($projectroot."includes/objects/guestbook.php");


/// init variables for guestbook constructur
$postername="";
$addy="";
$subject="";
$messagetext="";
$offset=0;
$showguestbookform=false;
$showpost=false;
$showleavemessagebutton=true;
$itemsperpage=getproperty('Guestbook Entries Per Page');
$title="";
$listtitle="";
$message="";
$error=""; 
$postadded=false;


//display new contact form
if(isset($_POST['post']))
{
	$showguestbookform=true;
	$listtitle=getlang("guestbook_latestentries");
	$showleavemessagebutton=false;
  	$title=getlang("guestbook_leavemessageguestbook");
}
// submit an entry to the guestbook
elseif(isset($_POST['submitpost']))
{

	$showguestbookform=true;
	$listtitle=getlang("guestbook_latestentries");
	$showleavemessagebutton=false;
	$showpost=true;

  	$postername=trim($_POST['postername']);
  	$postername=fixquotes($postername);
  	$addy=trim($_POST[$emailvariables['E-Mail Address Variable']['property_value']]);
  	$subject=trim($_POST[$emailvariables['Subject Line Variable']['property_value']]);
  	$subject=fixquotes($subject);
  	$messagetext=trim(stripslashes($_POST[$emailvariables['Message Text Variable']['property_value']]));
  	$messagetext=fixquotes($messagetext);
  
  	$error=emailerror($addy,utf8_decode($subject),utf8_decode($messagetext),0);
  	if(strlen($postername)<1)
  	{
  		// todo why is formatting needed?
    	$error.='<p class="highlight">'.getlang("guestbook_needname").'</p>'.$error;
    	//$error.=getlang("guestbook_needname").$error;
  	}
	if(!$error)
  	{
  		$showguestbookform=false;
  		$postadded=true;
  		$offset=0;
    	addguestbookentry($postername,$addy,$subject,$messagetext);
		sendemail($addy,$subject,$messagetext,1,getproperty("Admin Email Address"),true);
  	}

  	
}
// display entries
else
{
	$title=getlang("pageintro_guestbook");
	if(isset($_GET["offset"])) $offset=$_GET["offset"];
	else $offset=0;
}
  	
$postername=utf8_decode($postername);
$subject=utf8_decode($subject);
$messagetext=utf8_decode($messagetext);
 
$guestbook = new Guestbook($postername,$addy,$subject,$messagetext, $offset, $showguestbookform, $showpost, $showleavemessagebutton, $itemsperpage, $title, $listtitle, $message, $error,$postadded); 
print($guestbook->toHTML());

$db->closedb();

?>
