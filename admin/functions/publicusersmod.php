<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."functions/db.php");
include_once($projectroot."functions/publicusers.php");

//
//
//
function addpublicuser($user,$pass) {
	$sql = new SQLInsertStatement(
		PUBLICUSERS_TABLE,
		array('user_active', 'username', 'password'),
		array(1, $user, $pass),
		'iss');
	return $sql->insert();
}


//
//
//
function changepublicuserpasswordadmin($userid,$newpass,$confirmpass) {
	$result="Failed to change password";

	if(isadmin())
	{
		if(strlen($newpass)>7)
		{
			if($newpass===$confirmpass)
			{
				$sql = new SQLUpdateStatement(PUBLICUSERS_TABLE,
					array('password'), array('user_id'),
					array(md5($newpass), $userid), 'si');
				if($sql->run()) {
					$result="Password changed successfully";
				}
			}
			else
			{
				$result="Passwords did not match";
			}
		}
		else
		{
			$result="Your password must be at least 8 digits long";
		}
	}
	else
	{
		$result="Please hack someone else.";
	}
	return $result;
}


//
//
//
function activatepublicuser($userid) {
	$sql = new SQLUpdateStatement(PUBLICUSERS_TABLE,
		array('user_active'), array('user_id'),
		array(1, $userid), 'ii');
	$sql->run();
}

//
//
//
function deactivatepublicuser($userid) {
	$sql = new SQLUpdateStatement(PUBLICUSERS_TABLE,
		array('user_active'), array('user_id'),
		array(0, $userid), 'ii');
	$sql->run();
}


//
//
//
function publicuserexists($username) {
	$sql = new SQLSelectStatement(PUBLICUSERS_TABLE, 'username', array('username'), array($username), 's');
	return $sql->fetch_value();
}


//
//
//
function getallpublicusers() {
	$sql = new SQLSelectStatement(PUBLICUSERS_TABLE, 'user_id');
	$sql->set_order(array('username' => 'ASC'));
	return $sql->fetch_column();
}

// *************************** restricted access **************************** //

//
//
//
function addpageaccess($userids,$pageid) {
	$result = true;
	for($i=0;$i<count($userids);$i++)
	{
		$sql = new SQLInsertStatement(
			RESTRICTEDPAGESACCESS_TABLE,
			array('page_id', 'publicuser_id'),
			array($pageid, $userids[$i]),
			'ii');
		$result = $result & $sql->insert();;
	}
	return $result;
}

//
//
//
function removepageaccess($userids, $pageid) {
	$result = true;
	foreach ($userids as $id) {
		$sql = new SQLDeleteStatement(RESTRICTEDPAGESACCESS_TABLE, array('page_id', 'publicuser_id'), array($pageid, $id), 'ii');
		$result = $result & $sql->run();
	}
	return $result;
}

//
//
//
function getallpublicuserswithaccessforpage($pageid) {
	$sql = new SQLSelectStatement(RESTRICTEDPAGESACCESS_TABLE, 'publicuser_id', array('page_id'), array($pageid), 'i');
	return $sql->fetch_column();
}


// *************************** ip ban **************************** //

//
//
//
function addbannedipforrestrictedpages($ip) {
	$ip = ip2long($ip);
	$sql = new SQLSelectStatement(RESTRICTEDPAGESBANNEDIPS_TABLE, 'ip', array('ip'), array($ip), 'i');
	if($ip !== $sql->fetch_value()) {
		$sql = new SQLInsertStatement(RESTRICTEDPAGESBANNEDIPS_TABLE, array('ip'), array($ip), 'i');
		return $sql->insert();
	}
	return false;
}

//
//
//
function removebannedipforrestrictedpageas($ip) {
	$sql = new SQLDeleteStatement(RESTRICTEDPAGESBANNEDIPS_TABLE, array('ip'), array(ip2long($ip)), 'i');
	return $sql->run();
}

//
//
//
function getalladdbannedipforrestrictedpages() {
	$sql = new SQLSelectStatement(RESTRICTEDPAGESBANNEDIPS_TABLE, 'ip');
	$sql->set_order(array('ip' => 'ASC'));
	$longips = $sql->fetch_column();

	return array_map ("long2ip", $longips);
}

?>
