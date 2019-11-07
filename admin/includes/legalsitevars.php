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
    'ascdesc' => 1,
    'backup' => 1,
    'bannerid' => 1,
    'changeaccess' => 1,
    'changelevel' => 1,
    'clearpagecache' => 1,
    'contact' => 1,
    'display' => 1,
    'filterpermission' => 1,
    'generate' => 1,
    'holder' => 1,
    'offset' => 1,
    'order' => 1,
    'page' => 1,
    'postaction' => 1,
    'profile' => 1,
    'ref' => 1,
    'search' => 1,
    'sid' => 1,
    'structure' => 1,
    'type' => 1,
    'userid' => 1,
    'username' => 1,
    'sitestats' => 1,
    'serverprotocol' => 1,
);

foreach ($_GET as $key => $value) {
    if (!array_key_exists($key, $LEGALVARS)) {
        header("HTTP/1.0 404 Not Found");
        print("HTTP 404: Sorry, but this page does not exist.");
        if (DEBUG) {
            print("<br />'".$key."' not registered with legalsitevars.");
        }
        exit;
    }
}

?>
