<?php
//todo: store games in database
// words array, dimension, noofwords
include_once("config.php");

    $db=@mysql_connect($dbhost,$dbuser,$dbpasswd)
      or die(mysql_errno().": ".mysql_error());

    @mysql_select_db($dbname)
      or die("Can't find database. Please try again later.");


$dimension=20;

$noofwords=5;


$grid = array();
$words=array();


initgrid($dimension);
//print_r($grid);
findfirstword($dimension);
findsecondword($dimension);
for($i=0;$i<$noofwords-2;$i++)
{
	//findword($dimension);
}


/**
 *
 */
function initgrid($dimension)
{
	global $grid;
	for($i=0; $i<$dimension; $i++)
	{
		$grid[$i]= array();
		for($j=0; $j<$dimension; $j++)
		{
			$grid[$i][$j]="";
		}
	}

}


/**
 *
 */
function findfirstword($dimension)
{
	global $grid, $words;
	
	$word=array();
	
	$minlength=7;
	if($minlength>$dimension) $minlength=$dimension/2;
	
	$wildcard="";
	for($i=0; $i<$minlength;$i++)
	{
		$wildcard.="_";
	}
	
	// try to get minimum length word
	for($i=0; count($word)<1 && $i<MAX_RETRIES; $i++)
	{
		$query = "SELECT * FROM ".DBTABLE.", (SELECT FLOOR(MAX(".DBTABLE.".id) * RAND()) AS randId FROM ".DBTABLE.") AS someRandId WHERE ".DBTABLE.".id = someRandId.randId AND solution like '".$wildcard."%';";
		$word = getwordfromdatabase($query);
			
  		print("<br />strlen=".strlen($word["solution"]));
  		if(strlen($word["solution"])>$dimension)
  		{
  			print(" first word: too long! ");
  			print($word["solution"]);
  			$word=array();
  		}
 
	}
	// if no word found, get any word
	if(!count($word))
	{
		$query = "SELECT * FROM ".DBTABLE.", (SELECT FLOOR(MAX(".DBTABLE.".id) * RAND()) AS randId FROM ".DBTABLE.") AS someRandId WHERE ".DBTABLE.".id = someRandId.randId;";
		$word = getwordfromdatabase($query);
			
  		print("<br />strlen=".strlen($word["solution"]));
  		if(strlen($word["solution"])>$dimension)
  		{
  			print(" first word: too long! ");
  			print($word["solution"]);
  			$word=array();
  		}
	}
	if(count($word))
	{
		print("<br />adding first word: ");
		
		$word["ypos"]=rand(0,$dimension-strlen($word["solution"]));
		$word["xpos"]=rand(0,$dimension-strlen($word["solution"]));
		$word["ishorizontal"]=rand(0,1);
		print_r($word);
		addword($word);
	}
	
}




/**
 *
 */
