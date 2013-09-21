<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"functions"));
include_once($projectroot ."config.php");
include_once($projectroot ."includes/constants.php");

################################################################################
##                                                                            ##
##        Functions                                                           ##
##                                                                            ##
################################################################################

// *************************** basic db functions *************************** //


$db = new Database();


$properties = getproperties();


/*
 * Use Database object to limit number of connections
 */
class Database {
	var $db;
	/*
	 * open DB at beginning of script
	 */
	function Database()
  	{
    	global $dbname,$dbhost,$dbuser,$dbpasswd;
    	
		if(DEBUG)
  		{
			$this->db=@mysql_connect($dbhost,$dbuser,$dbpasswd)
				or die(mysql_errno().": ".mysql_error());

			@mysql_select_db($dbname)
				or die("Can't find database. Please try again later.");
		}
		else
		{
			$this->db=@mysql_connect($dbhost,$dbuser,$dbpasswd)
				or die("Can't connect to database. Please try again later.");

			@mysql_select_db($dbname)
				or die("Can't find database. Please try again later.");
		}
	}
	

	// executes a single query
	// $query a string with a mysql command
	// returns the query result
	// 1 = success, "" = failure
	//
	function singlequery($query) {

		if(DEBUG)
  		{
	    	$result=@mysql_query($query)
				or die(mysql_errno().": ".mysql_error().' <i>in query:</i> '.$query);
		}
		else
		{
			$result=@mysql_query($query)
				or die("Can't get data from database. Please notify the admin.");
		}

		if(preg_match ("/insert/i",$query))
		{
			$result= mysql_insert_id($this->db);
		}
		
		return $result;
	}

	// executes a list of queries
	// $queries an array of strings with a mysql commands
	// returns an array of query results
	// 1 = success, "" = failure
	function multiquery($queries) {
	
	
		$result[0]="";
  
		if(DEBUG)
		{

			for($i=0;$i<count($queries);$i++)
			{
				$result[$i]=@mysql_query($queries[$i])
					or die(mysql_errno().": ".mysql_error().' <i>in query:</i> '.$queries[$i]);
			}
		}
		else
		{

			for($i=0;$i<count($queries);$i++)
			{
				$result[$i]=@mysql_query($queries[$i])
					or die("Can't get data from database. Please notify the admin.");
			}
		}
  
		return $result;	
	}
	
	
	//
	// security, use with all user input
	//
	function setinteger($var)
	{
  		if(!(@is_numeric($var) || @ctype_digit($var))) return @settype($var,"int");
  		else return $var;
	}

	//
	// security, use with all user input
	// also handles UTF-8 encoding!
	//
	function setstring($var)
	{
  		$result= @mysql_real_escape_string($var);
		return utf8_decode($result);
	}
	
	
	/*
	 * close DB at end of script
	 */
	function closedb() {
		@mysql_close($db);
	}
}


// *************************** db convenience functions ********************* //

//
//
//
function getcolumn($fieldname, $table, $condition)
{
	global $db;

//  print('cond: '.$condition.'<p>');

  $result=array();
  
  $query="select ".$fieldname." from ".$table." where ".$condition;
//  print($query);
  $sql=$db->singlequery($query);
  if($sql)
  {
    // get column
    while($row=mysql_fetch_row($sql))
    {
      array_push($result,$row[0]);

    }
  }
  return $result;
}

//
//
//
function getrowbykey($table, $keyname, $value, $fieldnames = array(0 => '*'))
{
	global $db;
  $result=array();

  $query="select ";
  $nooffields=count($fieldnames);
  for($i=0; $i<$nooffields-1;$i++)
  {
    $query.=$fieldnames[$i].", ";
  }
  $query.=$fieldnames[$nooffields-1];
  $query.=" from ".$table." where ".$keyname." = '".$value."'";

//  print($query);
  $sql=$db->singlequery($query);
  if($sql)
  {
    $fields=mysql_num_fields($sql);

    // get row
    if($row=mysql_fetch_row($sql))
    {
      // make associative array
      for($field=0;$field<$fields;$field++)
      {
        $result[mysql_field_name($sql,$field)]=$row[$field];
      }
    }
  }
//  print_r($result);
  return $result;
}



//
// $keyname: for result array
//
function getmultiplefields($table, $keyname, $condition, $fieldnames = array(0 => '*'), $orderby="", $ascdesc="ASC")
{
	global $db;
  $result=array();

  $query="select ";
  $nooffields=count($fieldnames);
  for($i=0; $i<$nooffields-1;$i++)
  {
    $query.=$fieldnames[$i].", ";
  }
  $query.=$fieldnames[$nooffields-1];
  $query.=" from ".$table." where ".$condition;
  if(strlen($orderby)>0)
  {
    $query.=" order by ".$orderby." ".$ascdesc;
  }
//  print($query);
  $sql=$db->singlequery($query);
  if($sql)
  {
    $fields=mysql_num_fields($sql);
    
    // get index for field name
    $found=false;
    for($field=0;!$found && $field<$fields;$field++)
    {
      if(mysql_field_name($sql,$field)==$keyname)
      {
        $fieldindex=$field;
        $found=true;
      }
    }

    // get column
    for($i=0;$row=mysql_fetch_row($sql);$i++)
    {
      // make associative array
      for($field=0;$field<$fields;$field++)
      {
        $result[$row[$fieldindex]][mysql_field_name($sql,$field)]=$row[$field];
      }
    }
  }
//  print_r($result);
  return $result;
}



