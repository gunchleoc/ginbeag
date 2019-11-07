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
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

// check legal vars
//include($projectroot."admin/includes/legaladminvars.php");

require_once $projectroot."admin/functions/sessions.php";

// some includes moved down because of silly cookie thing on nct server
require_once $projectroot."admin/includes/objects/loginforms.php";
require_once $projectroot."functions/email.php";
require_once $projectroot."includes/objects/elements.php";
require_once $projectroot."admin/functions/usersmod.php";

$message="";

//print_r($_GET);
//print_r($_POST);

$username="";
if (isset($_POST['user'])) { $username=urldecode($_POST['user']);
} elseif (isset($_GET['user'])) { $username=urldecode($_GET['user']);
}
$serverprotocol=getproperty('Server Protocol');

if(!isset($_GET["referrer"]) && isset($_SERVER["HTTP_REFERER"])) {
    $referrer=substr($_SERVER["HTTP_REFERER"], strpos($_SERVER["HTTP_REFERER"], "admin"));
    $referrer=substr($referrer, 0, strpos($referrer, ".php"));
    $referrer=substr($referrer, strpos($referrer, "/")+1);
    $_GET["referrer"]=$referrer;

    $action=substr($_SERVER["HTTP_REFERER"], strpos($_SERVER["HTTP_REFERER"], "action"));
    if(strpos($action, $serverprotocol)<0) {
        $action=substr($action, strpos($action, "="));
        $action=substr($action, 0, strpos($action, "&"));
        $_GET["action"]=$action;
    }
    elseif(!isset($_GET["action"]) && strpos($referrer, "site/")>0) {
        $_GET["action"]="site";
    }
    if(isset($_GET["action"])&& $_GET["action"]==="site") {
        $_GET["contents"]=substr($referrer, strpos($referrer, "/")+1);
    }

    $params=substr($_SERVER["HTTP_REFERER"], strpos($_SERVER["HTTP_REFERER"], "?"));
    if(strpos($params, $serverprotocol)<0) {
        $_GET["params"]=$params;
    }
}

if(isset($_POST['requestemail'])) {
    $userid=getuserid($username);
    if(!$userid) {
        $header = new AdminLoginHeader("This username does not exist!");
        $form = new ForgotEmailForm($username);
    }
    else
    {
        $header = new AdminLoginHeader("A request for a new password has been sent to the admin.");
        $message=$username." requests a new password";
        $subject="Webpage editing password request";
        $recipient=getproperty("Admin Email Address");
        sendplainemail($subject, $message, $recipient);
    }
}
elseif(isset($_GET['superforgetful'])) {
    $header = new AdminLoginHeader("Please specify your username. A request will be sent to the admin.");
    $form = new ForgotEmailForm($username);
}
elseif(isset($_GET['forgetful'])) {
    $header = new AdminLoginHeader("Please fill out this form to receive a new password. The new password will be sent to you by e-mail. You have to use the e-mail address stated in your profile.");
    $form = new ForgotPasswordForm($username);
}
elseif(isset($_POST['requestpassword'])) {
    $email=trim($_POST['email']);
    $userid=getuserid($username);
    $useremail=getuseremail($userid);
    if($useremail!==$email) {
        $header = new AdminLoginHeader("Wrong username or e-mail!<br />Please fill out this form to receive a new password. The new password will be sent to you by e-mail. You have to use the e-mail address stated in your profile.");
        $form = new ForgotPasswordForm($username);
    }
    else
    {
        $header = new AdminLoginHeader("You have been sent an e-mail with the new password.");
        $newpassword=makepassword($userid);
        $message="Your new password is";
        $message.="\r\n\r\n".$newpassword;
        $message.="\r\n\r\nYou can logon at ".getprojectrootlinkpath().'admin/login.php';
        $message.="\r\n\r\nPlease go to your profile to change your password after logging in.";
        $subject="Your webpage editing account";
        sendplainemail($subject, $message, $useremail);
    }
}
elseif(isset($_POST['user']) && isset($_POST['pass'])) {
    $userid= getuserid($username);

    if(!$userid) {
        $header = new AdminLoginHeader($lang["login_error_username"]);
        $form = new AdminLoginForm($username);
    }
    elseif(isactive($userid)) {
        $login=login($username, trim($_POST['pass']));
        if(array_key_exists('sid', $login)) {
            if($_GET["referrer"]==="editimagelist" || $_GET["referrer"]==="profile" || $_GET["referrer"]==="editcategories") {
                $contenturl=$_GET["referrer"].'.php';
                unset($_GET["referrer"]);
                $_GET['sid']= $login['sid'];
                $contenturl=$contenturl.makelinkparameters($_GET);
            }
            else
            {
                unset($_GET["referrer"]);
                $_GET['sid']= $login['sid'];
                $contenturl='admin.php'.makelinkparameters($_GET);
            }
            $header = new AdminLoginHeader("Login successful", true, $contenturl, "Enter");
        }
        else
        {
            $header = new AdminLoginHeader($login['message']);
            $form = new AdminLoginForm($username);
        }
    }
    else
    {
        $header = new AdminLoginHeader("Your account has been deactivated");
        $form = new AdminLoginForm("");
    }
}
else
{
    $header = new AdminLoginHeader($message);
    $form = new AdminLoginForm("");
}

if(!isset($header)) {
    $header = new AdminLoginHeader("");
}

print($header->toHTML());

if(isset($form)) {
    print($form->toHTML());
}

$footer = new HTMLFooter();
print($footer->toHTML());
?>
