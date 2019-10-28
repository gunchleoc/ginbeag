<?php
/**
 * An Gineadair Beag is a content management system to run websites with.
 *
 * PHP Version 7
 *
 * Copyright (C) 2005-2019 GunChleoc
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 * @category Ginbeag
 * @package  Ginbeag
 * @author   gunchleoc <fios@foramnagaidhlig.net>
 * @license  https://www.gnu.org/licenses/agpl-3.0.en.html GNU AGPL
 * @link     https://github.com/gunchleoc/ginbeag/
 */

$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."functions/db.php";
require_once $projectroot."admin/functions/sessions.php";
require_once $projectroot."admin/functions/pagesmod.php";
require_once $projectroot."includes/objects/elements.php";
require_once $projectroot."admin/includes/objects/site/dbutils.php";
require_once $projectroot."admin/includes/objects/adminmain.php";

checksession();
checkadmin();

if(isset($_GET['page'])) { $page=$_GET['page'];
} else { $page=0;
}

$message = "";
$error = false;

if(isset($_POST['backup'])) {
    if($_POST['structure']==="structure") {
        backupdatabase($_POST['display']);
    }
    else
    {
        backupdatabase($_POST['display'], false);
    }
}
elseif(isset($_POST['clearpagecache'])) {
    clearpagecache();
    $message="Cleared page cache";
}

if(!(isset($_POST['display']) && $_POST['display']==="screen")) {
    $content = new AdminMain($page, "sitedb", new AdminMessage($message, $error), new SiteDBUtilsBackupForm());
    print($content->toHTML());
}

//
//
//
function backupdatabase($display,$structureonly=true)
{
    global $db, $dbname, $table_prefix, $page, $message, $error;

    $sitename=title2html(getproperty("Site Name"));

    if($display==="screen") {
        $cr="&nbsp;<br />";
    }
    else
    {
        $cr="\r\n";
    }

    $result="#".$cr."# ".$sitename." Webpage Building database backup".$cr."#".$cr;
    $result.="# ".date(DATETIMEFORMAT, strtotime('now')).$cr;
    $result.="#".$cr."# Database: ".$dbname.$cr."#".$cr;
    if($structureonly) {
        $result.="# Structure only".$cr."#".$cr;
    }
    else
    {
        $result.="# Full backup".$cr."#".$cr;
    }

    // Iterate table names
    // TODO sanitize in the backend
    $sql = new RawSQLStatement("SHOW TABLES LIKE '" . $table_prefix . "%'");
    foreach ($sql->fetch_column() as $tablename) {
        // Verify table name
        $sql->check_table_name($tablename);
        if (!empty($db->error_report)) {
            print($db->error_report);
            return;
        }

        $result.=$cr."# ------------------------------------------------";
        $result.=$cr."#".$cr."# Table: ".$tablename.$cr."#";
        $result.=$cr."# ------------------------------------------------".$cr.$cr;
        $result.="DROP TABLE IF EXISTS `".$tablename."`;".$cr;
        $result.="CREATE TABLE `".$tablename."` (".$cr;

        // Get fields
        $sql = new RawSQLStatement("SHOW COLUMNS FROM " . $tablename);
        foreach ($sql->fetch_all() as $column) {
            // way around time limit?
            set_time_limit(0);
            $result.=" `".$column['Field']."` ".$column['Type'];
            if($column['Null']==="YES") { $result.=" NULL";
            } else { $result.=" NOT NULL";
            }
            if(!empty($column['Default'])) { $result.=" default '".$column['Default']."'";
            }
            if(!empty($column['Extra'])) { $result.=" ".$column['Extra'];
            }
            $result.=",".$cr;
        }

        // Get keys
        $sql = new RawSQLStatement("SHOW INDEX FROM " . $tablename);
        $keys = $sql->fetch_all();

        if (!empty($keys)) {
            $result=substr($result, 0, strlen($result)-strlen($cr));
        }
        else
        {
            $result=substr($result, 0, strlen($result)-1);
        }

        foreach ($keys as $key) {
            if($key['Key_name']==="PRIMARY") {
                $result.=$cr."  PRIMARY KEY";
            }
            elseif($key['Index_type']==="FULLTEXT") {
                $result.=$cr."  FULLTEXT KEY `".$key['Key_name']."`";
            }
            elseif(!$key['Non_unique']) {
                $result.=$cr."  UNIQUE KEY "." `".$key['Key_name']."`";
            }
            else
            {
                $result.=$cr."  KEY";
            }
            $result.=" (`".$key['Column_name']."`),";
        }
        $result=substr($result, 0, strlen($result)-1);
        $result.=$cr.");".$cr;

        if(!$structureonly) {
            $sql = new SQLSelectStatement($tablename, '*');
            $test = $sql->fetch_all();

            foreach ($sql->fetch_all() as $row) {
                $result.=$cr."INSERT INTO `".$tablename."` VALUES (";
                foreach ($row as $value) {
                    $entry = addslashes($value);
                    if($display==="screen") {
                        $entry=str_replace("<", "&lt;", $entry);
                        $entry=str_replace(">", "&gt;", $entry);
                    }
                    $entry=stripslashes($entry);
                    $result.="'".$entry."',";
                }
                $result=substr($result, 0, strlen($result)-1);
                $result.=");";
            }
        }
        $result.=$cr;
    }
    if($display==="screen") {
        $content = new AdminMain($page, "sitedb", new AdminMessage($message, $error), new SiteDBUtilsDBDump($result));
        //$content = new SiteDBUtilsDBDump($result);
        print($content->toHTML());
    }
    elseif($display==="download") {
        //    print("download this");
        $name=str_replace(" ", "_", $sitename)."_webpage_".$dbname;
        if($structureonly) { $name.="_structure";
        }
        $name.=".sql";
        header("Content-Type: text/x-delimtext; name=\"".$name."\"".'; charset=utf-8');
        header("Content-disposition: attachment; filename=".$name."");
        print($result);
    }
    else
    {
        //    print("gzip this");
        $name=str_replace(" ", "_", $sitename)."_webpage_".$dbname;
        if($structureonly) { $name.="_structure";
        }
        $name.=".sql.gz";
        header("Content-Type: application/x-gzip; name=\"".$name."\"");
        header("Content-disposition: attachment; filename=".$name."");

        print(@gzencode($result));
    }
    set_time_limit(30);
}

//
//
//
function clearpagecache()
{
    $sql = new RawSQLStatement("TRUNCATE table " . PAGECACHE_TABLE);
    $sql->run();
}
?>
