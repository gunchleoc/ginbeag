<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."functions/db.php";
require_once $projectroot."includes/objects/images.php";

// check legal vars
require_once $projectroot."admin/includes/legaladminvars.php";

require_once $projectroot."includes/includes.php";
require_once $projectroot."functions/images.php";
require_once $projectroot."functions/pages.php";
require_once $projectroot."includes/objects/showimage.php";
require_once $projectroot."admin/functions/sessions.php";

// print_r($_POST);
 //print_r($_GET);

checksession();

$nextitem=0;
$previousitem=0;
$image="";
$item=0;

if(isset($_GET['page'])) { $page=$_GET['page'];
} else { $page=0;
}

if(isset($_GET['image'])) {
    $image=$_GET['image'];
}
if(isset($_GET['item'])) {
    $item=$_GET['item'];
    // get image from item array
    $image=$_POST[$_GET['item']];
}

$showimage = new Showimage($page, $image, $item, true);
print($showimage->toHTML());
?>
