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
// todo handle short month
//
function formatdatetime($date,$short=false)
{
	$result ="";
  	if(!$short)
  	{
    	$format=getproperty("Date Time Format");
	  	$result= @date($format,strtotime($date));
	  	$result= str_replace("January",getlangarray("date_month",1),$result);
	  	$result= str_replace("February",getlangarray("date_month",2),$result);
	  	$result= str_replace("March",getlangarray("date_month",3),$result);
	  	$result= str_replace("April",getlangarray("date_month",4),$result);
	  	$result= str_replace("May",getlangarray("date_month",5),$result);
	  	$result= str_replace("June",getlangarray("date_month",6),$result);
	  	$result= str_replace("July",getlangarray("date_month",7),$result);
	  	$result= str_replace("August",getlangarray("date_month",8),$result);
	  	$result= str_replace("September",getlangarray("date_month",9),$result);
	  	$result= str_replace("October",getlangarray("date_month",10),$result);
	  	$result= str_replace("November",getlangarray("date_month",11),$result);
	  	$result= str_replace("December",getlangarray("date_month",12),$result);
  	}
  	else
  	{
    	$format=SHORTDATETIMEFORMAT;
    	$result= @date($format,strtotime($date));
  	}
  	return str_replace(" ","&nbsp;",$result);;
}

//
// todo handle short month
//
function formatdate($date)
{
	$result="";
  	$format=getproperty("Date Format");
  	$result=  @date($format,strtotime($date));
  	$result= str_replace("January",getlangarray("date_month",1),$result);
  	$result= str_replace("February",getlangarray("date_month",2),$result);
  	$result= str_replace("March",getlangarray("date_month",3),$result);
  	$result= str_replace("April",getlangarray("date_month",4),$result);
  	$result= str_replace("May",getlangarray("date_month",5),$result);
  	$result= str_replace("June",getlangarray("date_month",6),$result);
  	$result= str_replace("July",getlangarray("date_month",7),$result);
  	$result= str_replace("August",getlangarray("date_month",8),$result);
  	$result= str_replace("September",getlangarray("date_month",9),$result);
  	$result= str_replace("October",getlangarray("date_month",10),$result);
  	$result= str_replace("November",getlangarray("date_month",11),$result);
  	$result= str_replace("December",getlangarray("date_month",12),$result);
  	
  	return str_replace(" ","&nbsp;",$result);
}
?>
