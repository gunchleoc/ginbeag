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
function getpublishednewsitems($page, $number, $offset) {
    if (!$offset) {
        $offset = 0;
    }
    if (!$number > 0) {
        $number = 1;
    }

    $sql = new SQLSelectStatement(NEWSITEMS_TABLE, '*', array('page_id', 'ispublished'), array($page, 1), 'ii');
    $sql->set_order(array('date' => (displaynewestnewsitemfirst($page) ? 'DESC' : 'ASC')));
    $sql->set_limit($number, $offset);
    return $sql->fetch_many_rows();
}

//
//
//
function displaynewestnewsitemfirst($page)
{
    $sql = new SQLSelectStatement(NEWS_TABLE, 'shownewestfirst', array('page_id'), array($page), 'i');
    return $sql->fetch_value();
}

//
//
//
function countpublishednewsitems($page)
{
    $sql = new SQLSelectStatement(NEWSITEMS_TABLE, 'newsitem_id', array('page_id', 'ispublished'), array($page, 1), 'ii');
    $sql->set_operator('count');
    return $sql->fetch_value();
}


//
//
//
function getnewsitemcontents($newsitem)
{
    $sql = new SQLSelectStatement(NEWSITEMS_TABLE, '*', array('newsitem_id'), array($newsitem), 'i');
    return $sql->fetch_row();
}


//
//
//
function getfirstnewsitemcontents($page)
{
    $sql = new SQLSelectStatement(NEWSITEMS_TABLE, '*', array('page_id', 'ispublished'), array($page, 1), 'ii');
    $sql->set_order(array('date' => (displaynewestnewsitemfirst($page) ? 'DESC' : 'ASC')));
    $sql->set_limit(1, 0);
    return $sql->fetch_row();
}

//
//
//
function getoldestnewsitemdate($page)
{
    $sql = new SQLSelectStatement(NEWSITEMS_TABLE, 'date', array('page_id'), array($page), 'i');
    $sql->set_operator('min');
    return @getdate(strtotime($sql->fetch_value()));
}

//
//
//
function getnewestnewsitemdate($page)
{
    $sql = new SQLSelectStatement(NEWSITEMS_TABLE, 'date', array('page_id'), array($page), 'i');
    $sql->set_operator('max');
    return @getdate(strtotime($sql->fetch_value()));
}

//
//
//
function getnewsitempage($newsitem)
{
    $sql = new SQLSelectStatement(NEWSITEMS_TABLE, 'page_id', array('newsitem_id'), array($newsitem), 'i');
    return $sql->fetch_value();
}

//
//
//
function getnewsitemsynopsisimages($newsitem)
{
    $sql = new SQLSelectStatement(NEWSITEMSYNIMG_TABLE, array('newsitemimage_id', 'image_filename'), array('newsitem_id'), array($newsitem), 'i');
    $sql->set_order(array('position' => 'ASC'));
    return $sql->fetch_two_columns();
}

//
//
//
function getnewsitemsections($newsitem)
{
    $sql = new SQLSelectStatement(NEWSITEMSECTIONS_TABLE, 'newsitemsection_id', array('newsitem_id'), array($newsitem), 'i');
    $sql->set_order(array('sectionnumber' => 'ASC'));
    return $sql->fetch_column();
}

//
//
//
function getnewsitemsectionswithcontent($newsitem) {
    $sql = new SQLSelectStatement(NEWSITEMSECTIONS_TABLE, '*', array('newsitem_id'), array($newsitem), 'i');
    $sql->set_join('image_filename', IMAGES_TABLE, 'image_filename');
    $sql->set_order(array('sectionnumber' => 'ASC'));
    return $sql->fetch_many_rows();
}

//
//
//
function getfilterednewsitems($page,$selectedcat,$from,$to,$order,$ascdesc,$newsitemsperpage,$offset)
{
    $values = array();
    $datatypes = "";

    $months[1]='January';
    $months[2]='February';
    $months[3]='March';
    $months[4]='April';
    $months[5]='May';
    $months[6]='June';
    $months[7]='July';
    $months[8]='August';
    $months[9]='September';
    $months[10]='October';
    $months[11]='November';
    $months[12]='December';

    $date=$from["day"]." ".$months[$from["month"]]." ".$from["year"];
    $fromdate=date(DATETIMEFORMAT, strtotime($date));

    $date=$to["day"]." ".$months[$to["month"]]." ".$to["year"]." 23:59:59";
    $todate=date(DATETIMEFORMAT, strtotime($date));

    $query="SELECT DISTINCTROW * FROM ";
    $query.=NEWSITEMS_TABLE." AS items";

    // Filter for categories
    if ($selectedcat != 1) {
        // get all category descendants
        $categories = getcategorydescendants($selectedcat, CATEGORY_NEWS);
        $datatypes = str_pad($datatypes, count($categories) + strlen($datatypes), 'i');
        $placeholders = array_fill(0, count($categories), '?');
        $values = array_merge($values, $categories);

        $query .= ", ".NEWSITEMCATS_TABLE." AS cat";
        $query .= " WHERE cat.newsitem_id = items.newsitem_id";
        $query .= " AND cat.category IN (" . implode(',', $placeholders) . ") AND";
    } else {
        $query .= " WHERE";
    }
    // years
    $query .= " items.date BETWEEN ? AND ?";
    array_push($values, $fromdate);
    array_push($values, $todate);
    $datatypes .= 'ss';

    // get pages to search
    $query .= " AND items.page_id = ?";
    $query .= " AND items.ispublished = ?";
    array_push($values, $page);
    array_push($values, 1);
    $datatypes .= 'si';

    if($order) {
        $query .= " ORDER BY ";
        if ($order === "title") { $query .= "items.title ";
        } elseif ($order === "date") { $query .= "date ";
        } elseif ($order === "source") { $query .= "items.source ";
        }
        $query .= mb_strtolower($ascdesc, 'UTF-8') === "desc" ? "DESC" : "ASC";
    }

    $sql = new RawSQLStatement($query, $values, $datatypes);
    if($newsitemsperpage > 0) {
        $sql->set_limit($newsitemsperpage, $offset);
    }
    return $sql->fetch_many_rows();
}

//
//
//
function searchnewsitemtitles($search,$page,$showhidden=false)
{
    $query = "SELECT DISTINCTROW newsitem_id FROM " . NEWSITEMS_TABLE;
    $query .= " WHERE page_id = ? AND title like ?";

    $sql = new RawSQLStatement($query, array($page, '%' . trim($search) . '%'), 'is');
    return $sql->fetch_column();
}

?>
