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

$message = "";
$error = false;

if($postaction=='savesite')
{
	if(isset($_POST['renamevariables']))
	{
		$properties['Math CAPTCHA Reply Variable']=makerandomvariablename();
		$properties['Math CAPTCHA Answer Variable']=makerandomvariablename();
		$properties['Message Text Variable']=makerandomvariablename();
		$properties['Subject Line Variable']=makerandomvariablename();
		$properties['E-Mail Address Variable']=makerandomvariablename();
	}
	else if(isset($_POST['mathcaptcha']))
	{
		$properties['Use Math CAPTCHA']=$db->setinteger($_POST['usemathcaptcha']);
	}
	else if(isset($_POST['spamwords']))
	{
		$properties['Spam Words Subject']=$db->setstring($_POST['spamwords_subject']);
		$properties['Spam Words Content']=$db->setstring($_POST['spamwords_content']);
	}

	$success=updateentries(ANTISPAM_TABLE,$properties,"property_name","property_value");
	$error = !$success;

	if($success="1")
	{
		if(isset($_POST['renamevariables']))
			$message = "Renamed Variables";
		else
			$message = "Anti-Spam settings saved";
	}
	else
	{
		if(isset($_POST['renamevariables']))
			$message = "Failed to rename variables ".$sql;
		else
			$message = "Failed to save Anti-Spam settings ".$sql;
	}
}


$content = new AdminMain($page, "sitespam", new AdminMessage($message, $error), new SiteAntispam());
print($content->toHTML());
$db->closedb();


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
