<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/functions/bannersmod.php");
include_once($projectroot."admin/functions/files.php");
include_once($projectroot."includes/objects/page.php");
include_once($projectroot."admin/includes/objects/site/banners.php");
include_once($projectroot."includes/functions.php");
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

if($postaction=='editbanner')
{
	if(strlen($_POST['code'])>0)
	{
		$message='Edited banner #'.$_POST['bannerid'].'code <i>'.$_POST['header'].'</i>';
		updatebannercode($_POST['bannerid'], fixquotes($_POST['header']), $_POST['code']);
	}
	else
	{
		$filename=$_FILES['image']['name'];
		if(strlen($filename)>0)
		{
			$filename=cleanupfilename($filename);
			deletefile("img/banners",$_POST['oldimage']);
			$success= replacefile($_FILES,"img/banners","image",$filename);
		}
		else
		{
			$contents=getbannercontents($_POST['bannerid']);
			$filename=$contents['image'];
			$success=true;
		}
		if($success)
		{
			$message='Edited banner #'.$_POST['bannerid'].': <i>'.$_POST['header'].'</i>';
			updatebanner($_POST['bannerid'], fixquotes($_POST['header']), $filename,fixquotes($_POST['description']),$_POST['link']);
			if(!isbannercomplete($_POST['bannerid']))
			{
				$message='This banner is not complete and will not be displayed! Please fill out all required fields.';
			}
		}
		else
		{
			$message='Failed to edit banner #'.$_POST['bannerid'].': error uploading image!';
		}
	}
}
elseif($postaction=='addbanner')
{
	if(strlen($_POST['code'])>0)
	{
		$message='Added banner code <i>'.$_POST['header'].'</i>';
		addbannercode(fixquotes($_POST['header']), $_POST['code']);
	}
	else
	{
		$filename=$_FILES['image']['name'];
		$filename=cleanupfilename($filename);
		$success= replacefile($_FILES,"img/banners","image",$filename);
		
		if($success)
		{
			$banner=addbanner(fixquotes($_POST['header']), $filename ,fixquotes($_POST['description']),$_POST['link']);
			$message='Added banner <i>'.$_POST['header'].'</i>';
			if(!isbannercomplete($banner))
			{
				$message='This banner is not complete and will not be displayed! Please fill out all required fields.';
			}
		}
		else
		{
			$message='Failed to add banner: error uploading image!';
		}
	}
}
elseif($postaction=='movebanner')
{
	if(isset($_POST['movebannerup']))
	{
		$message='Moving banner #'.$_POST['bannerid'].' up';
		movebanner($_POST['bannerid'], "up", $_POST['positions']);
	}
	else
	{
		$message='Moving banner #'.$_POST['bannerid'].' down';
		movebanner($_POST['bannerid'], "down", $_POST['positions']);
	}
}
elseif($postaction=='deletebanner')
{
	if(isset($_POST['deletebannerconfirm']))
	{
		$message='Deleted banner #'.$_POST['bannerid'];
		deletebanner($_POST['bannerid']);
	}
	else
	{
		$message='You have to check "Confirm delete" in order to delete banner #'.$_POST['bannerid'];
	}
}

elseif($postaction=='displaybanners')
{
	updateentries(SITEPROPERTIES_TABLE,array('Display Banners' =>$_POST['toggledisplaybanners']),"property_name","property_value");
	$properties = getproperties(); // need to update global variable
}

unset($_POST['bannerid']);

$content = new AdminMain($page,"sitebanner",$message,new SiteBanners());
print($content->toHTML());
$db->closedb();
?>