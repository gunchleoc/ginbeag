<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"pagecontent"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."functions/db.php");

//
//
//
function updatearticlesource($page,$author,$location,$day,$month,$year,$source,$sourcelink,$toc)
{
	global $db;
	$page=$db->setinteger($page);

	if($toc=="true") $toc=1;
	else $toc=0;

	if(strlen($year)!=4) $year="0000";
	updatefield(ARTICLES_TABLE,"article_author",$db->setstring($author),"page_id='".$page."'");
	updatefield(ARTICLES_TABLE,"location",$db->setstring($location),"page_id='".$page."'");
	updatefield(ARTICLES_TABLE,"day",$db->setinteger($day),"page_id='".$page."'");
	updatefield(ARTICLES_TABLE,"month",$db->setinteger($month),"page_id='".$page."'");
	updatefield(ARTICLES_TABLE,"year",$db->setinteger($year),"page_id='".$page."'");
	updatefield(ARTICLES_TABLE,"source",$db->setstring($source),"page_id='".$page."'");
	updatefield(ARTICLES_TABLE,"sourcelink",$db->setstring($sourcelink),"page_id='".$page."'");
	updatefield(ARTICLES_TABLE,"use_toc",$toc,"page_id='".$page."'");
}

//
//
//
function addarticlepage($page)
{
	global $db;
	$numberofpages=numberofarticlepages($db->setinteger($page));
	if(getlastarticlesection($page,$numberofpages))
	{
		return updatefield(ARTICLES_TABLE,"numberofpages",$numberofpages+1,"page_id='".$page."'");
	}
	else return false;
}


//
//
//
function deletelastarticlepage($page)
{
	global $db;
	$page=$db->setinteger($page);
	$numberofpages=numberofarticlepages($db->setinteger($page));

	if(!getlastarticlesection($page,$numberofpages))
	{
		return updatefield(ARTICLES_TABLE,"numberofpages",$numberofpages-1,"page_id='".$page."'");
	}
	else return false;
}

//
//
//
function addarticlesection($page,$pagenumber)
{
	global $db;
	$page=$db->setinteger($page);
	$pagenumber=$db->setinteger($pagenumber);

	$lastsection=getlastarticlesection($page,$pagenumber);

	$values=array();
	$values[]=0;
	$values[]=$page;
	$values[]=$pagenumber;
	$values[]=$lastsection+1;
	$values[]="";
	$values[]="";
	$values[]="";
	$values[]="left";
	$values[]=1;
	$values[]=1;
	return insertentry(ARTICLESECTIONS_TABLE,$values);
}


//
//
//
function updatearticlesectionimagealign($articlesection,$imagealign)
{
	global $db;
	return updatefield(ARTICLESECTIONS_TABLE,"imagealign",$db->setstring($imagealign),"articlesection_id='".$db->setinteger($articlesection)."'");
}


//
//
//
function updatearticlesectionimagesize($articlesection,$autoshrink, $usethumbnail)
{
	global $db;
	$success = updatefield(ARTICLESECTIONS_TABLE,"imageautoshrink",$db->setinteger($autoshrink),"articlesection_id='".$db->setinteger($articlesection)."'");
	return $success & updatefield(ARTICLESECTIONS_TABLE,"usethumbnail",$db->setinteger($usethumbnail),"articlesection_id='".$db->setinteger($articlesection)."'");
}


//
//
//
function updatearticlesectionimagefilename($articlesection,$imagefilename)
{
	global $db;
	return updatefield(ARTICLESECTIONS_TABLE,"sectionimage",$db->setstring(basename($imagefilename)),"articlesection_id='".$db->setinteger($articlesection)."'");
}

//
//
//
function updatearticlesectiontitle($articlesection,$sectiontitle)
{
	global $db;
  	return updatefield(ARTICLESECTIONS_TABLE,"sectiontitle",$db->setstring($sectiontitle),"articlesection_id='".$db->setinteger($articlesection)."'");
}

