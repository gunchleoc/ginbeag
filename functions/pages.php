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
	if(!ispublished($aotd) || permissionrefused($aotd) || ispagerestricted($aotd))
	{
		//    print(!ispublished($aotd)." - ".permissionrefused($aotd)." - ".ispagerestricted($aotd)."<p>");
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
			$query.=" AND page.permission <> '".PERMISSION_REFUSED."'";
		
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
function getpagetype($page_id)
{
	global $db;
	return getdbelement("pagetype",PAGES_TABLE, "page_id", $db->setinteger($page_id));
}

//
//
//
function getpagetitle($page_id)
{
	global $db;
	return getdbelement("title_page",PAGES_TABLE, "page_id", $db->setinteger($page_id));
}

//
//
//
function getnavtitle($page_id)
{
	global $db;
	return getdbelement("title_navigator",PAGES_TABLE, "page_id", $db->setinteger($page_id));
}


//
//
//
function getpageintro($page_id)
{
	global $db;
	$fieldnames = array(0 => 'introtext', 1=> 'introimage', 2=>'imagehalign');
	return getrowbykey(PAGES_TABLE, "page_id", $db->setinteger($page_id), $fieldnames);
}

//
//
//
function getpageintrotext($page_id)
{
	global $db;
  	return getdbelement("introtext", PAGES_TABLE, "page_id", $db->setinteger($page_id));
}


//
//
//
function getpageintroimage($page_id)
{
	global $db;
  	return getdbelement("introimage", PAGES_TABLE, "page_id", $db->setinteger($page_id));
}


//
//
//
function getpageintrohalign($page_id)
{
	global $db;
  	return getdbelement("imagehalign", PAGES_TABLE, "page_id", $db->setinteger($page_id));
}


//
//
//
function getnavposition($page_id)
{
	global $db;
	return getdbelement("position_navigator",PAGES_TABLE, "page_id", $db->setinteger($page_id));
}

//
//
//
function getpageeditor($page_id)
{
	global $db;
	return getdbelement("editor_id",PAGES_TABLE, "page_id", $db->setinteger($page_id));
}

//
// returns array of copyright, imagecopyright, permission
// permission is one of the constants PERMISSION_GRANTED, NO_PERMISSION, PERMISSION_REFUSED
//
function getcopyright($page_id)
{
	global $db;
	$fieldnames = array(0 => 'copyright', 1=> 'image_copyright', 2=>'permission');
	return getrowbykey(PAGES_TABLE, "page_id", $db->setinteger($page_id), $fieldnames);
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
	global $db;
	return getdbelement("editdate", PAGES_TABLE, "page_id", $db->setinteger($page_id));
}

//
//
//
function getparent($page_id)
{
	global $db;
	return getdbelement("parent_id",PAGES_TABLE, "page_id", $db->setinteger($page_id));
}


//
//
//
function getsisters($page_id,$ascdesc="ASC")
{
	global $db;
	return getorderedcolumn("page_id",PAGES_TABLE, "parent_id='".getparent($db->setinteger($page_id))."'", "position_navigator", $db->setstring($ascdesc));
}

//
//
//
function getchildren($page_id,$ascdesc="ASC")
{
	global $db;
	return getorderedcolumn("page_id",PAGES_TABLE, "parent_id='".$db->setinteger($page_id)."'", "position_navigator", $db->setstring($ascdesc));
}

//
//
//
function ispublished($page_id)
{
	global $db;
	return getdbelement("ispublished",PAGES_TABLE, "page_id", $db->setinteger($page_id));
}


//
//
//
function pageexists($page_id)
{
	global $db;
	$foundpage = getdbelement("page_id",PAGES_TABLE, "page_id", $db->setinteger($page_id));
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
	global $db;
	return getdbelement("page_id",RESTRICTEDPAGES_TABLE, "page_id", $db->setinteger($page_id));
}


//
//
//
function isthisexactpagerestricted($page_id)
{
	global $db;
	return getdbelement("page_id",RESTRICTEDPAGES_TABLE, "masterpage", $db->setinteger($page_id));
}


//
//
//
function hasaccesssession($sid, $page_id)
{
	global $db;
	$result=true;

	$masterpage=getdbelement("masterpage",RESTRICTEDPAGES_TABLE, "page_id", $page_id);
  
	if($masterpage)
	{
		$user_id=getdbelement("session_user_id",PUBLICSESSIONS_TABLE, "session_id", $db->setstring($sid));
		$page_id=$db->setinteger($page_id);
		
		$query="select publicuser_id from ".RESTRICTEDPAGESACCESS_TABLE." where publicuser_id = '".$user_id."' AND page_id = '".$masterpage."';";
		$result = getdbresultsingle($query);
	}
	return $result;
}


//
//
//
function showpermissionrefusedimages($page_id)
{
	global $db;
	$showrefused = getdbelement("showpermissionrefusedimages",PAGES_TABLE, "page_id", $db->setinteger($page_id));
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
	return !imagepermissionrefused($image) || $showhidden || (ispagerestricted($page_id) && showpermissionrefusedimages($page_id));
}


//
//
//
function getsubpagesforpagetype($page_id, $pagetype)
{
	global $db;
	$result=array();
	$searchme=array(0 => $db->setinteger($page_id));
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
function hasrssfeed($page_id)
{
	global $db;
	return getdbelement("page_id",RSS_TABLE, "page_id", $db->setinteger($page_id));
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
