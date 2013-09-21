<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"pagecontent"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/dbmod.php");

//
//
//
function updatearticlesource($page_id,$author,$location,$day,$month,$year,$source,$sourcelink,$toc)
{
	global $db;
  $page_id=$db->setinteger($page_id);
  
  if($toc=="true") $toc=1;
  else $toc=0;
  
  if(strlen($year)!=4) $year="0000";
  updatefield(ARTICLES_TABLE,"article_author",$db->setstring($author),"page_id='".$page_id."'");
  updatefield(ARTICLES_TABLE,"location",$db->setstring($location),"page_id='".$page_id."'");
  updatefield(ARTICLES_TABLE,"day",$db->setinteger($day),"page_id='".$page_id."'");
  updatefield(ARTICLES_TABLE,"month",$db->setinteger($month),"page_id='".$page_id."'");
  updatefield(ARTICLES_TABLE,"year",$db->setinteger($year),"page_id='".$page_id."'");
  updatefield(ARTICLES_TABLE,"source",$db->setstring($source),"page_id='".$page_id."'");
  updatefield(ARTICLES_TABLE,"sourcelink",$db->setstring($sourcelink),"page_id='".$page_id."'");
  updatefield(ARTICLES_TABLE,"use_toc",$toc,"page_id='".$page_id."'");
}

//
//
//
function addarticlepage($page_id)
{
	global $db;
  $numberofpages=numberofarticlepages($db->setinteger($page_id));
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
	global $db;
  $numberofpages=numberofarticlepages($db->setinteger($article_id));

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
	global $db;
  $article_id=$db->setinteger($article_id);
  $pagenumber=$db->setinteger($pagenumber);

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
  return insertentry(ARTICLESECTIONS_TABLE,$values);
}


//
//
//
function updatearticlesectionimage($section_id,$imagefilename,$imagealign)
{
	global $db;
  $section_id=$db->setinteger($section_id);
  
  updatefield(ARTICLESECTIONS_TABLE,"sectionimage",$db->setstring(basename($imagefilename)),"articlesection_id='".$section_id."'");
  updatefield(ARTICLESECTIONS_TABLE,"imagealign",$db->setstring($imagealign),"articlesection_id='".$section_id."'");
}

//
//
//
function updatearticlesectiontitle($section_id,$sectiontitle)
{
	global $db;
  	$result = updatefield(ARTICLESECTIONS_TABLE,"sectiontitle",$db->setstring($sectiontitle),"articlesection_id='".$db->setinteger($section_id)."'");
  	return $result;
}

//
//
//
function updatearticlesectiontext($section_id,$text)
{
	global $db;
  	return updatefield(ARTICLESECTIONS_TABLE,"text",$db->setstring($text),"articlesection_id='".$db->setinteger($section_id)."'");
}


//
//
//
function deletearticlesection($articlesection_id)
{
	global $db;
  deleteentry(ARTICLESECTIONS_TABLE,"articlesection_id ='".$db->setinteger($articlesection_id)."'");
}


//
// returns the articlepage the section will be in after the move
//
function movearticlesection($section_id,$pagenumber,$direction)
{
	global $db;
  $section_id=$db->setinteger($section_id);
  $page_id=getdbelement("article_id",ARTICLESECTIONS_TABLE, "articlesection_id", $section_id);
  $pagenumber=$db->setinteger($pagenumber);
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

?>