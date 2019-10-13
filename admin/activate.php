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
require_once $projectroot."admin/includes/legaladminvars.php";

require_once $projectroot."admin/functions/usersmod.php";
require_once $projectroot."admin/functions/sessions.php";
require_once $projectroot."functions/email.php";


if(isset($_GET['user'])&&isset($_GET['key'])) {
    if(hasactivationkey($_GET['user'], $_GET['key'])) {
        $message='Activated user account for '.title2html($_GET["user"]);
        activateuser($_GET['user'], $_GET['key']);
        sendactivationnotification($_GET['user'], getuseremail(getuserid($_GET['user'])));
    }
    else
    {
        $message='The user account for '.title2html($_GET["user"]).' is already activated';
    }
}
else
{
    $message='No user to activate';
}

$header = new HTMLHeader("Activated user", "Webpage Building", $message);
print($header->toHTML());
$footer = new HTMLFooter();
print($footer->toHTML());

//
//
//
function sendactivationnotification($username,$recipient)
{
    $message="Welcome ".$username."!";
    $message.="\r\n\r\nYour webpage editing account has been activated";
    $message.="\r\n\r\nYou can logon at ".getprojectrootlinkpath().'admin/login.php';
    $subject="Your account has been activated";
    sendplainemail($subject, $message, $recipient);
}
?>
