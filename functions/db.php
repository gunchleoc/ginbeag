<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"functions"));
include_once($projectroot ."config.php");
include_once($projectroot ."includes/constants.php");

// security check: restrict which calling scripts get access to the database
$root4array=str_replace("\\","/",$projectroot);

$allowedscripts[]=$root4array."admin/activate.php";
$allowedscripts[]=$root4array."admin/admin.php";
$allowedscripts[]=$root4array."admin/edit/articleedit.php";
$allowedscripts[]=$root4array."admin/edit/galleryedit.php";
$allowedscripts[]=$root4array."admin/edit/linklistedit.php";
$allowedscripts[]=$root4array."admin/edit/menuedit.php";
$allowedscripts[]=$root4array."admin/edit/newsedit.php";
$allowedscripts[]=$root4array."admin/edit/pageintrosettingsedit.php";
$allowedscripts[]=$root4array."admin/editcategories.php";
$allowedscripts[]=$root4array."admin/editimagelist.php";
$allowedscripts[]=$root4array."admin/includes/ajax/articles/addcategories.php";
$allowedscripts[]=$root4array."admin/includes/ajax/articles/removecategories.php";
$allowedscripts[]=$root4array."admin/includes/ajax/articles/savesectiontitle.php";
$allowedscripts[]=$root4array."admin/includes/ajax/articles/savesource.php";
$allowedscripts[]=$root4array."admin/includes/ajax/articles/updatecategories.php";
$allowedscripts[]=$root4array."admin/includes/ajax/articles/updatesectiontitle.php";
$allowedscripts[]=$root4array."admin/includes/ajax/editor/collapseeditor.php";
$allowedscripts[]=$root4array."admin/includes/ajax/editor/editorcontentssavedialog.php";
$allowedscripts[]=$root4array."admin/includes/ajax/editor/expandeditor.php";
$allowedscripts[]=$root4array."admin/includes/ajax/editor/formatpreviewtext.php";
$allowedscripts[]=$root4array."admin/includes/ajax/editor/gettextfromdatabase.php";
$allowedscripts[]=$root4array."admin/includes/ajax/editor/savetext.php";
$allowedscripts[]=$root4array."admin/includes/ajax/galleries/saveimage.php";
$allowedscripts[]=$root4array."admin/includes/ajax/galleries/updateimage.php";
$allowedscripts[]=$root4array."admin/includes/ajax/imageeditor/saveimagefilename.php";
$allowedscripts[]=$root4array."admin/includes/ajax/imageeditor/saveimageproperties.php";
$allowedscripts[]=$root4array."admin/includes/ajax/imageeditor/showimageproperties.php";
$allowedscripts[]=$root4array."admin/includes/ajax/imageeditor/updateimage.php";
$allowedscripts[]=$root4array."admin/includes/ajax/imagelist/addcategories.php";
$allowedscripts[]=$root4array."admin/includes/ajax/imagelist/getimageusage.php";
$allowedscripts[]=$root4array."admin/includes/ajax/imagelist/removecategories.php";
$allowedscripts[]=$root4array."admin/includes/ajax/imagelist/savedescription.php";
$allowedscripts[]=$root4array."admin/includes/ajax/imagelist/updatecategories.php";
$allowedscripts[]=$root4array."admin/includes/ajax/imagelist/updateimage.php";
$allowedscripts[]=$root4array."admin/includes/ajax/linklists/";
$allowedscripts[]=$root4array."admin/includes/ajax/linklists/";
$allowedscripts[]=$root4array."admin/includes/ajax/linklists/savelinkproperties.php";
$allowedscripts[]=$root4array."admin/includes/ajax/linklists/updatelinktitle.php";
$allowedscripts[]=$root4array."admin/includes/ajax/menus/movepage.php";
$allowedscripts[]=$root4array."admin/includes/ajax/menus/saveoptions.php";
$allowedscripts[]=$root4array."admin/includes/ajax/menus/updatesubpages.php";
$allowedscripts[]=$root4array."admin/includes/ajax/news/addcategories.php";
$allowedscripts[]=$root4array."admin/includes/ajax/news/publish.php";
$allowedscripts[]=$root4array."admin/includes/ajax/news/removecategories.php";
$allowedscripts[]=$root4array."admin/includes/ajax/news/savedate.php";
$allowedscripts[]=$root4array."admin/includes/ajax/news/savepermissions.php";
$allowedscripts[]=$root4array."admin/includes/ajax/news/savesectiontitle.php";
$allowedscripts[]=$root4array."admin/includes/ajax/news/savesource.php";
$allowedscripts[]=$root4array."admin/includes/ajax/news/savetitle.php";
$allowedscripts[]=$root4array."admin/includes/ajax/news/unpublish.php";
$allowedscripts[]=$root4array."admin/includes/ajax/news/updatecategories.php";
$allowedscripts[]=$root4array."admin/includes/ajax/news/updatedate.php";
$allowedscripts[]=$root4array."admin/includes/ajax/news/updatesectiontitle.php";
$allowedscripts[]=$root4array."admin/includes/ajax/news/updatetitle.php";
$allowedscripts[]=$root4array."admin/includes/preview.php";
$allowedscripts[]=$root4array."admin/login.php";
$allowedscripts[]=$root4array."admin/pagedelete.php";
$allowedscripts[]=$root4array."admin/pagedisplay.php";
$allowedscripts[]=$root4array."admin/pageedit.php";
$allowedscripts[]=$root4array."admin/pagenew.php";
$allowedscripts[]=$root4array."admin/profile.php";
$allowedscripts[]=$root4array."admin/register.php";
$allowedscripts[]=$root4array."admin/showimage.php";
$allowedscripts[]=$root4array."contact.php";
$allowedscripts[]=$root4array."guestbook.php";
$allowedscripts[]=$root4array."index.php";
$allowedscripts[]=$root4array."login.php";
$allowedscripts[]=$root4array."rss.php";
$allowedscripts[]=$root4array."showimage.php";
$allowedscripts[]=$root4array."stuth/geamannan/bs/index.php";
$allowedscripts[]=$root4array."stuth/geamannan/crochadair/getword.php";
$allowedscripts[]=$root4array."stuth/geamannan/crochadair/index.php";
$allowedscripts[]=$root4array."stuth/geamannan/leacan/index.php";
$allowedscripts[]=$root4array."stuth/geamannan/leumadair/index.php";
$allowedscripts[]=$root4array."stuth/geamannan/longan/index.php";
$allowedscripts[]=$root4array."stuth/geamannan/matamataigs/index.php";
$allowedscripts[]=$root4array."stuth/geamannan/tetris/highscore.php";
$allowedscripts[]=$root4array."stuth/geamannan/tetris/index.php";
$allowedscripts[]=$root4array."stuth/geamannan/tt/getpuzzle.php";

