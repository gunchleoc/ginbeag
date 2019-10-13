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
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."functions/db.php";

//
//
//
function getmonthlypagestats($count=20,$year=0,$month=0)
{
    if (!$month || !$year) {
        $year = date("Y", strtotime('now'));
        $month = date("m", strtotime('now'));
    }

    $sql = new SQLSelectStatement(
        MONTHLYPAGESTATS_TABLE, array('page_id', 'viewcount'),
        array('year', 'month'), array($year, $month), 'ii'
    );
    $sql->set_order(array('viewcount' => 'DESC'));
    $sql->set_limit($count, 0);
    return $sql->fetch_two_columns();
}


//
//
//
function getyearlypagestats($count = 20, $year = 0)
{
    if (!$year) {
        $year=date("Y", strtotime('now'));
    }

    $query = "SELECT page_id, sum(viewcount) FROM ".MONTHLYPAGESTATS_TABLE
    . " WHERE year = ? GROUP BY page_id ORDER BY sum(viewcount) DESC LIMIT 0, ?";

    $sql = new RawSQLStatement($query, array($year, $count), 'ii');
    return $sql->fetch_two_columns();
}


//
//
//
function getstatsfirstyear()
{
    $sql = new SQLSelectStatement(MONTHLYPAGESTATS_TABLE, 'year');
    $sql->set_operator('min');
    return $sql->fetch_value();
}


?>
