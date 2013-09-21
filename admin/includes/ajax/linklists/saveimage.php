<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"linklists"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"ajax"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/pagecontent/linklistpagesmod.php");
include_once($projectroot."admin/functions/sessions.php");

//print_r($_POST);

$sid=$_POST['sid'];
checksession($sid);

header('Content-type: text/xml;	charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';

$message = getpagelock($_POST['page']);
if($message)
{
	print('<message error="1">');
	print($message);
}
else {
	$page=$_POST['page'];
	$filename=trim($_POST['imagefilename']);
	
	$success=-1;
	$message ="";
	if(imageexists($filename))
	{
		$success= updatelinkimage($_POST['linkid'],$filename);
		if(!getthumbnail($filename))
		{
	    	$message = '. Please create a thumbnail for this image!';
		}
	}
	
	else
	{
	  $message = ". Image '".$filename."' does not exist.";
	}
	
	if($success>=0)
	{
		print('<message error="0">');
		updateeditdata($page, $sid);
		print("Changed Image for Link ID: ".$_POST['linkid'].$message);
	}
	else
	{
		print('<message error="1">');
		print("Error Changing Image for Link ID: ".$_POST['linkid'].$message);
	}
}
print("</message>");
?>