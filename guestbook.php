<?php
$projectroot=dirname(__FILE__)."/";

// check legal vars
include_once($projectroot."includes/legalvars.php");

include_once($projectroot."functions/email.php");
include_once($projectroot."functions/guestbook.php");
include_once($projectroot."includes/templates/page.php");
include_once($projectroot."includes/templates/forms.php");
include_once($projectroot."includes/templates/guestbook.php");


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


// todo remove when email redesign done
$language="en";

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
  	$addy=trim($_POST['addy']);
  	$subject=trim($_POST['subject']);
  	$messagetext=trim(stripslashes($_POST['messagetext']));
  
  	$error=emailerror($addy,$subject,$messagetext,0,$_GET['language']);
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
		sendemail($addy,$subject,$messagetext,1,getproperty("Admin Email Address"),$_GET['language'],1);
  	}
}
// display entries
else
{
	$title=getlang("pageintro_guestbook");
	if(isset($_GET["offset"])) $offset=$_GET["offset"];
	else $offset=0;
}

 
$guestbook = new Guestbook($postername,$addy,$subject,$messagetext, $offset, $showguestbookform, $showpost, $showleavemessagebutton, $itemsperpage, $title, $listtitle, $message, $error,$postadded); 
print($guestbook->toHTML());

?>
