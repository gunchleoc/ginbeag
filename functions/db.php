<?php
$projectroot=dirname(__FILE__);

$projectroot=substr($projectroot,0,strrpos($projectroot,"functions"));

include_once($projectroot ."config.php");
include_once($projectroot ."includes/constants.php");

// security check: restrict which calling scripts get access to the database
$allowedscripts = array(
	"admin/activate.php",
	"admin/admin.php",
	"admin/edit/articleedit.php",
	"admin/edit/galleryedit.php",
	"admin/edit/linklistedit.php",
	"admin/edit/menuedit.php",
	"admin/edit/newsedit.php",
	"admin/edit/pageintrosettingsedit.php",
	"admin/editcategories.php",
	"admin/editimagelist.php",
	"admin/includes/ajax/articles/addcategories.php",
	"admin/includes/ajax/articles/removecategories.php",
	"admin/includes/ajax/articles/savesectiontitle.php",
	"admin/includes/ajax/articles/savesource.php",
	"admin/includes/ajax/articles/updatecategories.php",
	"admin/includes/ajax/articles/updatesectiontitle.php",
	"admin/includes/ajax/editor/collapseeditor.php",
	"admin/includes/ajax/editor/editorcontentssavedialog.php",
	"admin/includes/ajax/editor/expandeditor.php",
	"admin/includes/ajax/editor/formatpreviewtext.php",
	"admin/includes/ajax/editor/getserverprotocol.php",
	"admin/includes/ajax/editor/gettextfromdatabase.php",
	"admin/includes/ajax/editor/savetext.php",
	"admin/includes/ajax/galleries/saveimage.php",
	"admin/includes/ajax/galleries/updateimage.php",
	"admin/includes/ajax/imageeditor/saveimagefilename.php",
	"admin/includes/ajax/imageeditor/saveimagealignment.php",
	"admin/includes/ajax/imageeditor/saveimagesize.php",
	"admin/includes/ajax/imageeditor/showimagealignment.php",
	"admin/includes/ajax/imageeditor/showimagesize.php",
	"admin/includes/ajax/imageeditor/updateimage.php",
	"admin/includes/ajax/imagelist/addcategories.php",
	"admin/includes/ajax/imagelist/getimageusage.php",
	"admin/includes/ajax/imagelist/removecategories.php",
	"admin/includes/ajax/imagelist/savedescription.php",
	"admin/includes/ajax/imagelist/updatecategories.php",
	"admin/includes/ajax/imagelist/updateimage.php",
	"admin/includes/ajax/linklists/",
	"admin/includes/ajax/linklists/",
	"admin/includes/ajax/linklists/savelinkproperties.php",
	"admin/includes/ajax/linklists/updatelinktitle.php",
	"admin/includes/ajax/menus/movepage.php",
	"admin/includes/ajax/menus/saveoptions.php",
	"admin/includes/ajax/menus/updatesubpages.php",
	"admin/includes/ajax/news/addcategories.php",
	"admin/includes/ajax/news/publish.php",
	"admin/includes/ajax/news/removecategories.php",
	"admin/includes/ajax/news/savedate.php",
	"admin/includes/ajax/news/savepermissions.php",
	"admin/includes/ajax/news/savesectiontitle.php",
	"admin/includes/ajax/news/savesource.php",
	"admin/includes/ajax/news/savetitle.php",
	"admin/includes/ajax/news/unpublish.php",
	"admin/includes/ajax/news/updatecategories.php",
	"admin/includes/ajax/news/updatedate.php",
	"admin/includes/ajax/news/updatesectiontitle.php",
	"admin/includes/ajax/news/updatetitle.php",
	"admin/includes/pagelist.php",
	"admin/includes/preview.php",
	"admin/login.php",
	"admin/pagedelete.php",
	"admin/pagedisplay.php",
	"admin/pageedit.php",
	"admin/pagenew.php",
	"admin/profile.php",
	"admin/register.php",
	"admin/showimage.php",
	"contact.php",
	"guestbook.php",
	"index.php",
	"login.php",
	"rss.php",
	"showimage.php",
	"stuth/geamannan/bs/index.php",
	"stuth/geamannan/crochadair/getword.php",
	"stuth/geamannan/crochadair/index.php",
	"stuth/geamannan/leacan/index.php",
	"stuth/geamannan/leumadair/index.php",
	"stuth/geamannan/longan/index.php",
	"stuth/geamannan/matamataigs/index.php",
	"stuth/geamannan/tetris/highscore.php",
	"stuth/geamannan/tetris/index.php",
	"stuth/geamannan/tt/getpuzzle.php"
);

