<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/dbmod.php");
include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."functions/users.php");
include_once($projectroot."functions/pages.php");



// *************************** edit ***************************************** //

//
//
//
function renamepage($page, $title_navigator, $title_page)
{
	global $db;
	$result= updatefield(PAGES_TABLE,"title_page",$db->setstring($title_page),"page_id='".$db->setinteger($page)."'");
	$result= $result & updatefield(PAGES_TABLE,"title_navigator",$db->setstring($title_navigator),"page_id='".$db->setinteger($page)."'");
	return $result;
}


//
//
//
function updatepageintro($page, $introtext)
{
	global $db;
  	return updatefield(PAGES_TABLE,"introtext",$db->setstring($introtext) ,"page_id='".$db->setinteger($page)."'");
}


//
//
//
function updatepageintroimagealign($page,$imagealign)
{
	global $db;
  	return updatefield(PAGES_TABLE,"imagehalign",$db->setstring($imagealign),"page_id='".$db->setinteger($page)."'");
}


//
//
//
function updatepageintroimagesize($page,$autoshrink, $usethumbnail)
{
	global $db;
	$success = updatefield(PAGES_TABLE,"imageautoshrink",$db->setinteger($autoshrink),"page_id='".$db->setinteger($page)."'");
	return $success & updatefield(PAGES_TABLE,"usethumbnail",$db->setinteger($usethumbnail),"page_id='".$db->setinteger($page)."'");
}


//
//
//
function updatepageintroimagefilename($page,$imagefilename)
{
	global $db;
  	return updatefield(PAGES_TABLE,"introimage",$db->setstring(basename($imagefilename)),"page_id='".$db->setinteger($page)."'");
}


//
//
//
function movepage($page, $direction, $positions=1)
{
	global $db;
	$page=$db->setinteger($page);
	$result = true;
	
	if($direction==="top")
	{
		$minpos=getmin("position_navigator",PAGES_TABLE, "parent_id='".getparent($page)."'");
		if($minpos<=1)
		{
			$sisterids=getsisters($page);
			$newpos=array();
			for($i=0;$i<count($sisterids);$i++)
			{
				$newpos[$sisterids[$i]]=getnavposition($sisterids[$i])+1;
			}
				updateentries(PAGES_TABLE,$newpos,"page_id","position_navigator");
			}
		$result = $result & updatefield(PAGES_TABLE,"position_navigator",1,"page_id='".$page."'");
	}
	elseif($direction==="bottom")
	{
		$maxpos=getmax("position_navigator",PAGES_TABLE, "parent_id='".getparent($page)."'");
		$result = $result & updatefield(PAGES_TABLE,"position_navigator",$maxpos+1,"page_id='".$page."'");
	}
	elseif($positions>0)
	{
		if($direction==="down")
		{
			$sisterids=getsisters($page);
		}
		else
		{
			$sisterids=getsisters($page, "DESC");
		}
		$found=false;
		$idposition=0;
		for($i=0;$i<count($sisterids)&&!$found;$i++)
		{
			if($page==$sisterids[$i])
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
			$result = $result & updateentries(PAGES_TABLE,$swap,"page_id","position_navigator");
		}
	}
	return $result;
}

//
//
//
function getallsubpageids($page)
{
	global $db;
	return getorderedcolumn("page_id",PAGES_TABLE, "parent_id='".$db->setinteger($page)."'", "position_navigator", "ASC");
}

//
//
//
function getallsubpagenavtitles($page)
{
	global $db;
	return getorderedcolumn("title_navigator",PAGES_TABLE, "parent_id='".$db->setinteger($page)."'", "position_navigator", "ASC");
}

//
//
//
function getlastnavposition($pageid)
{
	global $db;
	return getmax("position_navigator",PAGES_TABLE, "parent_id = '".$db->setinteger($pageid)."'");
}


//
// todo return error states
//
function movetonewparentpage($page,$newparent)
{
	global $db;
	$result="";
	
	$newparent=$db->setinteger($newparent);
	$page=$db->setinteger($page);
	
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
			deleteentry(RESTRICTEDPAGES_TABLE,"page_id ='".$db->setinteger($page)."'");
		}
	}
	return $result;
}

