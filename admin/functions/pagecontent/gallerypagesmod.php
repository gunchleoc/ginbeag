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
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."admin/functions/moveitems.php";
require_once $projectroot."admin/functions/sessions.php";
require_once $projectroot."functions/users.php";
require_once $projectroot."functions/pages.php";

//
//
//
function addgalleryimage($page,$filename)
{
    $sql = new SQLInsertStatement(
        GALLERYITEMS_TABLE,
        array('page_id', 'image_filename', 'position'),
        array($page, $filename, getlastgalleryimageposition($page)),
        'isi'
    );
    return $sql->insert();
}

//
//
//
function changegalleryimage($galleryitem, $filename)
{
    $sql = new SQLUpdateStatement(
        GALLERYITEMS_TABLE,
        array('image_filename'), array('galleryitem_id'),
        array($filename, $galleryitem), 'si'
    );
    return $sql->run();
}

//
//
//
function removegalleryimage($galleryitem, $page)
{
    $sql = new SQLDeleteStatement(GALLERYITEMS_TABLE, array('galleryitem_id'), array($galleryitem), 'i');
    $success = $sql->run();
    if ($success) { reindexgallerypositions($page);
    }
    return $success;
}


//
//
//
function movegalleryimage($galleryitem, $direction, $positions=1)
{
    if ($positions > 0) {
        $sql = new SQLSelectStatement(GALLERYITEMS_TABLE, 'page_id', array('galleryitem_id'), array($galleryitem), 'i');
        $sql = new SQLSelectStatement(GALLERYITEMS_TABLE, 'galleryitem_id', array('page_id'), array($sql->fetch_value()), 'i');
        $sql->set_order(array('position' => ($direction==="down" ? 'ASC' : 'DESC')));
        return move_item(GALLERYITEMS_TABLE, 'position', 'galleryitem_id', $galleryitem, $sql->fetch_column(), $positions, $direction);
    }
    return false;
}

function reindexgallerypositions($page)
{
    $sql = new SQLSelectStatement(GALLERYITEMS_TABLE, 'galleryitem_id', array('page_id'), array($page), 'i');
    $sql->set_order(array('position' => 'ASC'));
    $items = $sql->fetch_column();

    // Bring into shape for the database call
    $values = array();
    for ($i = 0; $i < count($items); $i++) {
        array_push($values, array($i, $items[$i]));
    }

    // Write
    $sql = new SQLUpdateStatement(
        GALLERYITEMS_TABLE,
        array('position'), array('galleryitem_id'),
        array(), 'ii'
    );
    $sql->set_values($values);

    return $sql->run();
}
?>
