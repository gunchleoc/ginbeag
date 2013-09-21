<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

// check legal vars
include($projectroot."admin/includes/legalsitevars.php");

include_once($projectroot."admin/functions/dbmod.php");
include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/functions/pagesmod.php");
include_once($projectroot."admin/includes/adminelements.php");

$sid=$_GET['sid'];
checksession($sid);

//showtables();
if(isset($_GET['backup']))
{
  if($_GET['structure']==="structure")
  {
    backupdatabase($_GET['display']);
  }
  else
  {
    backupdatabase($_GET['display'],false);
  }
}
elseif(isset($_GET['clearpagecache']))
{
  clearpagecache();
  print('<p class="highlight">Cleared page cache.</p>');
  printforms();
}
else
{
  printforms();
}

//
//
//
function printforms()
{
   printbackupform();
   printclearcacheform();
}

//
//
//
function printbackupform()
{
  global $sid;
$header = new HTMLHeader("Database Utilities","Webpage Building");
print($header->toHTML());
?>
<table><tr><td class="bodyline">
<table cellpadding="5"><tr>
<th class="thHead" colspan="2">Backup Database</th>
</tr>
<form name="backupform" method="get">
<tr>
  <td class="gen"><b>Backup</b>
  </td>
  <td class="gen">
  <input type="radio" name="structure" value="structure">
  Structure only
  <div>
  <input type="radio" name="structure" value="full" checked>
  Full backup
  </div>
  </td>
</tr>
<tr>
  <td class="gen"><b>Display method</b>
  </td>
  <td class="gen">
  <input type="radio" name="display" value="screen">Display on screen
  <div>
  <input type="radio" name="display" value="download">Download uncompressed
  </div><div>
  <input type="radio" name="display" value="gzip" checked>Download gzip
  </div>
  </td>
</tr>
<tr>
<td colspan="2" class="table" align="center">
<input type="hidden" name="sid" value="<?php print($sid);?>">
<input type="submit" name="backup" value="Start Backup" class="mainoption">
</td>
</tr>
</form>
</table>
</td></tr>
</table>

<?php
$footer = new HTMLFooter();
print($footer->toHTML());
}


//
//
//
function printclearcacheform()
{
  global $sid;
?>
<table><tr><td class="bodyline">
<table cellpadding="5"><tr>
<th class="thHead">Clear Page Cache</th>
</tr>
<form name="clearcacheform" method="get">
<tr>
<td class="table" align="center">
<input type="hidden" name="sid" value="<?php print($sid);?>">
<input type="submit" name="clearpagecache" value="Clear Page Cache" class="mainoption">
</td>
</tr>
</form>
</table>
</td></tr>
</table>

<?php
}

//
//
//
function backupdatabase($display,$structureonly=true)
{
  global $dbname,$table_prefix;
  
  $sitename=getproperty("Site Name");
  
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

  $tables=singlequery($query);
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
    $columns=singlequery($query);
    
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
    $keys=singlequery($query);
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

      $data=singlequery($query);
      
      if(mysql_num_rows($data))
      {
        while($element = mysql_fetch_row($data))
        {
          $result.=$cr."INSERT INTO `".$tablename."` VALUES (";
          for($i=0;$i<count($element);$i++)
          {
            $result.="'".addslashes($element[$i])."',";
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
    $header = new HTMLHeader("Database Dump","Webpage Building");
    print($header->toHTML());
    print('<div class="gen">'.$result.'</div>');
    $footer = new HTMLFooter();
    print($footer->toHTML());
  }
  elseif($display==="download")
  {
//    print("download this");
    $name=str_replace(" ","_",$sitename)."_webpage_".$dbname;
    if($structureonly) $name.="_structure";
    $name.=".sql";
		header("Content-Type: text/x-delimtext; name=\"".$name."\"");
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
  global $table_prefix;
  
  $header = new HTMLHeader("Database Utilities","Webpage Building");
  print($header->toHTML());


  //get table names
  $query="SHOW TABLES LIKE '".$table_prefix."%';";

//  print($query.'<p>');

  $tables=singlequery($query);
  while($tablerow = mysql_fetch_row($tables))
  {
    $tablename=$tablerow[0];
    print('<p>'.$tablename.'<br>');

    // get fields
    $query="SHOW COLUMNS FROM ".$tablename;
    $columns=singlequery($query);

    $numfields=mysql_num_fields($columns);
    print('<table class="bodyline"><tr>');
    for($i=0;$i<$numfields;$i++)
    {
      print('<th class="gen">'.$i.": ".mysql_field_name ($columns,$i).'</th>');
    }
    print('</tr>');

    while($column = mysql_fetch_row($columns))
    {
      print('<tr>');
      for($i=0;$i<count($column);$i++)
      {
        print('<td class="gen">');
        print_r($column[$i]);
        print('</td>');
      }
      print('</tr>');
    }
    print('</tr></table>');
    
    
    // show keys
    $query="SHOW INDEX FROM ".$tablename;
    $keys=singlequery($query);
    
    $numfields=mysql_num_fields($keys);
    print('<p>Keys:<br><table class="bodyline"><tr>');
    for($i=0;$i<$numfields;$i++)
    {
      print('<th class="gen">'.$i.": ".mysql_field_name ($keys,$i).'</th>');
    }

    while($key = mysql_fetch_row($keys))
    {
      print('<tr>');
      for($i=0;$i<count($key);$i++)
      {
        print('<td class="gen">');
        print_r($key[$i]);
        print('</td>');
      }
      print('</tr>');

    }
    print('</tr></table>');
  }
  $footer = new HTMLFooter();
  print($footer->toHTML());
}

?>
