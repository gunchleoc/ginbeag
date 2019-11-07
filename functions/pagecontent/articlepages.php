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
$projectroot=substr($projectroot, 0, strrpos($projectroot, "functions"));

require_once $projectroot."functions/db.php";

//
//
//
function getarticlepagecontents($page)
{
    $sql = new SQLSelectStatement(ARTICLES_TABLE, '*', array('page_id'), array($page), 'i');
    return $sql->fetch_row();
}

//
// the section number on the page. Not the primary key!!!
//
function getarticlesections($page, $pagenumber)
{
    $sql = new SQLSelectStatement(ARTICLESECTIONS_TABLE, '*', array('article_id', 'pagenumber'), array($page, $pagenumber), 'ii');
    $sql->set_order(array('sectionnumber' => 'ASC'));
    return $sql->fetch_many_rows();
}

//
// for printview
//
function getallarticlesections($page)
{
    $sql = new SQLSelectStatement(ARTICLESECTIONS_TABLE, '*', array('article_id'), array($page), 'i');
    $sql->set_order(array('pagenumber' => 'ASC', 'sectionnumber' => 'ASC'));
    return $sql->fetch_many_rows();
}

//
//
//
function getarticlesectioncontents($articlesection)
{
    $sql = new SQLSelectStatement(ARTICLESECTIONS_TABLE, '*', array('articlesection_id'), array($articlesection), 'i');
    return $sql->fetch_row();
}
?>
