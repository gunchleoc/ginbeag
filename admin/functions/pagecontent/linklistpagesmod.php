<?php
/**
 * An Gineadair Beag is a content management system to run websites with.
 *
 * PHP Version 7
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
 *
 * @category Ginbeag
 * @package  Ginbeag
 * @author   gunchleoc <fios@foramnagaidhlig.net>
 * @license  https://www.gnu.org/licenses/agpl-3.0.en.html GNU AGPL
 * @link     https://github.com/gunchleoc/ginbeag/
 */

$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "pagecontent"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."admin/functions/moveitems.php";

//
//
//
function getlinktitle($link)
{
    $sql = new SQLSelectStatement(LINKS_TABLE, 'title', array('link_id'), array($link), 'i');
    return $sql->fetch_value();
}

//
//
//
function getlastlinkposition($page)
{
    $sql = new SQLSelectStatement(LINKS_TABLE, 'position', array('page_id'), array($page), 'i');
    $sql->set_operator('max');
    return $sql->fetch_value();
}

//
// TODO require some of these on the frontend
//
function addlink($page,$linktitle,$link,$imagefilename,$description)
{
    $columns = array('page_id', 'position');
    $values = array($page, getlastlinkposition($page) + 1);
    $datatypes = 'ii';

    if (!empty($linktitle)) {
        array_push($columns, 'title');
        array_push($values, $linktitle);
        $datatypes .= 's';
    }
    if (!empty($link)) {
        array_push($columns, 'link');
        array_push($values, $link);
        $datatypes .= 's';
    }
    if (!empty($imagefilename)) {
        array_push($columns, 'image_filename');
        array_push($values, $imagefilename);
        $datatypes .= 's';
    }
    if (!empty($description)) {
        array_push($columns, 'description');
        array_push($values, $description);
        $datatypes .= 's';
    }

    $sql = new SQLInsertStatement(LINKS_TABLE, $columns, $values, $datatypes);
    return $sql->insert();
}

//
//
//
function deletelink($link)
{
    $sql = new SQLDeleteStatement(LINKS_TABLE, array('link_id'), array($link), 'i');
    return $sql->run();
}

//
//
//
function updatelinkdescription($link, $text)
{
    $sql = new SQLUpdateStatement(
        LINKS_TABLE,
        array('description'), array('link_id'),
        array($text, $link), 'si'
    );
    return $sql->run();
}

//
//
//
function updatelinkproperties($linkid, $title, $link)
{
    $sql = new SQLUpdateStatement(
        LINKS_TABLE,
        array('title', 'link'), array('link_id'),
        array($title, $link, $linkid), 'ssi'
    );
    return $sql->run();
}

//
//
//
function updatelinkimagefilename($link, $image)
{
    $sql = new SQLUpdateStatement(
        LINKS_TABLE,
        array('image_filename'), array('link_id'),
        array($image, $link), 'si'
    );
    return $sql->run();
}

//
//
//
function movelink($link, $direction, $positions=1)
{
    if ($positions > 0) {
        $sql = new SQLSelectStatement(LINKS_TABLE, 'page_id', array('link_id'), array($link), 'i');
        $sql = new SQLSelectStatement(LINKS_TABLE, 'link_id', array('page_id'), array($sql->fetch_value()), 'i');
        $sql->set_order(array('position' => ($direction==="down" ? 'ASC' : 'DESC')));
        return move_item(LINKS_TABLE, 'position', 'link_id', $link, $sql->fetch_column(), $positions, $direction);
    }
    return false;
}


//
//
//
function sortlinksbyname($page)
{
    $sql = new SQLSelectStatement(LINKS_TABLE, 'link_id', array('page_id'), array($page), 'i');
    $sql->set_order(array('title' => 'ASC'));
    $items = $sql->fetch_column();

    // Bring into shape for the database call
    $values = array();
    $counter = 0;
    foreach ($items as $item) {
        array_push($values, array($counter++, $item));
    }

    // Write
    $sql = new SQLUpdateStatement(
        LINKS_TABLE,
        array('position'), array('link_id'),
        array(), 'ii'
    );
    $sql->set_values($values);
    return $sql->run();
}

?>
