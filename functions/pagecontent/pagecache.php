<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"pagecontent"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"functions"));

include_once($projectroot."functions/db.php");
include_once($projectroot."functions/publicsessions.php");
include_once($projectroot."functions/treefunctions.php");
include_once($projectroot ."config.php");

//
//
//
function getcachedpage($page, $parameters)
{
	global $db;
	$result="";
	$page = $db->setinteger($page);
	$parameters = $db->setstring($parameters);
	$fields=array();
	$fields[]='page_id';
	$fields[]='content_html';
	$fields[]='lastmodified';

	$condition="cache_key = '".$page.$parameters."'";

	$pagefields=getmultiplefields(PAGECACHE_TABLE, "page_id",$condition, $fields, $orderby="page_id, cache_key");
	if(isset($pagefields[$page]))
	{
		if(!iscachedpagedatecurrent($page, $pagefields[$page]["lastmodified"]))
		{
			deleteentry(PAGECACHE_TABLE, "page_id = '".$page."'");
		}
		else
		{
			$result = $pagefields[$page]["content_html"];
		}
	}
	return $result;
}



//
//
//
function makecachedpage($page, $parameters, $content_html)
{
	global $db;
	// create date
	$now=date(DATETIMEFORMAT, strtotime('now'));

	$page = $db->setinteger($page);
	$parameters = $db->setstring($parameters);
	$content_html = $db->setstring($content_html);
	$key = $page.$parameters;

	if(strlen($key)<=255)
	{

		// insert or update entries
		$dbentry="";

		$query="select page_id from ".PAGECACHE_TABLE." where ";
		$query.="cache_key = '".$key."'";

		//  print($query.'<br>');
		$sql=$db->singlequery($query);

		if($sql)
		{
			$row=mysql_fetch_row($sql);
			$dbentry=$row[0];
		}

		if($dbentry == $page)
		{
			updatefields(PAGECACHE_TABLE, array(0 => "content_html", 1 => "lastmodified"), array(0 => $content_html, 1 => $now), "cache_key", $key);
		}
		else
		{
			$values = array();
			$values[] = $key;
			$values[] = $page;
			$values[] = $content_html;
			$values[] = $now;
			insertentry(PAGECACHE_TABLE, $values);
		}
	}
}


//
// compare to edit date and to current date
// date = last time cache was modified
//
function iscachedpagedatecurrent($page, $date)
{
	global $db;
	$result=false;
	$pagedate=getdbelement("editdate", PAGES_TABLE, 'page_id', $db->setinteger($page));
	return $pagedate <= $date && $date > date(DATETIMEFORMAT, strtotime('-1 day'));
}

?>
