<?php
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
