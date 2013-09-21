<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/dbmod.php");
include_once($projectroot."functions/users.php");
include_once($projectroot."functions/pages.php");

################################################################################
##                                                                            ##
##        Functions                                                           ##
##                                                                            ##
################################################################################

// *************************** edit article ********************************* //

//
//
//
function updatearticlesynopsis($page_id, $synopsis)
{
  return updatefield(ARTICLES_TABLE,"synopsis",stripsid($synopsis) ,"page_id='".setinteger($page_id)."'");
}

//
//
//
function updatearticlesource($page_id,$author,$location,$day,$month,$year,$source,$sourcelink)
{
  $page_id=setinteger($page_id);
  
  if(strlen($year)!=4) $year="0000";
  updatefield(ARTICLES_TABLE,"article_author",setstring($author),"page_id='".$page_id."'");
  updatefield(ARTICLES_TABLE,"location",setstring($location),"page_id='".$page_id."'");
  updatefield(ARTICLES_TABLE,"day",setinteger($day),"page_id='".$page_id."'");
  updatefield(ARTICLES_TABLE,"month",setinteger($month),"page_id='".$page_id."'");
  updatefield(ARTICLES_TABLE,"year",setinteger($year),"page_id='".$page_id."'");
  updatefield(ARTICLES_TABLE,"source",setstring($source),"page_id='".$page_id."'");
  updatefield(ARTICLES_TABLE,"sourcelink",stripsid($sourcelink),"page_id='".$page_id."'");
}

//
//
//
function updatearticlesynopsisimage($page_id,$imagefilename,$imagealign,$imagevalign)
{
  $page_id=setinteger($page_id);
  
  updatefield(ARTICLES_TABLE,"synopsisimage",setstring(basename($imagefilename)),"page_id='".$page_id."'");
  updatefield(ARTICLES_TABLE,"imagealign",setstring($imagealign),"page_id='".$page_id."'");
  updatefield(ARTICLES_TABLE,"imagevalign",setstring($imagevalign),"page_id='".$page_id."'");
}


//
//
//
function addarticlepage($page_id)
{
  $numberofpages=numberofarticlepages(setinteger($page_id));
  if(getlastarticlesection($page_id,$numberofpages))
  {
    return updatefield(ARTICLES_TABLE,"numberofpages",$numberofpages+1,"page_id='".$page_id."'");
  }
  else
  {
    return false;
  }
}


//
//
//
function deletelastarticlepage($article_id)
{
  $numberofpages=numberofarticlepages(setinteger($article_id));

  if(!getlastarticlesection($article_id,$numberofpages))
  {
    return updatefield(ARTICLES_TABLE,"numberofpages",$numberofpages-1,"page_id='".$article_id."'");
  }
  else
  {
    return false;
  }
}

//
//
//
function addarticlesection($article_id,$pagenumber)
{
  $article_id=setinteger($article_id);
  $pagenumber=setinteger($pagenumber);

  $lastsection=getlastarticlesection($article_id,$pagenumber);
  
  $values=array();
  $values[]=0;
  $values[]=$article_id;
  $values[]=$pagenumber;
  $values[]=$lastsection+1;
  $values[]="";
  $values[]="";
  $values[]="";
  $values[]="left";
  $values[]="top";
  return insertentry(ARTICLESECTIONS_TABLE,$values);
}


//
//
//
function updatearticlesectionimage($section_id,$imagefilename,$imagealign,$imagevalign)
{
  $section_id=setinteger($section_id);
  
  updatefield(ARTICLESECTIONS_TABLE,"sectionimage",setstring(basename($imagefilename)),"articlesection_id='".$section_id."'");
  updatefield(ARTICLESECTIONS_TABLE,"imagealign",setstring($imagealign),"articlesection_id='".$section_id."'");
  updatefield(ARTICLESECTIONS_TABLE,"imagevalign",setstring($imagevalign),"articlesection_id='".$section_id."'");
}

//
//
//
function updatearticlesectiontitle($section_id,$sectiontitle)
{
  updatefield(ARTICLESECTIONS_TABLE,"sectiontitle",setstring($sectiontitle),"articlesection_id='".setinteger($section_id)."'");
}

//
//
//
function updatearticlesectiontext($section_id,$text)
{
  updatefield(ARTICLESECTIONS_TABLE,"text",stripsid($text),"articlesection_id='".setinteger($section_id)."'");
}


//
//
//
function deletearticlesection($articlesection_id)
{
  deleteentry(ARTICLESECTIONS_TABLE,"articlesection_id ='".setinteger($articlesection_id)."'");
}


//
// returns the articlepage the section will be in after the move
//
function movearticlesection($section_id,$pagenumber,$direction)
{
  $section_id=setinteger($section_id);
  $page_id=getdbelement("article_id",ARTICLESECTIONS_TABLE, "articlesection_id", $section_id);
  $pagenumber=setinteger($pagenumber);
  $navpos=getarticlesectionnumber($section_id);
  
  $result=$pagenumber;
  
  // move section to next articlepage
  if($direction==="down" && $navpos==getlastarticlesection($page_id,$pagenumber))
  {
    // prepare page
    $noofpages=numberofarticlepages($page_id);
    $newpage=$pagenumber+1;
    $result=$newpage;
    
    if($noofpages<$newpage)
    {
      addarticlepage($page_id);
    }
    else
    {
      // make room
      $sisterids=getorderedcolumn("articlesection_id",ARTICLESECTIONS_TABLE, "article_id='".$page_id."' AND pagenumber='".$newpage."'", "sectionnumber", "ASC");
      for($i=0;$i<count($sisterids);$i++)
      {
        $sisternavpos=getarticlesectionnumber($sisterids[$i]);
        updatefield(ARTICLESECTIONS_TABLE,"sectionnumber",$sisternavpos+1,"articlesection_id='".$sisterids[$i]."'");
      }
    }
    // move
    updatefield(ARTICLESECTIONS_TABLE,"pagenumber",$newpage,"articlesection_id='".$section_id."'");
    updatefield(ARTICLESECTIONS_TABLE,"sectionnumber",1,"articlesection_id='".$section_id."'");
  }
  // move section to previous articlepage
  elseif($direction==="up" && $navpos==1 && $pagenumber>1)
  {
    $newpage=$pagenumber-1;
    $result=$newpage;
    
    $newnav=getlastarticlesection($page_id,$newpage);
    $newnav++;
    updatefield(ARTICLESECTIONS_TABLE,"pagenumber",$newpage,"articlesection_id='".$section_id."'");
    updatefield(ARTICLESECTIONS_TABLE,"sectionnumber",$newnav,"articlesection_id='".$section_id."'");

    // fill space
    $sisterids=getorderedcolumn("articlesection_id",ARTICLESECTIONS_TABLE, "article_id='".$page_id."' AND pagenumber='".$pagenumber."'", "sectionnumber", "ASC");
    for($i=0;$i<count($sisterids);$i++)
    {
      $sisternavpos=getarticlesectionnumber($sisterids[$i]);
      updatefield(ARTICLESECTIONS_TABLE,"sectionnumber",$sisternavpos-1,"articlesection_id='".$sisterids[$i]."'");
    }
  }
  // move section within articlepage
  else
  {
    if($direction==="down")
    {
      $sisterids=getorderedcolumn("articlesection_id",ARTICLESECTIONS_TABLE, "article_id='".$page_id."' AND pagenumber='".$pagenumber."'", "sectionnumber", "ASC");
    }
    else
    {
      $sisterids=getorderedcolumn("articlesection_id",ARTICLESECTIONS_TABLE, "article_id='".$page_id."' AND pagenumber='".$pagenumber."'", "sectionnumber", "DESC");
    }
    $found=false;
    $idposition=0;
    for($i=0;$i<count($sisterids)&&!$found;$i++)
    {
      if($section_id==$sisterids[$i])
      {
        $found=true;
        $idposition=$i;
      }
    }
    if($found && $idposition+1<count($sisterids))
    {
      $otherid=$sisterids[$idposition+1];
      $navpos=getarticlesectionnumber($section_id);
      $othernavpos=getarticlesectionnumber($otherid);

      $swap[$section_id]=$othernavpos;
      $swap[$otherid]=$navpos;
      updateentries(ARTICLESECTIONS_TABLE,$swap,"articlesection_id","sectionnumber");
    }
  }
  return $result;
}


