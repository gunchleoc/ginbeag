<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"functions"));

/*include_once($projectroot."functions/db.php");
include_once($projectroot."functions/publicsessions.php");
include_once($projectroot."functions/treefunctions.php");
include_once($projectroot ."config.php");*/

################################################################################
##                                                                            ##
##        Functions                                                           ##
##                                                                            ##
################################################################################

// ***************************  article ************************************* //

//
//
//
function getarticlepagecontents($page_id)
{
  return getrowbykey(ARTICLES_TABLE, "page_id", setinteger($page_id));
}

//
//
//
function getarticlesynopsis($page_id)
{
  return getdbelement("synopsis", ARTICLES_TABLE, "page_id", setinteger($page_id));
}

//
//
//
function getarticlepageoverview($page_id)
{
  $fieldnames = array(0 => 'article_author', 1=> 'source', 2=>'day', 3=>'month', 4=>'year');
  return getrowbykey(ARTICLES_TABLE, "page_id", setinteger($page_id), $fieldnames);
}


//
//
//
function numberofarticlepages($page_id)
{
  return getdbelement("numberofpages",ARTICLES_TABLE, "page_id", setinteger($page_id));
}

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
function getlastarticlesection($article_id,$pagenumber)
{
  $condition="article_id ='".setinteger($article_id)."' and pagenumber ='".setinteger($pagenumber)."'";
  return getmax("sectionnumber",ARTICLESECTIONS_TABLE, $condition);
}


//
// the section number on the page. Not the primary key!!!
//
function getarticlesections($page_id, $pagenumber)
{
  $condition= "(article_id='".setinteger($page_id)."'";
  $condition.= " AND pagenumber='".setinteger($pagenumber)."')";
  
  return getorderedcolumn("articlesection_id",ARTICLESECTIONS_TABLE, $condition, "sectionnumber", "ASC");
}



//
// for printview
//
function getallarticlesections($page_id)
{
  $condition= "article_id='".setinteger($page_id)."'";
  return getorderedcolumn("articlesection_id",ARTICLESECTIONS_TABLE, $condition, "sectionnumber", "ASC");
}

//
//
//
function getarticlesectioncontents($section_id)
{
  return getrowbykey(ARTICLESECTIONS_TABLE, "articlesection_id", setinteger($section_id));
}

//
//
//
function getarticlesectiontext($section_id)
{
  return getdbelement("text",ARTICLESECTIONS_TABLE, "articlesection_id", setinteger($section_id));
}

//
//
//
function getarticlesectionnumber($section_id)
{
  return getdbelement("sectionnumber",ARTICLESECTIONS_TABLE, "articlesection_id", setinteger($section_id));
}

// ***************************  articlemenu ********************************* //

