<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."functions/db.php";
require_once $projectroot."functions/guestbook.php";

//
//
//
function deleteguestbookentry($message) 
{
    $sql = new SQLDeleteStatement(GUESTBOOK_TABLE, array('message_id'), array($message), 'i');
    $sql->run();
}

?>