// *************************** edit external ******************************** //

//
//
//
function updateexternallink($page_id, $link)
{
  return updatefield(EXTERNALS_TABLE,"link",stripsid($link) ,"page_id='".setinteger($page_id)."'");
}


// *************************** edit gallery ********************************* //

//
//
//
function updategalleryintro($page_id, $introtext)
{
  return updatefield(GALLERIES_TABLE,"introtext",stripsid($introtext) ,"page_id='".setinteger($page_id)."'");
}

//
//
//
function addgalleryimage($page_id,$filename)
{
  $page_id=setinteger($page_id);
  
  $lastposition=getlastgalleryimageposition($page_id);

  $values=array();
  $values[]=0;
  $values[]=$page_id;
  $values[]=setstring($filename);
  $values[]=$lastposition+1;
  return insertentry(GALLERYITEMS_TABLE,$values);
}

//
//
//
function changegalleryimage($galleryitem_id, $filename)
{
  return updatefield(GALLERYITEMS_TABLE,"image_filename",setstring($filename),"galleryitem_id='".setinteger($galleryitem_id)."'");
}

//
//
//
function removegalleryimage($galleryitem_id)
{
  return deleteentry(GALLERYITEMS_TABLE,"galleryitem_id='".setinteger($galleryitem_id)."'");
}


//
//
//
function movegalleryimage($galleryitem_id, $direction, $positions=1)
{
  if($positions>0)
  {
    $page_id=getdbelement("page_id",GALLERYITEMS_TABLE, "galleryitem_id", setinteger($galleryitem_id));
    if($direction==="down")
    {
      $sisterids=getorderedcolumn("galleryitem_id",GALLERYITEMS_TABLE, "page_id='".($page_id)."'", "position", "ASC");
    }
    else
    {
      $sisterids=getorderedcolumn("galleryitem_id",GALLERYITEMS_TABLE, "page_id='".($page_id)."'", "position", "DESC");
    }
    $found=false;
    $idposition=0;
    for($i=0;$i<count($sisterids)&&!$found;$i++)
    {
      if($galleryitem_id==$sisterids[$i])
      {
        $found=true;
        $idposition=$i;
      }
    }
    if($found)
    {
      if($idposition+$positions>=count($sisterids))
      {
        $positions=count($sisterids)-$idposition-1;
      }
      $swap=array();
      $currentid=$sisterids[$idposition+$positions];
      $navpos=getdbelement("position",GALLERYITEMS_TABLE, "galleryitem_id", $currentid);

      for($i=$idposition+$positions;$i>$idposition;$i--)
      {
        $otherid=$sisterids[$i-1];
        $othernavpos=getdbelement("position",GALLERYITEMS_TABLE, "galleryitem_id", $otherid);

        $swap[$currentid]=$othernavpos;
        $swap[$otherid]=$navpos;
        $currentid=$otherid;
      }
      updateentries(GALLERYITEMS_TABLE,$swap,"galleryitem_id","position");
    }
  }
}

function reindexgallerypositions($page_id)
{
  $items=array();

  $query="select galleryitem_id, position from ".GALLERYITEMS_TABLE." where page_id = ".setinteger($page_id)." order by position ASC;";
//  print($query.'<br>');
  $sql=singlequery($query);
  if($sql)
  {
    // get column
    while($row=mysql_fetch_row($sql))
    {
      array_push($items,$row[0]);
    }
    $newpos=array();
    for($i=0;$i<count($items);$i++)
    {
      $newpos[$items[$i]]=$i+1;
    }
    updateentries(GALLERYITEMS_TABLE,$newpos,"galleryitem_id","position");
  }
}

// *************************** edit linklist ******************************** //

//
//
//
function updatelinklistintro($page_id, $introtext)
{
  return updatefield(LINKLISTS_TABLE,"introtext",stripsid($introtext) ,"page_id='".setinteger($page_id)."'");
}

//
//
//
function updatelinklistimage($page_id, $filename)
{
  return updatefield(LINKLISTS_TABLE,"image",setstring($filename),"page_id='".setinteger($page_id)."'");
}

//
//
//
function addlink($page_id,$linktitle,$link,$imagefilename,$description)
{
  $page_id=setinteger($page_id);
  $lastposition=getlastlinkposition($page_id);

  $values=array();
  $values[]=0;
  $values[]=$page_id;
  $values[]=setstring($linktitle);
  $values[]=setstring($imagefilename);
  $values[]=setstring($link);
  $values[]=setstring($description);
  $values[]=$lastposition+1;
  return insertentry(LINKS_TABLE,$values);
}

//
//
//
function deletelink($link_id)
{
  deleteentry(LINKS_TABLE,"link_id ='".setinteger($link_id)."'");
}

//
//
//
function updatelinkdescription($link_id, $text)
{
  return updatefield(LINKS_TABLE,"description",stripsid($text),"link_id='".setinteger($link_id)."'");
}

//
//
//
function updatelinkproperties($link_id,$title,$link)
{
  updatefield(LINKS_TABLE,"title",setstring($title),"link_id='".setinteger($link_id)."'");
  updatefield(LINKS_TABLE,"link",stripsid($link),"link_id='".setinteger($link_id)."'");
}

