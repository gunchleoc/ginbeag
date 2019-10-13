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
$projectroot=substr($projectroot, 0, strrpos($projectroot, "functions"));

require_once $projectroot."functions/db.php";

function getallcategorieswithname($cattype)
{
    if($cattype==CATEGORY_NEWS) { $table = CATEGORIES_NEWS_TABLE;
    } elseif($cattype==CATEGORY_ARTICLE) { $table = CATEGORIES_ARTICLES_TABLE;
    } else {  $table = CATEGORIES_IMAGES_TABLE;
    }

    $sql = new SQLSelectStatement($table, array('category_id', 'parent_id', 'name'));
    $sql->set_order(array('parent_id' => 'ASC', 'name' => 'ASC'));
    return $sql->fetch_many_rows();
}

//
//
//
function getcategorynamessorted($categories, $cattype)
{
    if($cattype==CATEGORY_NEWS) { $table = CATEGORIES_NEWS_TABLE;
    } elseif($cattype==CATEGORY_ARTICLE) { $table = CATEGORIES_ARTICLES_TABLE;
    } else {  $table = CATEGORIES_IMAGES_TABLE;
    }

    if (count($categories) > 0) {
        $datatypes = str_pad("", count($categories), 's');
        $placeholders = array_fill(0, count($categories), '?');
        $sql = new SQLSelectStatement($table, 'name', array(), $categories, $datatypes, "category_id IN (".implode(",", $placeholders).")");
        $sql->set_order(array('name' => 'ASC'));
        return $sql->fetch_column();
    }
    else { return array();
    }
}

//
//
//
function getcategoryname($catid, $cattype)
{
    if($cattype==CATEGORY_NEWS) { $table = CATEGORIES_NEWS_TABLE;
    } elseif($cattype==CATEGORY_ARTICLE) { $table = CATEGORIES_ARTICLES_TABLE;
    } else {  $table = CATEGORIES_IMAGES_TABLE;
    }

    $sql = new SQLSelectStatement($table, 'name', array('category_id'), array($catid), 'i');
    return $sql->fetch_value();
}

//
//
//
function getcategorychildren($catid, $cattype)
{
    if($cattype==CATEGORY_NEWS) { $table = CATEGORIES_NEWS_TABLE;
    } elseif($cattype==CATEGORY_ARTICLE) { $table = CATEGORIES_ARTICLES_TABLE;
    } else { $table = CATEGORIES_IMAGES_TABLE;
    }

    $sql = new SQLSelectStatement($table, 'category_id', array('parent_id'), array($catid), 'i');
    $sql->set_order(array('name' => 'ASC'));
    return $sql->fetch_column();
}

function getcategorydescendants($catid, $cattype)
{
    $result = array();
    $pendingcategories = array($catid);
    while (!empty($pendingcategories)) {
        $catid = array_pop($pendingcategories);
        array_push($result, $catid);
        $pendingcategories = array_merge($pendingcategories, getcategorychildren($catid, $cattype));
    }
    return $result;
}


//
//
//
function getcategoryparent($catid, $cattype)
{
    if($cattype==CATEGORY_NEWS) { $table = CATEGORIES_NEWS_TABLE;
    } elseif($cattype==CATEGORY_ARTICLE) { $table = CATEGORIES_ARTICLES_TABLE;
    } else {  $table = CATEGORIES_IMAGES_TABLE;
    }

    $sql = new SQLSelectStatement($table, 'parent_id', array('category_id'), array($catid), 'i');
    return $sql->fetch_value();
}

//
//
//
function isroot($catid, $cattype)
{
    $parentid=getcategoryparent($catid, $cattype);
    return $parentid==0;
}


//
//
//
function getcategoryimages($catid)
{
    $sql = new SQLSelectStatement(IMAGECATS_TABLE, 'image_filename', array('category'), array($catid), 'i');
    $sql->set_order(array('image_filename' => 'ASC'));
    return $sql->fetch_column();
}

//
//
//
function getcategorypages($catid)
{
    $sql = new SQLSelectStatement(ARTICLECATS_TABLE, 'page_id', array('category'), array($catid), 'i');
    $sql->set_order(array('page_id' => 'ASC'));
    return $sql->fetch_column();
}

//
//
//
function getcategorynewsitems($catid)
{
    $sql = new SQLSelectStatement(NEWSITEMCATS_TABLE, 'newsitem_id', array('category'), array($catid), 'i');
    $sql->set_order(array('newsitem_id' => 'ASC'));
    return $sql->fetch_column();
}


//
//
//
function getcategoriesforimage($filename)
{
    if (empty($filename)) { return array();
    }
    $sql = new SQLSelectStatement(IMAGECATS_TABLE, 'category', array('image_filename'), array($filename), 's');
    $sql->set_order(array('category' => 'ASC'));
    $sql->set_distinct();
    return $sql->fetch_column();
}

//
//
//
function getcategoriesforpage($page)
{
    $sql = new SQLSelectStatement(ARTICLECATS_TABLE, 'category', array('page_id'), array($page), 'i');
    $sql->set_order(array('category' => 'ASC'));
    $sql->set_distinct();
    return $sql->fetch_column();
}

//
//
//
function getcategoriesfornewsitem($newsitem)
{
    $sql = new SQLSelectStatement(NEWSITEMCATS_TABLE, 'category', array('newsitem_id'), array($newsitem), 'i');
    $sql->set_order(array('category' => 'ASC'));
    $sql->set_distinct();
    return $sql->fetch_column();
}

?>
