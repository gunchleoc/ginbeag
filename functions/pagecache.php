<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"functions"));

include_once($projectroot."functions/db.php");
include_once($projectroot."functions/publicsessions.php");
include_once($projectroot."functions/treefunctions.php");
include_once($projectroot ."config.php");

################################################################################
##                                                                            ##
##        Functions                                                           ##
##                                                                            ##
################################################################################

/*  $result="";
  $page_id = setinteger($page_id);
  $parameters = setstring($parameters);
  $fields=array();
  $fields[]='page_id';
  $fields[]='content_html';
  $fields[]='lastmodified';

  $condition="cache_key = '".$page_id.$parameters."'";

  $pagefields=getmultiplefields(PAGECACHE_TABLE, "page_id",$condition, $fields, $orderby="page_id, cache_key");

  $date=$pagefields[$page_id]["lastmodified"]);
  $pagedate=getdbelement("editdate", PAGES_TABLE, $page_id, setinteger($page_id));
  print($pagedate <= $date && $date > date(DATETIMEFORMAT, strtotime('-1 day')));*/



//
//
//
function getcachedpage($page_id, $parameters)
{
  $result="";
  $page_id = setinteger($page_id);
  $parameters = setstring($parameters);
  $fields=array();
  $fields[]='page_id';
  $fields[]='content_html';
  $fields[]='lastmodified';
  
  $condition="cache_key = '".$page_id.$parameters."'";

  $pagefields=getmultiplefields(PAGECACHE_TABLE, "page_id",$condition, $fields, $orderby="page_id, cache_key");
//  print_r($pagefields);
  if(array_key_exists("page_id",$pagefields[$page_id]))
  {
    if(!iscachedpagedatecurrent($page_id, $pagefields[$page_id]["lastmodified"]))
    {
//      print("old page!");
      $query="DELETE FROM ".PAGECACHE_TABLE." where ".$condition.";";
      $sql=singlequery($query);
    }
    else
    {
      $result=$pagefields[$page_id]["content_html"];
    }
  }
  return $result;
}



//
//
//
function makecachedpage($page_id, $parameters, $content_html)
{
  // create date
  $now=date(DATETIMEFORMAT, strtotime('now'));

  $page_id = setinteger($page_id);
  $parameters = setstring($parameters);
  $content_html = setstring($content_html);
  $key=$page_id.$parameters;
  
  if(strlen($key)<=255)
  {

    // insert or update entries
    $dbentry="";

    $query="select page_id from ".PAGECACHE_TABLE." where ";
    $query.="cache_key = '".$key."'";

//  print($query.'<br>');
    $sql=singlequery($query);

    if($sql)
    {
      $row=mysql_fetch_row($sql);
      $dbentry=$row[0];
    }
  
    if($dbentry == $page_id)
    {
      $queries = array();
      $queries[]="UPDATE ".PAGECACHE_TABLE." SET content_html='".($content_html)."' WHERE cache_id='".$key."';";
      $queries[]="UPDATE ".PAGECACHE_TABLE." SET lastmodified='".($now)."' WHERE cache_id='".$key."';";
      $sql=query($query);
    }
    else
    {
      $query="INSERT INTO ".PAGECACHE_TABLE." values('".$key."','".$page_id."','".$content_html."','".$now."')";
      $sql=singlequery($query);
    }
  }
}


//
// compare to edit date and to current date
// date = last time cache was modified
//
function iscachedpagedatecurrent($page_id, $date)
{
  $result=false;
  $pagedate=getdbelement("editdate", PAGES_TABLE, 'page_id', setinteger($page_id));
/*  print ($pagedate <= $date && $date > date(DATETIMEFORMAT, strtotime('-1 day')));
  print('<p>cachedate:'.$date.'</p>');
  print('<p>pagedate:'.$pagedate.'</p>');
  print('<p>now - 1 day:'.date(DATETIMEFORMAT, strtotime('-1 day')).'</p>');
  print ($pagedate <= $date && $date > date(DATETIMEFORMAT, strtotime('-1 day')));*/
  return $pagedate <= $date && $date > date(DATETIMEFORMAT, strtotime('-1 day'));
}

?>