//
//
//
function updatelinkimage($link_id,$image)
{
  updatefield(LINKS_TABLE,"image",setstring($image),"link_id='".setinteger($link_id)."'");
}

//
//
//
function movelink($link_id, $direction, $positions=1)
{
  if($positions>0)
  {
    $page_id=getdbelement("page_id",LINKS_TABLE, "link_id", setinteger($link_id));
    if($direction==="down")
    {
      $sisterids=getorderedcolumn("link_id",LINKS_TABLE, "page_id='".($page_id)."'", "position", "ASC");
    }
    else
    {
      $sisterids=getorderedcolumn("link_id",LINKS_TABLE, "page_id='".($page_id)."'", "position", "DESC");
    }
    $found=false;
    $idposition=0;
    for($i=0;$i<count($sisterids)&&!$found;$i++)
    {
      if($link_id==$sisterids[$i])
      {
        $found=true;
        $idposition=$i;
      }
    }
    if($found)
    {
      if($idposition+$positions>=count($sisterids))
      {
        $positions=count($sisterids)-$idposition-1;
      }
      $swap=array();
      $currentid=$sisterids[$idposition+$positions];
      $navpos=getdbelement("position",LINKS_TABLE, "link_id", $currentid);

      for($i=$idposition+$positions;$i>$idposition;$i--)
      {
        $otherid=$sisterids[$i-1];
        $othernavpos=getdbelement("position",LINKS_TABLE, "link_id", $otherid);

        $swap[$currentid]=$othernavpos;
        $swap[$otherid]=$navpos;
        $currentid=$otherid;
      }
      updateentries(LINKS_TABLE,$swap,"link_id","position");
    }
  }
}



// *************************** edit menu ************************************ //

//
//
//
function updatemenuintro($page_id, $introtext)
{
  return updatefield(MENUS_TABLE,"introtext",stripsid($introtext) ,"page_id='".setinteger($page_id)."'");
}


//
//
//
function updatemenunavigation($page_id, $navigatordepth , $displaydepth , $sistersinnavigator)
{
  $page_id=setinteger($page_id);
  
  $sql=updatefield(MENUS_TABLE,"navigatordepth",setinteger($navigatordepth) ,"page_id='".$page_id."'");

  if($sql)
  {
    $sql= updatefield(MENUS_TABLE,"displaydepth ",setinteger($displaydepth) ,"page_id='".$page_id."'");
  }
  if($sql)
  {
    $sql= updatefield(MENUS_TABLE,"sistersinnavigator ",setinteger($sistersinnavigator) ,"page_id='".$page_id."'");
  }
  return $sql;
}

// *************************** edit newsitem ******************************** //


//
//
//
function setdisplaynewestnewsitemfirst($page_id, $shownewestfirst)
{
  if($shownewestfirst)
  {
    updatefield(NEWS_TABLE,"shownewestfirst ",1,"page_id='".setinteger($page_id)."'");
  }
  else
  {
    updatefield(NEWS_TABLE,"shownewestfirst ",0,"page_id='".setinteger($page_id)."'");
  }
}


//
//
//
function getnewsitems($page_id,$number,$offset)
{
  return getorderedcolumnlimit("newsitem_id",NEWSITEMS_TABLE, "page_id='".setinteger($page_id)."'", "date", setinteger($offset), setinteger($number),"DESC");
}

//
//
//
function countnewsitems($page_id)
{
  return countelementscondition("newsitem_id",NEWSITEMS_TABLE, "page_id='".setinteger($page_id)."'");
}

//
//
//
function getpagefornewsitem($newsitem_id)
{
  return getdbelement("page_id",NEWSITEMS_TABLE, "newsitem_id", setinteger($newsitem_id));
}


//
//
//
function updatenewsitemtitle($newsitem_id, $title)
{
  updatefield(NEWSITEMS_TABLE,"title",setstring($title),"newsitem_id='".setinteger($newsitem_id)."'");
}

//
//
//
function addnewsitemsynopsisimage($newsitem_id, $filename)
{
  $newsitem_id= setinteger($newsitem_id);
  $lastposition= getmax("position",NEWSITEMSYNIMG_TABLE, "newsitem_id = '".$newsitem_id."'");

  $values=array();
  $values[]=0;
  $values[]=$newsitem_id;
  $values[]=setstring($filename);
  $values[]=$lastposition+1;
  return insertentry(NEWSITEMSYNIMG_TABLE,$values);
}

//
//
//
function editnewsitemsynopsisimage($newsitemimage_id, $filename)
{
  updatefield(NEWSITEMSYNIMG_TABLE,"image_filename",setstring($filename),"newsitemimage_id='".setinteger($newsitemimage_id)."'");
}

//
//
//
function removenewsitemsynopsisimage($newsitemimage_id)
{
  deleteentry(NEWSITEMSYNIMG_TABLE,"newsitemimage_id ='".setinteger($newsitemimage_id)."'");
}

//
//
//
function updatenewsitemsource($newsitem_id, $source, $sourcelink, $location, $contributor)
{
  updatefield(NEWSITEMS_TABLE,"source",setstring($source),"newsitem_id='".setinteger($newsitem_id)."'");
  updatefield(NEWSITEMS_TABLE,"sourcelink",stripsid($sourcelink),"newsitem_id='".setinteger($newsitem_id)."'");
  updatefield(NEWSITEMS_TABLE,"location",setstring($location),"newsitem_id='".setinteger($newsitem_id)."'");
  updatefield(NEWSITEMS_TABLE,"contributor",setstring($contributor),"newsitem_id='".setinteger($newsitem_id)."'");
}

//
//
//
function fakethedate($newsitem_id,$day,$month,$year,$hours,$minutes,$seconds)
{
  if(strlen($day)==1) $day="0".$day;
  if(strlen($month)==1) $month="0".$month;
  if(strlen($hours)==1) $hours="0".$hours;
  if(strlen($minutes)==1) $minutes="0".$minutes;
  if(strlen($seconds)==1) $seconds="0".$seconds;
  if(strlen($year)==4)
  {
    $date=setinteger($year)."-".setinteger($month)."-".setinteger($day)." ".setinteger($hours).":".setinteger($minutes).":".setinteger($seconds);
    updatefield(NEWSITEMS_TABLE,"date",$date,"newsitem_id='".setinteger($newsitem_id)."'");
  }
}

//
//
//
function publishnewsitem($newsitem_id)
{
  if(!newsitempermissionrefused($newsitem_id))
  {
    return updatefield(NEWSITEMS_TABLE,"ispublished",1,"newsitem_id='".setinteger($newsitem_id)."'");
  }
  else return false;
}


//
//
//
function unpublishnewsitem($newsitem_id)
{
  return updatefield(NEWSITEMS_TABLE,"ispublished",0,"newsitem_id='".setinteger($newsitem_id)."'");
}

