<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/dbmod.php");
include_once($projectroot."functions/users.php");
//include_once($projectroot."functions/pages.php");

################################################################################
##                                                                            ##
##        Functions                                                           ##
##                                                                            ##
################################################################################


//
//
//
function getcopyrightinfo($copyright)
{
	global $db;
	$copyright=$db->setinteger($copyright);
	
	$result=array();
	$result['holder']= getdbelement("holder", COPYRIGHT_TABLE, "copyright_id", $copyright);
	$result['contact']= getdbelement("contact", COPYRIGHT_TABLE, "copyright_id", $copyright);
	$result['comments']= getdbelement("comments", COPYRIGHT_TABLE, "copyright_id", $copyright);
	$result['permission']= getdbelement("permission", COPYRIGHT_TABLE, "copyright_id", $copyright);
	$result['credit']= getdbelement("credit", COPYRIGHT_TABLE, "copyright_id", $copyright);
	$result['added']= getdbelement("added", COPYRIGHT_TABLE, "copyright_id", $copyright);
	$result['editdate']= getdbelement("editdate", COPYRIGHT_TABLE, "copyright_id", $copyright);
	$result['editorid']= getdbelement("editor_id", COPYRIGHT_TABLE, "copyright_id", $copyright);
	return $result;
}

//
//
//
function getcopyrightholder($copyright)
{
	global $db;
	return getdbelement("holder", COPYRIGHT_TABLE, "copyright_id", $db->setinteger($copyright));
}

//
//
//
function holderexists($holder)
{
	global $db;
	return getdbelement("holder", COPYRIGHT_TABLE, "holder", $db->setstring($holder));
}

//
//
//
function getcopyrightids($order="copyright_id",$ascdesc="ASC",$filterpermission=100000)
{
	global $db;
	if($filterpermission>50)
	{
		$condition="1";
	}
	else
	{
		$condition="permission ='".$db->setinteger($filterpermission)."'";
	}
	return getorderedcolumn("copyright_id",COPYRIGHT_TABLE,$condition, $db->setstring($order), $db->setstring($ascdesc));
}

//
//
//
function searchholder($holder,$order="copyright_id",$ascdesc="ASC",$filterpermission=100000)
{
	global $db;
	$condition="holder like '%".$db->setstring($holder)."%'";
	
	if($filterpermission<50)
	{
		$condition.=" AND permission ='".$db->setinteger($filterpermission)."'";
	}
	return getorderedcolumn("copyright_id",COPYRIGHT_TABLE,$condition, $db->setstring($order), $db->setstring($ascdesc));
}


//
//
//
function updatecopyrightholder($copyright,$holder,$contact,$comments,$permission,$credit,$sid)
{
	global $db;
	$values=array();
	$values[]=$db->setinteger($copyright);
	$values[]=$db->setstring($holder);
	$values[]=$db->setstring($contact);
	$values[]=$db->setstring($comments);
	$values[]=$db->setinteger($permission);
	$values[]=$db->setstring($credit);
	$values[]=date(DATETIMEFORMAT, strtotime('now'));
	$values[]=getsiduser($sid);
	
	$keys=array();
	$keys[]="copyright_id";
	$keys[]="holder";
	$keys[]="contact";
	$keys[]="comments";
	$keys[]="permission";
	$keys[]="credit";
	$keys[]="editdate";
	$keys[]="editor_id";
	
	updatefields(COPYRIGHT_TABLE,$keys,$values,"copyright_id",$db->setinteger($copyright));
}


//
//
//
function addcopyrightholder($holder,$contact,$comments,$permission,$credit,$sid)
{
	global $db;
	if(!holderexists($holder))
	{
		$values=array();
		$values[]=0;
		$values[]=$db->setstring($holder);
		$values[]=$db->setstring($contact);
		$values[]=$db->setstring($comments);
		$values[]=$db->setinteger($permission);
		$values[]=$db->setstring($credit);
		$values[]=date(DATETIMEFORMAT, strtotime('now'));
		$values[]=date(DATETIMEFORMAT, strtotime('now'));
		$values[]=getsiduser($sid);
		
		return insertentry(COPYRIGHT_TABLE,$values);
	}
	else return false;
}

//
//
//
function deletecopyrightholder($copyright)
{
	global $db;
	deleteentry(COPYRIGHT_TABLE,"copyright_id ='".$db->setinteger($copyright)."'");
}

?>
