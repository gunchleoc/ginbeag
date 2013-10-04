<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"pagecontent"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"functions"));

include_once($projectroot."functions/db.php");

//
//
//
function getpublishednewsitems($page_id,$number,$offset)
{
	global $db;
	$page_id=$db->setinteger($page_id);
	if(!$offset) $offset=0;
	if(!$number>0) $number=1;
	$condition="page_id='".$page_id."' AND ispublished='1'";
	if(displaynewestnewsitemfirst($page_id)) $order="DESC";
	else $order="ASC";
	return getorderedcolumnlimit("newsitem_id",NEWSITEMS_TABLE,$condition, "date", $db->setinteger($offset), $db->setinteger($number),$order);
}



//
//
//
function displaynewestnewsitemfirst($page_id)
{
	global $db;
	return getdbelement("shownewestfirst",NEWS_TABLE, "page_id", $db->setinteger($page_id));
}

//
//
//
function getnewsitemoffset($page_id,$number,$newsitem,$showhidden=false)
{
	global $db;
	if(!$number>0) $number=1;
	$date=getdbelement("date",NEWSITEMS_TABLE, "newsitem_id", $db->setinteger($newsitem));
	$condition="page_id='".$db->setinteger($page_id)."'";
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
	global $db;
	$condition="page_id='".$db->setinteger($page_id)."' AND ispublished='1'";
	return countelementscondition("newsitem_id",NEWSITEMS_TABLE, $condition);
}


//
//
//
function getnewsitemcontents($newsitem_id)
{
	global $db;
	return getrowbykey(NEWSITEMS_TABLE, "newsitem_id", $db->setinteger($newsitem_id));
}

//
// returns a date array
//
function getnewsitemdate($newsitem_id)
{
	global $db;
	$date =getdbelement("date",NEWSITEMS_TABLE, "newsitem_id",$db->setinteger($newsitem_id));
	return @getdate(strtotime($date));
}

//
//
//
function getoldestnewsitemdate($page_id)
{
	global $db;
	$date=getmin("date",NEWSITEMS_TABLE, "page_id",$db->setinteger($page_id));
	return @getdate(strtotime($date));
}

//
//
//
function getnewestnewsitemdate($page_id)
{
	global $db;
	$date=getmax("date", NEWSITEMS_TABLE, "page_id",$db->setinteger($page_id));
	return @getdate(strtotime($date));
}

//
//
//
function getnewsitemsynopsistext($newsitem_id)
{
	global $db;
  return getdbelement("synopsis", NEWSITEMS_TABLE, "newsitem_id", $db->setinteger($newsitem_id));
}

//
//
//
function getnewsitemsynopsisimageids($newsitem_id)
{
	global $db;
	$condition= "newsitem_id='".$db->setinteger($newsitem_id)."'";
	return getorderedcolumn("newsitemimage_id",NEWSITEMSYNIMG_TABLE, $condition, "position", "ASC");
}

//
//
//
function getnewsitemsynopsisimage($newsitemimage_id)
{
	global $db;
	return getdbelement("image_filename",NEWSITEMSYNIMG_TABLE, "newsitemimage_id", $db->setinteger($newsitemimage_id));
}


//
//
//
function getnewsitemsynopsisimages($newsitem_id)
{
	global $db;
	$condition= "newsitem_id='".$db->setinteger($newsitem_id)."'";
	return getorderedcolumn("image_filename",NEWSITEMSYNIMG_TABLE, $condition, "position", "ASC");
}

//
//
//
function getnewsitemsections($newsitem_id)
{
	global $db;
	$condition= "newsitem_id='".$db->setinteger($newsitem_id)."'";
	return getorderedcolumn("newsitemsection_id",NEWSITEMSECTIONS_TABLE, $condition, "sectionnumber", "ASC");
}

//
//
//
function getnewsitemsectioncontents($section_id)
{
	global $db;
	return getrowbykey(NEWSITEMSECTIONS_TABLE, "newsitemsection_id", $db->setinteger($section_id));
}

//
//
//
function getnewsitemsectiontext($section_id)
{
	global $db;
	return getdbelement("text",NEWSITEMSECTIONS_TABLE, "newsitemsection_id", $db->setinteger($section_id));
}


//
//
//
function getnewsitemsectionimage($section_id)
{
	global $db;
	return getdbelement("sectionimage",NEWSITEMSECTIONS_TABLE, "newsitemsection_id", $db->setinteger($section_id));
}


//
//
//
function getnewsitemsectionimagealign($section_id)
{
	global $db;
	return getdbelement("imagealign",NEWSITEMSECTIONS_TABLE, "newsitemsection_id", $db->setinteger($section_id));
}


//
//
//
function getnewsitemsectionnumber($section_id)
{
	global $db;
	return getdbelement("sectionnumber",NEWSITEMSECTIONS_TABLE, "newsitemsection_id", $db->setinteger($section_id));
}

//
//
//
function isnewsitempublished($newsitem_id)
{
	global $db;
	return getdbelement("ispublished",NEWSITEMS_TABLE, "newsitem_id", $db->setinteger($newsitem_id));
}


//
// returns array of copyright, imagecopyright, permission
// permission is one of the constants PERMISSION_GRANTED, NO_PERMISSION, PERMISSION_REFUSED
//
function getnewsitemcopyright($newsitem_id)
{
	global $db;
	$fieldnames = array(0 => 'copyright', 1=> 'image_copyright', 2=>'permission');
	return getrowbykey(NEWSITEMS_TABLE, "newsitem_id", $db->setinteger($newsitem_id), $fieldnames);
}

//
//
//
function getnewsitempermission($newsitem_id)
{
	global $db;
	return getdbelement("permission",NEWSITEMS_TABLE, "newsitem_id", $db->setinteger($newsitem_id));
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
	global $db;
	return getmax("sectionnumber",NEWSITEMSECTIONS_TABLE,"newsitem_id ='".$db->setinteger($newsitem_id)."'");
}




//
//
//
function getfilterednewsitems($page,$selectedcat,$from,$to,$order,$ascdesc,$newsitemsperpage,$offset)
{
	global $db;
	$page=$db->setinteger($page);
	$selectedcat=$db->setinteger($selectedcat);
	$order=$db->setstring($order);
	$ascdesc=$db->setstring($ascdesc);
	$offset=$db->setinteger($offset);
	
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
	return getdbresultcolumn($query);
}

//
//
//
function searchnewsitemtitles($search,$page,$showhidden=false)
{
	$query="SELECT DISTINCTROW newsitem_id FROM ".NEWSITEMS_TABLE;
	$query.=" WHERE page_id = '".$db->setinteger($page)."'";
	$query.=" AND title like '%".$db->setstring(trim($search))."%'";
	
	//  $query.=" AND MATCH(title) AGAINST('".str_replace(" ",",",trim($db->setstring($search)))."'))";
	return getdbresultcolumn($query);
}

//
// todo: refine match all
//
function searchnewsitems($search,$page,$all,$showhidden=false)
{
	global $db;
	$page=$db->setinteger($page);
	
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
	
	$sql=$db->singlequery($query);
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
	$sql=$db->singlequery($query);
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
			
			$sql=$db->singlequery($query);
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
			
			$sql=$db->singlequery($query);
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

?>