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

// Flood control
function check_flood($parameter, $value)
{
    global $emailvariables;

    // Check if message was sent with the given parameter value
    $sql = new SQLSelectStatement(ANTISPAM_TOKENS_TABLE, 'session_time', array($parameter, 'sent'), array($value, 1), 'si');
    $time = $sql->fetch_value();

    if (!empty($time)) {
        $floodtime = new DateTime($time);
        $floodtime->add(date_interval_create_from_date_string($emailvariables['Flood Interval']));
        $now = new DateTime('NOW');

        if ($now < $floodtime) {
            return errormessage("email_toosoon");
        }
    }
    return "";
}

// check data
// returns error message
// returns "" on success
//
function emailerror($sender, $subject, $messagetext, $token, $useragent, $ip, $mathreply, $mathanswer)
{
    global $emailvariables;

    if (!hastoken($token, $useragent)) {
        return errormessage("email_invalidtoken");
    }

    // Flood control
    $result = check_flood('token_id', $token);
    if (!empty($result)) {
        return $result;
    }
    $result = check_flood('session_ip', $ip);
    if (!empty($result)) {
        return $result;
    }
    $result = check_flood('email', $sender);
    if (!empty($result)) {
        return $result;
    }

    // Compare contents
    $sql = new SQLSelectStatement(ANTISPAM_TOKENS_TABLE, array('subject', 'message'));
    $previouscontents = $sql->fetch_row();
    if ($previouscontents['subject'] === $subject || $previouscontents['message'] === $messagetext) {
        return errormessage("email_duplicate");
    }

    // Overall, restrict to messages per X minutes
    $sql = new SQLSelectStatement(ANTISPAM_TOKENS_TABLE, 'session_time', array('sent'), array(1, date(DATETIMEFORMAT, strtotime('-2 minutes'))), 'is', 'session_time < ?');
    $sql->set_operator('count');
    if ($sql->fetch_value() > 2 * $emailvariables['Maximum E-mails Per Minute']) {
        return errormessage("email_toomany");
    }

    // Spam words
    $spamwords_subject = explode("\n", $emailvariables['Spam Words Subject']);
    $spamwords_content = explode("\n", $emailvariables['Spam Words Content']);
    $spamwords = false;
    foreach ($spamwords_subject as $spamword)
    {
        if (match_spamword($subject, $spamword)) {
            $spamwords = true;
            break;
        }
    }
    foreach ($spamwords_content as $spamword) {
        if (match_spamword($messagetext, $spamword)) {
            $spamwords = true;
            break;
        }
    }

    if ($spamwords) {
        return errormessage("email_generic_error");
    }

    // Technical antispam is done. Now let's validate the user input.

    // check e-mail addy
    if (empty($sender)) {
        $result .= errormessage("email_enteremail");
    } else if(!filter_var($sender, FILTER_VALIDATE_EMAIL)) {
        $result .= errormessage("email_reenteremail");
    }
    // check subject
    if (empty($subject)) {
        $result .= errormessage("email_specifysubject");
    }

    // check message text
    if (empty($messagetext)) {
        $result .= errormessage("email_emptymessage");
    }

    // test captcha
    if ($emailvariables['Use Math CAPTCHA']) {
        if ($mathreply !== $mathanswer) {
            $result .= errormessage("email_wrongmathcaptcha");
        }
    }
    return $result;
}

