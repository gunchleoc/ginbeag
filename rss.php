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
if(getproperty("Local Path") == "") { $testpath = "";
}

if(!((isset($_SERVER["ORIG_PATH_TRANSLATED"]) && $_SERVER["ORIG_PATH_TRANSLATED"] == $projectroot."rss.php")
    || $_SERVER["PHP_SELF"] == $testpath."/rss.php")
) {
    header("HTTP/1.0 404 Not Found");
    print("HTTP 404: Sorry, but this page does not exist.");
    exit;
}

// check legal vars
require_once $projectroot."includes/legalvars.php";

require_once $projectroot."functions/pages.php";
require_once $projectroot."includes/objects/rss.php";

$page=$_GET['page'];

if(hasrssfeed($page)) {
    header("Content-type: text/xml;	charset=utf-8");
    $printme = new RSSPage($page);
    print($printme->toHTML());
}
else
{
    header("HTTP/1.0 404 Not Found");
    $sitename=getproperty("Site Name");
    $title=title2html($sitename.' - '.getnavtitle($page));
    $rootlink=getprojectrootlinkpath();
    $link=$rootlink.'index.php'.makelinkparameters($_GET);
    print('HTTP 404: Sorry, but there is no RSS-Feed available for this page.<p class="highlight"><a href="'.$link.'">Return to '.$title.'</a></p>');
}
?>
