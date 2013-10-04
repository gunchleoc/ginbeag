<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"pagecontent"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/dbmod.php");


//
//
//
function updatemenunavigation($page_id, $navigatordepth , $displaydepth , $sistersinnavigator)
{
	global $db;
	$page_id=$db->setinteger($page_id);
	
	$sql=updatefield(MENUS_TABLE,"navigatordepth",$db->setinteger($navigatordepth) ,"page_id='".$page_id."'");
	
	if($sql)
	{
		$sql= updatefield(MENUS_TABLE,"displaydepth ",$db->setinteger($displaydepth) ,"page_id='".$page_id."'");
	}
	if($sql)
	{
		$sql= updatefield(MENUS_TABLE,"sistersinnavigator ",$db->setinteger($sistersinnavigator) ,"page_id='".$page_id."'");
	}
	return $sql;
}

?>