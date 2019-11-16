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
    'addthumb' => 1,
    'addunknownfile' => 1,
    'action' => 1,
    'ascdesc' => 1,
    'caption' => 1,
    'categoriesblank' => 1,
    'clear' => 1,
    'copyright' => 1,
    'copyrightblank' => 1,
    'createthumbnail' => 1,
    'delete' => 1,
    'deletefile' => 1,
    'deletefileconfirm' => 1,
    'deletethumb' => 1,
    'dontcreatethumbnail' => 1,
    'executethumbnaildelete' => 1,
    'filename' => 1,
    'filter' => 1,
    'image' => 1,
    'missing' => 1,
    'missingthumb' => 1,
    'newname' => 1,
    'nodelete' => 1,
    'noofimages' => 1,
    'nothumb' => 1,
    'number' => 1,
    'offset' => 1,
    'order' => 1,
    'page' => 1,
    'permission' => 1,
    'replaceimage' => 1,
    'replacethumb' => 1,
    'resizeimage' => 1,
    's_caption' => 1,
    's_categoriesblank' => 1,
    's_copyright' => 1,
    's_copyrightblank' => 1,
    's_filename' => 1,
    's_missing' => 1,
    's_missingthumb' => 1,
    's_nothumb' => 1,
    's_selectedcat' => 1,
    's_source' => 1,
    's_sourceblank' => 1,
    's_unknown' => 1,
    's_unused' => 1,
    's_uploader' => 1,
    'selectedcat' => 1,
    'sid' => 1,
    'source' => 1,
    'sourceblank' => 1,
    'sourcelink' => 1,
    'subpath' => 1,
    'unknown' => 1,
    'unused' => 1,
    'uploader' => 1,
    'doorder' => 1,
);

foreach ($_GET as $key => $value) {
    if (!array_key_exists($key, $LEGALVARS)) {
        header("HTTP/1.0 404 Not Found");
        print("HTTP 404: Sorry, but this page does not exist.");
        require_once $projectroot . "config.php";
        if (DEBUG) {
            print("<br />'".$key."' not registered with legalimagevars.");
        }
        exit;
    }
}

?>
