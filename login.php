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

// anti bot nonsense links
// ********************************* achtung - bot secure ist server-spezifisch!
$testpath = "/".getproperty("Local Path");
if (getproperty("Local Path") == "") {
    $testpath = "";
}

if (!((isset($_SERVER["ORIG_PATH_TRANSLATED"])
    && $_SERVER["ORIG_PATH_TRANSLATED"] == $projectroot."login.php")
    || $_SERVER["PHP_SELF"] == $testpath."/login.php")
) {
    //    print("test: ".$_SERVER["PHP_SELF"]);
    header("HTTP/1.0 404 Not Found");
    print("HTTP 404: Sorry, but this page does not exist.");
    exit;
}

// check legal vars
require_once $projectroot."includes/legalvars.php";

require_once $projectroot."includes/includes.php";
require_once $projectroot."functions/publicsessions.php";
require_once $projectroot."functions/publicusers.php";
require_once $projectroot."includes/objects/elements.php";
require_once $projectroot."includes/objects/login.php";
require_once $projectroot."includes/objects/page.php";



//print_r($_GET);
//print_r($_POST);

if (isset($_POST['user'])) {
    $user=trim($_POST['user']);
    $userid=getpublicuserid($user);

    if (!$userid) {
        $header = new PageHeader(0, utf8_decode(getlang("login_pagetitle")), "");
        $loginform = new LoginForm($user, getlang("login_error_username"));
        $footer = new PageFooter();
    } elseif (ispublicuseractive($userid)) {
        $login=publiclogin($user, trim($_POST['pass']));
        if (array_key_exists('sid', $login)) {
            $_GET['sid']= $login['sid'];
            $contenturl='index.php'.makelinkparameters($_GET);

            $header = new HTMLHeader(
                getlang("login_pagetitle"),
                getlang("login_pagetitle"),
                $login['message'],
                $contenturl,
                getlang("login_enter"), true
            );

            $footer = new HTMLFooter();
        } else {
            $header = new PageHeader(0, utf8_decode(getlang("login_pagetitle")), "");
            $loginform = new LoginForm($user, $login['message']);
            $footer = new PageFooter();
        }
    } else {
        $header = new PageHeader(0, utf8_decode(getlang("login_pagetitle")), "");
        $loginform = new LoginForm("", getlang("login_error_inactive"));
        $footer = new PageFooter();
    }
} else {
    $header = new PageHeader(0, utf8_decode(getlang("login_pagetitle")), "");
    $loginform = new LoginForm("");
    $footer = new PageFooter();
}

print($header->toHTML());
if (isset($loginform)) {
    print($loginform->toHTML());
}
print($footer->toHTML());
?>
