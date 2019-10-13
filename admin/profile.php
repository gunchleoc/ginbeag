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

require_once $projectroot."admin/functions/usersmod.php";
require_once $projectroot."admin/functions/sessions.php";
require_once $projectroot."admin/includes/objects/profile.php";
require_once $projectroot."includes/functions.php";
require_once $projectroot."admin/includes/objects/adminmain.php";

checksession();

if(isset($_GET['page'])) { $page=$_GET['page'];
} else { $page=0;
}

// HTTP-vars
if(isset($_POST['oldpass'])) { $oldpass=trim($_POST['oldpass']);
} else { $oldpass="";
}

if(isset($_POST['pass'])) { $pass=trim($_POST['pass']);
} else { $pass="";
}

if(isset($_POST['passconfirm'])) { $passconf=trim($_POST['passconfirm']);
} else { $passconf="";
}

if(isset($_POST['email'])) { $email=trim($_POST['email']);
} else { $email="";
}


$userid=getsiduser();
$message = "";
$error = false;

if(isset($_POST['contact'])) {
    $message='Changed contact page options';

    if(isset($_POST['iscontact'])) {
        changeiscontact($userid, 1);
    }
    else
    {
        changeiscontact($userid, 0);
    }
    changecontactfunction($userid, fixquotes($_POST['contactfunction']));
}
else
{
    if($pass) {
        $passresult = changeuserpassword($userid, $oldpass, $pass, $passconf);
        if($passresult["error"]) {
            $error = true;
            $message = 'Failed to change password: '.$passresult["message"]." ";
        }
        else
        {
            $message = 'Changed password.';
        }
    }
    if($email) {
        if(emailexists($email, $userid)) {
            $message.=' E-mail <i>'.$email.'</i> already exists!';
            $error = true;
        }
        else
        {
            changeuseremail($userid, $email);
            $message.= 'Changed e-mail address.';
        }
    }
}

$content = new AdminMain($page, "profile", new AdminMessage($message, $error), new ProfilePage($userid));

print($content->toHTML());
?>
