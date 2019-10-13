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
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."functions/db.php";

//
//  todo: restrictions
// todo: return error state
//
function createpage($parent, $title, $navtitle, $pagetype, $user, $ispublishable)
{
    if (!$parent) { $parent=0;
    }

    $date = date(DATETIMEFORMAT);
    $sql = new SQLInsertStatement(
        PAGES_TABLE,
        array('parent_id', 'title_navigator', 'title_page', 'imagehalign', 'imageautoshrink',
        'usethumbnail', 'position_navigator', 'pagetype', 'editdate', 'editor_id',
        'permission', 'ispublished', 'ispublishable', 'showpermissionrefusedimages'),
        array($parent, $navtitle, $title, 'left', 1,
        1, create_getlastnavposition($parent) + 1, $pagetype, date(DATETIMEFORMAT), $user,
        NO_PERMISSION, 0, $ispublishable, 0),
        'isssiiissiiiii'
    );
    $sql->insert();

    $sql = new SQLSelectStatement(PAGES_TABLE, 'page_id', array('editdate'), array($date), 's');
    $page = $sql->fetch_value();

    if ($page > 0) {
        if($pagetype==="article") {
            createemptyarticle($page);
        } elseif($pagetype==="external") {
            createemptyexternal($page);
        } elseif($pagetype==="menu" || $pagetype==="articlemenu" || $pagetype==="linklistmenu") {
            createemptymenu($page);
        } elseif($pagetype==="news") {
            createemptynewspage($page);
        }

        if ($parent != 0 && ispagerestricted($parent)) {
            $sql = new SQLInsertStatement(
                RESTRICTEDPAGES_TABLE,
                array('page_id', 'masterpage'),
                array($page, getpagerestrictionmaster($parent)),
                'ii'
            );
            $sql->insert();
        }
    }
    return $page;
}

//
//
//
function create_getlastnavposition($pageid)
{
    $sql = new SQLSelectStatement(PAGES_TABLE, 'position_navigator', array('parent_id'), array($pageid), 'i');
    $sql->set_operator('max');
    return $sql->fetch_value();
}

//
//
//
function createemptyarticle($page)
{
    $now=getdate(strtotime('now'));
    $sql = new SQLInsertStatement(
        ARTICLES_TABLE,
        array('page_id', 'day', 'month', 'year', 'numberofpages', 'use_toc'),
        array($page, $now['mday'], $now['mon'], $now['year'], 1, 0),
        'isssii'
    );
    return $sql->insert();
}

//
//
//
function createemptyexternal($page)
{
    $sql = new SQLInsertStatement(
        EXTERNALS_TABLE,
        array('page_id'),
        array($page),
        'i'
    );
    return $sql->insert();
}


//
//
//
function createemptymenu($page)
{
    $sql = new SQLInsertStatement(
        MENUS_TABLE,
        array('page_id', 'navigatordepth', 'displaydepth', 'sistersinnavigator'),
        array($page, 1, 2, 1),
        'iiii'
    );
    return $sql->insert();
}


//
//
//
function createemptynewspage($page)
{
    $sql = new SQLInsertStatement(
        NEWS_TABLE,
        array('page_id', 'shownewestfirst'),
        array($page, 1),
        'ii'
    );
    return $sql->insert();
}
?>
