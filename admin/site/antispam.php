<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/includes/objects/site/antispam.php");
include_once($projectroot."admin/includes/objects/adminmain.php");

checksession();
checkadmin();

if(isset($_GET['page'])) $page=$_GET['page'];
else $page=0;

$postaction="";
if(isset($_GET['postaction'])) $postaction=$_GET['postaction'];
unset($_GET['postaction']);

//  print_r($_POST);

$message="";

if($postaction=='savesite')
{
  	$message = savesitefeatures();
}


$content = new AdminMain($page,"sitespam",$message,new SiteAntispam());
print($content->toHTML());
$db->closedb();


function savesitefeatures()
{
	global $_POST, $db;
	
	$result = "";
	
	if(isset($_POST['renamevariables']))
	{
		$properties['Math CAPTCHA Reply Variable']=makerandomvariablename();
		$properties['Math CAPTCHA Answer Variable']=makerandomvariablename();
		$properties['Message Text Variable']=makerandomvariablename();
		$properties['Subject Line Variable']=makerandomvariablename();
		$properties['E-Mail Address Variable']=makerandomvariablename();
	}
	else
	{
		$properties['Use Math CAPTCHA']=$db->setinteger($_POST['usemathcaptcha']);
	}
	
	$success=updateentries(ANTISPAM_TABLE,$properties,"property_name","property_value");
	
	if($success="1")
	{
		if(isset($_POST['renamevariables']))
			$result = "Renamed Variables";
		else
			$result = "Anti-Spam settings saved";
	}
	else
	{
		if(isset($_POST['renamevariables']))
			$result = "Failed to rename variables ".$sql;
		else
			$result = "Failed to save Anti-Spam settings ".$sql;
	}
	return $result;
}


function makerandomvariablename()
{
	$result="";
	$letters="aAbBcCdDeEfFgGhHiIjJkKlLmMnNoOpPqQrRsStTuUvVwWxXyYzZ";
	
	list($usec, $sec) = explode(' ', microtime());
	$randomlength= (((float) $sec + ((float) $usec * 100000)) % 25)+6;
	
	for($i=0;$i<$randomlength;$i++)
	{
		list($usec, $sec) = explode(' ', microtime());
		$position= ((float) $sec + ((float) $usec * 100000)) % strlen($letters);
		$result.= substr($letters,$position,1);
	}
	return $result;
}

?>