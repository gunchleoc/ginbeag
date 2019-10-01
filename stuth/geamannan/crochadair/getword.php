<?php

include_once("config.php");

if(isset($_GET['mode']))
{
	if($_GET['mode']=="placenames")
	{
		makeXML("aiteachan", PLACENAMES_TABLE, 'id');

	}
	elseif($_GET['mode']=="words")
	{
		makeXML("faclan", WORDS_TABLE, 'id');
	}

	elseif($_GET['mode']=="wordssmall")
	{
		makeXML("faclanbeag", WORDS_SMALL_TABLE, 'id');
	}
}


//
// get random entry form database and print as XML for AJAX
//
function makeXML($wrapper,$dbtable,$key)
{
	$dbrow="";
	$row=array();

	$query = "SELECT * FROM ".$dbtable.", (SELECT FLOOR(MAX(".$dbtable.".".$key.") * RAND()) AS randId FROM ".$dbtable.") AS someRandId WHERE ".$dbtable.".".$key." = someRandId.randId;";

	while($dbrow=="")
	{
		$sql=singlequery($query);
		//print_r($query);
		if($sql)
  		{
    		$fields = $sql->field_count;

    		// get row
    		if ($dbrow = $sql->fetch_row()) {
      			// make associative array
      			for($field=0;$field<$fields;$field++)
      			{
        			$row[$sql->fetch_field_direct($field)->name] = $dbrow[$field];
      			}
    		}
  		}
  	}

	$xml = "<".$wrapper.">";
	$xml .= "<entry>";

	if($wrapper =="aiteachan")
	{
		$keys=array_keys($row);
		$noofkeys = count($keys);

		for ($j=0; $j<$noofkeys; $j++)
		{
			$key=strtolower($keys[$j]);

			$element=$row[$keys[$j]];
			$xml .= "<$key>".utf8_encode($element)."</$key>";
		}
	}
	else
	{
		$key="facal";
		$element=$row[$key];
		$xml .= "<$key>".utf8_encode($element)."</$key>";
	}

	$xml .= "</entry>";

	header('Content-type: text/xml;	charset=utf-8');
 	echo '<?xml version="1.0" encoding="UTF-8"?>';

	$xml .= "</".$wrapper.">";
	echo $xml;
}

//
// get random entry form database and print as XML for AJAX
// slower than other the one even for small database
//
function makeXMLSmallDB($wrapper,$dbtable,$key)
{
	$columnkeys = getcolumn($key, $dbtable, '1');
	$noofelements = count($columnkeys);

	list($usec, $sec) = explode(' ', microtime());
    $random= ((float) $sec + ((float) $usec * 100000)) % $noofelements;

	$row= getrowbykey($dbtable, $key, $columnkeys[$random]);

	$keys=array_keys($row);

	$xml = "<".$wrapper.">";

	$noofkeys = count($keys);

	$xml .= "<noofentries>1</noofentries>";
	$xml .= "<entry>";

	for ($j=0; $j<$noofkeys; $j++)
	{
		$key=strtolower($keys[$j]);

		$element=$row[$key];
	//	$xml .= "<$key>".$element."</$key>";
		$xml .= "<$key>".utf8_encode($element)."</$key>";
	}
	$xml .= "</entry>";

	header('Content-type: text/xml;	charset=utf-8');
 	echo '<?xml version="1.0" encoding="UTF-8"?>';

	$xml .= "</".$wrapper.">";
	echo $xml;
}



// *************************** db convenience functions ********************* //


//
//
//
function getrowbykey($table, $keyname, $value, $fieldnames = array(0 => '*'))
{
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
  $sql=singlequery($query);
  if($sql)
  {
    $fields = $sql->field_count;

    // get row
    if($row = $sql->fetch_row()) {
      // make associative array
      for($field=0;$field<$fields;$field++)
      {
        $result[$sql->fetch_field_direct($field)->name] = $row[$field];
      }
    }
  }
//  print_r($result);
  return $result;
}


//
//
//
function getcolumn($fieldname, $table, $condition)
{

//  print('cond: '.$condition.'<p>');

  $result=array();

  $query="select ".$fieldname." from ".$table." where ".$condition;
//  print($query);
  $sql=singlequery($query);
  if($sql)
  {
    // get column
    while ($row = $sql->fetch_row()) {
      array_push($result,$row[0]);

    }
  }
  return $result;
}


//
//
//
function singlequery($query)
{
  global $dbname,$dbhost,$dbuser,$dbpasswd;

  $result=$query;

  $db=@new mysqli($dbhost, $dbuser, $dbpasswd, $dbname);
  if (!$db) {
    echo "Can't connect to database. Please try again later." . PHP_E
    exit();
  }

  $result = @$db->query($query);
  if (!$result) {
    print("Can't get data from database. Please notify the admin." . PHP_EOL);
    exit();
  }

  if (preg_match ("/insert/i",$query))
  {
    $result= $db->insert_id;
  }

  @$db->close();
  return $result;
}
?>