//
//
//
function updatenewsitemcopyright($newsitem_id,$copyright,$imagecopyright,$permission)
{
  updatefield(NEWSITEMS_TABLE,"copyright",setstring($copyright),"newsitem_id='".setinteger($newsitem_id)."'");
  updatefield(NEWSITEMS_TABLE,"image_copyright",setstring($imagecopyright),"newsitem_id='".setinteger($newsitem_id)."'");

  if($permission == PERMISSION_REFUSED)
  {
    unpublishnewsitem($newsitem_id);
  }
  updatefield(NEWSITEMS_TABLE,"permission",setinteger($permission),"newsitem_id='".setinteger($newsitem_id)."'");
}


//
//
//
function addnewsitem($page_id,$sid)
{
  $values=array();
  $values[]=0;
  $values[]=setinteger($page_id);
  $values[]='';
  $values[]='';
  $values[]='';
  $values[]='';
  $values[]='';
  $values[]='';
  $values[]=date(DATETIMEFORMAT, strtotime('now'));
  $values[]=getsiduser($sid);
  $values[]='';
  $values[]='';
  $values[]=NO_PERMISSION;
  $values[]=0;

  return insertentry(NEWSITEMS_TABLE,$values);
}

//
//
//
function deletenewsitem($newsitem_id)
{
  deleteentry(NEWSITEMS_TABLE,"newsitem_id ='".setinteger($newsitem_id)."'");
  deleteentry(NEWSITEMCATS_TABLE,"newsitem_id ='".setinteger($newsitem_id)."'");
  deleteentry(NEWSITEMSECTIONS_TABLE,"newsitem_id ='".setinteger($newsitem_id)."'");
}

//
// moves all newsitems in $page that are not newer than $day, $month, $year
// to a new page below $page
// returns number of archived newsitems
//
function archivenewsitems($page,$day,$month,$year,$sid)
{
  $page = setinteger($page);
  
  $maxpagetitlelength=200;
  $maxnavtitlelength=30;
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

  $date=$day." ".$months[$month]." ".$year." 23:59:59";
  $comparedate=date(DATETIMEFORMAT, strtotime($date));
  
  $moveids=getcolumn("newsitem_id",NEWSITEMS_TABLE,"page_id = ".$page." AND date <= '".$comparedate."'");
  $noofitems=count($moveids);
  
  if($noofitems>0)
  {
  
    $oldestdate=getoldestnewsitemdate($page);
  
    $from=makearticledate($oldestdate['mday'],$oldestdate['mon'],$oldestdate['year']);
    $from2=$oldestdate['mday']." ".substr($months[$oldestdate['mon']],0,3)." ".$oldestdate['year'];
  
    $to=makearticledate($day,$month,$year);
    $to2=$day." ".substr($months[$month],0,3)." ".$year;
  
    if($from!=$to)
    {
      $interval=" (".$from." - ".$to.")";
    }
    else
    {
      $interval=" (".$from.")";
    }
    $pagetitle=getpagetitle($page);

    if(strlen($pagetitle)+strlen($interval)>$maxpagetitlelength)
    {
      $pagetitle=substr($pagetitle,0,$maxpagetitlelength-strlen($interval));
      $pagetitle=substr($pagetitle,0,strrpos($pagetitle," "));
    }
    $pagetitle.=$interval;
  
    if($from2!=$to2)
    {
      $interval2=" (".$from2." - ".$to2.")";
    }
    else
    {
      $interval2=" (".$from.")";
    }
    $navtitle=getnavtitle($page);
    if(strlen($navtitle)+strlen($interval)>$maxnavtitlelength)
    {
      $navtitle=substr($navtitle,0,$maxnavtitlelength-strlen($interval2));
      $navtitle=substr($navtitle,0,strrpos($navtitle," "));
    }
    $navtitle.=$interval2;

    $newpage=createpage($page,$pagetitle,$navtitle,"news",getsiduser($sid),ispublishable($page));
    $values="";
    for($i=0;$i<$noofitems;$i++)
    {
      $values.="'".$moveids[$i]."',";
    }
    $values=substr($values,0,strlen($values)-1);
    $query=("update ");
    $query.=(NEWSITEMS_TABLE." set ");
    $query.="page_id=";
    $query.="'".$newpage."'";
    $query.=" where newsitem_id IN (".$values.");";
    singlequery($query);
  }
  return $noofitems;
}

//
//
//
function updatenewsitemsynopsistext($newsitem_id,$text)
{
  updatefield(NEWSITEMS_TABLE,"synopsis",stripsid($text),"newsitem_id='".setinteger($newsitem_id)."'");
}



//
//
//
function addnewsitemsection($newsitem_id, $section_id,$isquote=false)
{
  $newsitem_id=setinteger($newsitem_id);
  $section_id=setinteger($section_id);
  
  if(!$section_id)
  {
    $sections=getnewsitemsections($newsitem_id);
    $section_id=$sections[count($sections)-1];
  }
  $sectionnumber=getnewsitemsectionnumber($section_id);
  
  if($isquote)
  {
    $offset=3;
  }
  else
  {
    $offset=1;
  }

  //make room

  if(getlastnewsitemsection($newsitem_id)!=$sectionnumber)
  {
    $sections=getnewsitemsections($newsitem_id);
    $finished=false;
    for($i=count($sections)-1;$i>0 && !$finished;$i--)
    {
      $currentsectionnumber=getnewsitemsectionnumber($sections[$i]);
      if($currentsectionnumber > $sectionnumber)
      {
          updatefield(NEWSITEMSECTIONS_TABLE,"sectionnumber",$currentsectionnumber+$offset,"newsitemsection_id='".$sections[$i]."'");
      }
      else
      {
        $finished=true;
      }
    }
  }

  $newsectionnumber=$sectionnumber+1;
  
  if($isquote)
  {
    $values=array();
    $values[]=0;
    $values[]=$newsitem_id;
    $values[]=$newsectionnumber;
    $values[]='';
    $values[]='[quote]';
    $values[]='';
    $values[]='left';
    $values[]='top';
    insertentry(NEWSITEMSECTIONS_TABLE,$values);

    $newsectionnumber=$newsectionnumber+1;
  }

  $values=array();
  $values[]=0;
  $values[]=$newsitem_id;
  $values[]=$newsectionnumber;
  $values[]='';
  $values[]='';
  $values[]='';
  $values[]='left';
  $values[]='top';

  $result=insertentry(NEWSITEMSECTIONS_TABLE,$values);
  
  if($isquote)
  {
    $newsectionnumber=$newsectionnumber+1;

    $values=array();
    $values[]=0;
    $values[]=$newsitem_id;
    $values[]=$newsectionnumber;
    $values[]='';
    $values[]='[unquote]';
    $values[]='';
    $values[]='left';
    $values[]='top';
    insertentry(NEWSITEMSECTIONS_TABLE,$values);
  }
  return $result;
}


