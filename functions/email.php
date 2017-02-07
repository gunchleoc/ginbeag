<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"functions"));

include_once($projectroot."functions/users.php");
include_once($projectroot."language/languages.php");

//initialize anti-spam variable names
$emailvariables=getmultiplefields(ANTISPAM_TABLE, "property_name", "1",
	array(0 => 'property_name', 1 => 'property_value'));

// check data
// returns error message
// returns "" on success
//
function emailerror($addy,$subject,$messagetext,$sendcopy)
{
	global $_POST, $emailvariables;
	$result="";


	// check e-mail addy
	if($addy=="")
	{
		$result.=errormessage("email_enteremail");
	}
	else
	{
		$mail_chars="0123456789aAbBcCdDeEfFgGhHiIjJkKlLmMnNoOpPqQrRsStTuUvVwWxXyYzZ-_.@";
		$addy_length=strlen($addy);
		$illegals="";
		$at_detected=false;
		$dot_detected=false;
		// check e-mail for illegal chars and for @-sign
		for($i=0;$i<$addy_length;$i++)
		{
			$current_char=substr($addy,$i,1);
			if(strstr($mail_chars,$current_char)==false)
			{
				$illegals = $illegals.$current_char." ";
			}
			if($current_char=="@")
			{
				$at_detected=true;
			}
			if($at_detected)
			{
				if($current_char==".")
				{
					$dot_detected=true;
				}
			}
		}
		if($illegals)
		{
			$result.=errormessage("email_illegalchar");
			$result.=$illegals;
			$result.=errormessage("email_reenteremail");
		}
		elseif(!$dot_detected)
		{
			$result.=errormessage("email_notvalidemail");
			$result.=errormessage("email_reenteremail");
		}
	}
	// check subject
	if($subject=="")
	{
		$result.=errormessage("email_specifysubject");
	}
	// check message text
	if($messagetext=="")
	{
		$result.=errormessage("email_emptymessage");
	}

	// test captcha
	if($emailvariables['Use Math CAPTCHA']['property_value'])
	{
		if($_POST[$emailvariables['Math CAPTCHA Reply Variable']['property_value']] != $_POST[$emailvariables['Math CAPTCHA Answer Variable']['property_value']])
		{
			$result.=errormessage("email_wrongmathcaptcha");
		}
	}
	$spamwords_subject = explode("\n",$emailvariables['Spam Words Subject']['property_value']);
	$spamwords_content = explode("\n",$emailvariables['Spam Words Content']['property_value']);
	$spamwords = false;
	foreach ($spamwords_subject as $spamword)
	{
		if(strpos($subject, $spamword))
		{
			$spamwords = true;
			break;
		}
	}
	foreach ($spamwords_content as $spamword)
	{
		if(strpos($messagetext, $spamword))
		{
			$spamwords = true;
			break;
		}
	}
	if($spamwords) {
		$result.=errormessage("email_spamwords");
	}
	return $result;
}

//
//
//
function printemailinfo($addy,$subject,$messagetext,$sendcopy)
{
	print('<p class="pagetitle">'.getlang("email_enteredmessage").':</p><p><hr><p><div>');

	// display e-mail
	print("<p><b>".getlang("email_email")."</b> ".$addy."<br>");
	// display subject
	$subject=stripslashes($subject);
	print("<p><b>".getlang("email_subject").":</b> ".$subject."<p>");

	// display message
	$message_display=$messagetext;
	$message_display=stripslashes(nl2br($message_display));
	print("<p><b>".getlang("email_message").":</b><br>".$message_display);

	// display copy info
	if($sendcopy)
	{
		print("<p><b>".getlang("email_copyrequested")."</b>");
	}
	else
	{
		print("<p><b>".getlang("email_nocopyrequested")."</b>");
	}
	print('</div>');
}

//
//
//
function sendemail($addy,$subject,$messagetext,$sendcopy,$recipient,$isguestbookentry=false)
{
	$subject= utf8_decode($subject);
	$messagetext= utf8_decode($messagetext);

	if($isguestbookentry)
	{
		$message_intro=utf8_decode(getlang("email_yourguestbookentry").' @ '. html_entity_decode(getproperty("Site Name")))."\n";
	}
	else
	{
		$message_intro=getlang("email_from").$addy."\n".getlang("email_to").$recipient."\n";
	}
	$message_intro.="________________________________________________________________\n\n";

	$messagetext=stripslashes($messagetext);
	$messagetext=str_replace("\n","\r\n",$messagetext);

	$messagetext.="\n\n________________________________________________________________\n\n";
	$messagetext.=utf8_decode(html_entity_decode(getproperty("Email Signature")));

	$subject=stripslashes($subject);

	if($isguestbookentry)
	{
		@mail($recipient,utf8_decode(html_entity_decode(getlang("email_guestbooksubject").getproperty("Site Name")))." - ".$subject,utf8_decode(html_entity_decode(getlang("email_guestbooksubject").getproperty("Site Name")))."\n\n".$messagetext,"From: ".$recipient);
		@mail($addy,utf8_decode(html_entity_decode(getlang("email_yourguestbookentry").' @ '.getproperty("Site Name")))." - ".$subject,$message_intro.$messagetext,"From: ".$recipient);
	}
	else
	{
		@mail($recipient,utf8_decode(sprintf(getlang("email_contactsubject"), html_entity_decode(getproperty("Site Name")))).$subject,$message_intro.$messagetext,"From: ".$addy)
		or die('<p class="highlight"><b>'.utf8_decode(getlang("email_errorsending")).'</b></p>');
		if($sendcopy)
		{
			@mail($addy,utf8_decode(sprintf(getlang("email_yourmessage"), html_entity_decode(getproperty("Site Name")))).$subject,getlang("email_thisemailwassent").":\n\n".$message_intro.$messagetext,"From: ".$addy)
			or die('<p class="highlight"><b>'.utf8_decode(getlang("email_errorsending")).'</b></p>');
		}
	}
	unset($_POST['addy']);
}

//
//
//
function sendplainemail($subject,$message,$recipient)
{
	$subject= utf8_decode($subject);
	$message= utf8_decode($message);

  	$adminemail=getproperty("Admin Email Address");

	$error='<p class="highlight">'.utf8_decode(getlang("email_errorsending")).sprintf(utf8_decode(getlang("email_contactwebmaster")),'<a href="../contact.php'.makelinkparameters(array("user" => "webmaster")).'">','</a>').'</p>';

  	@mail($recipient,$subject,$message,"From: ".$adminemail)
      	or die($error);
  	print('<p>'.getlang("email_emailsent").'</p>');
}

//
//
//
function errormessage($key)
{
	return'<p class="highlight">'.getlang($key)."</p>";
}

//
//
//
function makemathcaptcha()
{
    $result=array();

    list($usec, $sec) = explode(' ', microtime());
    $number1= ((float) $sec + ((float) $usec * 100000)) % 20;
    list($usec, $sec) = explode(' ', microtime());
    $number2= ((float) $sec + ((float) $usec * 100000)) % 10;

    $result["question"] = ($number1+1)."&nbsp;+ ".($number2+1)." = ";
    $result["answer"] = $number1+$number2+2;
    return $result;
}

?>
