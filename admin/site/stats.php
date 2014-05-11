<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/dbmod.php");
include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/includes/objects/site/stats.php");
include_once($projectroot."admin/includes/objects/adminmain.php");

checksession();

if(isset($_GET['page'])) $page=$_GET['page'];
else $page=0;

$count=20;
$year=date("Y",strtotime('now'));
$month=date("m",strtotime('now'));
$timespan = "month";

if(isset($_POST['selectmonth']))
{
	$count=$_POST['countmonth'];
	$year=$_POST['month_year'];
	$month=$_POST['month'];
	unset($_POST);
}
elseif(isset($_POST['selectyear']))
{
	$count=$_POST['countyear'];
	$year=$_POST['year_year'];
	$month=$_POST['month'];
	$timespan="year";
	unset($_POST);
}

$content = new AdminMain($page, "sitestats", new AdminMessage("", false), new SiteStatsTable($count, $year, $month, $timespan));
print($content->toHTML());
$db->closedb();
?>
