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
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."admin/functions/moveitems.php";
require_once $projectroot."functions/banners.php";

//
// fngt an zu spinnen, wenn keine image da ist,
// oder bereits ein Datensatz mit leerem image vorhanden ist
//
function addbanner($header, $imagefilename,$description,$link)
{
    $sql = new SQLSelectStatement(BANNERS_TABLE, 'position');
    $sql->set_operator('max');

    $columns = array('position');
    $values = array($sql->fetch_value() + 1);
    $datatypes = 'i';

    if (!empty($header)) {
        array_push($columns, 'header');
        array_push($values, $header);
        $datatypes .= 's';
    }
    if (!empty($imagefilename)) {
        array_push($columns, 'image');
        array_push($values, $imagefilename);
        $datatypes .= 's';
    }
    if (!empty($description)) {
        array_push($columns, 'description');
        array_push($values, $description);
        $datatypes .= 's';
    }
    if (!empty($link)) {
        array_push($columns, 'link');
        array_push($values, $link);
        $datatypes .= 's';
    }

    $sql = new SQLInsertStatement(BANNERS_TABLE, $columns, $values, $datatypes);
    return $sql->insert();
}



//
// fngt an zu spinnen, wenn keine image da ist,
// oder bereits ein Datensatz mit leerem image vorhanden ist
//
function addbannercode($header, $code)
{
    $sql = new SQLSelectStatement(BANNERS_TABLE, 'position');
    $sql->set_operator('max');

    $columns = array('position');
    $values = array($sql->fetch_value() + 1);
    $datatypes = 'i';

    if (!empty($header)) {
        array_push($columns, 'header');
        array_push($values, $header);
        $datatypes .= 's';
    }
    if (!empty($code)) {
        array_push($columns, 'code');
        array_push($values, $code);
        $datatypes .= 's';
    }

    $sql = new SQLInsertStatement(BANNERS_TABLE, $columns, $values, $datatypes);
    return $sql->insert();
}

//
//
//
function updatebanner($banner, $header, $imagefilename, $description, $link)
{
    $sql = new SQLUpdateStatement(
        BANNERS_TABLE,
        array('header', 'image', 'description', 'link', 'code'), array('banner_id'),
        array($header, basename($imagefilename), $description, $link, '', $banner), 'sssssi'
    );
    return $sql->run();
}



//
//
//
function updatebannercode($banner, $header, $code)
{
    if (strlen($code) > 0) {
        $sql = new SQLUpdateStatement(
            BANNERS_TABLE,
            array('header', 'image', 'description', 'link', 'code'), array('banner_id'),
            array($header, '', '', '', $code, $banner), 'sssssi'
        );
        return $sql->run();
    }
    return false;
}



//
//
//
function deletebanner($banner)
{
    $sql = new SQLDeleteStatement(BANNERS_TABLE, array('banner_id'), array($banner), 'i');
    return $sql->run();
}


//
//
//
function movebanner($banner, $direction, $positions=1)
{
    if ($positions > 0) {
        $sql = new SQLSelectStatement(BANNERS_TABLE, 'banner_id');
        $sql->set_order(array('position' => ($direction==="down" ? 'ASC' : 'DESC')));
        return move_item(BANNERS_TABLE, 'position', 'banner_id', $banner, $sql->fetch_column(), $positions, $direction);
    }
    return false;
}
?>
