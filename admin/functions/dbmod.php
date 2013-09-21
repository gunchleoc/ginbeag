<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."functions/db.php");
include_once($projectroot."includes/functions.php");

################################################################################
##                                                                            ##
##        Functions                                                           ##
##                                                                            ##
################################################################################


// *************************** modification ********************************* //


//
//
//
function deleteentry($table,$condition)
{
	global $db;
  $query="DELETE FROM ".$table;
  $query.=(" where ".$condition.";");
//  print($query.'<p>');
  $sql=$db->singlequery($query);
  return $sql;
}

//
//
//
function updatefield($table,$field,$value,$condition)
{
	global $db;
  	$query=("update ");
  	$query.=($table." set ");
  	$query.=$field."=";
  	$query.="'".$value."'";
  	$query.=(" where ".$condition.";");
//  print($query.'<p>');
  	$sql=$db->singlequery($query);
  return $sql;
}

//
// updates multiple values for the same entry
//
function updatefields($table,$fieldnames,$values,$primarykeyname,$primarykeyvalue)
{
	global $db;
  $queries=array();

  for($i=0;$i<count($fieldnames);$i++)
  {
    $query=("update ");
    $query.=($table." set ");
    $query.=$fieldnames[$i]."=";
    $query.="'".$values[$i]."'";
    $query.=(" where ".$primarykeyname." = '".$primarykeyvalue."';");
    array_push($queries,$query);
  }
//  print_r($queries);
  $sql=$db->multiquery($queries);
  return $sql;
}

//
// updates the same field for multiple entries
//
function updateentries($table,$values,$primarykeyname,$fieldname)
{
	global $db;
  $queries=array();
  $keys=array_keys($values);

  for($i=0;$i<count($keys);$i++)
  {
    $query=("update ");
    $query.=($table." set ");
    $query.=$fieldname."=";
    $query.="'".$values[$keys[$i]]."'";
    $query.=(" where ".$primarykeyname." = '".$keys[$i]."';");
    array_push($queries,$query);
  }
  $sql=$db->multiquery($queries);
  return $sql;
}


//
//  array values have to be in the right order for table
//
function insertentry($table,$values)
{
	global $db;
  $query="insert into ";
  $query.=$table." values(";
  for($i=0;$i<count($values)-1;$i++)
  {
    $query.="'".$values[$i]."', ";
  }
  $query.="'".$values[count($values)-1]."');";
//  print('<p>'.$query);

  $sql=$db->singlequery($query);
  return $sql;
}


//
//
//
function optimizetable($table)
{
	global $db;
  $query="OPTIMIZE TABLE ".$table.";";
//  print($query.'<p>');
  $sql=$db->singlequery($query);
  return $sql;
}
?>
