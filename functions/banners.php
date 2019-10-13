<?php
/*
 * An Gineadair Beag is a content management system to run websites with.
 *
 * Copyright (C) 2005-2019 GunChleoc
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

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
