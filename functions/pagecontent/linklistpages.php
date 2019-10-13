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
$projectroot=substr($projectroot, 0, strrpos($projectroot, "pagecontent"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "functions"));

require_once $projectroot."functions/db.php";

//
//
//
function getlinklistitems($page)
{
    $sql = new SQLSelectStatement(LINKS_TABLE, 'link_id', array('page_id'), array($page), 'i');
    $sql->set_order(array('position' => 'ASC'));
    return $sql->fetch_column();
}

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
function getlinkcontents($link)
{
    $sql = new SQLSelectStatement(LINKS_TABLE, '*', array('link_id'), array($link), 'i');
    return $sql->fetch_row();
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
//
//
function getlinkdescription($link)
{
    $sql = new SQLSelectStatement(LINKS_TABLE, 'description', array('link_id'), array($link), 'i');
    return $sql->fetch_value();
}
?>
