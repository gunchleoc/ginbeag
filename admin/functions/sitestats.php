<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."functions/db.php");

//
//
//
function getmonthlypagestats($count=20,$year=0,$month=0)
{
	global $db;
	if(!$month || !$year)
	{
		$year=date("Y",strtotime('now'));
		$month=date("m",strtotime('now'));
		//    echo "year: $year month: $month";
	}
	$query="SELECT page_id, viewcount FROM ".MONTHLYPAGESTATS_TABLE." WHERE ";
	$query.="year='".$db->setinteger($year)."' AND month='".$db->setinteger($month)."'";
	$query.=" ORDER BY viewcount DESC LIMIT 0,".$count;
	//  print($query);
	$sql=$db->singlequery($query);
	$result=array();
	if($sql)
	{
		// get column
		while($row = $sql->fetch_row()) {
			array_push($result,array($row[0],$row[1]));
		}
	}
	return $result;
}


//
//
//
function getyearlypagestats($count=20,$year=0)
{
	global $db;
	if(!$year)
	{
		$year=date("Y",strtotime('now'));
		//    echo "year: $year";
	}
	$query="SELECT page_id, sum(viewcount) FROM ".MONTHLYPAGESTATS_TABLE." WHERE ";
	$query.="year='".$db->setinteger($year)."'";
	$query.=" GROUP BY page_id ORDER BY sum(viewcount) DESC LIMIT 0,".$count;
	//  print($query);
	$sql=$db->singlequery($query);
	$result=array();
	if($sql)
	{
		// get column
		while($row = $sql->fetch_row()) {
			array_push($result,array($row[0],$row[1]));
		}
	}
	return $result;
}


//
//
//
function getstatsfirstyear()
{
	return getmin("year", MONTHLYPAGESTATS_TABLE, "1");
}


?>
