<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"pagecontent"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."functions/db.php");


//
//
//
function updatemenunavigation($page, $navigatordepth , $displaydepth , $sistersinnavigator) {
	$sql = new SQLUpdateStatement(MENUS_TABLE,
		array('navigatordepth', 'displaydepth', 'sistersinnavigator'), array('page_id'),
		array($navigatordepth, $displaydepth, $sistersinnavigator, $page), 'iiii');
	return $sql->run();
}

?>
