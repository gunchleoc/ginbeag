<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"pagecontent"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/dbmod.php");
include_once($projectroot."functions/pagecontent/newspages.php");

//
//
//
function setdisplaynewestnewsitemfirst($page_id, $shownewestfirst)
{
	global $db;
  if($shownewestfirst)
  {
    updatefield(NEWS_TABLE,"shownewestfirst ",1,"page_id='".$db->setinteger($page_id)."'");
  }
  else
  {
    updatefield(NEWS_TABLE,"shownewestfirst ",0,"page_id='".$db->setinteger($page_id)."'");
  }
}


//
//
//
function getnewsitems($page_id,$number,$offset)
{
	global $db;
  return getorderedcolumnlimit("newsitem_id",NEWSITEMS_TABLE, "page_id='".$db->setinteger($page_id)."'", "date", $db->setinteger($offset), $db->setinteger($number),"DESC");
}

//
//
//
function countnewsitems($page_id)
{
	global $db;
  return countelementscondition("newsitem_id",NEWSITEMS_TABLE, "page_id='".$db->setinteger($page_id)."'");
}

//
//
//
function getpagefornewsitem($newsitem_id)
{
	global $db;
  return getdbelement("page_id",NEWSITEMS_TABLE, "newsitem_id", $db->setinteger($newsitem_id));
}


//
//
//
function updatenewsitemtitle($newsitem_id, $title)
{
	global $db;
  	return updatefield(NEWSITEMS_TABLE,"title",$db->setstring($title),"newsitem_id='".$db->setinteger($newsitem_id)."'");
}

//
//
//
function addnewsitemsynopsisimage($newsitem_id, $filename)
{
	global $db;
  $newsitem_id= $db->setinteger($newsitem_id);
  $lastposition= getmax("position",NEWSITEMSYNIMG_TABLE, "newsitem_id = '".$newsitem_id."'");

  $values=array();
  $values[]=0;
  $values[]=$newsitem_id;
  $values[]=$db->setstring($filename);
  $values[]=$lastposition+1;
  return insertentry(NEWSITEMSYNIMG_TABLE,$values);
}

//
//
//
function editnewsitemsynopsisimage($newsitemimage_id, $filename)
{
	global $db;
  updatefield(NEWSITEMSYNIMG_TABLE,"image_filename",$db->setstring($filename),"newsitemimage_id='".$db->setinteger($newsitemimage_id)."'");
}

//
//
//
function removenewsitemsynopsisimage($newsitemimage_id)
{
	global $db;
  deleteentry(NEWSITEMSYNIMG_TABLE,"newsitemimage_id ='".$db->setinteger($newsitemimage_id)."'");
}

//
//
//
function updatenewsitemsource($newsitem_id, $source, $sourcelink, $location, $contributor)
{
	global $db;
	$result = true;
  	$result = $result & updatefield(NEWSITEMS_TABLE,"source",$db->setstring($source),"newsitem_id='".$db->setinteger($newsitem_id)."'");
  	$result = $result & updatefield(NEWSITEMS_TABLE,"sourcelink",$db->setstring($sourcelink),"newsitem_id='".$db->setinteger($newsitem_id)."'");
  	$result = $result & updatefield(NEWSITEMS_TABLE,"location",$db->setstring($location),"newsitem_id='".$db->setinteger($newsitem_id)."'");
  	$result = $result & updatefield(NEWSITEMS_TABLE,"contributor",$db->setstring($contributor),"newsitem_id='".$db->setinteger($newsitem_id)."'");
  	return $result;
}

//
//
//
function fakethedate($newsitem_id,$day,$month,$year,$hours,$minutes,$seconds)
{
	global $db;
	$result=false;
  	if(strlen($day)==1) $day="0".$day;
  	if(strlen($month)==1) $month="0".$month;
  	if(strlen($hours)==1) $hours="0".$hours;
  	if(strlen($minutes)==1) $minutes="0".$minutes;
  	if(strlen($seconds)==1) $seconds="0".$seconds;
  	if(strlen($year)==4)
  	{
    	$date=$db->setinteger($year)."-".$db->setinteger($month)."-".$db->setinteger($day)." ".$db->setinteger($hours).":".$db->setinteger($minutes).":".$db->setinteger($seconds);
    	$result=updatefield(NEWSITEMS_TABLE,"date",$date,"newsitem_id='".$db->setinteger($newsitem_id)."'");
  	}
  	return $result;
}

//
//
//
function publishnewsitem($newsitem_id)
{
	global $db;
  if(!newsitempermissionrefused($newsitem_id))
  {
    return updatefield(NEWSITEMS_TABLE,"ispublished",1,"newsitem_id='".$db->setinteger($newsitem_id)."'");
  }
  else return false;
}


