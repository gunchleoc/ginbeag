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
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."functions/db.php";
require_once $projectroot."admin/functions/sessions.php";
require_once $projectroot."admin/includes/objects/site/stats.php";
require_once $projectroot."admin/includes/objects/adminmain.php";

checksession();

if(isset($_GET['page'])) { $page=$_GET['page'];
} else { $page=0;
}

$count=20;
$year=date("Y", strtotime('now'));
$month=date("m", strtotime('now'));
$timespan = "month";

if(isset($_POST['selectmonth'])) {
    $count=$_POST['countmonth'];
    $year=$_POST['month_year'];
    $month=$_POST['month'];
    unset($_POST);
}
elseif(isset($_POST['selectyear'])) {
    $count=$_POST['countyear'];
    $year=$_POST['year_year'];
    $month=$_POST['month'];
    $timespan="year";
    unset($_POST);
}

$content = new AdminMain($page, "sitestats", new AdminMessage("", false), new SiteStatsTable($count, $year, $month, $timespan));
print($content->toHTML());
?>
