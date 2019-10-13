<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"functions"));

include_once($projectroot."functions/db.php");

//
//
//
function addguestbookentry($postername, $email, $subject, $messagetext) {
	$sql = new SQLInsertStatement(
			GUESTBOOK_TABLE,
			array('name', 'email', 'subject', 'message', 'date'),
			array($postername, $email, $subject, $messagetext, date(DATETIMEFORMAT, strtotime('now'))),
			'sssss');
	return $sql->insert();
}

//
//
//
function getguestbookentries($number,$offset) {
	if(!$offset) $offset=0;
	if(!$number>0) $number=1;

	$sql = new SQLSelectStatement(GUESTBOOK_TABLE, 'message_id');
	$sql->set_order(array('date' => 'DESC'));
	$sql->set_limit($number, $offset);
	return $sql->fetch_column();
}

//
//
//
function countguestbookentries() {
	$sql = new SQLSelectStatement(GUESTBOOK_TABLE, 'message_id');
	$sql->set_operator('count');
	return $sql->fetch_value();
}

//
//
//
function getguestbookentrycontents($message) {
	$sql = new SQLSelectStatement(GUESTBOOK_TABLE, '*', array('message_id'), array($message), 'i');
	return $sql->fetch_row();
}
?>
