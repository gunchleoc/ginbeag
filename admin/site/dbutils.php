<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/dbmod.php");
include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/functions/pagesmod.php");
include_once($projectroot."includes/objects/elements.php");
include_once($projectroot."admin/includes/objects/site/dbutils.php");
include_once($projectroot."admin/includes/objects/adminmain.php");

checksession();
checkadmin();

if(isset($_GET['page'])) $page=$_GET['page'];
else $page=0;

$message = "";
$error = false;


//showtables();
if(isset($_POST['backup']))
{
	if($_POST['structure']==="structure")
	{
		backupdatabase($_POST['display']);
	}
	else
	{
		backupdatabase($_POST['display'],false);
	}
}
elseif(isset($_POST['clearpagecache']))
{
	clearpagecache();
	$message="Cleared page cache";
}

if(!(isset($_POST['display']) && $_POST['display']==="screen"))
{
	$content = new AdminMain($page, "sitedb", new AdminMessage($message, $error), new SiteDBUtilsBackupForm());
	print($content->toHTML());
}
$db->closedb();


//
//
//
function backupdatabase($display,$structureonly=true)
{
	global $dbname, $table_prefix, $db, $page, $message, $error;
	
	$sitename=title2html(getproperty("Site Name"));
	
	if($display==="screen")
	{
		$cr="&nbsp;<br />";
	}
	else
	{
		$cr="\r\n";
	}
	
	$result="#".$cr."# ".$sitename." Webpage Building database backup".$cr."#".$cr;
	$result.="# ".date(DATETIMEFORMAT, strtotime('now')).$cr;
	$result.="#".$cr."# Database: ".$dbname.$cr."#".$cr;
	if($structureonly)
	{
		$result.="# Structure only".$cr."#".$cr;
	}
	else
	{
		$result.="# Full backup".$cr."#".$cr;
	}
  
	//get table names
	$query="SHOW TABLES LIKE '".$table_prefix."%';";
	
	//  print($query.'<p>');
	
	$tables=$db->singlequery($query);
	while($tablerow = mysql_fetch_row($tables))
	{
		$tablename=$tablerow[0];
		
		$result.=$cr."# ------------------------------------------------";
		$result.=$cr."#".$cr."# Table: ".$tablename.$cr."#";
		$result.=$cr."# ------------------------------------------------".$cr.$cr;
		$result.="DROP TABLE IF EXISTS `".$tablename."`;".$cr;
		$result.="CREATE TABLE `".$tablename."` (".$cr;
		
		// get fields
		$query="SHOW COLUMNS FROM ".$tablename;
		$columns=$db->singlequery($query);
		
		$numfields=mysql_num_fields($columns);
		
		while($column = mysql_fetch_row($columns))
		{
			// way around time limit?
			set_time_limit(0);
			$result.=" `".$column[0]."` ".$column[1];
			if($column[2]==="YES") $result.=" NULL";
			else $result.=" NOT NULL";
			if(strlen($column[4])>0) $result.=" default '".$column[4]."'";
			if(strlen($column[5])>0) $result.=" ".$column[5];
			$result.=",".$cr;
		}
    
		// get keys
		$query="SHOW INDEX FROM ".$tablename;
		$keys=$db->singlequery($query);
		if(mysql_num_rows($keys))
		{
			$result=substr($result,0,strlen($result)-strlen($cr));
		}
		else
		{
			$result=substr($result,0,strlen($result)-1);
		}

		// 0: Table
		// 1: Non_unique
		// 2: Key_name
		// 3: Seq_in_index
		// 4: Column_name
		// 5: Collation
		// 6: Cardinality
		// 7: Sub_part
		// 8: Packed
		// 9: Null
		// 10: Index_type
		// 11: Comment
		while($key = mysql_fetch_row($keys))
		{
			if($key[2]==="PRIMARY")
			{
				$result.=$cr."  PRIMARY KEY";
			}
			elseif($key[10]==="FULLTEXT")
			{
				$result.=$cr."  FULLTEXT KEY `".$key[2]."`";
			}
			elseif(!$key[1])
			{
				$result.=$cr."  UNIQUE KEY "." `".$key[2]."`";
			}
			else
			{
				$result.=$cr."  KEY";
			}
      		$result.=" (`".$key[4]."`),";
		}
		$result=substr($result,0,strlen($result)-1);
		$result.=$cr.");".$cr;
		if(!$structureonly)
		{
			$query="SELECT * from ".$tablename.";";
			
			$data=$db->singlequery($query);
			
			if(mysql_num_rows($data))
			{
				while($element = mysql_fetch_row($data))
				{
					$result.=$cr."INSERT INTO `".$tablename."` VALUES (";
					for($i=0;$i<count($element);$i++)
					{
						$entry = utf8_encode(addslashes($element[$i]));
						if($display==="screen")
						{
							$entry=str_replace ("<", "&lt;", $entry);
							$entry=str_replace (">", "&gt;", $entry);
						}
						$entry=stripslashes($entry);
						$result.="'".$entry."',";
					}
					$result=substr($result,0,strlen($result)-1);
					$result.=");";
				}
			}
		}
		$result.=$cr;
	}
	if($display==="screen")
	{
		$content = new AdminMain($page, "sitedb", new AdminMessage($message, $error), new SiteDBUtilsDBDump($result));
		//$content = new SiteDBUtilsDBDump($result);
		print($content->toHTML());
	}
	elseif($display==="download")
	{
		//    print("download this");
		$name=str_replace(" ","_",$sitename)."_webpage_".$dbname;
		if($structureonly) $name.="_structure";
		$name.=".sql";
			header("Content-Type: text/x-delimtext; name=\"".$name."\"".'; charset=utf-8');
			header("Content-disposition: attachment; filename=".$name."");
		print($result);
	}
	else
	{
		//    print("gzip this");
		$name=str_replace(" ","_",$sitename)."_webpage_".$dbname;
		if($structureonly) $name.="_structure";
		$name.=".sql.gz";
			header("Content-Type: application/x-gzip; name=\"".$name."\"");
			header("Content-disposition: attachment; filename=".$name."");
		
		print(@gzencode($result));
	}
	set_time_limit(30);
}

