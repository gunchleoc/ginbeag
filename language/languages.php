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
$projectroot=substr($projectroot, 0, strrpos($projectroot, "language"));

require_once $projectroot."includes/functions.php";

if (isset($defaultlanguage)
    && file_exists($projectroot."language/".$defaultlanguage.".php")
) {
    include_once $projectroot."language/".$defaultlanguage.".php";
} else {
    include_once $projectroot."language/en.php";
}


/**
 * Get translated string for key string
 *
 * @param string $key Key for fetching the translated string
 *
 * @return the translation for the given key
 */
function getlang($key)
{
    global $lang;
    if (array_key_exists($key, $lang)) {
        return $lang[$key];
    } else {
        return "[".$key."]";
    }
}

/**
 * Get translated string from array of key strings, e.g. to get a specific month name
 *
 * @param string $key   Main key, e.g. "date_month_short"
 * @param int    $index Sub index, e.g. 1
 *
 * @return The translation for the given key at the given index
 */
function getlangarray($key, $index)
{
    global $lang;
    return $lang[$key][$index];
}

?>
