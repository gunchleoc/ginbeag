<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"pagecontent"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/dbmod.php");
//include_once($projectroot."admin/functions/sessions.php");
//include_once($projectroot."functions/users.php");
//include_once($projectroot."functions/pages.php");

//
//
//
function addlink($page_id,$linktitle,$link,$imagefilename,$description)
{
	global $db;
  $page_id=$db->setinteger($page_id);
  $lastposition=getlastlinkposition($page_id);

  $values=array();
  $values[]=0;
  $values[]=$page_id;
  $values[]=$db->setstring($linktitle);
  $values[]=$db->setstring($imagefilename);
  $values[]=$db->setstring($link);
  $values[]=$db->setstring($description);
  $values[]=$lastposition+1;
  return insertentry(LINKS_TABLE,$values);
}

//
//
//
function deletelink($link_id)
{
	global $db;
  deleteentry(LINKS_TABLE,"link_id ='".$db->setinteger($link_id)."'");
}

//
//
//
function updatelinkdescription($link_id, $text)
{
	global $db;
  return updatefield(LINKS_TABLE,"description",$db->setstring($text),"link_id='".$db->setinteger($link_id)."'");
}

//
//
//
function updatelinkproperties($link_id,$title,$link)
{
	global $db;
  updatefield(LINKS_TABLE,"title",$db->setstring($title),"link_id='".$db->setinteger($link_id)."'");
  updatefield(LINKS_TABLE,"link",$db->setstring($link),"link_id='".$db->setinteger($link_id)."'");
}

//
//
//
function updatelinkimagefilename($link_id,$image)
{
	global $db;
  	return updatefield(LINKS_TABLE,"image",$db->setstring($image),"link_id='".$db->setinteger($link_id)."'");
}

//
//
//
function movelink($link_id, $direction, $positions=1)
{
	global $db;
  if($positions>0)
  {
    $page_id=getdbelement("page_id",LINKS_TABLE, "link_id", $db->setinteger($link_id));
    if($direction==="down")
    {
      $sisterids=getorderedcolumn("link_id",LINKS_TABLE, "page_id='".($page_id)."'", "position", "ASC");
    }
    else
    {
      $sisterids=getorderedcolumn("link_id",LINKS_TABLE, "page_id='".($page_id)."'", "position", "DESC");
    }
    $found=false;
    $idposition=0;
    for($i=0;$i<count($sisterids)&&!$found;$i++)
    {
      if($link_id==$sisterids[$i])
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
      $navpos=getdbelement("position",LINKS_TABLE, "link_id", $currentid);

      for($i=$idposition+$positions;$i>$idposition;$i--)
      {
        $otherid=$sisterids[$i-1];
        $othernavpos=getdbelement("position",LINKS_TABLE, "link_id", $otherid);

        $swap[$currentid]=$othernavpos;
        $swap[$otherid]=$navpos;
        $currentid=$otherid;
      }
      updateentries(LINKS_TABLE,$swap,"link_id","position");
    }
  }
}
?>