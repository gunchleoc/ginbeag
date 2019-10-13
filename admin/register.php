<?php
/*
 * An Gineadair Beag is a content management system to run websites with.
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
 */

$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

// check legal vars
require $projectroot."admin/includes/legaladminvars.php";

require_once $projectroot."includes/functions.php";
require_once $projectroot."admin/functions/usersmod.php";
require_once $projectroot."functions/email.php";
require_once $projectroot."admin/includes/objects/profile.php";

// all HTTP-vars used in this file
$user="";
if(isset($_POST['user'])) { $user=trim($_POST['user']);
}

$pass="";
if(isset($_POST['pass'])) { $pass=trim($_POST['pass']);
}

$passconf="";
if(isset($_POST['passconfirm'])) { $passconf=trim($_POST['passconfirm']);
}

$email="";
if(isset($_POST['email'])) { $email=trim($_POST['email']);
}

$message="";
$showform=true;

if($user && $pass===$passconf) {
    if(userexists($user)) {
        $message='Username already exists!';
    }
    elseif(!$pass) {
        $message='Please specify a password!';
    }
    elseif(emailexists($email)) {
        $message='E-mail <i>'.$email.'</i> already exists!';
        $email="";
    }
    elseif(!$email) {
        $message='Please specify an e-mail address!';
        $email="";
    }
    else
    {
        $register=register($user, $pass, $email);

        if($register) {
            $message='Registering successful.';
            $message='<br />You will be able to log in as soon as the admin activates your account.';
            sendactivationemail($user, $register);
            $showform=false;
        }
        else
        {
            $message='error';
        }
    }
}
elseif($user && $pass!=$passconf) {
    $message='Passwords did not match!';
}

$content = new RegisterPage($user, $email, $message, $showform);
print($content->toHTML());

//
//
//
function sendactivationemail($username,$activationkey)
{
    $recipient=getproperty("Admin Email Address");
    $message="A new user has registered: ".$username;
    $message.="\r\n\r\n".getprojectrootlinkpath().'admin/activate.php'.makelinkparameters(array("user" => urlencode($username), "key" => $activationkey));

    $subject="New web page user account";
    sendplainemail($subject, $message, $recipient);

}
?>