//
//
//
function updatenewsitemsectionimage($section_id,$imagefilename,$imagealign,$imagevalign)
{
  $section_id=setinteger($section_id);

  updatefield(NEWSITEMSECTIONS_TABLE,"sectionimage",setstring(basename($imagefilename)),"newsitemsection_id='".$section_id."'");
  updatefield(NEWSITEMSECTIONS_TABLE,"imagealign",setstring($imagealign),"newsitemsection_id='".$section_id."'");
  updatefield(NEWSITEMSECTIONS_TABLE,"imagevalign",setstring($imagevalign),"newsitemsection_id='".$section_id."'");
}


//
//
//
function updatenewsitemsectionttitle($section_id,$title)
{
  updatefield(NEWSITEMSECTIONS_TABLE,"sectiontitle",setstring($title),"newsitemsection_id='".setinteger($section_id)."'");
}


//
//
//
function updatenewsitemsectiontext($section_id,$text)
{
  updatefield(NEWSITEMSECTIONS_TABLE,"text",stripsid($text),"newsitemsection_id='".setinteger($section_id)."'");
}


//
//
//
function deletenewsitemsection($newsitem_id, $section_id)
{
  $section_id=setinteger($section_id);

  // remove quotes if necessary
  $sections=getnewsitemsections($newsitem_id);
  $found=false;
  for($i=1;$i<count($sections)-1&&!$found;$i++)
  {
    if($sections[$i]==$section_id)
    {
      $found=true;
      $text1=getnewsitemsectiontext($sections[$i-1]);
      $text2=getnewsitemsectiontext($sections[$i+1]);
//      print("<p>text".$text1.$text2);
      if($text1==="[quote]" && $text2==="[unquote]")
      {
        deleteentry(NEWSITEMSECTIONS_TABLE,"newsitemsection_id ='".$sections[$i-1]."'");
        deleteentry(NEWSITEMSECTIONS_TABLE,"newsitemsection_id ='".$sections[$i+1]."'");
      }
    }
  }
  // delete
  deleteentry(NEWSITEMSECTIONS_TABLE,"newsitemsection_id ='".$section_id."'");
}



// *************************** edit ***************************************** //

//
//
//
function renamepage($page_id, $title_navigator, $title_page)
{
  $sql=updatefield(PAGES_TABLE,"title_page",setstring($title_page),"page_id='".setinteger($page_id)."'");
  
  if($sql)
  {
    $sql= updatefield(PAGES_TABLE,"title_navigator",setstring($title_navigator),"page_id='".setinteger($page_id)."'");
  }
  return $sql;
}


//
//
//
function movepage($page_id, $direction, $positions=1)
{
  $page_id=setinteger($page_id);
  
  if($direction==="top")
  {
    $minpos=getmin("position_navigator",PAGES_TABLE, "parent_id='".getparent($page_id)."'");
    if($minpos<=1)
    {
      $sisterids=getsisters($page_id);
      $newpos=array();
      for($i=0;$i<count($sisterids);$i++)
      {
        $newpos[$sisterids[$i]]=getnavposition($sisterids[$i])+1;
      }
      updateentries(PAGES_TABLE,$newpos,"page_id","position_navigator");
    }
    updatefield(PAGES_TABLE,"position_navigator",1,"page_id='".$page_id."'");
  }
  elseif($direction==="bottom")
  {
    $maxpos=getmax("position_navigator",PAGES_TABLE, "parent_id='".getparent($page_id)."'");
    updatefield(PAGES_TABLE,"position_navigator",$maxpos+1,"page_id='".$page_id."'");
  }
  elseif($positions>0)
  {
    if($direction==="down")
    {
      $sisterids=getsisters($page_id);
    }
    else
    {
      $sisterids=getsisters($page_id, "DESC");
    }
    $found=false;
    $idposition=0;
    for($i=0;$i<count($sisterids)&&!$found;$i++)
    {
      if($page_id==$sisterids[$i])
      {
        $found=true;
        $idposition=$i;
      }
    }

    if($found)
    {
      if($idposition+$positions>=count($sisterids))
      {
        $positions=count($sisterids)-$idposition-1;
      }
      $swap=array();
      $currentid=$sisterids[$idposition+$positions];
      $navpos=getnavposition($currentid);

      for($i=$idposition+$positions;$i>$idposition;$i--)
      {
        $otherid=$sisterids[$i-1];
        $othernavpos=getnavposition($otherid);

        $swap[$currentid]=$othernavpos;
        $swap[$otherid]=$navpos;
        $currentid=$otherid;
      }
      updateentries(PAGES_TABLE,$swap,"page_id","position_navigator");
    }
  }
}

//
//
//
function getallsubpageids($page)
{
  return getorderedcolumn("page_id",PAGES_TABLE, "parent_id='".setinteger($page)."'", "position_navigator", "ASC");
}

//
//
//
function getallsubpagenavtitles($page)
{
  return getorderedcolumn("title_navigator",PAGES_TABLE, "parent_id='".setinteger($page)."'", "position_navigator", "ASC");
}


//
//
//
function movetonewparentpage($page,$newparent)
{
  $result="";
  
  $newparent=setinteger($newparent);
  $page=setinteger($page);

  $navposition=getlastnavposition($newparent)+1;
  updatefield(PAGES_TABLE,"position_navigator",$navposition,"page_id='".$page."'");
  updatefield(PAGES_TABLE,"parent_id",$newparent,"page_id='".$page."'");
  
  $parentrestricted=getdbelement("page_id",RESTRICTEDPAGES_TABLE, "page_id", $newparent);

  if(!isthisexactpagerestricted($page))
  {
    if($parentrestricted==$newparent && $newparent!=0)
    {
      if(!ispagerestricted($page))
      {
        $result="This page now has restricted access.";
        insertentry(RESTRICTEDPAGES_TABLE,array(0=>$page, 1=>getpagerestrictionmaster($newparent)));
      }
      else
      {
        updatefield(RESTRICTEDPAGES_TABLE,"masterpage",getpagerestrictionmaster($newparent),'page_id ='.$page);
      }
    }
    elseif(ispagerestricted($page))
    {
      $result="Access restriction to this page removed";
      deleteentry(RESTRICTEDPAGES_TABLE,"page_id ='".setinteger($page)."'");
    }
  }
  return $result;
}

