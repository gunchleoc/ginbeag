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

$projectroot=dirname(__FILE__)."/";

require_once $projectroot."functions/db.php";

// anti bot nonsense links
// ********************************* achtung - bot secure ist server-spezifisch!
$testpath = "/".getproperty("Local Path");
if(getproperty("Local Path") == "") { $testpath = "";
}

if(!DEBUG && !((isset($_SERVER["ORIG_PATH_TRANSLATED"]) && $_SERVER["ORIG_PATH_TRANSLATED"] == $projectroot."index.php")
    || $_SERVER["PHP_SELF"] == $testpath."/index.php")
) {
    header("HTTP/1.0 404 Not Found");
    print("HTTP 404: Sorry, but this page does not exist.");
    exit;
}

// check legal vars
require_once $projectroot."includes/legalvars.php";

// get includes
require_once $projectroot."functions/publicsessions.php";
require_once $projectroot."includes/objects/page.php";
require_once $projectroot."language/languages.php";

if(isset($_GET['sid'])) { $sid = $_GET['sid'];
} elseif(isset($_POST['sid'])) { $sid = $_POST['sid'];
} else { $sid="";
}

if(strlen($sid) > 0 && ! ispublicloggedin()) {
    $sid="";
    unset($_GET['sid']);
    unset($_POST['sid']);
}

if(isset($_GET['page'])) { $page = $_GET['page'];
} elseif(isset($_POST['page'])) { $page = $_POST['page'];
} elseif(isset($_GET['newsitem'])) {
    include_once $projectroot."functions/pagecontent/newspages.php";
    $page = getnewsitempage($_GET['newsitem']);
    $_GET['page']=$page;
}
else { $page=0;
}

$wasloggedout = false;

// logout public user
if(isset($_GET['logout'])) {
    publiclogout($_GET['sid']);
    unset($_GET['sid']);
    $sid="";
    unset($_GET['logout']);
    $wasloggedout = true;
}

// show splash page
if(!isset($_GET['page']) || ($page == 0 && $wasloggedout)) {
    $page = new Page("splashpage");
}
// show normal page
else
{
    if(isset($_GET['printview'])) {
        $page = new Printview();
    }
    else
    {
        $page = new Page("page");
    }
}

print($page->toHTML());
?>
