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

require_once $projectroot."functions/db.php";
require_once $projectroot."admin/functions/sessions.php";
require_once $projectroot."admin/functions/referrersmod.php";
require_once $projectroot."admin/includes/objects/site/referrers.php";
require_once $projectroot."admin/includes/objects/adminmain.php";

checksession();
checkadmin();

if(isset($_GET['page'])) { $page=$_GET['page'];
} else { $page=0;
}

$message = "";
$error = false;

// print_r($_POST);
// print_r($_GET);

if(isset($_POST['unblock'])) {
    $referrers= new SiteReferrerUnblockForm($_POST['referrer']);
}
else
{
    if(isset($_POST['confirmunblock'])) {
        $message='Unblocked Referrer <i>'.$_POST['referrer'].'</i>';
        deleteblockedreferrer($_POST['referrer']);
    }
    elseif(isset($_POST['block'])) {
        $message='Blocked Referrer <i>'.$_POST['referrer'].'</i>';
        addblockedreferrer(trim($_POST['referrer']));
    }
    $referrers= new SiteReferrers();
}

$content = new AdminMain($page, "sitereferrers", new AdminMessage($message, $error), $referrers);
print($content->toHTML());
?>
