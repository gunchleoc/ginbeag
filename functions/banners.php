<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "functions"));

require_once $projectroot."functions/db.php";

//
//
//
function getbannercontents($banner) 
{
    $sql = new SQLSelectStatement(BANNERS_TABLE, '*', array('banner_id'), array($banner), 'i');
    return $sql->fetch_row();
}

//
//
//
function getbanners() 
{
    $sql = new SQLSelectStatement(BANNERS_TABLE, 'banner_id');
    $sql->set_order(array('position' => 'ASC'));
    return $sql->fetch_column();
}

//
//
//
function isbannercomplete($banner)
{
    if (empty($banner)) { return false;
    }
    $contents=getbannercontents($banner);
    $result=true;
    if(!strlen($contents['image'])>0 || !strlen($contents['description'])>0 || !strlen($contents['link'])>0) {
        $result=false;
    }
    if(!$result && strlen($contents['code'])>0) { $result=true;
    }
    return $result;
}
?>
