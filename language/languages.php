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
$projectroot=substr($projectroot, 0, strrpos($projectroot, "language"));

require_once $projectroot."includes/functions.php";

if(isset($defaultlanguage) && file_exists($projectroot."language/".$defaultlanguage.".php")) { include_once $projectroot."language/".$defaultlanguage.".php";
} else { include_once $projectroot."language/en.php";
}


// get lang for key string
function getlang($element)
{
    global $lang;
    if(array_key_exists($element, $lang)) { return $lang[$element];
    } else { return "[".$element."]";
    }
}

// get lang from array of key strings
function getlangarray($element,$index)
{
    global $lang;
    return $lang[$element][$index];
}

?>