//
// helper function for programming dump
//
function showtables()
{
	global $table_prefix, $db;
	
	//get table names
	$query="SHOW TABLES LIKE '".$table_prefix."%';";
	
	//  print($query.'<p>');
	
	$tables=$db->singlequery($query);
	while($tablerow = mysql_fetch_row($tables))
	{
		$tablename=$tablerow[0];
		print('<p>'.$tablename.'<br>');
		
		// get fields
		$query="SHOW COLUMNS FROM ".$tablename;
		$columns=$db->singlequery($query);
		
		$numfields=mysql_num_fields($columns);
		print('<table class="bodyline"><tr>');
		for($i=0;$i<$numfields;$i++)
		{
			print('<th>'.$i.": ".mysql_field_name ($columns,$i).'</th>');
		}
		print('</tr>');

		while($column = mysql_fetch_row($columns))
		{
			print('<tr>');
			for($i=0;$i<count($column);$i++)
			{
				print('<td>');
				print_r($column[$i]);
				print('</td>');
			}
			print('</tr>');
		}
		print('</tr></table>');
    
    
		// show keys
		$query="SHOW INDEX FROM ".$tablename;
		$keys=$db->singlequery($query);
		
		$numfields=mysql_num_fields($keys);
		print('<p>Keys:<br><table class="bodyline"><tr>');
		for($i=0;$i<$numfields;$i++)
		{
			print('<th>'.$i.": ".mysql_field_name ($keys,$i).'</th>');
		}

		while($key = mysql_fetch_row($keys))
		{
			print('<tr>');
			for($i=0;$i<count($key);$i++)
			{
				print('<td>');
				print_r($key[$i]);
				print('</td>');
			}
			print('</tr>');
		}
		print('</tr></table>');
	}
}

//
//
//
function clearpagecache()
{
	global $db;
	$query="TRUNCATE TABLE ".PAGECACHE_TABLE.";";
	$sql=$db->singlequery($query);
}
?>