$server_script = preg_replace('/\/\/+/', '/', $_SERVER["SCRIPT_FILENAME"]);
$install_with_root =  preg_replace('/\/\/+/', '/', ($_SERVER["DOCUMENT_ROOT"] . "/" . $installdir . "/"));

if(!in_array(substr($server_script, strlen($install_with_root)), $allowedscripts)) die;


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

		if(DEBUG) {
			$this->db=new mysqli($dbhost, $dbuser, $dbpasswd, $dbname);
		} else {
			$this->db=@new mysqli($dbhost, $dbuser, $dbpasswd, $dbname);
		}

		if (!$this->db) {
			echo "Can't connect to database. Please try again later." . PHP_EOL;
			if(DEBUG) {
				echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
				echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
			}
			exit();
		}
	}


	// executes a single query
	// $query a string with a mysql command
	// returns the query result
	// 1 = success, "" = failure
	//
	function singlequery($query) {
		$result = $this->db->query($query);
		if (!$result) {
			if (DEBUG) {
				printf("<strong>Error %d:</strong> %s<br /><strong>In query:</strong> %s%s", $this->db->errno, $this->db->error, $query, PHP_EOL);
			} else {
				print("Can't get data from database. Please notify the admin." . PHP_EOL);
			}
			exit();
		}

		if (preg_match ("/insert/i",$query))
		{
			$result= $this->db->insert_id;
		}

		return $result;
	}

	// executes a list of queries
	// $queries an array of strings with a mysql commands
	// returns an array of query results
	// true = success, false = failure
	function multiquery($queries) {
		for ($i=0; $i < count($queries); $i++) {
			$result = $this->db->query($queries[$i]);
			if (!$result) {
				if (DEBUG) {
					printf("<strong>Error %d:</strong> %s<br /><strong>In query:</strong> %s%s", $this->db->errno, $this->db->error, $queries[$i], PHP_EOL);
				}
				return false;
			}
		}
		return true;
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
  		$result= @$this->db->real_escape_string($var);
		return utf8_decode($result);
	}


	/*
	 * close DB at end of script
	 */
	function closedb()
	{
		@mysqli_close($db);
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
	if ($sql) {
		return $sql->fetch_row()[0];
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
	if ($sql) {
		while ($row = $sql->fetch_row()) {
			array_push($result, $row[0]);
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
	if ($sql) {
		$fields = $sql->field_count;
		// get row
		if ($row = $sql->fetch_row()) {
			// make associative array
			for($field = 0; $field < $fields; $field++) {
				$result[$sql->fetch_field_direct($field)->name]=$row[$field];
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
	if($sql) {
		$fields=$sql->field_count;

		// get index for field name
		$found=false;
		$fieldindex = 0;
		for ($field = 0; !$found && $field < $fields; $field++) {
			if ($sql->fetch_field_direct($field)->name==$keyname) {
				$fieldindex=$field;
				$found=true;
			}
		}

		// get column
		while ($row = $sql->fetch_row()) {
			// make associative array
			for ($field = 0; $field < $fields; $field++) {
				$result[$row[$fieldindex]][$sql->fetch_field_direct($field)->name] = $row[$field];
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


// *************************** changes *********************************** //

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
