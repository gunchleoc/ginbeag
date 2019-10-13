<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "pagecontent"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."functions/db.php";

//
//
//
function updateexternallink($page, $link) 
{
    $sql = new SQLUpdateStatement(
        EXTERNALS_TABLE,
        array('link'), array('page_id'),
        array($link, $page), 'si'
    );
    return $sql->run();
}

?>
