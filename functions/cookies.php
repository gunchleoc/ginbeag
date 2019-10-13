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
$projectroot=substr($projectroot, 0, strrpos($projectroot, "functions"));
require_once $projectroot."functions/db.php";

//
//
//
function makecookiepath($isadmin)
{
    $localpath =getproperty("Local Path");
    if($isadmin) { $localpath=$localpath."/admin/";
    }
    if(!str_startswith($localpath, "/")) { $localpath="/".$localpath;
    }
    return $localpath;
}

//
//
//
function set_session_cookie($isadmin,$sid,$userid)
{
    $cookieprefix = getproperty("Cookie Prefix");
    $cookiedomain = getproperty("Domain Name");
    $localpath = makecookiepath($isadmin);
    $cookiesecure = getproperty("Server Protocol") == "https://";

    // **PREVENTING SESSION HIJACKING**
    // Prevents javascript XSS attacks aimed to steal the session ID
    ini_set('session.cookie_httponly', 1);

    // **PREVENTING SESSION FIXATION**
    // Session ID cannot be passed through URLs
    ini_set('session.use_only_cookies', 1);

    // More session security stuff
    ini_set('session.use_strict_mode', 1);
    ini_set('session.referer_check', $cookiedomain);

    if($cookiesecure) {
        // Uses a secure connection (HTTPS) if possible
        ini_set('session.cookie_secure', 1);
    }

    setcookie($cookieprefix."sid", $sid, 0, $localpath, $cookiedomain, $cookiesecure, 1);
    setcookie($cookieprefix."userid", $userid, 0, $localpath, $cookiedomain, $cookiesecure, 1);
}
?>
