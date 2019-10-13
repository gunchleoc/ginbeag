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
	$newproperties = array();
	if(isset($_POST['renamevariables']))
	{
		$newproperties['Math CAPTCHA Reply Variable']=makerandomvariablename();
		$newproperties['Math CAPTCHA Answer Variable']=makerandomvariablename();
		$newproperties['Message Text Variable']=makerandomvariablename();
		$newproperties['Subject Line Variable']=makerandomvariablename();
		$newproperties['E-Mail Address Variable']=makerandomvariablename();
	}
	else if(isset($_POST['mathcaptcha']))
	{
		$newproperties['Use Math CAPTCHA'] = SQLStatement::setinteger($_POST['usemathcaptcha']);
	}
	else if(isset($_POST['spamwords']))
	{
		$newproperties['Spam Words Subject'] = fixquotes($_POST['spamwords_subject']);
		$newproperties['Spam Words Content'] = fixquotes($_POST['spamwords_content']);
	}

	$message .= updateproperties(ANTISPAM_TABLE, $newproperties);

	if (empty($message)) {
		if(isset($_POST['renamevariables']))
			$message = "Renamed Variables";
		else
			$message = "Anti-Spam settings saved";
	} else {
		$error = true;
		if(isset($_POST['renamevariables']))
			$message = "Failed to rename variables ".$message;
		else
			$message = "Failed to save Anti-Spam settings ".$message;
	}
}


$content = new AdminMain($page, "sitespam", new AdminMessage($message, $error), new SiteAntispam());
print($content->toHTML());


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
