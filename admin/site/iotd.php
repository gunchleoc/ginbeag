<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/includes/objects/site/iotd.php");
include_once($projectroot."admin/includes/objects/adminmain.php");

if(isset($_GET['sid'])) $sid=$_GET['sid'];
else $sid="";
checksession($sid);

if(!isadmin($sid))
{
	die('<p class="highlight">You have no permission for this area</p>');
}

if(isset($_GET['page'])) $page=$_GET['page'];
else $page=0;

$postaction="";
if(isset($_GET['postaction'])) $postaction=$_GET['postaction'];
unset($_GET['postaction']);

$message="";

//  print_r($_GET);

if($postaction=='savesite')
{
  	$message=savesitefeatures();
}



$content = new AdminMain($page,"siteiotd",$message,new SiteRandomItems());
print($content->toHTML());
  
$properties=getproperties();
$potdcats=explode(",",$properties["Picture of the Day Categories"]);
$potdcatnames=array();
if(!count($potdcats))
{
	$potdcatlistoutput="All Categories";
}
else
{
	for($j=0;$j<count($potdcats);$j++)
	{
	  	array_push($potdcatnames,getcategoryname($potdcats[$j]));
	}
	sort($potdcatnames);
	$potdcatlistoutput=implode(", ",$potdcatnames);
}
 
$db->closedb();


function savesitefeatures()
{
	global $sid, $_POST, $db;
	
	$message="";
	
	$properties['Display Picture of the Day']=$db->setinteger($_POST['displaypotd']);
	if(isset($_POST['selectedcat']))
	{
		$potdcats=$_POST['selectedcat'];
		for($i=0;$i<count($potdcats);$i++)
		{
			$potdcats[$i] = $db->setinteger($potdcats[$i]);
		}
		$properties['Picture of the Day Categories']=implode(",",$potdcats);
	}
	else $properties['Picture of the Day Categories']=$db->setstring($_POST['oldpotdcats']);
	
	$properties['Display Article of the Day']=$db->setinteger($_POST['displayaotd']);
	$aotdpages=explode(',',$_POST['aotdpages']);
	
	for($i=0;$i<count($aotdpages);$i++)
	{
		$aotdpages[$i] = $db->setinteger($aotdpages[$i]);
	}
	$properties['Article of the Day Start Pages']=implode(",",$aotdpages);
	
	$success=updateentries(SITEPROPERTIES_TABLE,$properties,"property_name","property_value");
	
	if($success="1")
	{
		$message="Random Items of the Day saved";
	}
	else
	{
		$message="Failed to save Random Items of the Day".$sql;
	}
	return $message;
}

?>