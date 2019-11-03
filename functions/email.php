<?php
/**
 * An Gineadair Beag is a content management system to run websites with.
 *
 * PHP Version 7
 *
 * Copyright (C) 2005-2019 GunChleoc
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 * @category Ginbeag
 * @package  Ginbeag
 * @author   gunchleoc <fios@foramnagaidhlig.net>
 * @license  https://www.gnu.org/licenses/agpl-3.0.en.html GNU AGPL
 * @link     https://github.com/gunchleoc/ginbeag/
 */

$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "functions"));

require_once $projectroot."functions/users.php";
require_once $projectroot."language/languages.php";

//initialize anti-spam variable names
$sql = new SQLSelectStatement(ANTISPAM_TABLE, array('property_name', 'property_value'));
$emailvariables = $sql->fetch_two_columns();

// Returns true if $matchme contains $spamword. Uses case-insensitive regex.
function match_spamword($matchme, $spamword)
{
    $findme = trim($spamword);
    $findme = str_replace("?", "\?", $findme);
    $findme = str_replace("@", "\@", $findme);
    $pattern ="/".$findme."/i";
    return preg_match($pattern, $matchme);
}

// check data
// returns error message
// returns "" on success
//
function emailerror($addy,$subject,$messagetext,$sendcopy)
{
    global $_POST, $emailvariables;
    $result="";

    // Spam words
    $spamwords_subject = explode("\n", $emailvariables['Spam Words Subject']);
    $spamwords_content = explode("\n", $emailvariables['Spam Words Content']);
    $spamwords = false;
    foreach ($spamwords_subject as $spamword)
    {
        if(match_spamword($subject, $spamword)) {
            $spamwords = true;
            break;
        }
    }
    foreach ($spamwords_content as $spamword)
    {
        if(match_spamword($messagetext, $spamword)) {
            $spamwords = true;
            break;
        }
    }

    if($spamwords) {
        return errormessage("email_generic_error");
    }


    // check e-mail addy
    if($addy=="") {
        $result.=errormessage("email_enteremail");
    }
    else if(!filter_var($addy, FILTER_VALIDATE_EMAIL)) {
        $result.=errormessage("email_reenteremail");
    }
    // check subject
    if($subject=="") {
        $result.=errormessage("email_specifysubject");
    }
    // check message text
    if($messagetext=="") {
        $result.=errormessage("email_emptymessage");
    }

    // test captcha
    if($emailvariables['Use Math CAPTCHA']) {
        if($_POST[$emailvariables['Math CAPTCHA Reply Variable']] != $_POST[$emailvariables['Math CAPTCHA Answer Variable']]) {
            $result.=errormessage("email_wrongmathcaptcha");
        }
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
    if($sendcopy) {
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

    if($isguestbookentry) {
        $message_intro = getlang("email_yourguestbookentry" . ' @ '. html_entity_decode(getproperty("Site Name"))) . "\n";
    }
    else
    {
        $message_intro=getlang("email_from").$addy."\n".getlang("email_to").$recipient."\n";
    }
    $message_intro.="________________________________________________________________\n\n";

    $messagetext=stripslashes($messagetext);
    $messagetext=str_replace("\n", "\r\n", $messagetext);

    $messagetext.="\n\n________________________________________________________________\n\n";
    $messagetext.=utf8_decode(html_entity_decode(getproperty("Email Signature")));

    $subject=stripslashes($subject);

    if($isguestbookentry) {
        @mail($recipient, utf8_decode(html_entity_decode(getlang("email_guestbooksubject").getproperty("Site Name")))." - ".$subject, utf8_decode(html_entity_decode(getlang("email_guestbooksubject").getproperty("Site Name")))."\n\n".$messagetext, "From: ".$recipient);
        @mail($addy, utf8_decode(html_entity_decode(getlang("email_yourguestbookentry").' @ '.getproperty("Site Name")))." - ".$subject, $message_intro.$messagetext, "From: ".$recipient);
    }
    else
    {
        @mail($recipient, utf8_decode(sprintf(getlang("email_contactsubject"), html_entity_decode(getproperty("Site Name")))).$subject, $message_intro.$messagetext, "From: ".$addy)
        or die('<p class="highlight"><b>' . getlang("email_errorsending") . '</b></p>');
        if($sendcopy) {
            @mail($addy, utf8_decode(sprintf(getlang("email_yourmessage"), html_entity_decode(getproperty("Site Name")))).$subject, getlang("email_thisemailwassent").":\n\n".$message_intro.$messagetext, "From: ".$addy)
            or die('<p class="highlight"><b>' . getlang("email_errorsending") . '</b></p>');
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

    $error='<p class="highlight">' . getlang("email_errorsending") . sprintf(getlang("email_contactwebmaster"), '<a href="../contact.php'.makelinkparameters(array("user" => "webmaster")).'">', '</a>').'</p>';

    @mail($recipient, $subject, $message, "From: ".$adminemail)
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
