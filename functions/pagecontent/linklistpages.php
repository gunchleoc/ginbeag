<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "pagecontent"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "functions"));

require_once $projectroot."functions/db.php";

//
//
//
function getlinklistitems($page) 
{
    $sql = new SQLSelectStatement(LINKS_TABLE, 'link_id', array('page_id'), array($page), 'i');
    $sql->set_order(array('position' => 'ASC'));
    return $sql->fetch_column();
}

//
//
//
function getlinktitle($link) 
{
    $sql = new SQLSelectStatement(LINKS_TABLE, 'title', array('link_id'), array($link), 'i');
    return $sql->fetch_value();
}

//
//
//
function getlinkcontents($link) 
{
    $sql = new SQLSelectStatement(LINKS_TABLE, '*', array('link_id'), array($link), 'i');
    return $sql->fetch_row();
}

//
//
//
function getlastlinkposition($page) 
{
    $sql = new SQLSelectStatement(LINKS_TABLE, 'position', array('page_id'), array($page), 'i');
    $sql->set_operator('max');
    return $sql->fetch_value();
}

//
//
//
function getlinkdescription($link) 
{
    $sql = new SQLSelectStatement(LINKS_TABLE, 'description', array('link_id'), array($link), 'i');
    return $sql->fetch_value();
}
?>
