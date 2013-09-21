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
// remove sid from links
// todo: Wo benutzt? Noch Notwendig?
//
function stripsid($text)
{
/*
  $patterns = array(
           "/http:(.*?)".getproperty("Domain Name")."(.*?)(sid=)(\w*?)(&)/",
           "/http:(.*?)".getproperty("Domain Name")."(.*?)(\?sid=|&sid=)(\w*?)(\W|\s|$)/",
           "/(".str_replace("/","\/",getprojectrootlinkpath()).")(index|.*admin.*|.*includes.*|.*functions.*)(.php)(.*)/"
       );
  $replacements = array(
           "http:\\2",
           "http:\\2\\5",
           "\\4"
       );*/
  return setstring($text);
}



/*function stripsid($text)
{

  $patterns = array(
           "/http://(.*?)".getproperty("Domain Name")."(.*?)(sid=)(\w*?)(&)/",
           "/http://(.*?)".getproperty("Domain Name")."(.*?)(\?sid=|&sid=)(\w*?)(\W|\s|$)/",
           "/(".str_replace("/","\/",getprojectrootlinkpath()).")(index|.*admin.*|.*includes.*|.*functions.*)(.php)(.*)/"
       );
  $replacements = array(
           "http:\\2",
           "http:\\2\\4",
           "\\4"
       );
  $text = preg_replace($patterns,$replacements, $text);
  return setstring($text);
}*/

//
//
//
function deleteentry($table,$condition)
{
  $query="DELETE FROM ".$table;
  $query.=(" where ".$condition.";");
//  print($query.'<p>');
  $sql=singlequery($query);
  return $sql;
}

//
//
//
function updatefield($table,$field,$value,$condition)
{
  $query=("update ");
  $query.=($table." set ");
  $query.=$field."=";
  $query.="'".$value."'";
  $query.=(" where ".$condition.";");
//  print($query.'<p>');
  $sql=singlequery($query);
  return $sql;
}

//
// updates multiple values for the same entry
//
function updatefields($table,$fieldnames,$values,$primarykeyname,$primarykeyvalue)
{
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
  $sql=query($queries);
  return $sql;
}

//
// updates the same field for multiple entries
//
function updateentries($table,$values,$primarykeyname,$fieldname)
{
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
  $sql=query($queries);
  return $sql;
}


//
//  array values have to be in the right order for table
//
function insertentry($table,$values)
{
  $query="insert into ";
  $query.=$table." values(";
  for($i=0;$i<count($values)-1;$i++)
  {
    $query.="'".$values[$i]."', ";
  }
  $query.="'".$values[count($values)-1]."');";
//  print('<p>'.$query);

  $sql=singlequery($query);
  return $sql;
}


//
//
//
function optimizetable($table)
{
  $query="OPTIMIZE TABLE ".$table.";";
//  print($query.'<p>');
  $sql=singlequery($query);
  return $sql;
}
?>
