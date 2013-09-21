<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"pagecontent"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"functions"));

include_once($projectroot."functions/db.php");


//
//
//
function getmenucontents($page_id)
{
	global $db;
  return getrowbykey(MENUS_TABLE, "page_id", $db->setinteger($page_id));
}


//
//
//
function getmenunavigatordepth($page_id)
{
	global $db;
  return getdbelement("navigatordepth",MENUS_TABLE, "page_id", $db->setinteger($page_id));
}


//
//
//
function getarticlepageoverview($page_id)
{
	global $db;
  $fieldnames = array(0 => 'article_author', 1=> 'source', 2=>'day', 3=>'month', 4=>'year');
  return getrowbykey(ARTICLES_TABLE, "page_id", $db->setinteger($page_id), $fieldnames);
}


// ***************************  articlemenu ********************************* //

//
//
//
function getallarticleyears()
{
  return getdistinctorderedcolumn("year",ARTICLES_TABLE,"1","year","ASC");
}


//
//
//
function getfilteredarticles($page,$selectedcat,$from,$to,$order,$ascdesc,$includesubs,$showhidden=false)
{
	global $db,$_GET;
			if(isset($_GET['sid'])) $sid=$_GET['sid'];
		else $sid="";
  $page=$db->setinteger($page);
  $selectedcat=$db->setinteger($selectedcat);
  $from=$db->setinteger($from);
  $to=$db->setinteger($to);
  $order=$db->setstring($order);
  $ascdesc=$db->setstring($ascdesc);

  $result=array();
  
  // get all category children
  $categories=array();
  if($selectedcat!=1)
  {
    $pendingcategories=array(0 => $selectedcat);
    while(count($pendingcategories))
    {
      $selectedcat=array_pop($pendingcategories);
      array_push($categories,$selectedcat);
      $pendingcategories=array_merge($pendingcategories,getcategorychildren($selectedcat));
    }
  }
  
  $query="SELECT DISTINCTROW art.page_id FROM ";
  $query.=ARTICLES_TABLE." AS art, ";
  $query.=PAGES_TABLE." AS page";
  // all parameters
  if(count($categories)>0 && $from!="all" && $to!="all")
  {
    $query.=", ".PAGECATS_TABLE." AS cat";
    $query.=" WHERE cat.page_id = art.page_id";
    $query.=" AND cat.category IN (";
    for($i=0;$i<count($categories);$i++)
    {
      $query.="'".$categories[$i]."',";
    }
    $query=substr($query,0,strlen($query)-1);
    $query.=")";
    $query.=" AND art.year BETWEEN '".$from."' AND '".$to."' AND";
  }
  // all years, filtered for categories
  elseif(count($categories)>0)
  {
    $query.=", ".PAGECATS_TABLE." AS cat";
    $query.=" WHERE cat.page_id = art.page_id";
    $query.=" AND cat.category IN (";
    for($i=0;$i<count($categories);$i++)
    {
      $query.="'".$categories[$i]."',";
    }
    $query=substr($query,0,strlen($query)-1);
    $query.=") AND";
  }
  // only years
  elseif($from!="all" && $to!="all")
  {
    $query.=" WHERE art.year BETWEEN '".$from."' AND '".$to."' AND ";
  }
  else
  {
    $query.=" WHERE ";
  }

  $query.=" page.page_id = art.page_id AND ";
  
  if(!$showhidden)
  {
    $query.=" page.ispublished = '1' AND ";
  }
    
  // get pages to search
  if($includesubs)
  {
    $pages=getsubpagesforpagetype($page, "articlemenu");
  }
  else
  {
    $pages=array(0 => $page);
  }
  $query.="page.parent_id IN (";
  for($i=0;$i<count($pages);$i++)
  {
    $query.="'".$pages[$i]."',";
  }
  $query=substr($query,0,strlen($query)-1);
  $query.=")";
  
  if($order)
  {
    $query.=" ORDER BY ";
    if($order=="title") $query.="page.title_page ";
    elseif($order=="author") $query.="art.article_author ";
    elseif($order=="date") $query.="art.year, art.month, art.day ";
    elseif($order=="source") $query.="art.source ";
    elseif($order=="editdate") $query.="page.editdate ";
    $query.=$ascdesc;
  }
  
//  print($query.'<p>');

  if($query)
  {
    $sql=$db->singlequery($query);
  }
  if($sql)
  {
    // get column
    while($row=mysql_fetch_row($sql))
    {
      if(displaylinksforpagearray($sid,$row[0]))
      {
        array_push($result,$row[0]);
      }
    }
  }
  return $result;
}