function findsecondword($dimension)
{
	global $grid, $words;
	
	$minlength=7;
	if($minlength>$dimension) $minlength=$dimension/2;
	
	$minlengthwildcard="";
	for($i=0; $i<$minlength;$i++)
	{
		$minlengthwildcard.="_";
	}
	$minlengthwildcard ="_"; // todo remove this line
	
	$attachword=$words[0];
	
	print("<br />attaching word to: ");
	print_r($attachword);
	
	$word=array();
	
	for($i=0; count($word)<1 && $i<MAX_RETRIES; $i++)
	{
		print("<br /><br />Searching... ".$i."<br />");
		$attachpos=rand(0,strlen($attachword["solution"])-1);
		$attachletter=$attachword["solution"]{$attachpos};
		
	
		//"+xpos+"_"+(ypos+i)) for horizontal
		$attachxpos=$attachword["xpos"];
		$attachypos=$attachword["ypos"];
		
		$maxlettersbefore=0;
		$maxlettersafter=0;
		

		print("<br />Attaching to letter: ".$attachletter." ".$attachxpos." ".$attachypos);

		if($attachword["ishorizontal"])
		{
			$attachypos+=$attachpos;
			$maxlettersbefore=$attachxpos-1;
			$maxlettersafter=$dimension-$attachxpos-1;
		}
		else
		{
			$attachxpos+=$attachpos;
			$maxlettersbefore=$attachypos-1;
			$maxlettersafter=$dimension-$attachypos-1;
		}
		
		print(" - Attachpos: ".$attachxpos." ".$attachypos);
		
		$checkxpos=$attachxpos;
		$checkypos=$attachypos;
		//if (!hasneighbours($checkxpos, $checkypos, $attachword["ishorizontal"])
		{
		
			// create wildcard statement
			// will work for both back and front (a% OR _a% OR __a% ...) AND (%a OR %a_ OR %a__ ...) 
			// todo_ exclude words already used -> id NOT LIKE
			// nested select with length?
			//$wildcard="";
			$wildcardbefore="";
			$wildcardafter="";
			$statement1 = " `solution` LIKE '".$attachletter."%'";
			$statement2 = " `solution` LIKE '%".$attachletter."'";
			// create statement 1 for number of letters before attachletter
			for($j=0;$j<$maxlettersbefore;$j++)
			{
				if($attachword["ishorizontal"])
				{
					$checkxpos=$checkxpos-1;
				}
				else
				{
					$checkypos=$checkypos-1;
				}
				$wildcardbefore.="_";
				$statement1 .= " OR `solution` LIKE '".$wildcardbefore.$attachletter."%'";
			}
			// create statement 2 for number of letters after attachletter
			$checkxpos=$attachxpos;
			$checkypos=$attachypos;
			for($j=0;$j<$maxlettersafter;$j++)
			{
				if($attachword["ishorizontal"])
				{
					$checkxpos=$checkxpos+1;
				}
				else
				{
					$checkypos=$checkypos+1;
				}
				$wildcardafter.="_";
				$statement1 .= " OR `solution` LIKE '".$attachletter.$wildcardafter."%'";
			}
			
			// todo: assemble statement and add to query + minlength
			
			$condition = " (".$statement1.") AND (".$statement2.") AND `solution` LIKE '%".$minlengthwildcard."%'";
			print('<br />condition: '.$condition);
			
			//for($j=0;count($word)<1 && $j<$maxlettersbefore;$j++)
			{
				$query = "SELECT * FROM ".DBTABLE.", (SELECT FLOOR(MAX(".DBTABLE.".id) * RAND()) AS randId FROM ".DBTABLE.") AS someRandId WHERE ".DBTABLE.".id = someRandId.randId AND ".$condition;
				$word = getwordfromdatabase($query);
			}
			
			// todo check length again?
		}
	}
	if(count($word))
	{
		print("<br />adding  word: ");
	
		if($attachword["ishorizontal"])
		{
			// todo: fix position - multiple occurrence of attachletter
			$word["xpos"]=$attachxpos-strpos($word,$attachletter)+1;
			$word["ypos"]=$attachypos;
			$word["ishorizontal"]=0;
		}
		else
		{
			$word["xpos"]=$attachxpos;
			// todo: fix position
			$word["ypos"]=$attachypos-strpos($word,$attachletter)+1;
			$word["ishorizontal"]=1;
		}
		print_r($word);
		addword($word);
	}
	
}







/**
 *
 */
function findword($dimension)
{
	global $grid, $words;
	
	$temp=rand(0,count($words)-1);
	
	print("<br />***********");
	
	$attachword=$words[$temp];
	
	print("<br />attaching word to: ");
	print_r($attachword);
	
	$word=array();
	
	for($i=0; count($word)<1 && $i<MAX_RETRIES; $i++)
	{
		// todo: check startpos - nicht schon ein Element da!
		print("<br /><br />Searching... ".$i."<br />");
		$attachpos=rand(0,strlen($attachword["solution"])-1);
		$attachletter=$attachword["solution"]{$attachpos};
		
	
		//"+xpos+"_"+(ypos+i)) for horizontal
		$attachxpos=$attachword["xpos"];
		$attachypos=$attachword["ypos"];
		
		$maxlettersbefore=0;
		$maxlettersafter=0;
		

		print("<br />Attaching to letter: ".$attachletter." ".$attachxpos." ".$attachypos);


		// todo: search grid for words in the way for max length
		// don't concatenate
		// check grid and add letter to statement
		// starting from 3rd word
		if($attachword["ishorizontal"])
		{
			$attachypos+=$attachpos;
			$maxlettersbefore=$attachxpos-1;
			$maxlettersafter=$dimension-$attachxpos-1;
		}
		else
		{
			$attachxpos+=$attachpos;
			$maxlettersbefore=$attachypos-1;
			$maxlettersafter=$dimension-$attachypos-1;
		}
		
		print(" - Attachpos: ".$attachxpos." ".$attachypos);
		
		// try from back to front
		// todo: OR in database query for wildcard lengths
		// will work for both back and front (a% OR _a% OR __a% ...) AND (%a OR %a_ OR %a__ ...) 
		$wildcard="";
		for($j=0;count($word)<1 && $j<$maxlettersbefore;$j++)
		{
			$query = "SELECT * FROM ".DBTABLE.", (SELECT FLOOR(MAX(".DBTABLE.".id) * RAND()) AS randId FROM ".DBTABLE.") AS someRandId WHERE ".DBTABLE.".id = someRandId.randId AND solution like '".$wildcard.$attachletter."%';";
			$word = getwordfromdatabase($query);
			
  			print("<br />checking word:");
  			print_r($word);
  			//print("<br />strlen=".strlen($word["solution"]));
  			
  			// todo: check for max letters after
  			if(strlen($word["solution"])>$dimension)
  			{
  				print(" word: too long! ");
  				print($word["solution"]);
  				$word=array();
  			}
  			
 			$wildcard.="_";
		}
	}
	if(count($word))
	{
		print("<br />adding  word: ");
	
		if($attachword["ishorizontal"])
		{
			$word["xpos"]=$attachxpos-strlen($wildcard)+1;
			$word["ypos"]=$attachypos;
			$word["ishorizontal"]=0;
		}
		else
		{
			$word["xpos"]=$attachxpos;
			$word["ypos"]=$attachypos-strlen($wildcard)+1;
			$word["ishorizontal"]=1;
		}
		print_r($word);
		addword($word);
	}
	
}