//
//
//
function updatearticlesectiontext($articlesection,$text)
{
	global $db;
  	return updatefield(ARTICLESECTIONS_TABLE,"text",$db->setstring($text),"articlesection_id='".$db->setinteger($articlesection)."'");
}


//
//
//
function deletearticlesection($articlesection)
{
	global $db;
	return deleteentry(ARTICLESECTIONS_TABLE,"articlesection_id ='".$db->setinteger($articlesection)."'");
}


//
// returns the articlepage the section will be in after the move
//
function movearticlesection($articlesection,$pagenumber,$direction)
{
	global $db;
	$articlesection=$db->setinteger($articlesection);
	$page=getdbelement("article_id",ARTICLESECTIONS_TABLE, "articlesection_id", $articlesection);
	$pagenumber=$db->setinteger($pagenumber);
	$navpos=getarticlesectionnumber($articlesection);

	$result=$pagenumber;

	// move section to next articlepage
	if($direction==="down" && $navpos==getlastarticlesection($page,$pagenumber))
	{
		// prepare page
		$noofpages=numberofarticlepages($page);
		$newpage=$pagenumber+1;
		$result=$newpage;

		if($noofpages<$newpage)
		{
			addarticlepage($page);
		}
		else
		{
			// make room
			$sisterids=getorderedcolumn("articlesection_id",ARTICLESECTIONS_TABLE, "article_id='".$page."' AND pagenumber='".$newpage."'", "sectionnumber", "ASC");
			for($i=0;$i<count($sisterids);$i++)
			{
				$sisternavpos=getarticlesectionnumber($sisterids[$i]);
				updatefield(ARTICLESECTIONS_TABLE,"sectionnumber",$sisternavpos+1,"articlesection_id='".$sisterids[$i]."'");
			}
		}
		// move
		updatefield(ARTICLESECTIONS_TABLE,"pagenumber",$newpage,"articlesection_id='".$articlesection."'");
		updatefield(ARTICLESECTIONS_TABLE,"sectionnumber",1,"articlesection_id='".$articlesection."'");
	}
	// move section to previous articlepage
	elseif($direction==="up" && $navpos==1 && $pagenumber>1)
	{
		$newpage=$pagenumber-1;
		$result=$newpage;

		$newnav=getlastarticlesection($page,$newpage);
		$newnav++;
		updatefield(ARTICLESECTIONS_TABLE,"pagenumber",$newpage,"articlesection_id='".$articlesection."'");
		updatefield(ARTICLESECTIONS_TABLE,"sectionnumber",$newnav,"articlesection_id='".$articlesection."'");

		// fill space
		$sisterids=getorderedcolumn("articlesection_id",ARTICLESECTIONS_TABLE, "article_id='".$page."' AND pagenumber='".$pagenumber."'", "sectionnumber", "ASC");
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
			$sisterids=getorderedcolumn("articlesection_id",ARTICLESECTIONS_TABLE, "article_id='".$page."' AND pagenumber='".$pagenumber."'", "sectionnumber", "ASC");
		}
		else
		{
			$sisterids=getorderedcolumn("articlesection_id",ARTICLESECTIONS_TABLE, "article_id='".$page."' AND pagenumber='".$pagenumber."'", "sectionnumber", "DESC");
		}
		$found=false;
		$idposition=0;
		for($i=0;$i<count($sisterids)&&!$found;$i++)
		{
			if($articlesection==$sisterids[$i])
			{
				$found=true;
				$idposition=$i;
			}
		}
		if($found && $idposition+1<count($sisterids))
		{
			$otherid=$sisterids[$idposition+1];
			$navpos=getarticlesectionnumber($articlesection);
			$othernavpos=getarticlesectionnumber($otherid);

			$swap[$articlesection]=$othernavpos;
			$swap[$otherid]=$navpos;
			updateentries(ARTICLESECTIONS_TABLE,$swap,"articlesection_id","sectionnumber");
		}
	}
	return $result;
}

?>
