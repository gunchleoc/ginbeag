<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"imageeditor"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"ajax"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

//include_once($projectroot."admin/functions/pagecontent/linklistpagesmod.php");

include_once($projectroot."admin/functions/sessions.php");

checksession();

header('Content-type: text/xml;	charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';

$errormessage = getpagelock($_POST['page']);
$message="";
if($errormessage)
{
	print('<message error="1">');
	print($errormessage);
}
else {
	$page=$_POST['page'];
	$item=$_POST['item'];
	$imagefilename=trim($_POST['imagefilename']);
	$elementtype=$_POST['elementtype'];

	$success=false;
	
	if(!($imagefilename==="" || imageexists($imagefilename)))
	{
		$errormessage = "Error saving image ".$imagefilename." - we don't have this image!";
	}
	else
	{

		if($elementtype=="pageintro")
		{
			include_once($projectroot."admin/functions/pagesmod.php");
			$success=updatepageintroimagefilename($page,$imagefilename);
			if($success)
			{
				if($imagefilename) $message= "Saved synopsis image: ".$imagefilename;
				else $message= "Removed image from synopsis";
			}
			else $errormessage = "Error saving synopsis image ".$imagefilename." for page ".$page;
		}
		elseif($elementtype=="articlesection")
		{
			include_once($projectroot."admin/functions/pagecontent/articlepagesmod.php");
		    $success=updatearticlesectionimagefilename($item,$imagefilename);
		    if($success)
		    {
		    	if($imagefilename) $message="Saved section image: ".$imagefilename;
		    	else $message= "Removed image from section";
		    }
		    else $errormessage = "Error saving section image ".$imagefilename." for page ".$page." and section ".$item;
		}
		elseif($elementtype=="newsitemsection")
		{
			include_once($projectroot."admin/functions/pagecontent/newspagesmod.php");
		    $success=updatenewsitemsectionimagefilename($item,$imagefilename);
		    if($success)
		    {
		    	if($imagefilename) $message="Saved section image: ".$imagefilename;
		    	else $message= "Removed image from section";
		    }
		    else $errormessage = "Error saving section image ".$imagefilename." for page ".$page." and section ".$item;
		}
		elseif($elementtype=="link")
		{
			include_once($projectroot."admin/functions/pagecontent/linklistpagesmod.php");
		    $success= updatelinkimagefilename($item,$imagefilename);
		    if($success)
		    {
		    	if($imagefilename) $message="Saved link image: ".$imagefilename;
		    	else $message= "Removed image from link";
		    }
		    else $errormessage = "Error saving link image ".$imagefilename." for page ".$page." and link ".$item;
		}
		else $errormessage = 'Error saving image: unknown element type "'.$elementtype.'"';
	}
	  	
	if($errormessage)
	{
		print('<message error="1">');
		print($errormessage);
	}
	else
	{
		print('<message error="0">');
		updateeditdata($page);
		print($message);
	}
//print_r($_POST);
//print_r($_POST);

}
print("</message>");


?>