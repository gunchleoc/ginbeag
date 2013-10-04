<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"pagecontent"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/dbmod.php");
include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."functions/users.php");
include_once($projectroot."functions/pages.php");



//
//
//
function addgalleryimage($page_id,$filename)
{
	global $db;
	$page_id=$db->setinteger($page_id);
	
	$lastposition=getlastgalleryimageposition($page_id);
	
	$values=array();
	$values[]=0;
	$values[]=$page_id;
	$values[]=$db->setstring($filename);
	$values[]=$lastposition+1;
	return insertentry(GALLERYITEMS_TABLE,$values);
}

//
//
//
function changegalleryimage($galleryitem_id, $filename)
{
	global $db;
	return updatefield(GALLERYITEMS_TABLE,"image_filename",$db->setstring($filename),"galleryitem_id='".$db->setinteger($galleryitem_id)."'");
}

//
//
//
function removegalleryimage($galleryitem_id)
{
	global $db;
	return deleteentry(GALLERYITEMS_TABLE,"galleryitem_id='".$db->setinteger($galleryitem_id)."'");
}


//
//
//
function movegalleryimage($galleryitem_id, $direction, $positions=1)
{
	global $db;
	$result=false;
	
	if($positions>0)
	{
		$page_id=getdbelement("page_id",GALLERYITEMS_TABLE, "galleryitem_id", $db->setinteger($galleryitem_id));
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
			$result= updateentries(GALLERYITEMS_TABLE,$swap,"galleryitem_id","position");
		}
	}
	return $result;
}

function reindexgallerypositions($page_id)
{
	global $db;
	$items=array();
	
	$query="select galleryitem_id, position from ".GALLERYITEMS_TABLE." where page_id = ".$db->setinteger($page_id)." order by position ASC;";
	//  print($query.'<br>');
	$sql=$db->singlequery($query);
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
		return updateentries(GALLERYITEMS_TABLE,$newpos,"galleryitem_id","position");
	}
	else return false;
}
?>