//
//
//
function unpublishnewsitem($newsitem_id)
{
	global $db;
  return updatefield(NEWSITEMS_TABLE,"ispublished",0,"newsitem_id='".$db->setinteger($newsitem_id)."'");
}

//
//
//
function updatenewsitemcopyright($newsitem_id,$copyright,$imagecopyright,$permission)
{
	global $db;
	$result=true;
  	$result = $result & updatefield(NEWSITEMS_TABLE,"copyright",$db->setstring($copyright),"newsitem_id='".$db->setinteger($newsitem_id)."'");
  	$result = $result & updatefield(NEWSITEMS_TABLE,"image_copyright",$db->setstring($imagecopyright),"newsitem_id='".$db->setinteger($newsitem_id)."'");

  	if($permission == PERMISSION_REFUSED)
  	{
    	unpublishnewsitem($newsitem_id);
  	}
  	$result = $result & updatefield(NEWSITEMS_TABLE,"permission",$db->setinteger($permission),"newsitem_id='".$db->setinteger($newsitem_id)."'");
  	return $result;
}


//
//
//
function addnewsitem($page_id,$sid)
{
	global $db;
  $values=array();
  $values[]=0;
  $values[]=$db->setinteger($page_id);
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
	global $db;
  deleteentry(NEWSITEMS_TABLE,"newsitem_id ='".$db->setinteger($newsitem_id)."'");
  deleteentry(NEWSITEMCATS_TABLE,"newsitem_id ='".$db->setinteger($newsitem_id)."'");
  deleteentry(NEWSITEMSECTIONS_TABLE,"newsitem_id ='".$db->setinteger($newsitem_id)."'");
}

//
// moves all newsitems in $page that are not newer than $day, $month, $year
// to a new page below $page
// returns number of archived newsitems
//
function archivenewsitems($page,$day,$month,$year,$sid)
{
	global $db;
  $page = $db->setinteger($page);
  
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
    $db->singlequery($query);
  }
  return $noofitems;
}

//
//
//
function updatenewsitemsynopsistext($newsitem_id,$text)
{
	global $db;
  	return updatefield(NEWSITEMS_TABLE,"synopsis",$db->setstring($text),"newsitem_id='".$db->setinteger($newsitem_id)."'");
}



//
//
//
function addnewsitemsection($newsitem_id, $section_id,$isquote=false)
{
	global $db;
  $newsitem_id=$db->setinteger($newsitem_id);
  $section_id=$db->setinteger($section_id);
  
  if(!$section_id)
  {
    $sections=getnewsitemsections($newsitem_id);
    if(count($sections)>0)
  	{
    	$section_id=$sections[count($sections)-1];
    }
    else $section_id=0;
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
    insertentry(NEWSITEMSECTIONS_TABLE,$values);
  }
  return $result;
}


//
//
//
function updatenewsitemsectionimagealign($section_id,$imagealign)
{
	global $db;
	return updatefield(NEWSITEMSECTIONS_TABLE,"imagealign",$db->setstring($imagealign),"newsitemsection_id='".$db->setinteger($section_id)."'");
}


//
//
//
function updatenewsitemsectionimagefilename($section_id,$imagefilename)
{
	global $db;
	return updatefield(NEWSITEMSECTIONS_TABLE,"sectionimage",$db->setstring(basename($imagefilename)),"newsitemsection_id='".$db->setinteger($section_id)."'");
}

//
//
//
function updatenewsitemsectionttitle($section_id,$title)
{
	global $db;
  	$result = updatefield(NEWSITEMSECTIONS_TABLE,"sectiontitle",$db->setstring($title),"newsitemsection_id='".$db->setinteger($section_id)."'");
  	return $result;
}


//
//
//
function updatenewsitemsectiontext($section_id,$text)
{
	global $db;
  	return updatefield(NEWSITEMSECTIONS_TABLE,"text",$db->setstring($text),"newsitemsection_id='".$db->setinteger($section_id)."'");
}


//
//
//
function deletenewsitemsection($newsitem_id, $section_id)
{
	global $db;
  $section_id=$db->setinteger($section_id);

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

//
//
//
function addrssfeed($page_id)
{
	global $db;
  insertentry(RSS_TABLE,array( 0=> $db->setinteger($page_id)));
}

//
//
//
function removerssfeed($page_id)
{
	global $db;
  deleteentry(RSS_TABLE,"page_id='".$db->setinteger($page_id)."'");
}
?>