//
//
//
function getmovetargets($page)
{
  $parent=getparent($page);
  $pagetype=getpagetype(setinteger($page));

  $legaltypes=getlegalparentpagetypes($pagetype);

  $allpages=getallpages(array(0 => 'page_id', 1 => 'pagetype'));


  $result=array();
  if($legaltypes['root'])
  {
    $result=array(0 => 0);
  }

  while($currentpage=current($allpages))
  {
    if(array_key_exists($currentpage['pagetype'],$legaltypes) && $currentpage['page_id']!=$page && $currentpage['page_id']!=$parent)
    {
      array_push($result,$currentpage['page_id']);
    }
    next($allpages);
  }
  return $result;
}

//
// the types of pages that can be parentpages of a page with $pagetype
// returns an associative array of legal page types
//
function getlegalparentpagetypes($pagetype)
{
  $result=array();
  $allowroot=getdbelement("allow_root",PAGETYPES_TABLE, "type_key",setstring($pagetype));
  $allowmenu=getdbelement("allow_simplemenu",PAGETYPES_TABLE, "type_key",setstring($pagetype));
  $allowself=getdbelement("allow_self",PAGETYPES_TABLE, "type_key",setstring($pagetype));
  if($allowroot)
  {
    $result["root"] = true;
  }
  if($allowmenu)
  {
    $result["menu"] = true;
  }
  if($allowself)
  {
    $result[$pagetype] = true;
  }
  
  // special menu types
  if($pagetype==="article")
  {
    $result["articlemenu"] = true;
  }
  elseif($pagetype==="linklist")
  {
    $result["linklistmenu"] = true;
  }
  elseif($pagetype==="external")
  {
    $result["linklistmenu"] = true;
    $result["articlemenu"] = true;
    $result["news"] = true;
  }
  return $result;
}

//
// can a page of $pagetype be a direct subpage of $parentpage?
//
function islegalparentpage($pagetype, $parentpage)
{
  $result=false;
  
  if($parentpage==0)
  {
    $parentpagetype="root";
  }
  else
  {
    $parentpagetype=getpagetype(setinteger($parentpage));
  }
  $legaltypes=getlegalparentpagetypes($pagetype);
  
  if(array_key_exists($parentpagetype,$legaltypes)) $result=true;
  return $result;
}

//
//
//
function getrestrictions($pagetype)
{
  $result=array();
  $pagetype = setstring($pagetype);
  $result["allowroot"]=getdbelement("allow_root",PAGETYPES_TABLE,"type_key", $pagetype);
  $result["allowsimplemenu"]=getdbelement("allow_simplemenu",PAGETYPES_TABLE,"type_key", $pagetype);
  $result["allowself"]=getdbelement("allow_self",PAGETYPES_TABLE,"type_key", $pagetype);
  return $result;
}

//
//
//
function updaterestrictions($pagetype,$allowroot,$allowsimplemenu)
{
  updatefield(PAGETYPES_TABLE,"allow_root",setinteger($allowroot),"type_key='".setstring($pagetype)."'");
  updatefield(PAGETYPES_TABLE,"allow_simplemenu",setinteger($allowsimplemenu),"type_key='".setstring($pagetype)."'");
}

//
//
//
function publish($page_id)
{
  if(ispublishable($page_id))
  {
    return updatefield(PAGES_TABLE,"ispublished",1 ,"page_id='".setinteger($page_id)."'");
  }
  else
  {
    return false;
  }
}

//
//
//
function unpublish($page_id)
{
  return updatefield(PAGES_TABLE,"ispublished",0 ,"page_id='".setinteger($page_id)."'");
}


//
//
//
function makepublishable($page_id)
{
    return updatefield(PAGES_TABLE,"ispublishable",1 ,"page_id='".setinteger($page_id)."'");
}


//
//
//
function hide($page_id)
{
  if(!ispublished($page_id))
  {
    return updatefield(PAGES_TABLE,"ispublishable",0 ,"page_id='".setinteger($page_id)."'");
  }
  else
  {
    return false;
  }
}



//
//
//
function ispublishable($page_id)
{
  $pagetype=getpagetype($page_id);

  if(getpermission($page_id)==PERMISSION_REFUSED)
  {
    return 0;
  }
  else
  {
    return getdbelement("ispublishable",PAGES_TABLE, "page_id",setinteger($page_id));
  }
}

//
//
//
function lockpage($user_id, $page_id)
{
  $now=date(DATETIMEFORMAT, strtotime('now'));
  
  $page_id=setinteger($page_id);
  $user_id=setinteger($user_id);
  
  $lockuserid=getdbelement("user_id",LOCKS_TABLE, "page_id", $page_id);
  if($lockuserid)
  {
    updatefield(LOCKS_TABLE,"locktime",$now,"page_id='".$page_id."'");
    updatefield(LOCKS_TABLE,"user_id",$user_id,"page_id='".$page_id."'");
  }
  else
  {
    $values=array();
    $values[]=$page_id;
    $values[]=$user_id;
    $values[]=$now;

    return insertentry(LOCKS_TABLE,$values);
  }
}

//
//
//
function unlockpage($page_id)
{
  return deleteentry(LOCKS_TABLE,"page_id='".setinteger($page_id)."'");
}

//
//
//
function unlockuserpages($sid)
{
  return deleteentry(LOCKS_TABLE,"user_id='".getsiduser($sid)."'");
}

//
// array user_id, locktime
//
function getlock($page_id, $user_id=false)
{
  // clear old locks
  $time=date(DATETIMEFORMAT, strtotime('-30 minutes'));
  deleteentry(LOCKS_TABLE,"locktime<'".$time."'");

  $result['user_id']= getdbelement("user_id",LOCKS_TABLE, "page_id",setinteger($page_id));
  if($result['user_id'])
  {
    $result['locktime']=getdbelement("locktime",LOCKS_TABLE, "page_id",setinteger($page_id));
  }
  return $result;
}

//
// array user_id, locktime
//
function islocked($page_id)
{
  return getlock($page_id);
}

//
//
//
function updateeditdata($page_id, $sid)
{
  $now=date(DATETIMEFORMAT, strtotime('now'));
  updatefield(PAGES_TABLE,"editdate",$now,"page_id='".setinteger($page_id)."'");
  updatefield(PAGES_TABLE,"editor_id",getsiduser($sid),"page_id='".setinteger($page_id)."'");
}

//
// permission is one of the constants PERMISSION_GRANTED, NO_PERMISSION, PERMISSION_REFUSED
//
function updatecopyright($page_id,$copyright,$imagecopyright,$permission)
{
  $page_id=setinteger($page_id);
  updatefield(PAGES_TABLE,"copyright",setstring($copyright),"page_id='".$page_id."'");
  updatefield(PAGES_TABLE,"image_copyright",setstring($imagecopyright),"page_id='".$page_id."'");
  updatefield(PAGES_TABLE,"permission",setinteger($permission),"page_id='".$page_id."'");

  if(setinteger($permission)==PERMISSION_REFUSED)
  {
    unpublish($page_id);
    hide($page_id);
  }
}


