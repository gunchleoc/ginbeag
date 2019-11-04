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

$projectroot=dirname(__FILE__)."/";

require_once $projectroot."functions/db.php";
require_once $projectroot."includes/functions.php";

// anti bot nonsense links
// ********************************* achtung - bot secure ist server-spezifisch!
$testpath = "/".getproperty("Local Path");
if (getproperty("Local Path") == "") {
    $testpath = "";
}

if (!((isset($_SERVER["ORIG_PATH_TRANSLATED"])
    && $_SERVER["ORIG_PATH_TRANSLATED"] == $projectroot."guestbook.php")
    || $_SERVER["PHP_SELF"] == $testpath."/guestbook.php")
) {
    //    print("test: ".$_SERVER["PHP_SELF"]);
    header("HTTP/1.0 404 Not Found");
    print("HTTP 404: Sorry, but this page does not exist.");
    exit;
}

// check legal vars
require_once $projectroot."includes/legalvars.php";

require_once $projectroot."functions/email.php";
require_once $projectroot."functions/antispam.php";
require_once $projectroot."functions/guestbook.php";
require_once $projectroot."includes/objects/page.php";
require_once $projectroot."includes/objects/forms.php";
require_once $projectroot."includes/objects/guestbook.php";

if (isset($_GET['sid'])) {
    $sid = $_GET['sid'];
} elseif (isset($_POST['sid'])) {
    $sid = $_POST['sid'];
} else {
    $sid="";
}

$token = "";
if (isset($_POST['token'])) {
    $token = $_POST['token'];
    unset($_POST['token']);
} else {
    $token = createtoken($_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']);
}

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
if (isset($_POST['post'])) {
    $showguestbookform=true;
    $listtitle=getlang("guestbook_latestentries");
    $showleavemessagebutton=false;
    $title=getlang("guestbook_leavemessageguestbook");
} elseif (isset($_POST['submitpost'])) {
    // submit an entry to the guestbook
    $showguestbookform = true;
    $listtitle = getlang("guestbook_latestentries");
    $showpost = true;

    $postername = trim($_POST['postername']);
    $postername = fixquotes($postername);
    $addy = trim($_POST[$emailvariables['E-Mail Address Variable']]);
    $subject = trim($_POST[$emailvariables['Subject Line Variable']]);
    $subject = html_entity_decode(fixquotes($subject));
    $messagetext
        = trim(
            stripslashes($_POST[$emailvariables['Message Text Variable']])
        );
    $messagetext = html_entity_decode(fixquotes($messagetext));

    $error
        = emailerror($addy, $subject, $messagetext, $token,
            $_SERVER['HTTP_USER_AGENT'],
            $_SERVER['REMOTE_ADDR'],
            $_POST[$emailvariables['Math CAPTCHA Reply Variable']],
            $_POST[$emailvariables['Math CAPTCHA Answer Variable']]
        );
    if (strlen($postername) < 1) {
        $error = errormessage("guestbook_needname").$error;
    }
    if (empty($error)) {
        $showguestbookform = false;
        $postadded = true;
        $showleavemessagebutton = true;
        $offset = 0;
        addguestbookentry($postername, $addy, $subject, $messagetext);
        sendemail(
            $addy, $subject, $messagetext,
            getproperty("Admin Email Address"), $token, true
        );
    } else {
        if (!hastoken($token, $_SERVER['HTTP_USER_AGENT'])) {
            $error = errormessage("guestbook_invalidtoken");
            $token = createtoken($_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']);
        }
    }
} else {
    // display entries
    $title=getlang("pageintro_guestbook");
    if (isset($_GET["offset"])) {
        $offset=$_GET["offset"];
    } else {
        $offset=0;
    }
}

$guestbook
    = new Guestbook(
        $postername, $addy, $subject, $messagetext, $token, $offset,
        $showguestbookform, $showpost, $showleavemessagebutton, $itemsperpage,
        $title, $listtitle, $message, $error, $postadded
    );
print($guestbook->toHTML());
?>
