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
function getcopyrightinfo($copyright_id)
{
  $copyright_id=setinteger($copyright_id);

  $result=array();
  $result['holder']= getdbelement("holder", COPYRIGHT_TABLE, "copyright_id", $copyright_id);
  $result['contact']= getdbelement("contact", COPYRIGHT_TABLE, "copyright_id", $copyright_id);
  $result['comments']= getdbelement("comments", COPYRIGHT_TABLE, "copyright_id", $copyright_id);
  $result['permission']= getdbelement("permission", COPYRIGHT_TABLE, "copyright_id", $copyright_id);
  $result['credit']= getdbelement("credit", COPYRIGHT_TABLE, "copyright_id", $copyright_id);
  $result['added']= getdbelement("added", COPYRIGHT_TABLE, "copyright_id", $copyright_id);
  $result['editdate']= getdbelement("editdate", COPYRIGHT_TABLE, "copyright_id", $copyright_id);
  $result['editorid']= getdbelement("editor_id", COPYRIGHT_TABLE, "copyright_id", $copyright_id);
  return $result;
}

//
//
//
function getcopyrightholder($copyright_id)
{
  return getdbelement("holder", COPYRIGHT_TABLE, "copyright_id", setinteger($copyright_id));
}

//
//
//
function holderexists($holder)
{
  return getdbelement("holder", COPYRIGHT_TABLE, "holder", setstring($holder));
}

//
//
//
function getcopyrightids($order="copyright_id",$ascdesc="ASC",$filterpermission=100000)
{
  if($filterpermission>50)
  {
    $condition="1";
  }
  else
  {
    $condition="permission ='".setinteger($filterpermission)."'";
  }
  return getorderedcolumn("copyright_id",COPYRIGHT_TABLE,$condition, setstring($order), setstring($ascdesc));
}

//
//
//
function searchholder($holder,$order="copyright_id",$ascdesc="ASC",$filterpermission=100000)
{
  $condition="holder like '%".setstring($holder)."%'";

  if($filterpermission<50)
  {
    $condition.=" AND permission ='".setinteger($filterpermission)."'";
  }
  return getorderedcolumn("copyright_id",COPYRIGHT_TABLE,$condition, setstring($order), setstring($ascdesc));
}


//
//
//
function updatecopyrightholder($copyright_id,$holder,$contact,$comments,$permission,$credit,$sid)
{
  $values=array();
  $values[]=setinteger($copyright_id);
  $values[]=setstring($holder);
  $values[]=setstring($contact);
  $values[]=setstring($comments);
  $values[]=setinteger($permission);
  $values[]=setstring($credit);
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
  
  updatefields(COPYRIGHT_TABLE,$keys,$values,"copyright_id",setinteger($copyright_id));
}


//
//
//
function addcopyrightholder($holder,$contact,$comments,$permission,$credit,$sid)
{
  if(!holderexists($holder))
  {
    $values=array();
    $values[]=0;
    $values[]=setstring($holder);
    $values[]=setstring($contact);
    $values[]=setstring($comments);
    $values[]=setinteger($permission);
    $values[]=setstring($credit);
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
function deletecopyrightholder($copyright_id)
{
  deleteentry(COPYRIGHT_TABLE,"copyright_id ='".setinteger($copyright_id)."'");
}

?>
