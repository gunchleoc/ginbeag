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


$LEGALVARS = array(
    'action' => 1,
    'all' => 1,
    'articlepage' => 1,
    'articlesection' => 1,
    'ascdesc' => 1,
    'caption' => 1,
    'categoriesblank' => 1,
    'clear' => 1,
    'contents' => 1,
    'copyright' => 1,
    'copyrightblank' => 1,
    'elementtype' => 1,
    'fbclid' => 1, // Appended by facebook to track you with
    'filename' => 1,
    'filter' => 1,
    'filterpage' => 1,
    'forgetful' => 1,
    'from' => 1,
    'fromday' => 1,
    'frommonth' => 1,
    'fromyear' => 1,
    'image' => 1,
    'imageid' => 1,
    'item' => 1,
    'jumppage' => 1,
    'jump' => 1,
    'key' => 1,
    'link' => 1,
    'logout' => 1,
    'm' => 1,
    'missing' => 1,
    'missingthumb' => 1,
    'mode' => 1,
    'newsitem' => 1,
    'newsitemsection' => 1,
    'noofimages' => 1,
    'nothumb' => 1,
    'number' => 1,
    'offset' => 1,
    'order' => 1,
    'override' => 1,
    'page' => 1,
    'pageposition' => 1,
    'params' => 1,
    'permission' => 1,
    'printview' => 1,
    'search' => 1,
    'selectedcat' => 1,
    'showall' => 1,
    'sid' => 1,
    'sitemap' => 1,
    'sitepolicy' => 1,
    'source' => 1,
    'sourceblank' => 1,
    'sourcelink' => 1,
    'subpages' => 1,
    'superforgetful' => 1,
    'text' => 1,
    'to' => 1,
    'today' => 1,
    'tomonth' => 1,
    'toyear' => 1,
    'unknown' => 1,
    'unlock' => 1,
    'unused' => 1,
    'uploader' => 1,
    'user' => 1,
);

foreach ($_GET as $key => $value) {
    if (!array_key_exists($key, $LEGALVARS)) {
        header("HTTP/1.0 404 Not Found");
        print("HTTP 404: Sorry, but this page does not exist.");
        require_once $projectroot . "config.php";
        if (DEBUG) {
            print("<br />'".$key."' not registered with legalvars.");
        }
        exit;
    }
}
?>
