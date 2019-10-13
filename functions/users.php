<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "functions"));

require_once $projectroot."functions/db.php";

//
//
//
function getusername($user) 
{
    $sql = new SQLSelectStatement(USERS_TABLE, 'username', array('user_id'), array($user), 'i');
    return $sql->fetch_value();
}

//
//
//
function getuseremail($user) 
{
    $sql = new SQLSelectStatement(USERS_TABLE, 'email', array('user_id'), array($user), 'i');
    return $sql->fetch_value();
}

//
//
//
function getuserid($username) 
{
    $sql = new SQLSelectStatement(USERS_TABLE, 'user_id', array('username'), array($username), 's');
    return $sql->fetch_value();
}


//
//
//
function getallcontacts() 
{
    $sql = new SQLSelectStatement(USERS_TABLE, 'user_id', array('iscontact'), array(1), 'i');
    $sql->set_order(array('username' => 'ASC'));
    return $sql->fetch_column();
}

//
//
//
function getiscontact($user) 
{
    $sql = new SQLSelectStatement(USERS_TABLE, 'iscontact', array('user_id'), array($user), 'i');
    return $sql->fetch_value();
}

//
//
//
function getcontactfunction($user) 
{
    $sql = new SQLSelectStatement(USERS_TABLE, 'contactfunction', array('user_id'), array($user), 'i');
    return $sql->fetch_value();
}

?>
