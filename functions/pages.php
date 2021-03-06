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
require_once $projectroot."functions/images.php";
require_once $projectroot."functions/treefunctions.php";

//
//
//
function getarticleoftheday()
{
    $date=date("Y-m-d", strtotime('now'));

    // Get the current article of the day if any
    $sql = new SQLSelectStatement(ARTICLEOFTHEDAY_TABLE, 'aotd_id', array('aotd_date'), array($date), 's');
    $aotd = $sql->fetch_value();

    // Ensure that it's still public
    if ($aotd > 0 && (!ispublished($aotd) || ispagerestricted($aotd))) {
        $sql = new SQLDeleteStatement(ARTICLEOFTHEDAY_TABLE, array('aotd_date'), array($date), 's');
        $sql->run();
        $aotd=0;
    }

    if (!$aotd) {
        // We need a new article. Collect viable articlemenus.
        $pagestosearch=explode(",", getproperty("Article of the Day Start Pages"));

        $pages=array();
        foreach ($pagestosearch as $searchme) {
            // Test for nonsense in the site properties ...
            if (getpagetype($searchme) === "articlemenu" && !ispagerestricted($searchme)) {
                // ... and get submenus
                $pages = array_merge($pages, getsubpagesforpagetype($searchme, "articlemenu"));
            }
        }
        if (!empty($pages)) {
            // Found some viable article menus, so get their articles
            $sql = new SQLSelectStatement(PAGES_TABLE, 'page_id', array('pagetype', 'ispublished'), array('article', '1'), 'si');
            $sql->set_distinct();
            $sql->add_integer_range_condition('parent_id', $pages);
            $pagesforselection = $sql->fetch_column();

            if (count($pagesforselection) > 0) {
                list($usec, $sec) = explode(' ', microtime());
                $random= ((float) $sec + ((float) $usec * 100000)) % count($pagesforselection);

                $aotd = $pagesforselection[$random];
                if ($aotd) {
                    $sql = new SQLInsertStatement(
                        ARTICLEOFTHEDAY_TABLE,
                        array('aotd_date', 'aotd_id'),
                        array($date, $aotd),
                        'si'
                    );
                    $sql->insert();
                }
            }
        }
    }
    return $aotd;
}

// *************************** pages general ************************************* //


//
//
//
function getpagetypes()
{
    $sql = new SQLSelectStatement(PAGETYPES_TABLE, array('type_key', 'type_description'));
    $sql->set_order(array('type_key' => 'ASC'));
    return $sql->fetch_two_columns();
}

//
//
//
function getpagecontents($page)
{
    $sql = new SQLSelectStatement(PAGES_TABLE, '*', array('page_id'), array($page), 'i');
    $sql->set_join('image_filename', IMAGES_TABLE, 'image_filename');
    return $sql->fetch_row();
}

//
//
//
function getmaintitle($pagecontents) {
    $result="";
    if ($pagecontents['pagetype'] !== "news") {
        $parent = $pagecontents['parent_id'];
        if ($parent > 0) {
            return getpagetitle($parent);
        }
    }
    return $pagecontents['title_page'];
}

//
//
//
function getsisters($page)
{
    return getchildren(getparent($page));
}

function getsisters_with_navinfo($page)
{
    return getchildren_with_navinfo(getparent($page));
}


//
//
//
function getallpages($fields)
{
    $sql = new SQLSelectStatement(PAGES_TABLE, $fields);
    $sql->set_order(array('page_id' => 'ASC'));
    return $sql->fetch_many_rows();
}

//
//
//
function hasaccesssession($page)
{
    global $sid;

    $masterpage = getpagerestrictionmaster($page);

    if ($masterpage > 0) {
        if (empty($sid)) { return false;
        }

        $sql = new SQLSelectStatement(PUBLICSESSIONS_TABLE, 'session_user_id', array('session_id'), array($sid), 's');
        $user_id = $sql->fetch_value();

        $sql = new SQLSelectStatement(RESTRICTEDPAGESACCESS_TABLE, 'publicuser_id', array('publicuser_id', 'page_id'), array($user_id, $masterpage), 'ii');
        return $sql->fetch_value();
    }
    return true;
}

//
//
//
function getsubpagesforpagetype($page, $pagetype)
{
    $result=array();
    $searchme=array($page);
    while(count($searchme)) {
        $currentpage=array_shift($searchme);
        if(getpagetype($currentpage)===$pagetype) {
            array_push($result, $currentpage);
        }

        $sql = new SQLSelectStatement(PAGES_TABLE, 'page_id', array('parent_id', 'pagetype'), array($currentpage, $pagetype), 'ii');
        $sql->set_order(array('position_navigator' => 'ASC'));
        $submenus = $sql->fetch_column();

        $searchme=array_merge($searchme, $submenus);
    }
    return $result;
}

//
//
//
function hasrssfeed($page)
{
    $sql = new SQLSelectStatement(RSS_TABLE, 'page_id', array('page_id'), array($page), 'i');
    return $sql->fetch_value();
}

//
//
//
function updatepagestats($page)
{
    if ($page > 0) {
        $year=date("Y", strtotime('now'));
        $month=date("m", strtotime('now'));

        $sql = new SQLSelectStatement(
            MONTHLYPAGESTATS_TABLE,
            array('stats_id', 'viewcount'), array('page_id', 'year', 'month'),
            array($page, $year, $month), 'iii'
        );
        $stats = $sql->fetch_row();

        if (empty($stats)) {
            $sql = new SQLInsertStatement(
                MONTHLYPAGESTATS_TABLE,
                array('page_id', 'viewcount', 'month', 'year'),
                array($page, 1, $month, $year),
                'iiii'
            );
            $sql->insert();
        } else {
            $sql = new SQLUpdateStatement(
                MONTHLYPAGESTATS_TABLE,
                array('viewcount'), array('stats_id'),
                array($stats['viewcount'] + 1, $stats['stats_id']), 'ii'
            );
            $sql->run();
        }
        return;
    }
}
?>