if(!in_array($_SERVER["SCRIPT_FILENAME"],$allowedscripts)) die;


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
	function singlequery($query)
	{
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
	function multiquery($queries)
	{
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
	function closedb()
	{
		@mysql_close($db);
	}
}


// *************************** helpers for db convenience functions ********************* //

//
//
//
function getdbresultsingle($query)
{
	global $db;
	
	$sql=$db->singlequery($query);
	if($sql)
	{
		$row=mysql_fetch_row($sql);
		return $row[0];
	}
	else return false;
}


//
//
//
function getdbresultcolumn($query)
{
	global $db;
	
	$result=array();
	$sql=$db->singlequery($query);
	if($sql)
	{
		while($row=mysql_fetch_row($sql))
		{
			array_push($result,$row[0]);
		}
	}
	return $result;
}

// *************************** db convenience functions ********************* //


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
function getcolumn($fieldname, $table, $condition)
{
	global $db;
	$query="select ".$fieldname." from ".$table." where ".$condition;
	return getdbresultcolumn($query);
}


//
//
//
function getorderedcolumn($fieldname, $table, $condition, $orderby, $ascdesc="DESC")
{
	global $db;
	$query="select ".$fieldname." from ".$table." where ".$condition." order by ".$orderby." ".$ascdesc;
	return getdbresultcolumn($query);
}

//
//
//
function getorderedcolumnlimit($fieldname, $table, $condition, $orderby, $offset, $number, $ascdesc="DESC")
{
	global $db;
	$query="select ".$fieldname." from ".$table." where ".$condition." order by ".$orderby." ".$ascdesc;
	$query.=" limit ".$offset.", ".$number;
	return getdbresultcolumn($query);
}


//
//
//
function getdistinctorderedcolumn($fieldname, $table, $condition, $orderby, $ascdesc="DESC")
{
	global $db;
	$query="select distinct ".$fieldname." from ".$table." where ".$condition." order by ".$orderby." ".$ascdesc;
	return getdbresultcolumn($query);
}


//
//
//
function getdbelement($fieldname, $table, $conditionkey, $conditionvalue)
{
	global $db;
	$query="select ".$fieldname." from ".$table." where ".$conditionkey." = '".$conditionvalue."';";
	return getdbresultsingle($query);
}

//
//
//
function getmax($fieldname, $table, $condition)
{
	global $db;
	$query="select max(".$fieldname.") from ".$table." where ".$condition.";";
	return getdbresultsingle($query);
}

//
//
//
function getmin($fieldname, $table, $condition)
{
	global $db;
	$query="select min(".$fieldname.") from ".$table." where ".$condition.";";
	return getdbresultsingle($query);
}

//
//
//
function countelements($keyname, $table)
{
	global $db;
	$query="select count(".$keyname.") from ".$table.";";
	return getdbresultsingle($query);
}

//
//
//
function countelementscondition($keyname, $table,$condition)
{
	global $db;
	$query="select count(".$keyname.") from ".$table." WHERE ".$condition.";";
	return getdbresultsingle($query);
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