//
//
//
function getmovetargets($page)
{
	global $db;
	$parent=getparent($page);
	$pagetype=getpagetype($db->setinteger($page));
	
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
	global $db;
	$result=array();
	$allowroot=getdbelement("allow_root",PAGETYPES_TABLE, "type_key",$db->setstring($pagetype));
	$allowmenu=getdbelement("allow_simplemenu",PAGETYPES_TABLE, "type_key",$db->setstring($pagetype));
	$allowself=getdbelement("allow_self",PAGETYPES_TABLE, "type_key",$db->setstring($pagetype));
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
	global $db;
	$result=false;
	
	if($parentpage==0)
	{
		$parentpagetype="root";
	}
	else
	{
		$parentpagetype=getpagetype($db->setinteger($parentpage));
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
	global $db;
	$result=array();
	$pagetype = $db->setstring($pagetype);
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
	global $db;
	$result = updatefield(PAGETYPES_TABLE,"allow_root",$db->setinteger($allowroot),"type_key='".$db->setstring($pagetype)."'");
	$result = $result & updatefield(PAGETYPES_TABLE,"allow_simplemenu",$db->setinteger($allowsimplemenu),"type_key='".$db->setstring($pagetype)."'");
}

//
//
//
function publish($page)
{
	global $db;
	if(ispublishable($page))
	{
		return updatefield(PAGES_TABLE,"ispublished",1 ,"page_id='".$db->setinteger($page)."'");
	}
	else return false;
}

//
//
//
function unpublish($page)
{
	global $db;
	return updatefield(PAGES_TABLE,"ispublished",0 ,"page_id='".$db->setinteger($page)."'");
}


//
//
//
function makepublishable($page)
{
	global $db;
    return updatefield(PAGES_TABLE,"ispublishable",1 ,"page_id='".$db->setinteger($page)."'");
}


//
//
//
function hide($page)
{
	global $db;
	if(!ispublished($page))
	{
		return updatefield(PAGES_TABLE,"ispublishable",0 ,"page_id='".$db->setinteger($page)."'");
	}
	else return false;
}



//
//
//
function ispublishable($page)
{
	global $db;
	$pagetype=getpagetype($page);
	
	if(getpermission($page)==PERMISSION_REFUSED)
	{
		return 0;
	}
	else
	{
		return getdbelement("ispublishable",PAGES_TABLE, "page_id",$db->setinteger($page));
	}
}



//
//
//
function updateeditdata($page)
{
	global $db;
	$now=date(DATETIMEFORMAT, strtotime('now'));
	$result = updatefield(PAGES_TABLE,"editdate",$now,"page_id='".$db->setinteger($page)."'");
	$result = $result & updatefield(PAGES_TABLE,"editor_id",getsiduser(),"page_id='".$db->setinteger($page)."'");
}

//
// permission is one of the constants PERMISSION_GRANTED, NO_PERMISSION, PERMISSION_REFUSED
//
function updatecopyright($page,$copyright,$imagecopyright,$permission)
{
	global $db;
	$page=$db->setinteger($page);
	$result = updatefield(PAGES_TABLE,"copyright",$db->setstring($copyright),"page_id='".$page."'");
	$result = $result & updatefield(PAGES_TABLE,"image_copyright",$db->setstring($imagecopyright),"page_id='".$page."'");
	$result = $result & updatefield(PAGES_TABLE,"permission",$db->setinteger($permission),"page_id='".$page."'");
	
	if($db->setinteger($permission)==PERMISSION_REFUSED)
	{
		$result = $result & unpublish($page);
		$result = $result & hide($page);
	}
	return $result;
}



// *************************** restricted access **************************** //

//
//
//
function restrictaccess($page)
{
	global $db;
	$page = $db->setinteger($page);
	$result = true;
	if(ispagerestricted($page))
	{
		$result = $result & updatefield(RESTRICTEDPAGES_TABLE,"masterpage",$page,"page_id = ".$page);
	}
	else
	{
		$result = $result & insertentry(RESTRICTEDPAGES_TABLE,array(0=>$page, 1=>$page));
	}
	rebuildaccessrestrictionindex();
	return $result;
}


//
//
//
function removeaccessrestriction($page)
{
	global $db;
	$result = deleteentry(RESTRICTEDPAGES_TABLE,"masterpage ='".$db->setinteger($page)."'");
	$result = $result & deleteentry(RESTRICTEDPAGESACCESS_TABLE,"page_id ='".$db->setinteger($page)."'");
	rebuildaccessrestrictionindex();
	return $result;
}

//
// must be called when editing the pages that are restricted
//
function rebuildaccessrestrictionindex()
{
	global $db;
	
	$result="";
	
	$result.='<p class="highlight">Rebuilt index of restricted pages:</p>';
	// get masterpages from access table
	$masterpages=getcolumn("page_id",RESTRICTEDPAGESACCESS_TABLE, "1");
	$masterpages2=getdistinctorderedcolumn("masterpage", RESTRICTEDPAGES_TABLE,"1", "masterpage","ASC");
	$masterpages=array_unique(array_merge($masterpages,$masterpages2));
	
	// clear masterpages
	$sql = "truncate table ".RESTRICTEDPAGES_TABLE;
	$db->singlequery($sql);

	// define masterpages
	while($masterpage=current($masterpages))
	{
		$result.=' '.$masterpage;
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
				$result.=' '.$child;
				insertentry(RESTRICTEDPAGES_TABLE,array(0=>$child, 1=>$masterpage));
				$children = array_merge($children,getchildren($child));
			}
		}
	}
	//$result.='<span class="highlight"> ... done!</span>';
	return $result;
}


