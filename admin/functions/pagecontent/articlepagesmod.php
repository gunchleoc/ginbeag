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
function updatearticlesource($page, $author, $location, $day, $month, $year, $source, $sourcelink, $toc)
{
    $toc === "true" ? $toc = 1 : $toc = 0;
    if (strlen($year) != 4) { $year = "0000";
    }
    $sql = new SQLUpdateStatement(
        ARTICLES_TABLE,
        array('article_author', 'location', 'day', 'month', 'year', 'source', 'sourcelink', 'use_toc'), array('page_id'),
        array($author, $location, $day, $month, $year, $source, $sourcelink, $toc, $page), 'ssiiissii'
    );
    return $sql->run();
}

//
//
//
function addarticlepage($page)
{
    $numberofpages = numberofarticlepages($page);
    if (getlastarticlesection($page, $numberofpages)) {
        $sql = new SQLUpdateStatement(
            ARTICLES_TABLE,
            array('numberofpages'), array('page_id'),
            array($numberofpages + 1, $page), 'ii'
        );
        return $sql->run();
    }
    else { return false;
    }
}


//
// TODO broken
//
function deletelastarticlepage($page)
{
    $numberofpages = numberofarticlepages($page);
    if (getlastarticlesection($page, $numberofpages)) {
        $sql = new SQLUpdateStatement(
            ARTICLES_TABLE,
            array('numberofpages'), array('page_id'),
            array($numberofpages - 1, $page), 'ii'
        );
        return $sql->run();
    }
    else { return false;
    }
}

//
//
//
function addarticlesection($page, $pagenumber)
{
    $sql = new SQLInsertStatement(
        ARTICLESECTIONS_TABLE,
        array('article_id', 'pagenumber', 'sectionnumber', 'imagealign', 'imageautoshrink', 'usethumbnail'),
        array($page, $pagenumber, getlastarticlesection($page, $pagenumber) + 1, "left", 1, 1),
        'iiisii'
    );
    return $sql->insert();
}


//
//
//
function updatearticlesectionimagealign($articlesection, $imagealign)
{
    $sql = new SQLUpdateStatement(
        ARTICLESECTIONS_TABLE,
        array('imagealign'), array('articlesection_id'),
        array($imagealign, $articlesection), 'si'
    );
    return $sql->run();
}


//
//
//
function updatearticlesectionimagesize($articlesection, $autoshrink, $usethumbnail)
{
    $sql = new SQLUpdateStatement(
        ARTICLESECTIONS_TABLE,
        array('usethumbnail', 'imageautoshrink'), array('articlesection_id'),
        array($usethumbnail, $autoshrink, $articlesection), 'iii'
    );
    return $sql->run();
}


//
//
//
function updatearticlesectionimagefilename($articlesection, $imagefilename)
{
    $sql = new SQLUpdateStatement(
        ARTICLESECTIONS_TABLE,
        array('sectionimage'), array('articlesection_id'),
        array(basename($imagefilename), $articlesection), 'si'
    );
    return $sql->run();
}

//
//
//
function updatearticlesectiontitle($articlesection, $sectiontitle)
{
    $sql = new SQLUpdateStatement(
        ARTICLESECTIONS_TABLE,
        array('sectiontitle'), array('articlesection_id'),
        array($sectiontitle, $articlesection), 'si'
    );
    return $sql->run();
}

//
//
//
function updatearticlesectiontext($articlesection, $text)
{
    $sql = new SQLUpdateStatement(
        ARTICLESECTIONS_TABLE,
        array('text'), array('articlesection_id'),
        array($text, $articlesection), 'si'
    );
    return $sql->run();
}


//
//
//
function deletearticlesection($articlesection)
{
    $sql = new SQLDeleteStatement(ARTICLESECTIONS_TABLE, array('articlesection_id'), array($articlesection), 'i');
    return $sql->run();
}


//
// returns the articlepage the section will be in after the move
//
function movearticlesection($articlesection, $pagenumber, $direction)
{
    $sql = new SQLSelectStatement(ARTICLESECTIONS_TABLE, 'article_id', array('articlesection_id'), array($articlesection), 'i');
    $page = $sql->fetch_value();

    $navpos=getarticlesectionnumber($articlesection);

    $newpage = $pagenumber;

    // move section to next articlepage
    if($direction==="down" && $navpos==getlastarticlesection($page, $pagenumber)) {
        // prepare page
        $noofpages=numberofarticlepages($page);
        $newpage=$pagenumber+1;

        if($noofpages<$newpage) {
            addarticlepage($page);
        } else {
            // make room
            $sql = new SQLSelectStatement(ARTICLESECTIONS_TABLE, array('articlesection_id'), array('article_id', 'pagenumber'), array($page, $newpage), 'ii');
            $sql->set_order(array('sectionnumber' => 'ASC'));
            $items = $sql->fetch_column();

            // Bring into shape for the database call
            $values = array();
            $counter = 2;
            foreach ($items as $id) {
                array_push($values, array($counter++, $id));
            }

            // Write
            $sql = new SQLUpdateStatement(
                ARTICLESECTIONS_TABLE,
                array('sectionnumber'), array('articlesection_id'),
                array(), 'ii'
            );
            $sql->set_values($values);
            $sql->run();
        }
        // Move section over
        $sql = new SQLUpdateStatement(
            ARTICLESECTIONS_TABLE,
            array('pagenumber', 'sectionnumber'), array('articlesection_id'),
            array($newpage, 1, $articlesection), 'iii'
        );
        $sql->run();
    }
    // move section to previous articlepage
    elseif($direction==="up" && $navpos==getfirstarticlesection($page, $pagenumber) && $pagenumber>1) {
        $newpage = $pagenumber-1;

        // Shift existing pages
        $sql = new SQLSelectStatement(ARTICLESECTIONS_TABLE, array('articlesection_id'), array('article_id', 'pagenumber'), array($page, $newpage), 'ii');
        $sql->set_order(array('sectionnumber' => 'ASC'));
        $items = $sql->fetch_column();

        // Bring into shape for the database call
        $values = array();
        $counter = 1;
        foreach ($items as $id) {
            array_push($values, array($counter++, $id));
        }

        // Write
        $sql = new SQLUpdateStatement(
            ARTICLESECTIONS_TABLE,
            array('sectionnumber'), array('articlesection_id'),
            array(), 'ii'
        );
        $sql->set_values($values);
        $sql->run();

        // Move section over
        $sql = new SQLUpdateStatement(
            ARTICLESECTIONS_TABLE,
            array('pagenumber', 'sectionnumber'), array('articlesection_id'),
            array($newpage, $counter, $articlesection), 'iii'
        );
        $sql->run();
    } else {
        // move section within articlepage
        $sql = new SQLSelectStatement(ARTICLESECTIONS_TABLE, 'articlesection_id', array('article_id', 'pagenumber'), array($page, $pagenumber), 'ii');
        $sql->set_order(array('sectionnumber' => ($direction==="down" ? 'ASC' : 'DESC')));
        move_item(ARTICLESECTIONS_TABLE, 'sectionnumber', 'articlesection_id', $articlesection, $sql->fetch_column(), 1, $direction);
    }
    return $newpage;
}

?>