//
// todo: refine match all
//
function searcharticles($search,$page,$all,$showhidden=false)
{
	global $db;
  $page=$db->setinteger($page);

  $result=array();

  // get articles to search
  $pagestosearch=array();

  $menupages=getsubpagesforpagetype($page, "articlemenu");
  $query="SELECT DISTINCTROW page.page_id FROM ";
  $query.=PAGES_TABLE." AS page WHERE ";
  $query.="page.parent_id IN (";
  for($i=0;$i<count($menupages);$i++)
  {
    $query.="'".$menupages[$i]."',";
  }
  $query=substr($query,0,strlen($query)-1);
  $query.=")";
//  print($query);
  $sql=$db->singlequery($query);

  if($sql)
  {
    // get column
    while($row=mysql_fetch_row($sql))
    {
      array_push($pagestosearch,$row[0]);
    }
  }
//  print('testing'.$pagestosearch);


  // search articles
  $query="SELECT page.page_id FROM ";
  $query.=ARTICLES_TABLE." AS art, ";
  $query.=ARTICLESECTIONS_TABLE." AS sec, ";
  $query.=PAGES_TABLE." AS page WHERE ";

  $query.="page.page_id IN (";
  for($i=0;$i<count($pagestosearch);$i++)
  {
    $query.="'".$pagestosearch[$i]."',";
  }
  $query=substr($query,0,strlen($query)-1);
  $query.=")";

  if(!$showhidden)
  {
    $query.=" AND page.ispublished = '1'";
  }

  // search sections
  $query.=" AND (MATCH(sec.text) AGAINST('".str_replace(" ",",",trim($search))."')";
  $query.=" AND page.page_id = sec.article_id";
  // search synopses
  $query.=" OR MATCH(art.synopsis) AGAINST('".str_replace(" ",",",trim($search))."')";
  $query.=" AND page.page_id = art.page_id";
  // search author
  $query.=" OR MATCH(art.article_author) AGAINST('".str_replace(" ",",",trim($search))."')";
  $query.=" AND page.page_id = art.page_id";
  // search source
  $query.=" OR MATCH(art.source) AGAINST('".str_replace(" ",",",trim($search))."')";
  $query.=" AND page.page_id = art.page_id";
  //search title
  $query.=" OR MATCH(page.title_page) AGAINST('".str_replace(" ",",",trim($search))."')";
  $query.=")";

  print($query);
  $sql=$db->singlequery($query);
  if($sql)
  {
    // get column
    while($row=mysql_fetch_row($sql))
    {
      array_push($result,$row[0]);
    }
  }
  
// doto: endless loop?
  // from the search result, kick out entries that don't match all words
/*  if($all)
  {
    $allresult=array();
    for($i=0;$i<count($result);$i++)
    {
      // get a concatenated string
      $query="SELECT sec.text FROM ";
//      $query.=ARTICLES_TABLE." AS art, ";
      $query.=ARTICLESECTIONS_TABLE." AS sec WHERE ";
      $query.="sec.article_id ='".$result[$i]."'";

      $sql=singlequery($query);
      $entry=array();
      if($sql)
      {
        // get column
        while($row=mysql_fetch_row($sql))
        {
          array_push($entry,$row[0]);
        }
      }
      $concat=implode(" ",$entry);

      $query="SELECT CONCAT(art.synopsis, art.article_author, art.source, pages.title_page) FROM ";
      $query.=PAGES_TABLE." AS pages, ";
      $query.=ARTICLES_TABLE." AS art WHERE ";
      $query.="art.page_id ='".$result[$i]."'";
      $query.=" AND pages.page_id ='".$result[$i]."'";

//      print('<p>'.$query.'<p>');

      $sql=$db->singlequery($query);
      if($sql)
      {
        // get column
        $row=mysql_fetch_row($sql);
        $concat.=$row[0];
      }
      
      // search concatenated string for all terms
      $concat=strtolower(text2html($concat));
      $keys=explode(" ",$search);
      $found=true;
      for($j=0;$j<count($keys) && $found;$j++)
      {
        if(strlen($keys[$j])>3)
        {
          if(!strpos($concat,strtolower(text2html($keys[$j])))) $found=false;
        }
      }
      if($found) array_push($allresult,$result[$i]);
    }
  }
//  print('<p>'.count($result).'<p>');
//  print_r($allresult);
  if($all)
  {
    return $allresult;
  }
*/
//  else
   return $result;

  
  
//  print('<p>'.count($result));
//  return $result;
}
?>