//
//
//
function sendemail($sender, $subject, $messagetext, $recipient, $token, $isguestbookentry=false)
{
    $errormessage = "";

    $messagetext = stripslashes($messagetext);
    $messagetext = str_replace("\n", "\r\n", $messagetext);

    $messagetext .= "\r\n\r\n________________________________________________________________\r\n\r\n";
    $messagetext .= getproperty("Email Signature");

    $subject = stripslashes($subject);

    $sitename = getproperty("Site Name");

    if ($isguestbookentry) {
        $message_intro = getlang("email_guestbookintro") . "\r\n\r\n";
        $message_intro .= "________________________________________________________________\r\n\r\n";

        $errormessage = do_send_email(
            $recipient,
            sprintf(getlang("email_guestbooksubject"), $sitename, $subject),
            $message_intro . $messagetext,
            $sender
        );
    } else {
        $message_intro = getlang("email_contactintro") . "\r\n\r\n";
        $message_intro .= getlang("email_from") . $sender . "\r\n" . getlang("email_to") . $recipient;
        $message_intro .= "\r\n\r\n________________________________________________________________\r\n\r\n";

        $errormessage = do_send_email(
            $recipient,
            sprintf(getlang("email_contactsubject"), $sitename, $subject),
            $message_intro . $messagetext,
            $sender
        );
    }
    unset($_POST['addy']);

    // Flood control
    if (empty($errormessage) || $isguestbookentry) {
        // Add e-mail info to antispam for flood control
        $sql = new SQLUpdateStatement(
            ANTISPAM_TOKENS_TABLE,
            array('session_time', 'sent', 'email', 'subject', 'message'), array('token_id'),
            array(date(DATETIMEFORMAT, strtotime('now')), 1, $sender, $subject, $messagetext, $token), 'sissss'
        );
        $sql->run();

        // If no more sessions are active, rename e-mail variables
        $sql = new SQLSelectStatement(ANTISPAM_TOKENS_TABLE, 'session_time', array('sent'), array(0, date(DATETIMEFORMAT, strtotime('-1 hour'))), 'is', 'session_time < ?');
        $sql->set_operator('count');
        if ($sql->fetch_value() < 1) {
            rename_variables();
        }
    } else {
        // Set e-mail to not sent
        $sql = new SQLUpdateStatement(
            ANTISPAM_TOKENS_TABLE,
            array('sent'), array('token_id'),
            array(0, $token), 'is'
        );
        $sql->run();
    }
    return $errormessage;
}

//
//
//
function sendplainemail($subject,$message,$recipient)
{
    $error = getlang("email_errorsending") . sprintf(getlang("email_contactwebmaster"), '<a href="../contact.php'.makelinkparameters(array("user" => "webmaster")).'">', '</a>');

    $errormessage = do_send_email($recipient, $subject, $message, getproperty("Admin Email Address"), $error);
    if (empty($errormessage)) {
        print('<p>' . getlang("email_emailsent") . '</p>');
    }
    return $errormessage;
}


// Does the actual sending of the e-mail and returns an error message. Returns empty string on success.
function do_send_email($to, $subject, $message, $from, $errormessage = "")
{
    $headers = array
    (
        'MIME-Version: 1.0',
        'Content-Type: text/plain; charset="UTF-8";',
        'Content-Transfer-Encoding: 7bit',
        'Date: ' . date('r', $_SERVER['REQUEST_TIME']),
        'Message-ID: <' . $_SERVER['REQUEST_TIME'] . md5($_SERVER['REQUEST_TIME']) . '@' . $_SERVER['SERVER_NAME'] . '>',
        'From: ' . $from,
        'Reply-To: ' . $from,
        'Return-Path: ' . $from,
        'X-Mailer: PHP v' . phpversion(),
        'X-Originating-IP: ' . $_SERVER['SERVER_ADDR'],
        'Content-Transfer-Encoding: 8bit'
    );

    $subject = '=?UTF-8?B?' . base64_encode(html_entity_decode($subject)) . '?=';

    $success = false;
    if (DEBUG) {
        print("Sending e-mail from $from to $to<br/>");
        $success = mail($to, $subject, html_entity_decode($message), implode("\n", $headers));
    } else {
        $success = @mail($to, $subject, html_entity_decode($message), implode("\n", $headers));
    }
    if ($success) {
        return "";
    } else {
        return '<p class="highlight"><b>' . getlang("email_errorsending") . $errormessage . '</b>' . error_get_last()['message'] . '</p>';
    }
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