// *************************** create *************************************** //

//
//  todo: restrictions
//
function createpage($parent_id, $title, $navtitle, $pagetype, $user_id, $ispublishable)
{
  $parent_id=setinteger($parent_id);
  $title=setstring($title);
  $navtitle=setstring($navtitle);
  $pagetype=setstring($pagetype);
  $user_id=setinteger($user_id);
  $ispublishable=setinteger($ispublishable);
  
  if(!$parent_id)
  {
    $parent_id=0;
  }
  $lastnavposition=1+getlastnavposition($parent_id);
  
  $date=date(DATETIMEFORMAT);

  $values=array();
  $values[]=0;
  $values[]=$parent_id;
  $values[]=$navtitle;
  $values[]=$title;
  $values[]=$lastnavposition;
  $values[]=$pagetype;
  $values[]=$date;
  $values[]=$user_id;
  $values[]="";
  $values[]="";
  $values[]=NO_PERMISSION;
  $values[]=0;
  $values[]=$ispublishable;
  $values[]=0;
  
  $sql= insertentry(PAGES_TABLE,$values);
  if($sql)
  {
    $page_id=getdbelement("page_id",PAGES_TABLE, "editdate", $date);
  
    if($pagetype==="article")
    {
      createemptyarticle($page_id);
    }
    elseif($pagetype==="external")
    {
      createemptyexternal($page_id);
    }
    elseif($pagetype==="gallery")
    {
      createemptygallery($page_id);
    }
    elseif($pagetype==="linklist")
    {
      createemptylinklist($page_id);
    }
    elseif($pagetype==="menu" || $pagetype==="articlemenu" || $pagetype==="linklistmenu")
    {
      createemptymenu($page_id);
    }
    elseif($pagetype==="news")
    {
      createemptynewspage($page_id);
    }
  }

  $parentrestricted=getdbelement("page_id",RESTRICTEDPAGES_TABLE, "page_id", $parent_id);
  if($parentrestricted==$parent_id && $parent_id!=0)
  {
    insertentry(RESTRICTEDPAGES_TABLE,array(0=>$page_id, 1=>getpagerestrictionmaster($parent_id)));
  }
  return $sql;
}

//
//
//
function getlastnavposition($pageid)
{
  return getmax("position_navigator",PAGES_TABLE, "parent_id = '".setinteger($pageid)."'");
}

//
//
//
function createemptyarticle($page_id)
{
  $now=getdate(strtotime('now'));

  $values=array();
  $values[]=setinteger($page_id);
  $values[]='';
  $values[]='';
  $values[]='';
  $values[]='';
  $values[]='';
  $values[]=$now['mday'];
  $values[]=$now['mon'];
  $values[]=$now['year'];
  $values[]='';
  $values[]='left';
  $values[]='top';
  $values[]=1;

  insertentry(ARTICLES_TABLE,$values);
}

//
//
//
function createemptyexternal($page_id)
{
  return insertentry(EXTERNALS_TABLE,array(0=>setinteger($page_id), 1=>''));
}

//
//
//
function createemptygallery($page_id)
{
  return insertentry(GALLERIES_TABLE,array(0=>setinteger($page_id), 1=>''));
}

//
//
//
function createemptylinklist($page_id)
{
  return insertentry(LINKLISTS_TABLE,array(0=>setinteger($page_id), 1=>'', 2=>''));
}

//
//
//
function createemptymenu($page_id)
{
  $values=array();
  $values[]=setinteger($page_id);
  $values[]='1';
  $values[]='2';
  $values[]='1';
  $values[]='';

  return insertentry(MENUS_TABLE,$values);
}


//
//
//
function createemptynewspage($page_id)
{
  $values=array();
  $values[]=setinteger($page_id);
  $values[]='1';

  return insertentry(NEWS_TABLE,$values);
}


// *************************** delete *************************************** //

//
// todo: reorganize position_navigator with page locking
//
function deletepage($page_id, $sid)
{
	$page_id=setinteger($page_id);
  	$sid=setstring($sid);
  

//  print("hallo delete");
    $pagestosearch[0]=$page_id;
    $deleteids=array();

    while(count($pagestosearch))
    {
      $currentpage=array_pop($pagestosearch);
      array_push($deleteids,$currentpage);
      $pageids=getorderedcolumn("page_id",PAGES_TABLE, "parent_id='".$currentpage."'", "position_navigator", "ASC");
      for($i=0;$i<count($pageids);$i++)
      {
        array_push($pagestosearch,$pageids[$i]);
      }
    }
    for($i=0;$i<count($deleteids);$i++)
    {
      $pagetype=getpagetype($deleteids[$i]);
      deleteentry(PAGES_TABLE,"page_id='".$deleteids[$i]."'");
      deleteentry(RESTRICTEDPAGES_TABLE,"page_id ='".setinteger($deleteids[$i])."'");
      deleteentry(RESTRICTEDPAGESACCESS_TABLE,"page_id ='".setinteger($deleteids[$i])."'");
      removerssfeed($deleteids[$i]);
      deleteentry(PAGECACHE_TABLE,"page_id='".$deleteids[$i]."'");
      
      if($pagetype==="article")
      {
        deleteentry(PAGECATS_TABLE,"page_id ='".$deleteids[$i]."'");
        deleteentry(ARTICLESECTIONS_TABLE,"article_id='".$deleteids[$i]."'");
        deleteentry(ARTICLES_TABLE,"page_id='".$deleteids[$i]."'");
      }
      elseif($pagetype==="external")
      {
        deleteentry(EXTERNALS_TABLE,"page_id='".$deleteids[$i]."'");
      }
      elseif($pagetype==="gallery")
      {
        deleteentry(GALLERIES_TABLE,"page_id='".$deleteids[$i]."'");
        deleteentry(GALLERYITEMS_TABLE,"page_id='".$deleteids[$i]."'");
      }
      elseif($pagetype==="linklist")
      {
        deleteentry(LINKLISTS_TABLE,"page_id='".$deleteids[$i]."'");
        deleteentry(LINKS_TABLE,"page_id='".$deleteids[$i]."'");
      }
      elseif($pagetype==="menu" ||$pagetype==="articlemenu" || $pagetype==="linklistmenu" )
      {
        deleteentry(MENUS_TABLE,"page_id='".$deleteids[$i]."'");
      }
      elseif($pagetype==="news")
      {
        deleteentry(NEWS_TABLE,"page_id='".$deleteids[$i]."'");
        $newsitems=getcolumn("newsitem_id",NEWSITEMS_TABLE,"page_id='".$deleteids[$i]."'");
        for($j=0;$j<count($newsitems);$j++)
        {
          deleteentry(NEWSITEMSECTIONS_TABLE,"newsitem_id='".$newsitems[$j]."'");
          deleteentry(NEWSITEMSYNIMG_TABLE,"newsitem_id='".$newsitems[$j]."'");
          deleteentry(NEWSITEMCATS_TABLE,"newsitem_id='".$newsitems[$j]."'");
        }
        deleteentry(NEWSITEMS_TABLE,"page_id='".$deleteids[$i]."'");
      }
    }
  	rebuildaccessrestrictionindex();
  	return count($deleteids);
}

