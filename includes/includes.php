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
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));

require_once $projectroot."includes/functions.php";
require_once $projectroot."language/languages.php";

//
// get adjusted offset for page jumped to
//
function getoffsetforjumppage($noofitems,$itemsperpage,$offset)
{
    global $_GET;

    if(isset($_GET['jumppage']) && $_GET['jumppage']>0
        && $noofitems && $_GET['jumppage']<=ceil($noofitems/$itemsperpage)
    ) {
        $offset=($_GET['jumppage']-1)*$itemsperpage;
        unset($_GET['jumppage']);
    }
    return $offset;
}


//
// makes copyright information.
//
function makecopyright($permissions)
{
    $textcopyright="";
    $imagecopyright="";
    $bypermission="";
    if(strlen($permissions['copyright'])>0) {
        $textcopyright= sprintf(getlang("footer_textcopyright"), title2html($permissions['copyright']));
    }
    if(strlen($permissions['image_copyright'])>0) {
        $imagecopyright= sprintf(getlang("footer_imagecopyright"), title2html($permissions['image_copyright']));
    }
    if(($permissions['permission'])==PERMISSION_GRANTED) {
        $bypermission=getlang("footer_bypermission");
    }
    return sprintf(getlang("footer_copyright"), $textcopyright, $imagecopyright, $bypermission);
}



//
// formats date and time
//
function formatdatetime($date)
{
    $result = @date(getproperty("Date Time Format"), strtotime($date));
    $result = translateday($result, getproperty("Date Time Format"));
    $result = translatemonth($result, getproperty("Date Time Format"));
    return str_replace(" ", "&nbsp;", $result);;
}

//
// formats a date
//
function formatdate($date)
{
    $result = @date(getproperty("Date Format"), strtotime($date));
    $result = translateday($result, getproperty("Date Format"));
    $result = translatemonth($result, getproperty("Date Format"));
    return str_replace(" ", "&nbsp;", $result);
}

//
// helper function for formatdate and formatdatetime
// Only works if date starts with the day of the month
//
function translateday($date, $format)
{
    if(str_containsstr($format, "F")) {
        $date_time = explode(" ", $date);
        $date_time[0] = lang_date_day_format(intval($date_time[0]));
        return implode(" ", $date_time);
    }
    else { return $date;
    }
}

//
// helper function for formatdate and formatdatetime
//
function translatemonth($date, $format)
{
    if(str_containsstr($format, "F")) {
        $date = str_replace("January", getlangarray("date_month_format", 1), $date);
        $date = str_replace("February", getlangarray("date_month_format", 2), $date);
        $date = str_replace("March", getlangarray("date_month_format", 3), $date);
        $date = str_replace("April", getlangarray("date_month_format", 4), $date);
        $date = str_replace("May", getlangarray("date_month_format", 5), $date);
        $date = str_replace("June", getlangarray("date_month_format", 6), $date);
        $date = str_replace("July", getlangarray("date_month_format", 7), $date);
        $date = str_replace("August", getlangarray("date_month_format", 8), $date);
        $date = str_replace("September", getlangarray("date_month_format", 9), $date);
        $date = str_replace("October", getlangarray("date_month_format", 10), $date);
        $date = str_replace("November", getlangarray("date_month_format", 11), $date);
        $date = str_replace("December", getlangarray("date_month_format", 12), $date);
    }
    else
    {
        $date = str_replace("Jan", getlangarray("date_month_short", 1), $date);
        $date = str_replace("Feb", getlangarray("date_month_short", 2), $date);
        $date = str_replace("Mar", getlangarray("date_month_short", 3), $date);
        $date = str_replace("Apr", getlangarray("date_month_short", 4), $date);
        $date = str_replace("May", getlangarray("date_month_short", 5), $date);
        $date = str_replace("Jun", getlangarray("date_month_short", 6), $date);
        $date = str_replace("Jul", getlangarray("date_month_short", 7), $date);
        $date = str_replace("Aug", getlangarray("date_month_short", 8), $date);
        $date = str_replace("Sep", getlangarray("date_month_short", 9), $date);
        $date = str_replace("Oct", getlangarray("date_month_short", 10), $date);
        $date = str_replace("Nov", getlangarray("date_month_short", 11), $date);
        $date = str_replace("Dec", getlangarray("date_month_short", 12), $date);
    }
    return $date;
}


?>