//
//
//
function getpageaccessforpublicuser($user)
{
	global $db;
	return getcolumn("page_id",RESTRICTEDPAGESACCESS_TABLE, "publicuser_id = '".$db->setinteger($user)."'");
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
function getpagerestrictionmaster($page)
{
	global $db;
	return getdbelement("masterpage",RESTRICTEDPAGES_TABLE, "page_id", $db->setinteger($page));
}


//
//
//
function hasaccess($user, $page)
{
	global $db;
	$masterpage=getdbelement("masterpage",RESTRICTEDPAGES_TABLE, "page_id", $db->setinteger($page));
	$query="select publicuser_id from ".RESTRICTEDPAGESACCESS_TABLE." where publicuser_id = '".$db->setinteger($user)."' AND page_id = '".$masterpage."';";
	return getdbresultsingle($query);
}


//
//
//
function setshowpermissionrefusedimages($page, $show)
{
	global $db;
	$page = $db->setinteger($page);
	
	if($show && ispagerestricted($page))
	{
		$result = updatefield(PAGES_TABLE,"showpermissionrefusedimages",1,"page_id='".$page."'");
	}
	else
	{
		$result = updatefield(PAGES_TABLE,"showpermissionrefusedimages",0,"page_id='".$page."'");
	}
	return $result;
}


// *************************** lock handling **************************************** //



//
// lock handling
// returns empty string when lock has been obtained
// else returns string containing reason for lock
//
function getpagelock($page)
{
  	global $db;

  	$result="";

    $lock=getlock($page);
    if($lock['user_id'] && $lock['user_id']!==getsiduser() )
    {
    	// if session of lock owner has espired, clear lock
    	$other_sid= getdbelement("session_id",SESSIONS_TABLE, "session_user_id", $db->setinteger($lock['user_id']));
    	if(timeout($other_sid))
    	{
    		unlockpage($page);
    	}
    	else
    	{
    	
	      $result="This page has been locked by <i>";
	      $result.=getusername($lock['user_id']);
	      $result.="</i> on ";
	      $result.=formatdatetime($lock['locktime']);
		}
    }
    else
    {
      lockpage(getsiduser(), $page);
    }

  	return $result;
}


//
//
//
function lockpage($user, $page)
{
	global $db;
	$now=date(DATETIMEFORMAT, strtotime('now'));
	
	$page=$db->setinteger($page);
	$user=$db->setinteger($user);
	
	$lockuserid=getdbelement("user_id",LOCKS_TABLE, "page_id", $page);
	if($lockuserid)
	{
		$result = updatefield(LOCKS_TABLE,"locktime",$now,"page_id='".$page."'");
		$result = $result & updatefield(LOCKS_TABLE,"user_id",$user,"page_id='".$page."'");
	}
	else
	{
		$values=array();
		$values[]=$page;
		$values[]=$user;
		$values[]=$now;
		$result = insertentry(LOCKS_TABLE,$values);
	}
	return $result;
}

//
//
//
function unlockpage($page)
{
	global $db;
	return deleteentry(LOCKS_TABLE,"page_id='".$db->setinteger($page)."'");
}

//
//
//
function unlockuserpages()
{
	return deleteentry(LOCKS_TABLE,"user_id='".getsiduser()."'");
}

//
// array user_id, locktime
//
function getlock($page, $user=false)
{
	global $db;
	// clear old locks
	$time=date(DATETIMEFORMAT, strtotime('-30 minutes'));
	deleteentry(LOCKS_TABLE,"locktime<'".$time."'");
	
	$result['user_id']= getdbelement("user_id",LOCKS_TABLE, "page_id",$db->setinteger($page));
	if($result['user_id'])
	{
		$result['locktime']=getdbelement("locktime",LOCKS_TABLE, "page_id",$db->setinteger($page));
	}
	return $result;
}

//
// array user_id, locktime
//
function islocked($page)
{
	return getlock($page);
}
?>