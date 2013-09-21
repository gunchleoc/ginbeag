<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"functions"));

include_once($projectroot."functions/db.php");

################################################################################
##                                                                            ##
##        Functions                                                           ##
##                                                                            ##
################################################################################


//
//
//
function isreferrerblocked($referrer)
{
	global $db;

  $query="select referrerurl from ".BLOCKEDREFERRERS_TABLE;
  $sql=$db->singlequery($query);
  $result=false;
  while(!$result && $row=mysql_fetch_row($sql))
  {
    $result=strpos($referrer,$row[0]);
  }
  return $result;
}
?>
