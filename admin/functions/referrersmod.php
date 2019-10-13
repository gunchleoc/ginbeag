<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."functions/db.php";
require_once $projectroot."functions/referrers.php";

//
//
//
function getblockedreferrers() 
{
    $sql = new SQLSelectStatement(BLOCKEDREFERRERS_TABLE, 'referrerurl');
    $sql->set_order(array('referrerurl' => 'ASC'));
    return $sql->fetch_column();
}

//
//
//
function addblockedreferrer($referrer) 
{
    if(!isreferrerblocked($referrer) && strlen($referrer) > 1) {
        $sql = new SQLInsertStatement(BLOCKEDREFERRERS_TABLE, array('referrerurl'), array($referrer), 's');
        return $sql->insert();
    } else {
        print($referrer." is already blocked");
        return false;
    }
}



//
//
//
function deleteblockedreferrer($referrer) 
{
    $sql = new SQLDeleteStatement(BLOCKEDREFERRERS_TABLE, array('referrerurl'), array($referrer), 's');
    return $sql->run();
}

?>