// *************************** general ************************************** //

//
//
//
function addrssfeed($page_id)
{
  insertentry(RSS_TABLE,array( 0=> setinteger($page_id)));
}

//
//
//
function removerssfeed($page_id)
{
  deleteentry(RSS_TABLE,"page_id='".setinteger($page_id)."'");
}

// *************************** link validation ****************************** //

//
//
//
function getallpagesforpagetype($pagetype)
{
  return getorderedcolumn("page_id",PAGES_TABLE,"pagetype = '".setstring($pagetype)."'", "page_id", "ASC");
}


//
//
//
function getpageforlinkid($link_id)
{
  return getdbelement("page_id",LINKS_TABLE,"link = '".setinteger($link_id)."'","page_id", "ASC");
}

// *************************** restricted access **************************** //

//
//
//
function restrictaccess($page_id)
{
  $page_id = setinteger($page_id);
  if(ispagerestricted($page_id))
  {
    updatefield(RESTRICTEDPAGES_TABLE,"masterpage",$page_id,"page_id = ".$page_id);
  }
  else
  {
    insertentry(RESTRICTEDPAGES_TABLE,array(0=>$page_id, 1=>$page_id));
  }
  rebuildaccessrestrictionindex();
}


//
//
//
function removeaccessrestriction($page_id)
{
  deleteentry(RESTRICTEDPAGES_TABLE,"masterpage ='".setinteger($page_id)."'");
  deleteentry(RESTRICTEDPAGESACCESS_TABLE,"page_id ='".setinteger($page_id)."'");
  rebuildaccessrestrictionindex();
}

//
// must be called when editing the pages that are restricted
//
function rebuildaccessrestrictionindex()
{
  print('<p class="highlight">Rebuilding index of restricted pages...</p>');
  print('<span class="gen">');
  // get masterpages from access table
  $masterpages=getcolumn("page_id",RESTRICTEDPAGESACCESS_TABLE, "1");
  $masterpages2=getdistinctorderedcolumn("masterpage", RESTRICTEDPAGES_TABLE,"1", "masterpage","ASC");
  $masterpages=array_unique(array_merge($masterpages,$masterpages2));

  // clear masterpages
  $sql = "truncate table ".RESTRICTEDPAGES_TABLE;
  singlequery($sql);

  // define masterpages
  while($masterpage=current($masterpages))
  {
    print(' '.$masterpage);
    insertentry(RESTRICTEDPAGES_TABLE,array(0=>$masterpage, 1=>$masterpage));
    
    next($masterpages);
  }
  
  // iterate through subpages
  while(count($masterpages))
  {
    $masterpage=array_pop($masterpages);

    $children = getchildren($masterpage);
    while(count($children)>0)
    {
      $child=array_pop($children);
      if(!isthisexactpagerestricted($child))
      {
        print(' '.$child);
        insertentry(RESTRICTEDPAGES_TABLE,array(0=>$child, 1=>$masterpage));
        $children = array_merge($children,getchildren($child));
      }
    }
  }
  print('</span>');
  print('<span class="highlight"> ... done!</span>');
}


//
//
//
function getpageaccessforpublicuser($user_id)
{
  return getcolumn("page_id",RESTRICTEDPAGESACCESS_TABLE, "publicuser_id = '".setinteger($user_id)."'");
}

//
//
//
function getrestrictedpages()
{
  return getdistinctorderedcolumn("page_id", RESTRICTEDPAGES_TABLE,"page_id = masterpage", "page_id","ASC");
}

//
//
//
function getpagerestrictionmaster($page_id)
{
  return getdbelement("masterpage",RESTRICTEDPAGES_TABLE, "page_id", setinteger($page_id));
}

//
//
//
function hasaccess($user_id, $page_id)
{
  $result=false;
  $user_id=setinteger($user_id);
  $page_id=setinteger($page_id);
  $masterpage=getdbelement("masterpage",RESTRICTEDPAGES_TABLE, "page_id", $page_id);
  $query="select publicuser_id from ".RESTRICTEDPAGESACCESS_TABLE." where publicuser_id = '".$user_id."' AND page_id = '".$masterpage."';";
//print($query);
  $sql=singlequery($query);
  if($sql)
  {
    $row=mysql_fetch_row($sql);
    $result=$row[0];
  }
  return $result;
}


//
//
//
function setshowpermissionrefusedimages($page_id, $show)
{
  $page_id = setinteger($page_id);
  
  if($show && ispagerestricted($page_id))
  {
    updatefield(PAGES_TABLE,"showpermissionrefusedimages",1,"page_id='".$page_id."'");
  }
  else
  {
    updatefield(PAGES_TABLE,"showpermissionrefusedimages",0,"page_id='".$page_id."'");
  }
}


// *************************** stats **************************************** //

//
//
//
function getmonthlypagestats($count=10,$year=0,$month=0)
{
  if(!$month || !$year)
  {
    $year=date("Y",strtotime('now'));
    $month=date("m",strtotime('now'));
//    echo "year: $year month: $month";
  }
  $query="SELECT page_id, viewcount FROM ".MONTHLYPAGESTATS_TABLE." WHERE ";
  $query.="year='".setinteger($year)."' AND month='".setinteger($month)."'";
  $query.=" ORDER BY viewcount DESC LIMIT 0,".$count;
//  print($query);
  $sql=singlequery($query);
  $result=array();
  if($sql)
  {
    // get column
    while($row=mysql_fetch_row($sql))
    {
      array_push($result,array($row[0],$row[1]));
    }
  }
  return $result;
}



//
// lock handling
// returns empty string when lock has been obtained
// else returns string containing reason for lock
//
function getpagelock($page)
{
  	global $sid, $_GET;

  	$result="";

	if(isset($_GET['override']))
    {
      unlockpage($page);
      unset($_GET['override']);
    }

    $lock=getlock($page);
    if($lock['user_id'] && $lock['user_id']!==getsiduser($sid) )
    {
      $result="This page has been locked by <i>";
      $result.=getusername($lock['user_id']);
      $result.="</i> on ";
      $result.=formatdatetime($lock['locktime']);
    }
    else
    {
      lockpage(getsiduser($sid), $page);
    }

  	return $result;
}


// *************************** cache ***************************************** //

//
//
//
function clearpagecache()
{
  $query="TRUNCATE TABLE ".PAGECACHE_TABLE.";";
  $sql=singlequery($query);
}
?>
