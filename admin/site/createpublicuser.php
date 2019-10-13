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

require_once $projectroot."admin/functions/sessions.php";
require_once $projectroot."admin/functions/publicusersmod.php";
require_once $projectroot."admin/includes/objects/site/users.php";
require_once $projectroot."includes/functions.php";
require_once $projectroot."admin/includes/objects/adminmain.php";

checksession();
checkadmin();

if(isset($_GET['page'])) { $page=$_GET['page'];
} else { $page=0;
}

// print_r($_POST);
// print_r($_GET);

$message = "";
$error = false;
$register=-1;

if(isset($_POST['username'])) { $username=fixquotes(trim($_POST['username']));
} else { $username="";
}

if(isset($_POST['pass'])) { $pass=$_POST['pass'];
} else { $pass="";
}

if(isset($_POST['passconfirm'])) { $passconf=$_POST['passconfirm'];
} else { $passconf="";
}

if(isset($_POST['createuser']) && $_POST['createuser'] === "Create User") {
    if($username && $pass===$passconf) {
        if(publicuserexists($username)) {
            $message = 'Username already exists!';
            $error = true;
        }
        elseif(!$pass) {
            $message = 'Please specify a password!';
            $error = true;
        }
        else
        {
            $register=addpublicuser($username, $pass);

            if($register) {
                $message='Created user <em>'.$username.'</em> successfully';
                $username="";
            }
            else
            {
                $message = 'Error creating user: ' . $register;
                $error = true;
            }
        }
    }
    elseif($username && $pass!=$passconf) {
        $message = 'Passwords did not match!';
        $error = true;
    }
    else
    {
        $message = 'Please specify a username';
        $error = true;
    }
}

$content = new AdminMain($page, "siteusercreate", new AdminMessage($message, $error), new SiteCreatePublicUser($username, $message, $register));
print($content->toHTML());
?>
