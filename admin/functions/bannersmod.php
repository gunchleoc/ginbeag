<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/dbmod.php");
include_once($projectroot."functions/banners.php");

################################################################################
##                                                                            ##
##        Functions                                                           ##
##                                                                            ##
################################################################################


//
// fngt an zu spinnen, wenn keine image da ist,
// oder bereits ein Datensatz mit leerem image vorhanden ist
//
function addbanner($header, $imagefilename,$description,$link)
{
	global $db;
  $header=$db->setstring($header);
  $imagefilename=$db->setstring(basename($imagefilename));
  $description=$db->setstring($description);
  $link=$db->setstring($link);

  $lastposition=getmax("position", BANNERS_TABLE,1);

  $values=array();
  $values[]=0;
  $values[]=$header;
  $values[]=$imagefilename;
  $values[]=$description;
  $values[]=$link;
  $values[]="";
  $values[]=$lastposition+1;

  return insertentry(BANNERS_TABLE,$values);
}



//
// fngt an zu spinnen, wenn keine image da ist,
// oder bereits ein Datensatz mit leerem image vorhanden ist
//
function addbannercode($header, $code)
{
	global $db;
  $header=$db->setstring($header);
  $code=$db->setstring($code);

  $lastposition=getmax("position", BANNERS_TABLE,1);

  $values=array();
  $values[]=0;
  $values[]=$header;
  $values[]=" ";
  $values[]="";
  $values[]="";
  $values[]=$code;
  $values[]=$lastposition+1;

  return insertentry(BANNERS_TABLE,$values);
}

//
//
//
function updatebanner($banner_id, $header, $imagefilename,$description,$link)
{
	global $db;
  $banner_id=$db->setinteger($banner_id);
  
  updatefield(BANNERS_TABLE,"header",$db->setstring($header),"banner_id='".$banner_id."'");
  updatefield(BANNERS_TABLE,"image",$db->setstring(basename($imagefilename)),"banner_id='".$banner_id."'");
  updatefield(BANNERS_TABLE,"description",$db->setstring($description),"banner_id='".$banner_id."'");
  updatefield(BANNERS_TABLE,"link",$db->setstring($link),"banner_id='".$banner_id."'");
  updatefield(BANNERS_TABLE,"code","","banner_id='".$banner_id."'");
}



//
//
//
function updatebannercode($banner_id, $header, $code)
{
	global $db;
  $banner_id=$db->setinteger($banner_id);
  if(strlen($code)>0)
  {
    updatefield(BANNERS_TABLE,"header",$db->setstring($header),"banner_id='".$banner_id."'");
    updatefield(BANNERS_TABLE,"image","","banner_id='".$banner_id."'");
    updatefield(BANNERS_TABLE,"description","","banner_id='".$banner_id."'");
    updatefield(BANNERS_TABLE,"link","","banner_id='".$banner_id."'");
    updatefield(BANNERS_TABLE,"code",$db->setstring($code),"banner_id='".$banner_id."'");
  }
}



//
//
//
function deletebanner($banner_id)
{
	global $db;
  deleteentry(BANNERS_TABLE,"banner_id ='".$db->setinteger($banner_id)."'");
}


//
//
//
function movebanner($banner_id, $direction, $positions=1)
{
  if($positions>0)
  {
    if($direction==="down")
    {
      $sisterids=getorderedcolumn("banner_id",BANNERS_TABLE, 1, "position", "ASC");
    }
    else
    {
      $sisterids=getorderedcolumn("banner_id",BANNERS_TABLE, 1, "position", "DESC");
    }
    $found=false;
    $idposition=0;
    for($i=0;$i<count($sisterids)&&!$found;$i++)
    {
      if($banner_id==$sisterids[$i])
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
      $navpos=getdbelement("position",BANNERS_TABLE, "banner_id", $currentid);

      for($i=$idposition+$positions;$i>$idposition;$i--)
      {
        $otherid=$sisterids[$i-1];
        $othernavpos=getdbelement("position",BANNERS_TABLE, "banner_id", $otherid);

        $swap[$currentid]=$othernavpos;
        $swap[$otherid]=$navpos;
        $currentid=$otherid;
      }
      updateentries(BANNERS_TABLE,$swap,"banner_id","position");
    }
  }
}
?>
