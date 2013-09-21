<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/dbmod.php");
include_once($projectroot."functions/guestbook.php");

################################################################################
##                                                                            ##
##        Functions                                                           ##
##                                                                            ##
################################################################################


//
//
//
function deleteguestbookentry($message_id)
{
	global $db;
  deleteentry(GUESTBOOK_TABLE,"message_id ='".$db->setinteger($message_id)."'");
}

?>
