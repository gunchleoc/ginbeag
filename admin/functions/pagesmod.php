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
function renamepage($page_id, $title_navigator, $title_page)
{
	global $db;
	$result= updatefield(PAGES_TABLE,"title_page",$db->setstring($title_page),"page_id='".$db->setinteger($page_id)."'");
	$result= $result & updatefield(PAGES_TABLE,"title_navigator",$db->setstring($title_navigator),"page_id='".$db->setinteger($page_id)."'");
	return $result;
}


//
//
//
function updatepageintro($page_id, $introtext)
{
	global $db;
  	return updatefield(PAGES_TABLE,"introtext",$db->setstring($introtext) ,"page_id='".$db->setinteger($page_id)."'");
}


//
//
//
function updatepageintroimagealign($page_id,$imagealign)
{
	global $db;
  	return updatefield(PAGES_TABLE,"imagehalign",$db->setstring($imagealign),"page_id='".$db->setinteger($page_id)."'");
}

//
//
//
function updatepageintroimagefilename($page_id,$imagefilename)
{
	global $db;
  	return updatefield(PAGES_TABLE,"introimage",$db->setstring(basename($imagefilename)),"page_id='".$db->setinteger($page_id)."'");
}


//
//
//
function movepage($page_id, $direction, $positions=1)
{
	global $db;
	$page_id=$db->setinteger($page_id);
	$result = true;
	
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
		$result = $result & updatefield(PAGES_TABLE,"position_navigator",1,"page_id='".$page_id."'");
	}
	elseif($direction==="bottom")
	{
		$maxpos=getmax("position_navigator",PAGES_TABLE, "parent_id='".getparent($page_id)."'");
		$result = $result & updatefield(PAGES_TABLE,"position_navigator",$maxpos+1,"page_id='".$page_id."'");
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
function publish($page_id)
{
	global $db;
	if(ispublishable($page_id))
	{
		return updatefield(PAGES_TABLE,"ispublished",1 ,"page_id='".$db->setinteger($page_id)."'");
	}
	else return false;
}

//
//
//
function unpublish($page_id)
{
	global $db;
	return updatefield(PAGES_TABLE,"ispublished",0 ,"page_id='".$db->setinteger($page_id)."'");
}


//
//
//
function makepublishable($page_id)
{
	global $db;
    return updatefield(PAGES_TABLE,"ispublishable",1 ,"page_id='".$db->setinteger($page_id)."'");
}


//
//
//
function hide($page_id)
{
	global $db;
	if(!ispublished($page_id))
	{
		return updatefield(PAGES_TABLE,"ispublishable",0 ,"page_id='".$db->setinteger($page_id)."'");
	}
	else return false;
}



//
//
//
function ispublishable($page_id)
{
	global $db;
	$pagetype=getpagetype($page_id);
	
	if(getpermission($page_id)==PERMISSION_REFUSED)
	{
		return 0;
	}
	else
	{
		return getdbelement("ispublishable",PAGES_TABLE, "page_id",$db->setinteger($page_id));
	}
}



//
//
//
function updateeditdata($page_id, $sid)
{
	global $db;
	$now=date(DATETIMEFORMAT, strtotime('now'));
	$result = updatefield(PAGES_TABLE,"editdate",$now,"page_id='".$db->setinteger($page_id)."'");
	$result = $result & updatefield(PAGES_TABLE,"editor_id",getsiduser($sid),"page_id='".$db->setinteger($page_id)."'");
}

//
// permission is one of the constants PERMISSION_GRANTED, NO_PERMISSION, PERMISSION_REFUSED
//
function updatecopyright($page_id,$copyright,$imagecopyright,$permission)
{
	global $db;
	$page_id=$db->setinteger($page_id);
	$result = updatefield(PAGES_TABLE,"copyright",$db->setstring($copyright),"page_id='".$page_id."'");
	$result = $result & updatefield(PAGES_TABLE,"image_copyright",$db->setstring($imagecopyright),"page_id='".$page_id."'");
	$result = $result & updatefield(PAGES_TABLE,"permission",$db->setinteger($permission),"page_id='".$page_id."'");
	
	if($db->setinteger($permission)==PERMISSION_REFUSED)
	{
		$result = $result & unpublish($page_id);
		$result = $result & hide($page_id);
	}
	return $result;
}



// *************************** restricted access **************************** //

//
//
//
function restrictaccess($page_id)
{
	global $db;
	$page_id = $db->setinteger($page_id);
	$result = true;
	if(ispagerestricted($page_id))
	{
		$result = $result & updatefield(RESTRICTEDPAGES_TABLE,"masterpage",$page_id,"page_id = ".$page_id);
	}
	else
	{
		$result = $result & insertentry(RESTRICTEDPAGES_TABLE,array(0=>$page_id, 1=>$page_id));
	}
	rebuildaccessrestrictionindex();
	return $result;
}


//
//
//
function removeaccessrestriction($page_id)
{
	global $db;
	$result = deleteentry(RESTRICTEDPAGES_TABLE,"masterpage ='".$db->setinteger($page_id)."'");
	$result = $result & deleteentry(RESTRICTEDPAGESACCESS_TABLE,"page_id ='".$db->setinteger($page_id)."'");
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
function getpageaccessforpublicuser($user_id)
{
	global $db;
	return getcolumn("page_id",RESTRICTEDPAGESACCESS_TABLE, "publicuser_id = '".$db->setinteger($user_id)."'");
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
	global $db;
	return getdbelement("masterpage",RESTRICTEDPAGES_TABLE, "page_id", $db->setinteger($page_id));
}


//
//
//
function hasaccess($user_id, $page_id)
{
	global $db;
	$masterpage=getdbelement("masterpage",RESTRICTEDPAGES_TABLE, "page_id", $db->setinteger($page_id));
	$query="select publicuser_id from ".RESTRICTEDPAGESACCESS_TABLE." where publicuser_id = '".$db->setinteger($user_id)."' AND page_id = '".$masterpage."';";
	return getdbresultsingle($query);
}


//
//
//
function setshowpermissionrefusedimages($page_id, $show)
{
	global $db;
	$page_id = $db->setinteger($page_id);
	
	if($show && ispagerestricted($page_id))
	{
		$result = updatefield(PAGES_TABLE,"showpermissionrefusedimages",1,"page_id='".$page_id."'");
	}
	else
	{
		$result = updatefield(PAGES_TABLE,"showpermissionrefusedimages",0,"page_id='".$page_id."'");
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
  	global $sid, $_GET;

  	$result="";

    $lock=getlock($page);
    if($lock['user_id'] && $lock['user_id']!==getsiduser($sid) )
    {
    	// if session of lock owner has espired, clear lock
    	$other_sid=getusersid($lock['user_id']);
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
      lockpage(getsiduser($sid), $page);
    }

  	return $result;
}

//
//
//
function lockpage($user_id, $page_id)
{
	global $db;
	$now=date(DATETIMEFORMAT, strtotime('now'));
	
	$page_id=$db->setinteger($page_id);
	$user_id=$db->setinteger($user_id);
	
	$lockuserid=getdbelement("user_id",LOCKS_TABLE, "page_id", $page_id);
	if($lockuserid)
	{
		$result = updatefield(LOCKS_TABLE,"locktime",$now,"page_id='".$page_id."'");
		$result = $result & updatefield(LOCKS_TABLE,"user_id",$user_id,"page_id='".$page_id."'");
	}
	else
	{
		$values=array();
		$values[]=$page_id;
		$values[]=$user_id;
		$values[]=$now;
		$result = insertentry(LOCKS_TABLE,$values);
	}
	return $result;
}

//
//
//
function unlockpage($page_id)
{
	global $db;
	return deleteentry(LOCKS_TABLE,"page_id='".$db->setinteger($page_id)."'");
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
	global $db;
	// clear old locks
	$time=date(DATETIMEFORMAT, strtotime('-30 minutes'));
	deleteentry(LOCKS_TABLE,"locktime<'".$time."'");
	
	$result['user_id']= getdbelement("user_id",LOCKS_TABLE, "page_id",$db->setinteger($page_id));
	if($result['user_id'])
	{
		$result['locktime']=getdbelement("locktime",LOCKS_TABLE, "page_id",$db->setinteger($page_id));
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
?>