//
//
//
function getorderedcolumn($fieldname, $table, $condition, $orderby, $ascdesc="DESC")
{
	global $db;

//  print('cond: '.$condition.'<p>');

  $result=array();

  $query="select ".$fieldname." from ".$table." where ".$condition." order by ".$orderby." ".$ascdesc;
//  print($query.'<br>');
  $sql=$db->singlequery($query);
  if($sql)
  {
    // get column
    while($row=mysql_fetch_row($sql))
    {
      array_push($result,$row[0]);

    }
  }
  return $result;
}

//
//
//
function getorderedcolumnlimit($fieldname, $table, $condition, $orderby, $offset, $number, $ascdesc="DESC")
{
	global $db;

//  print('cond: '.$condition.'<p>');

  $result=array();

  $query="select ".$fieldname." from ".$table." where ".$condition." order by ".$orderby." ".$ascdesc;
  $query.=" limit ".$offset.", ".$number;
//  print($query.'<br>');
  $sql=$db->singlequery($query);
  if($sql)
  {
    // get column
    while($row=mysql_fetch_row($sql))
    {
      array_push($result,$row[0]);
    }
  }
  return $result;
}



//
//
//
function getdistinctorderedcolumn($fieldname, $table, $condition, $orderby, $ascdesc="DESC")
{
	global $db;

//  print('cond: '.$condition.'<p>');

  $result=array();

  $query="select distinct ".$fieldname." from ".$table." where ".$condition." order by ".$orderby." ".$ascdesc;
//  print($query.'<br>');
  $sql=$db->singlequery($query);
  if($sql)
  {
    // get column
    while($row=mysql_fetch_row($sql))
    {
      array_push($result,$row[0]);
    }
  }
  return $result;
}

//
//
//
function getdbelement($fieldname, $table, $conditionkey, $conditionvalue)
{
	global $db;
  $result="";

  $query="select ".$fieldname." from ".$table." where ".$conditionkey." = '".$conditionvalue."';";
//  print($query.'<br>');
  $sql=$db->singlequery($query);

  if($sql)
  {
    $row=mysql_fetch_row($sql);
    $result=$row[0];
  }


  return $result;
}

//
//
//
function getmax($fieldname, $table, $condition)
{
	global $db;

  $result="";

  $query="select max(".$fieldname.") from ".$table;
  $query.=" where ".$condition.";";
  
//  print($query.'<br>');
  $sql=$db->singlequery($query);

  if($sql)
  {
    $row=mysql_fetch_row($sql);
    $result=$row[0];
  }
  return $result;
}

//
//
//
function getmin($fieldname, $table, $condition)
{
	global $db;
  $result="";

  $query="select min(".$fieldname.") from ".$table;
  $query.=" where ".$condition.";";

//  print($query.'<br>');
  $sql=$db->singlequery($query);

  if($sql)
  {
    $row=mysql_fetch_row($sql);
    $result=$row[0];
  }
  return $result;
}

//
//
//
function countelements($keyname, $table)
{
	global $db;
  $result="";

  $query="select count(".$keyname.") from ".$table.";";
//  print($query.'<br>');
  $sql=$db->singlequery($query);

  if($sql)
  {
    $row=mysql_fetch_row($sql);
    $result=$row[0];
  }


  return $result;
}

//
//
//
function countelementscondition($keyname, $table,$condition)
{
	global $db;
  $result="";

  $query="select count(".$keyname.") from ".$table." WHERE ".$condition.";";
//  print($query.'<br>');
  $sql=$db->singlequery($query);

  if($sql)
  {
    $row=mysql_fetch_row($sql);
    $result=$row[0];
  }
  return $result;
}


// *************************** properties *********************************** //

//
// returns an associative array of properties
//
function getproperties()
{
  $result=array();
  $names=getorderedcolumn("property_name",SITEPROPERTIES_TABLE, "1", "property_name", "ASC");
  $values=getorderedcolumn("property_value",SITEPROPERTIES_TABLE, "1", "property_name", "ASC");
  for($i=0;$i<count($names);$i++)
  {
    $result[$names[$i]]=$values[$i];
  }
  return $result;
}

//
//
//
function getproperty($propertyname)
{
  global $properties;
  //debug_print_backtrace();
  return $properties[$propertyname];
  
}

?>
