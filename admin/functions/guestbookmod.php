<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."functions/db.php");
include_once($projectroot."functions/guestbook.php");

################################################################################
##                                                                            ##
##        Functions                                                           ##
##                                                                            ##
################################################################################


//
//
//
function deleteguestbookentry($message)
{
	global $db;
  	return deleteentry(GUESTBOOK_TABLE,"message_id ='".$db->setinteger($message)."'");
}

?>
