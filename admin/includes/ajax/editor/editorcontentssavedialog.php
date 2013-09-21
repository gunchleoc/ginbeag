<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"editor"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"ajax"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/includes/objects/editor.php");

$editor = new EditorContentsSaveDialog($_POST['page'],$_POST['item'],$_POST['elementtype'], $_POST['edittext'],$_POST['title']);
print($editor->toHTML());
?>