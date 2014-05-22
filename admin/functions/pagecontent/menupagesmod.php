<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"pagecontent"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."functions/db.php");


//
//
//
function updatemenunavigation($page, $navigatordepth , $displaydepth , $sistersinnavigator)
{
	global $db;
	$page=$db->setinteger($page);
	
	$sql=updatefield(MENUS_TABLE,"navigatordepth",$db->setinteger($navigatordepth) ,"page_id='".$page."'");
	
	if($sql)
	{
		$sql= updatefield(MENUS_TABLE,"displaydepth ",$db->setinteger($displaydepth) ,"page_id='".$page."'");
	}
	if($sql)
	{
		$sql= updatefield(MENUS_TABLE,"sistersinnavigator ",$db->setinteger($sistersinnavigator) ,"page_id='".$page."'");
	}
	return $sql;
}

?>