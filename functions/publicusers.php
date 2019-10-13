<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"functions"));

include_once($projectroot."functions/db.php");

//
//
//
function getpublicusername($user) {
	$sql = new SQLSelectStatement(PUBLICUSERS_TABLE, 'username', array('user_id'), array($user), 'i');
	return $sql->fetch_value();
}

//
//
//
function getpublicuserid($username) {
	$sql = new SQLSelectStatement(PUBLICUSERS_TABLE, 'user_id', array('username'), array($username), 's');
	return $sql->fetch_value();
}

//
//
//
function ispublicuseractive($user) {
	$sql = new SQLSelectStatement(PUBLICUSERS_TABLE, 'user_active', array('user_id'), array($user), 'i');
	return $sql->fetch_value();
}

?>
