<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"functions"));

include_once($projectroot."functions/db.php");

################################################################################
##                                                                            ##
##        Functions                                                           ##
##                                                                            ##
################################################################################


//
//
//
function addguestbookentry($postername,$addy,$subject,$messagetext)
{
	global $db;
	$values[0]=0;
	$values[1]=$db->setstring($postername);
	$values[2]=$db->setstring($addy);
	$values[3]=$db->setstring($subject);
	$values[4]=$db->setstring($messagetext);
	$values[5]=date(DATETIMEFORMAT, strtotime('now'));
	return $newpage = insertentry(GUESTBOOK_TABLE, $values);
}

//
//
//
function getguestbookentries($number,$offset)
{
	global $db;
	if(!$offset) $offset=0;
	if(!$number>0) $number=1;
	return getorderedcolumnlimit("message_id",GUESTBOOK_TABLE,"1", "date", $db->setinteger($offset), $db->setinteger($number),"DESC");
}

//
//
//
function countguestbookentries()
{
	return countelements("message_id", GUESTBOOK_TABLE);
}

//
//
//
function getguestbookentrycontents($message)
{
	global $db;
	$result=array();
	$message=$db->setinteger($message);
	$result['name']= getdbelement("name",GUESTBOOK_TABLE, "message_id", $message);
	$result['email']= getdbelement("email",GUESTBOOK_TABLE, "message_id", $message);
	$result['subject']= getdbelement("subject",GUESTBOOK_TABLE, "message_id", $message);
	$result['message']= getdbelement("message",GUESTBOOK_TABLE, "message_id", $message);
	$result['date']= getdbelement("date",GUESTBOOK_TABLE, "message_id", $message);
	
	return $result;
}
?>
