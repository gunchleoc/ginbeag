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
require_once $projectroot."admin/functions/usersmod.php";
require_once $projectroot."admin/functions/publicusersmod.php";
require_once $projectroot."admin/includes/objects/site/userpermissions.php";
require_once $projectroot."admin/includes/objects/adminmain.php";

checksession();
checkadmin();

if(isset($_GET['page'])) { $page=$_GET['page'];
} else { $page=0;
}

$message = "";
$error = false;

if(isset($_GET['userid'])) { $userid=$_GET['userid'];
} elseif(isset($_POST['userid'])) { $userid=$_POST['userid'];
} else { $userid=-1;
}

if(isset($_GET['username'])) { $username=$_GET['username'];
} else { $username="";
}

// print_r($_POST);
// print_r($_GET);

if(isset($_POST['searchuser'])) {
    $userid=getuserid($_POST['username']);
}
elseif(isset($_POST['searchpublicuser'])) {
    $userid=getpublicuserid($_POST['username']);
}
if((isset($_POST['searchuser']) || isset($_POST['searchpublicuser'])) && !$userid) {
    $message = 'User <i>'.$_POST['username'].'</i> not found.';
    $error = true;
}
// public users for restricted areas
elseif(isset($_GET['changeaccess']) && $_GET['changeaccess']==="removepage") {
    removepageaccess(array(0 => $userid), $_POST["pageid"]);
    $message='Removed Page';
}
elseif(isset($_GET['changeaccess']) && $_GET['changeaccess']==="addpage") {
    addpageaccess(array(0 => $userid), $_POST["pageid"]);
    $message='Added Page';
}
// webpage editors
elseif(isset($_POST['changelevel']) || isset($_GET['changelevel'])) {
    setuserlevel($userid, $_POST['userlevel']);
    if($_POST['userlevel']==USERLEVEL_USER) {
        $message='Userlevel for <i>'.getusername($userid).'</i> set to <i>User</i>';
    }
    elseif($_POST['userlevel']==USERLEVEL_ADMIN) {
        $message='Userlevel for <i>'.getusername($userid).'</i> set to <i>Administrator</i>';
    }
}
if($userid>0) {
    if(isset($_GET['type']) && $_GET['type']==="public" || isset($_POST['searchpublicuser'])) {
        $contents= new SitePublicUserAccessForm($userid);
    }
    else
    {
        $contents= new SiteUserLevelForm($userid);
    }
}
else
{
    $contents= new SiteSelectUserPermissionsForm($username);
}

$content = new AdminMain($page, "siteuserperm", new AdminMessage($message, $error), $contents);
print($content->toHTML());
?>
