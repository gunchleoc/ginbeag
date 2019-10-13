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

require_once $projectroot."includes/constants.php";
require_once $projectroot."functions/db.php";
require_once $projectroot."functions/categories.php";

//
//
//
function addcategory($parent,$name, $cattype)
{
    if($cattype==CATEGORY_NEWS) { $table = CATEGORIES_NEWS_TABLE;
    } elseif($cattype==CATEGORY_ARTICLE) { $table = CATEGORIES_ARTICLES_TABLE;
    } else {  $table = CATEGORIES_IMAGES_TABLE;
    }

    $sql = new SQLInsertStatement(
        $table,
        array('parent_id', 'name'),
        array($parent, $name),
        'is'
    );
    return $sql->insert();
}

//
// returns false if isroot($catid)
//
function renamecategory($catid, $name, $cattype)
{
    if($cattype==CATEGORY_NEWS) { $table = CATEGORIES_NEWS_TABLE;
    } elseif($cattype==CATEGORY_ARTICLE) { $table = CATEGORIES_ARTICLES_TABLE;
    } else {  $table = CATEGORIES_IMAGES_TABLE;
    }

    if (!isroot($catid, $cattype)) {
        $sql = new SQLUpdateStatement(
            $table,
            array('name'), array('category_id'),
            array($name, $catid), 'si'
        );
        return $sql->run();
    }
    return false;
}

//
// returns false if isroot($catid)
//
function movecategory($catid, $newparent, $cattype)
{
    if($cattype==CATEGORY_NEWS) { $table = CATEGORIES_NEWS_TABLE;
    } elseif($cattype==CATEGORY_ARTICLE) { $table = CATEGORIES_ARTICLES_TABLE;
    } else {  $table = CATEGORIES_IMAGES_TABLE;
    }

    if (!isroot($catid, $cattype) && !isdescendant($catid, $newparent, $cattype)) {
        $sql = new SQLUpdateStatement(
            $table,
            array('parent_id'), array('category_id'),
            array($newparent, $catid), 'ii'
        );
        return $sql->run();
    }
    return false;
}

//
//
//
function isdescendant($parent,$descendant, $cattype)
{
    $result=false;
    $children=getcategorychildren($parent, $cattype);
    while(!$result && count($children))
    {
        $currentchild=array_pop($children);
        if($currentchild==$descendant) {
            $result=true;
        }
        else
        {
            $children=array_merge($children, getcategorychildren($currentchild, $cattype));
        }
    }
    return $result;
}

//
// removes category from elements
// if category is not a root category, replaces category with parent category
//
function deletecategory($catid, $cattype)
{
    $result=true;

    if($cattype==CATEGORY_NEWS) {
        $table = CATEGORIES_NEWS_TABLE;
        $newsitemids=getcategorynewsitems($catid);
        for($i=0;$i<count($newsitemids);$i++)
        {
            removenewsitemcategories($newsitemids[$i], array(0 => $catid));
            if(!isroot($catid, $cattype)) {
                $result= $result & addnewsitemcategories($newsitemids[$i], array(0 => getcategoryparent($catid, $cattype)));
            }
        }
    }
    elseif($cattype==CATEGORY_ARTICLE) {
        $table = CATEGORIES_ARTICLES_TABLE;
        $pageids=getcategorypages($catid);
        for($i=0;$i<count($pageids);$i++)
        {
            removearticlecategories($pageids[$i], array(0 => $catid));
            if(!isroot($catid, $cattype)) {
                $result= $result & addpagecategories($pageids[$i], array(0 => getcategoryparent($catid, $cattype)));
            }
        }
    }
    else
    {
        $table = CATEGORIES_IMAGES_TABLE;
        $imagefilenames=getcategoryimages($catid);
        $result= true;

        for($i=0;$i<count($imagefilenames);$i++)
        {
            removeimagecategories($imagefilenames[$i], array(0 => $catid));
            if(!isroot($catid, $cattype)) {
                $result= $result & addimagecategories($imagefilenames[$i], array(0 => getcategoryparent($catid, $cattype)));
            }
        }
    }

    $sql = new SQLDeleteStatement($table, array('category_id'), array($catid), 'i');
    $result = $result & $sql->run();
    return $result;
}


//
//
//
function addimagecategories($filename, $categories)
{
    $result = true;

    $existingcategories = getcategoriesforimage($filename);

    foreach ($categories as $cat) {
        if (!isroot($cat, CATEGORY_IMAGE)) {
            if (!in_array($cat, $existingcategories)) {
                $sql = new SQLInsertStatement(
                    IMAGECATS_TABLE,
                    array('image_filename', 'category'),
                    array($filename, $cat),
                    'si'
                );
                $result = $result & ($sql->insert() > 0);
            }
        }
    }
    return $result;
}


//
//
//
function removeimagecategories($filename, $categories)
{
    $result = true;
    foreach ($categories as $cat) {
        $sql = new SQLDeleteStatement(IMAGECATS_TABLE, array('image_filename', 'category'), array($filename, $cat), 'si');
        $result = $result & $sql->run();
    }
    return $result;
}


//
//
//
function addpagecategories($page, $categories)
{
    $result = true;

    $existingcategories = getcategoriesforpage($page);

    foreach ($categories as $cat) {
        if (!isroot($cat, CATEGORY_ARTICLE)) {
            if (!in_array($cat, $existingcategories)) {
                $sql = new SQLInsertStatement(
                    ARTICLECATS_TABLE,
                    array('page_id', 'category'),
                    array($page, $cat),
                    'ii'
                );
                $result = $result & ($sql->insert() > 0);
            }
        }
    }
    return $result;
}


//
//
//
function removearticlecategories($page, $categories)
{
    $result = true;
    foreach ($categories as $cat) {
        $sql = new SQLDeleteStatement(ARTICLECATS_TABLE, array('page_id', 'category'), array($page, $cat), 'ii');
        $result = $result & $sql->run();
    }
    return $result;
}



//
//
//
function addnewsitemcategories($newsitem,$categories)
{
    $result = true;

    $existingcategories = getcategoriesfornewsitem($newsitem);

    foreach ($categories as $cat) {
        if (!isroot($cat, CATEGORY_NEWS)) {
            if (!in_array($cat, $existingcategories)) {
                $sql = new SQLInsertStatement(
                    NEWSITEMCATS_TABLE,
                    array('newsitem_id', 'category'),
                    array($newsitem, $cat),
                    'ii'
                );
                $result = $result & ($sql->insert() > 0);
            }
        }
    }
    return $result;
}


//
//
//
function removenewsitemcategories($newsitem, $categories)
{
    $result = true;
    foreach ($categories as $cat) {
        $sql = new SQLDeleteStatement(NEWSITEMCATS_TABLE, array('newsitem_id', 'category'), array($newsitem, $cat), 'ii');
        $result = $result & $sql->run();
    }
    return $result;
}
?>
