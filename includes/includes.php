<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));

include_once($projectroot."includes/functions.php");
include_once($projectroot."language/languages.php");

//
// get adjusted offset for page jumped to
//
function getoffsetforjumppage($noofitems,$itemsperpage,$offset)
{
	global $_GET;

	if(isset($_GET['jumppage']) && $_GET['jumppage']>0
		&& $noofitems && $_GET['jumppage']<=ceil($noofitems/$itemsperpage))
	{
		$offset=($_GET['jumppage']-1)*$itemsperpage;
		unset($_GET['jumppage']);
	}
	return $offset;
}


//
// makes copyright information.
//
function makecopyright($permissions)
{
	$textcopyright="";
	$imagecopyright="";
	$bypermission="";
	if(strlen($permissions['copyright'])>0)
	{
		$textcopyright= sprintf(getlang("footer_textcopyright"),title2html($permissions['copyright']));
	}
	if(strlen($permissions['image_copyright'])>0)
	{
		$imagecopyright= sprintf(getlang("footer_imagecopyright"),title2html($permissions['image_copyright']));
	}
	if(($permissions['permission'])==PERMISSION_GRANTED)
	{
		$bypermission=getlang("footer_bypermission");
	}
	return sprintf(getlang("footer_copyright"),$textcopyright, $imagecopyright, $bypermission);
}



//
// formats date and time
//
function formatdatetime($date)
{
	$result = @date(getproperty("Date Time Format"), strtotime($date));
	$result = translatemonth($result);
  	return str_replace(" ","&nbsp;",$result);;
}

//
// formats a date
//
function formatdate($date)
{
	$result = @date(getproperty("Date Format"), strtotime($date));
	$result = translatemonth($result);
  	return str_replace(" ","&nbsp;",$result);
}

//
// helper function for formatdate and formatdatetime
//
function translatemonth($date)
{
	$date = str_replace("January", getlangarray("date_month",1), $date);
	$date = str_replace("February", getlangarray("date_month",2), $date);
	$date = str_replace("March", getlangarray("date_month",3), $date);
	$date = str_replace("April", getlangarray("date_month",4), $date);
	$date = str_replace("May", getlangarray("date_month",5), $date);
	$date = str_replace("June", getlangarray("date_month",6), $date);
	$date = str_replace("July", getlangarray("date_month",7), $date);
	$date = str_replace("August", getlangarray("date_month",8), $date);
	$date = str_replace("September", getlangarray("date_month",9), $date);
	$date = str_replace("October", getlangarray("date_month",10), $date);
	$date = str_replace("November", getlangarray("date_month",11), $date);
	$date = str_replace("December", getlangarray("date_month",12), $date);
	$date = str_replace("Jan", getlangarray("date_month_short",1), $date);
	$date = str_replace("Feb", getlangarray("date_month_short",2), $date);
	$date = str_replace("Mar", getlangarray("date_month_short",3), $date);
	$date = str_replace("Apr", getlangarray("date_month_short",4), $date);
	$date = str_replace("May", getlangarray("date_month_short",5), $date);
	$date = str_replace("Jun", getlangarray("date_month_short",6), $date);
	$date = str_replace("Jul", getlangarray("date_month_short",7), $date);
	$date = str_replace("Aug", getlangarray("date_month_short",8), $date);
	$date = str_replace("Sep", getlangarray("date_month_short",9), $date);
	$date = str_replace("Oct", getlangarray("date_month_short",10), $date);
	$date = str_replace("Nov", getlangarray("date_month_short",11), $date);
	$date = str_replace("Dec", getlangarray("date_month_short",12), $date);
	return $date;
}


?>
