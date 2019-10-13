<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"pagecontent"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"functions"));

include_once($projectroot."functions/db.php");

//
//
//
function getarticlepagecontents($page) {
	$sql = new SQLSelectStatement(ARTICLES_TABLE, '*', array('page_id'), array($page), 'i');
	return $sql->fetch_row();
}



//
//
//
function numberofarticlepages($page) {
	$sql = new SQLSelectStatement(ARTICLES_TABLE, 'numberofpages', array('page_id'), array($page), 'i');
	return $sql->fetch_value();
}


//
//
//
function getlastarticlesection($page,$pagenumber) {
	$sql = new SQLSelectStatement(ARTICLESECTIONS_TABLE, 'sectionnumber', array('article_id', 'pagenumber'), array($page, $pagenumber), 'ii');
	$sql->set_operator('max');
	return $sql->fetch_value();
}

//
//
//
function getfirstarticlesection($page,$pagenumber) {
	$sql = new SQLSelectStatement(ARTICLESECTIONS_TABLE, 'sectionnumber', array('article_id', 'pagenumber'), array($page, $pagenumber), 'ii');
	$sql->set_operator('min');
	return $sql->fetch_value();
}


//
// the section number on the page. Not the primary key!!!
//
function getarticlesections($page, $pagenumber) {
	$sql = new SQLSelectStatement(ARTICLESECTIONS_TABLE, 'articlesection_id', array('article_id', 'pagenumber'), array($page, $pagenumber), 'ii');
	$sql->set_order(array('sectionnumber' => 'ASC'));
	return $sql->fetch_column();
}



//
// for printview
//
function getallarticlesections($page) {
	$sql = new SQLSelectStatement(ARTICLESECTIONS_TABLE, 'articlesection_id', array('article_id'), array($page), 'i');
	$sql->set_order(array('pagenumber' => 'ASC', 'sectionnumber' => 'ASC'));
	return $sql->fetch_column();
}

//
//
//
function getarticlesectioncontents($articlesection) {
	$sql = new SQLSelectStatement(ARTICLESECTIONS_TABLE, '*', array('articlesection_id'), array($articlesection), 'i');
	return $sql->fetch_row();
}

//
//
//
function getarticlesectiontitle($articlesection) {
	$sql = new SQLSelectStatement(ARTICLESECTIONS_TABLE, 'sectiontitle', array('articlesection_id'), array($articlesection), 'i');
	return $sql->fetch_value();
}

//
//
//
function getarticlesectiontext($articlesection) {
	$sql = new SQLSelectStatement(ARTICLESECTIONS_TABLE, 'text', array('articlesection_id'), array($articlesection), 'i');
	return $sql->fetch_value();
}

//
//
//
function getarticlesectionnumber($articlesection) {
	$sql = new SQLSelectStatement(ARTICLESECTIONS_TABLE, 'sectionnumber', array('articlesection_id'), array($articlesection), 'i');
	return $sql->fetch_value();
}

?>