//
//
//
function getfilteredarticles($page,$selectedcat,$from,$to,$order,$ascdesc,$includesubs,$showhidden=false)
{
  $page=setinteger($page);
  $selectedcat=setinteger($selectedcat);
  $from=setinteger($from);
  $to=setinteger($to);
  $order=setstring($order);
  $ascdesc=setstring($ascdesc);

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
    $sql=singlequery($query);
  }
  if($sql)
  {
    // get column
    while($row=mysql_fetch_row($sql))
    {
      if(displaylinksforpagearray($row[0]))
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
  $page=setinteger($page);

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
  $sql=singlequery($query);

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
  $sql=singlequery($query);
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

      $sql=singlequery($query);
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

//
//
//
function getarticleoftheday()
{
  $date=date("Y-m-d",strtotime('now'));
//  print($date);

  $aotd=getdbelement("aotd_id",ARTICLEOFTHEDAY_TABLE, "aotd_date", $date);
//  if(!ispublished($aotd) || permissionrefused($aotd))
  if(!ispublished($aotd) || permissionrefused($aotd) || ispagerestricted($aotd))
  {
//    print(!ispublished($aotd)." - ".permissionrefused($aotd)." - ".ispagerestricted($aotd)."<p>");
    $query="DELETE FROM ".ARTICLEOFTHEDAY_TABLE." where aotd_date= '".$date."';";
    $sql=singlequery($query);
    $aotd=0;
  }
//  print("aotd - ".$aotd."<p>");
  if(!$aotd)
  {
    // get pages to search
    $pagestosearch=explode(",",getproperty("Article of the Day Start Pages"));
    $count=count($pagestosearch);
    $pages=array();
    for($i=0;$i<$count;$i++)
    {
      // test for nonsense in the site properties
//      if(getpagetype($pagestosearch[$i])==="articlemenu")
      if(getpagetype($pagestosearch[$i])==="articlemenu" && !ispagerestricted($pagestosearch[$i]))
      {
        $pages=array_merge($pages,getsubpagesforpagetype($pagestosearch[$i],"articlemenu"));
      }
    }
    // there was a valid start page, so generate
    if(count($pages))
    {
      $query="SELECT DISTINCTROW page.page_id FROM ";
      $query.=PAGES_TABLE." AS page WHERE ";
      $query.="page.pagetype = 'article' AND ";
      $query.="page.parent_id IN (";
      for($i=0;$i<count($pages);$i++)
      {
        $query.="'".$pages[$i]."',";
      }
      $query=substr($query,0,strlen($query)-1);
      $query.=") AND page.ispublished = '1'";
      $query.=" AND page.permission <> '".PERMISSION_REFUSED."'";

//      print($query);
      $sql=singlequery($query);
      $pagesforselection=array();
      if($sql)
      {
        // get column
        while($row=mysql_fetch_row($sql))
        {
//    print("row - ".$row[0]."<p>");

          if(!ispagerestricted($row[0]))
          {
//    print(ispublished($row[0])." - ".!permissionrefused($row[0])." - ".!ispagerestricted($row[0])."<p>");
//    print("push - ".$row[0]."<p>");
            array_push($pagesforselection,$row[0]);
          }
        }
      }
//      print("hallo");
      if(count($pagesforselection)>0)
      {
        list($usec, $sec) = explode(' ', microtime());
        $random= ((float) $sec + ((float) $usec * 100000)) % count($pagesforselection);

        $aotd=$pagesforselection[$random];
        if($aotd)
        {
          $query="insert into ";
          $query.=(ARTICLEOFTHEDAY_TABLE." values(");
          $query.="'".$date."',";
          $query.="'".$aotd."'";
          $query.=");";
//    print($query);
          $sql=singlequery($query);
        }
      }
    }
  }
  return $aotd;

}


// *************************** external ************************************** //


//
//
//
function getexternallink($page_id)
{
  return getdbelement("link",EXTERNALS_TABLE, "page_id", setinteger($page_id));
}


// *************************** gallery ************************************** //

//
//
//
function getgalleryintro($page_id)
{
  return getdbelement("introtext",GALLERIES_TABLE, "page_id", setinteger($page_id));
}

//
//
//
function getgalleryimage($galleryitem_id)
{
  return getdbelement("image_filename",GALLERYITEMS_TABLE, "galleryitem_id",setinteger($galleryitem_id));
}

//
//
//
function getgalleryimages($page_id)
{
  return getorderedcolumn("galleryitem_id",GALLERYITEMS_TABLE, "page_id='".setinteger($page_id)."'", "position", "ASC");
}

//
//
//
function getgalleryimagefilenames($page_id, $showrefused,$showhidden=false)
{
  $result=array();
  
  if($showrefused || $showhidden)
  {
    $result=getorderedcolumn("image_filename",GALLERYITEMS_TABLE, "page_id='".setinteger($page_id)."'", "position", "ASC");
  }
  else
  {
    $query="select gallery.image_filename from ".GALLERYITEMS_TABLE." as gallery, ".IMAGES_TABLE." as images where ";
    $query.="gallery.page_id='".setinteger($page_id)."' AND gallery.image_filename = images.image_filename AND images.permission <> ".PERMISSION_REFUSED." order by position ASC";
    $sql=singlequery($query);
    if($sql)
    {
      while($row=mysql_fetch_row($sql))
      {
        array_push($result,$row[0]);
      }
    }
  }
  return $result;
}

//
//
//
function getlastgalleryimageposition($page_id)
{
  return getmax("position", GALLERYITEMS_TABLE, "page_id ='".setinteger($page_id)."'");
}

// *************************** linklist ************************************** //


//
//
//
function getlinklistintro($page_id)
{
  return getdbelement("introtext",LINKLISTS_TABLE, "page_id", setinteger($page_id));
}

//
//
//
function getlinklistimage($page_id)
{
  return getdbelement("image",LINKLISTS_TABLE, "page_id",setinteger($page_id));
}

//
//
//
function getlinklistitems($page_id)
{
  return getorderedcolumn("link_id",LINKS_TABLE, "page_id='".setinteger($page_id)."'", "position", "ASC");
}

//
//
//
function getlinktitle($link_id)
{
  return getdbelement("title",LINKS_TABLE, "link_id", setinteger($link_id));
}

//
//
//
function getlinkcontents($link_id)
{
  return getrowbykey(LINKS_TABLE, "link_id", setinteger($link_id));
}

//
//
//
function getlastlinkposition($page_id)
{
  return getmax("position",LINKS_TABLE, "page_id ='".setinteger($page_id)."'");
}

//
//
//
function getlinkdescription($link_id)
{
  return getdbelement("description",LINKS_TABLE, "link_id", setinteger($link_id));
}

// *************************** menu ***************************************** //


//
//
//
function getmenucontents($page_id)
{
  return getrowbykey(MENUS_TABLE, "page_id", setinteger($page_id));
}

//
//
//
function getmenuintro($page_id)
{
  return getdbelement("introtext",MENUS_TABLE, "page_id", setinteger($page_id));
}


//
//
//
function getmenunavigatordepth($page_id)
{
  return getdbelement("navigatordepth",MENUS_TABLE, "page_id", setinteger($page_id));
}

// *************************** newsitem ************************************* //


//
//
//
function getpublishednewsitems($page_id,$number,$offset)
{
  $page_id=setinteger($page_id);
  if(!$offset) $offset=0;
  if(!$number>0) $number=1;
  $condition="page_id='".$page_id."' AND ispublished='1'";
  if(displaynewestnewsitemfirst($page_id))
  {
    $order="DESC";
  }
  else
  {
    $order="ASC";
  }
  return getorderedcolumnlimit("newsitem_id",NEWSITEMS_TABLE,$condition, "date", setinteger($offset), setinteger($number),$order);
}



//
//
//
function displaynewestnewsitemfirst($page_id)
{
  return getdbelement("shownewestfirst",NEWS_TABLE, "page_id", setinteger($page_id));
}

//
//
//
function getnewsitemoffset($page_id,$number,$newsitem,$showhidden=false)
{
  if(!$number>0) $number=1;
  $date=getdbelement("date",NEWSITEMS_TABLE, "newsitem_id", setinteger($newsitem));
  $condition="page_id='".setinteger($page_id)."'";
  if(!$showhidden) $condition.=" AND ispublished='1'";
  $condition.=" AND date > '".$date."'";
  $noofelements = countelementscondition("newsitem_id",NEWSITEMS_TABLE,$condition);
  return floor($noofelements/$number);
}

//
//
//
function countpublishednewsitems($page_id)
{
  $condition="page_id='".setinteger($page_id)."' AND ispublished='1'";
  return countelementscondition("newsitem_id",NEWSITEMS_TABLE, $condition);
}


//
//
//
function getnewsitemcontents($newsitem_id)
{
  return getrowbykey(NEWSITEMS_TABLE, "newsitem_id", setinteger($newsitem_id));
}

//
// returns a date array
//
function getnewsitemdate($newsitem_id)
{
  $date =getdbelement("date",NEWSITEMS_TABLE, "newsitem_id",setinteger($newsitem_id));
  return @getdate(strtotime($date));
}

//
//
//
function getoldestnewsitemdate($page_id)
{
  $date=getmin("date",NEWSITEMS_TABLE, "page_id",setinteger($page_id));
  return @getdate(strtotime($date));
}

//
//
//
function getnewestnewsitemdate($page_id)
{
  $date=getmax("date", NEWSITEMS_TABLE, "page_id",setinteger($page_id));
  return @getdate(strtotime($date));
}

//
//
//
function getnewsitemsynopsistext($newsitem_id)
{
  return getdbelement("synopsis", NEWSITEMS_TABLE, "newsitem_id", setinteger($newsitem_id));
}

//
//
//
function getnewsitemsynopsisimageids($newsitem_id)
{
  $condition= "newsitem_id='".setinteger($newsitem_id)."'";
  return getorderedcolumn("newsitemimage_id",NEWSITEMSYNIMG_TABLE, $condition, "position", "ASC");
}

//
//
//
function getnewsitemsynopsisimage($newsitemimage_id)
{
  return getdbelement("image_filename",NEWSITEMSYNIMG_TABLE, "newsitemimage_id", setinteger($newsitemimage_id));
}


//
//
//
function getnewsitemsynopsisimages($newsitem_id)
{
  $condition= "newsitem_id='".setinteger($newsitem_id)."'";
  return getorderedcolumn("image_filename",NEWSITEMSYNIMG_TABLE, $condition, "position", "ASC");
}

//
//
//
function getnewsitemsections($newsitem_id)
{
  $condition= "newsitem_id='".setinteger($newsitem_id)."'";
  return getorderedcolumn("newsitemsection_id",NEWSITEMSECTIONS_TABLE, $condition, "sectionnumber", "ASC");
}

//
//
//
function getnewsitemsectioncontents($section_id)
{
  return getrowbykey(NEWSITEMSECTIONS_TABLE, "newsitemsection_id", setinteger($section_id));
}

//
//
//
function getnewsitemsectiontext($section_id)
{
  return getdbelement("text",NEWSITEMSECTIONS_TABLE, "newsitemsection_id", setinteger($section_id));
}


//
//
//
function getnewsitemsectionimage($section_id)
{
  return getdbelement("sectionimage",NEWSITEMSECTIONS_TABLE, "newsitemsection_id", setinteger($section_id));
}

//
//
//
function getnewsitemsectionnumber($section_id)
{
  return getdbelement("sectionnumber",NEWSITEMSECTIONS_TABLE, "newsitemsection_id", setinteger($section_id));
}

//
//
//
function isnewsitempublished($newsitem_id)
{
  return getdbelement("ispublished",NEWSITEMS_TABLE, "newsitem_id", setinteger($newsitem_id));
}


//
// returns array of copyright, imagecopyright, permission
// permission is one of the constants PERMISSION_GRANTED, NO_PERMISSION, PERMISSION_REFUSED
//
function getnewsitemcopyright($newsitem_id)
{
  $fieldnames = array(0 => 'copyright', 1=> 'image_copyright', 2=>'permission');
  return getrowbykey(NEWSITEMS_TABLE, "newsitem_id", setinteger($newsitem_id), $fieldnames);
}

//
//
//
function getnewsitempermission($newsitem_id)
{
  return getdbelement("permission",NEWSITEMS_TABLE, "newsitem_id", setinteger($newsitem_id));
}


//
//
//
function newsitempermissionrefused($newsitem_id)
{
  $permission = getnewsitempermission($newsitem_id);
  return $permission==PERMISSION_REFUSED;
}

//
//
//
function getlastnewsitemsection($newsitem_id)
{
  return getmax("sectionnumber",NEWSITEMSECTIONS_TABLE,"newsitem_id ='".setinteger($newsitem_id)."'");
}




//
//
//
function getfilterednewsitems($page,$selectedcat,$from,$to,$order,$ascdesc,$newsitemsperpage,$offset)
{
  $page=setinteger($page);
  $selectedcat=setinteger($selectedcat);
  $order=setstring($order);
  $ascdesc=setstring($ascdesc);
  $offset=setinteger($offset);
  
  $months[1]='January';
  $months[2]='February';
  $months[3]='March';
  $months[4]='April';
  $months[5]='May';
  $months[6]='June';
  $months[7]='July';
  $months[8]='August';
  $months[9]='September';
  $months[10]='October';
  $months[11]='November';
  $months[12]='December';
  
  $date=$from["day"]." ".$months[$from["month"]]." ".$from["year"];
  $fromdate=date(DATETIMEFORMAT, strtotime($date));
  
  $date=$to["day"]." ".$months[$to["month"]]." ".$to["year"]." 23:59:59";
  $todate=date(DATETIMEFORMAT, strtotime($date));

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
  
  $query="SELECT DISTINCTROW items.newsitem_id FROM ";
  $query.=NEWSITEMS_TABLE." AS items ";
  // all parameters
  if(count($categories)>0)
  {
    $query.=", ".NEWSITEMCATS_TABLE." AS cat";
    $query.=" WHERE cat.newsitem_id = items.newsitem_id";
    $query.=" AND cat.category IN (";
    for($i=0;$i<count($categories);$i++)
    {
      $query.="'".$categories[$i]."',";
    }
    $query=substr($query,0,strlen($query)-1);
    $query.=")";
    $query.=" AND items.date BETWEEN '".$fromdate."' AND '".$todate."'";
    $query.=" AND ";
  }
  // only years
  else
  {
    $query.=" WHERE items.date BETWEEN '".$fromdate."' AND '".$todate."'";
    $query.=" AND ";
  }

  // get pages to search
  $query.="items.page_id ='".$page."'";
  $query.=" AND items.ispublished = '1'";
    
  if($order)
  {
    $query.=" ORDER BY ";
    if($order=="title") $query.="items.title ";
    elseif($order=="date") $query.="date ";
    elseif($order=="source") $query.="items.source ";
    $query.=$ascdesc;
  }
  if($newsitemsperpage>0)
  {
    $query.=" limit ".$offset.", ".$newsitemsperpage;
  }

//  print($query.'<p>');

  if($query)
  {
    $sql=singlequery($query);
  }
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
function searchnewsitemtitles($search,$page,$showhidden=false)
{
  $query="SELECT DISTINCTROW newsitem_id FROM ".NEWSITEMS_TABLE;
  $query.=" WHERE page_id = '".setinteger($page)."'";
  $query.=" AND title like '%".setstring(trim($search))."%'";
  
//  $query.=" AND MATCH(title) AGAINST('".str_replace(" ",",",trim(setstring($search)))."'))";

  $sql=singlequery($query);
  $result=array();
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
// todo: refine match all
//
function searchnewsitems($search,$page,$all,$showhidden=false)
{
  $page=setinteger($page);

  $result=array();

  // search all subpages as well
  $pagestosearch=array();

  $newspages=getsubpagesforpagetype($page, "news");
  
  // get pages to search
  $query="SELECT DISTINCTROW page.page_id FROM ";
  $query.=PAGES_TABLE." AS page WHERE ";
  $query.="page.page_id IN (";
  for($i=0;$i<count($newspages);$i++)
  {
    $query.="'".$newspages[$i]."',";
  }
  $query=substr($query,0,strlen($query)-1);
  $query.=")";

  $sql=singlequery($query);
  if($sql)
  {
    // get column
    while($row=mysql_fetch_row($sql))
    {
      array_push($pagestosearch,$row[0]);
    }
  }

  // search news
  $query="SELECT items.newsitem_id FROM ";
  $query.=NEWSITEMS_TABLE." AS items, ";
  $query.=NEWSITEMSECTIONS_TABLE." AS sec, ";
  $query.=PAGES_TABLE." AS page WHERE ";

  $query.="page.page_id IN (";
  for($i=0;$i<count($pagestosearch);$i++)
  {
    $query.="'".$pagestosearch[$i]."',";
  }
  $query=substr($query,0,strlen($query)-1);
  $query.=")";

  $query.=" AND page.ispublished = '1'";
  $query.=" AND items.ispublished = '1'";
  $query.=" AND page.page_id = items.page_id";

  // search sections
  $query.=" AND ((items.newsitem_id = sec.newsitem_id";
  $query.=" AND MATCH(sec.text) AGAINST('".str_replace(" ",",",trim($search))."'))";
  // search synopses
  $query.=" OR MATCH(items.synopsis) AGAINST('".str_replace(" ",",",trim($search))."')";
  // search titles
  $query.=" OR MATCH(items.title) AGAINST('".str_replace(" ",",",trim($search))."'))";

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
  
  // from the search result, kick out entries that don't match all words
  if($all)
  {
    $allresult=array();
    for($i=0;$i<count($result);$i++)
    {
      // get a concatenated string
      $query="SELECT sec.text FROM ";
      $query.=NEWSITEMSECTIONS_TABLE." AS sec WHERE ";
      $query.="sec.newsitem_id ='".$result[$i]."'";

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
      
      $query="SELECT CONCAT(items.synopsis, items.title) FROM ";
      $query.=NEWSITEMS_TABLE." AS items WHERE ";
      $query.="items.newsitem_id ='".$result[$i]."'";

//      print('<p>'.$query.'<p>');

      $sql=singlequery($query);
      if($sql)
      {
        // get column
        $row=mysql_fetch_row($sql);
        $concat.=$row[0];
      }

      // search concatenated string for all terms
      $concat=strtolower(text2html($concat));
      $concat=str_replace("[quote]","",$concat);
      $concat=str_replace("[unquote]","",$concat);
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
//  print_r($result);
  if($all)
  {
    return $allresult;
  }
  else return $result;
}



//
//
//
function getpagetypes()
{
  $result=array();
  
  $keys=getorderedcolumn("type_key",PAGETYPES_TABLE, "1", "type_key", "ASC");
  $values=getorderedcolumn("type_description",PAGETYPES_TABLE, "1", "type_key", "ASC");
  for($i=0;$i<count($keys);$i++)
  {
    $result[$keys[$i]]=$values[$i];
  }
  return $result;
}


//
//
//
function getpagetype($page_id)
{
  return getdbelement("pagetype",PAGES_TABLE, "page_id", setinteger($page_id));
}

//
//
//
function getpagetitle($page_id)
{
  return getdbelement("title_page",PAGES_TABLE, "page_id", setinteger($page_id));
}

//
//
//
function getnavtitle($page_id)
{
  return getdbelement("title_navigator",PAGES_TABLE, "page_id", setinteger($page_id));
}

//
//
//
function getnavposition($page_id)
{
  return getdbelement("position_navigator",PAGES_TABLE, "page_id", setinteger($page_id));
}

//
//
//
function getpageeditor($page_id)
{
  return getdbelement("editor_id",PAGES_TABLE, "page_id", setinteger($page_id));
}

//
// returns array of copyright, imagecopyright, permission
// permission is one of the constants PERMISSION_GRANTED, NO_PERMISSION, PERMISSION_REFUSED
//
function getcopyright($page_id)
{
  $fieldnames = array(0 => 'copyright', 1=> 'image_copyright', 2=>'permission');
  return getrowbykey(PAGES_TABLE, "page_id", setinteger($page_id), $fieldnames);
}

//
// permission is one of the constants PERMISSION_GRANTED, NO_PERMISSION, PERMISSION_REFUSED
//
function getpermission($page_id)
{
  return getdbelement("permission",PAGES_TABLE, "page_id", $page_id);
}


//
//
//
function permissionrefused($filename)
{
  $refused= getpermission($filename);
  return $refused==PERMISSION_REFUSED;
}

//
//
//
function geteditdate($page_id)
{
  return getdbelement("editdate", PAGES_TABLE, "page_id", setinteger($page_id));
}

//
//
//
function getparent($page_id)
{
  return getdbelement("parent_id",PAGES_TABLE, "page_id", setinteger($page_id));
}


//
//
//
function getsisters($page_id,$ascdesc="ASC")
{
  return getorderedcolumn("page_id",PAGES_TABLE, "parent_id='".getparent(setinteger($page_id))."'", "position_navigator", setstring($ascdesc));
}

//
//
//
function getchildren($page_id,$ascdesc="ASC")
{
  return getorderedcolumn("page_id",PAGES_TABLE, "parent_id='".setinteger($page_id)."'", "position_navigator", setstring($ascdesc));
}

//
//
//
function ispublished($page_id)
{
  return getdbelement("ispublished",PAGES_TABLE, "page_id", setinteger($page_id));
}


//
//
//
function pageexists($page_id)
{
  $foundpage = getdbelement("page_id",PAGES_TABLE, "page_id", setinteger($page_id));
  return $foundpage>0 && $foundpage == $page_id;
}


//
//
//
function isrootpage($page_id)
{
  return getparent($page_id)==0;
}

//
//
//
function getrootpages()
{
  return getorderedcolumn("page_id",PAGES_TABLE, "parent_id='0'", "position_navigator", "ASC");
}


//
//
//
function getallpages($fields)
{
  return getmultiplefields(PAGES_TABLE, "page_id","1", $fields, $orderby="page_id");
}


//
//
//
function ispagerestricted($page_id)
{
  return getdbelement("page_id",RESTRICTEDPAGES_TABLE, "page_id", setinteger($page_id));
}


//
//
//
function isthisexactpagerestricted($page_id)
{
  return getdbelement("page_id",RESTRICTEDPAGES_TABLE, "masterpage", setinteger($page_id));
}

//
//
//
function showpermissionrefusedimages($page_id)
{
  $showrefused = getdbelement("showpermissionrefusedimages",PAGES_TABLE, "page_id", setinteger($page_id));
  if($showrefused)
  {
    $showrefused = $showrefused && ispagerestricted($page_id);
  }
  return $showrefused;
}

//
//
//
function mayshowimage($image,$page_id,$showhidden)
{
  return !imagepermissionrefused($image) || $showhidden || (ispagerestricted($page) && showpermissionrefusedimages($page));
}


//
//
//
function displaylinksforpage($page_id)
{
  return displaylinksforpagearray($page_id);
}

//
//
//
function getsubpagesforpagetype($page_id, $pagetype)
{
  $result=array();
  $searchme=array(0 => setinteger($page_id));
  $pagetype=setstring($pagetype);
  while(count($searchme))
  {
    $currentpage=array_shift($searchme);
    if(getpagetype($currentpage)===$pagetype)
    {
      array_push($result, $currentpage);
    }
    $condition= "parent_id='".$currentpage."' AND pagetype = '".$pagetype."'";
    $submenus= getorderedcolumn("page_id",PAGES_TABLE,$condition, "position_navigator", "ASC");
    $searchme=array_merge($searchme,$submenus);
  }
  return $result;
}

//
//
//
function hasrssfeed($page_id)
{
  return getdbelement("page_id",RSS_TABLE, "page_id", setinteger($page_id));
}

//
//
//
function updatepagestats($page)
{
  if($page>0)
  {
    $year=date("Y",strtotime('now'));
    $month=date("m",strtotime('now'));

    $query="SELECT stats_id, viewcount FROM ".MONTHLYPAGESTATS_TABLE." WHERE ";
    $query.="year='".$year."' AND month='".$month."' AND page_id='".setinteger($page)."'";
  
//  print($query);
    $sql=singlequery($query);
    $stats=array();
    if($sql)
    {
      // get column
      while($row=mysql_fetch_row($sql))
      {
        array_push($stats,array($row[0],$row[1]));
      }
    }
    if(count($stats))
    {
      $query="UPDATE ".MONTHLYPAGESTATS_TABLE." SET viewcount='".($stats[0][1]+1)."' WHERE stats_id='".$stats[0][0]."'";
    }
    else
    {
      $query="INSERT INTO ".MONTHLYPAGESTATS_TABLE." values('0','".setinteger($page)."','1','".$month."','".$year."')";
    }
//  print($query);
    $sql=singlequery($query);
  }
}
?>
