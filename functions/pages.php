<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"functions"));

include_once($projectroot."functions/db.php");
include_once($projectroot."functions/images.php");

################################################################################
##                                                                            ##
##        Functions                                                           ##
##                                                                            ##
################################################################################


//
//
//
function getarticleoftheday()
{
	global $db;
	$date=date("Y-m-d",strtotime('now'));

	$aotd=getdbelement("aotd_id",ARTICLEOFTHEDAY_TABLE, "aotd_date", $date);
	if(!ispublished($aotd) || ispagerestricted($aotd))
	{
		$query="DELETE FROM ".ARTICLEOFTHEDAY_TABLE." where aotd_date= '".$date."';";
		$sql=$db->singlequery($query);
		$aotd=0;
	}
	if(!$aotd)
	{
		// get pages to search
		$pagestosearch=explode(",",getproperty("Article of the Day Start Pages"));
		$count=count($pagestosearch);
		$pages=array();
		for($i=0;$i<$count;$i++)
		{
			// test for nonsense in the site properties
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
		
			$sql=$db->singlequery($query);
			$pagesforselection=array();
			if($sql)
			{
				// get column
				while($row=mysql_fetch_row($sql))
				{
					if(!ispagerestricted($row[0]))
					{
						array_push($pagesforselection,$row[0]);
					}
				}
			}
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
					$sql=$db->singlequery($query);
				}
			}
		}
	}
	return $aotd;
}

// *************************** pages general ************************************* //


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
function getpagetype($page)
{
	global $db;
	return getdbelement("pagetype",PAGES_TABLE, "page_id", $db->setinteger($page));
}

//
//
//
function getpagetitle($page)
{
	global $db;
	return getdbelement("title_page",PAGES_TABLE, "page_id", $db->setinteger($page));
}

//
//
//
function getnavtitle($page)
{
	global $db;
	return getdbelement("title_navigator",PAGES_TABLE, "page_id", $db->setinteger($page));
}


//
//
//
function getpageintro($page)
{
	global $db;
	$fieldnames = array(0 => 'introtext', 1=> 'introimage', 2=>'imagehalign', 3 => 'imageautoshrink', 4 => 'usethumbnail');
	return getrowbykey(PAGES_TABLE, "page_id", $db->setinteger($page), $fieldnames);
}

//
//
//
function getpageintrotext($page)
{
	global $db;
  	return getdbelement("introtext", PAGES_TABLE, "page_id", $db->setinteger($page));
}


//
//
//
function getpageintroimage($page)
{
	global $db;
  	return getdbelement("introimage", PAGES_TABLE, "page_id", $db->setinteger($page));
}


//
//
//
function getnavposition($page)
{
	global $db;
	return getdbelement("position_navigator",PAGES_TABLE, "page_id", $db->setinteger($page));
}

//
//
//
function getpageeditor($page)
{
	global $db;
	return getdbelement("editor_id",PAGES_TABLE, "page_id", $db->setinteger($page));
}

//
// returns array of copyright, imagecopyright, permission
// permission is one of the constants PERMISSION_GRANTED, NO_PERMISSION
//
function getcopyright($page)
{
	global $db;
	$fieldnames = array(0 => 'copyright', 1=> 'image_copyright', 2=>'permission');
	return getrowbykey(PAGES_TABLE, "page_id", $db->setinteger($page), $fieldnames);
}

//
// permission is one of the constants PERMISSION_GRANTED, NO_PERMISSION
//
function getpermission($page)
{
	return getdbelement("permission",PAGES_TABLE, "page_id", $page);
}


//
//
//
function geteditdate($page)
{
	global $db;
	return getdbelement("editdate", PAGES_TABLE, "page_id", $db->setinteger($page));
}

//
//
//
function getparent($page)
{
	global $db;
	return getdbelement("parent_id",PAGES_TABLE, "page_id", $db->setinteger($page));
}


//
//
//
function getsisters($page,$ascdesc="ASC")
{
	global $db;
	return getorderedcolumn("page_id",PAGES_TABLE, "parent_id='".getparent($db->setinteger($page))."'", "position_navigator", $db->setstring($ascdesc));
}

//
//
//
function getchildren($page,$ascdesc="ASC")
{
	global $db;
	return getorderedcolumn("page_id",PAGES_TABLE, "parent_id='".$db->setinteger($page)."'", "position_navigator", $db->setstring($ascdesc));
}

//
//
//
function ispublished($page)
{
	global $db;
	return getdbelement("ispublished",PAGES_TABLE, "page_id", $db->setinteger($page));
}


//
//
//
function pageexists($page)
{
	global $db;
	$foundpage = getdbelement("page_id",PAGES_TABLE, "page_id", $db->setinteger($page));
	return $foundpage>0 && $foundpage == $page;
}


//
//
//
function isrootpage($page)
{
	return getparent($page)==0;
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
function ispagerestricted($page)
{
	global $db;
	return getdbelement("page_id",RESTRICTEDPAGES_TABLE, "page_id", $db->setinteger($page));
}


//
//
//
function isthisexactpagerestricted($page)
{
	global $db;
	return getdbelement("page_id",RESTRICTEDPAGES_TABLE, "masterpage", $db->setinteger($page));
}


//
//
//
function hasaccesssession($page)
{
	global $db, $sid;
	$result=true;

	$masterpage=getdbelement("masterpage",RESTRICTEDPAGES_TABLE, "page_id", $page);
  
	if($masterpage)
	{
		$user_id=getdbelement("session_user_id",PUBLICSESSIONS_TABLE, "session_id", $db->setstring($sid));
		$page=$db->setinteger($page);
		
		$query="select publicuser_id from ".RESTRICTEDPAGESACCESS_TABLE." where publicuser_id = '".$user_id."' AND page_id = '".$masterpage."';";
		$result = getdbresultsingle($query);
	}
	return $result;
}


//
//
//
function getsubpagesforpagetype($page, $pagetype)
{
	global $db;
	$result=array();
	$searchme=array(0 => $db->setinteger($page));
	$pagetype=$db->setstring($pagetype);
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
function hasrssfeed($page)
{
	global $db;
	return getdbelement("page_id",RSS_TABLE, "page_id", $db->setinteger($page));
}

//
//
//
function updatepagestats($page)
{
	global $db;
	if($page>0)
	{
		$year=date("Y",strtotime('now'));
		$month=date("m",strtotime('now'));
		
		$query="SELECT stats_id, viewcount FROM ".MONTHLYPAGESTATS_TABLE." WHERE ";
		$query.="year='".$year."' AND month='".$month."' AND page_id='".$db->setinteger($page)."'";
		
		//  print($query);
		$sql=$db->singlequery($query);
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
			$query="INSERT INTO ".MONTHLYPAGESTATS_TABLE." values('0','".$db->setinteger($page)."','1','".$month."','".$year."')";
		}
		//  print($query);
		$sql=$db->singlequery($query);
	}
}
?>