/**
 *
 */
function addword($word)
{
	global $grid, $words;
	//"+xpos+"_"+(ypos+i)) for horizontal
	$length=strlen($word["solution"]);
	
	if($word["ishorizontal"])
	{
		for($i=0; $i<$length; $i++)
		{
			$grid[$word["xpos"]][$word["ypos"]+$i] = $word["solution"][$i];
		}
	}
	else
	{
		for($i=0; $i<$length; $i++)
		{
			$grid[$word["xpos"]+$i][$word["ypos"]] = $word["solution"][$i];
		}
	}
	$words[]=$word;
	displayarray($grid);
}



/**
 *
 */
function getwordfromdatabase($query)
{
	$dbrow="";
	while($dbrow=="")
	{
		$sql=singlequery($query);
		//print_r($query);
		if($sql)
		{
			$fields=mysql_num_fields($sql);

			// get row
			if($dbrow=mysql_fetch_row($sql))
			{
				// make associative array
				for($field=0;$field<$fields;$field++)
				{
					$word[mysql_field_name($sql,$field)]=$dbrow[$field];
				}
			}
		}
	}
	mysql_free_result($sql);
	return $word;
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
    		$fields=mysql_num_fields($sql);

    		// get row
    		if($dbrow=mysql_fetch_row($sql))
    		{
      			// make associative array
      			for($field=0;$field<$fields;$field++)
      			{
        			$row[mysql_field_name($sql,$field)]=$dbrow[$field];
      			}
    		}
  		}
  		
  	}
  	
	$keys=array_keys($row);
	
	$xml = "<".$wrapper.">";
	
	$noofkeys = count($keys);
	
	$xml .= "<noofentries>1</noofentries>";
	$xml .= "<entry>";
	
	for ($j=0; $j<$noofkeys; $j++)
	{
		$key=strtolower($keys[$j]);

		$element=$row[$keys[$j]];
	//	$xml .= "<$key>".$element."</$key>";
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
function singlequery($query)
{
  global $dbname,$dbhost,$dbuser,$dbpasswd,$db;

  $result=$query;

    $result=@mysql_query($query)
      or die(mysql_errno().": ".mysql_error().' <i>in query:</i> '.$query);
  
  if(preg_match ("/insert/i",$query))
  {
    $result= mysql_insert_id($db);
  }

  return $result;
}


//
// dislays an array[$row][$col] as table data
// ** for testing
//
function displayarray($data)
{
  print('<table border="1">');

  $rows=count($data);
  for($row=0;$row<$rows;$row++)
  {
    print('<tr>');

    $cols=count($data[$row]);
    for($col=0;$col<$cols;$col++)
    {
      print('<td>');
      $item=$data[$row][$col];
      if($item=="")
      {
        $item='&nbsp;';
      }
      print($item);
      print('</td>');
    }
    print('</tr>');
  }
  print('</table>');
}

  @mysql_close($db);
?>