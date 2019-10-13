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

        $pagecount = count($pages);
        if ($pagecount > 0) {
            // Found some viable article menus, so get their articles
            $query = "SELECT DISTINCTROW page_id FROM " . PAGES_TABLE . " WHERE
					pagetype = 'article' AND
					parent_id IN (" . implode(',', array_fill(0, $pagecount, '?')) . ")
					AND ispublished = '1'";

            $sql = new RawSQLStatement($query, $pages, str_pad("", $pagecount, 'i'));
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
function getpagetype($page)
{
    $sql = new SQLSelectStatement(PAGES_TABLE, 'pagetype', array('page_id'), array($page), 'i');
    return $sql->fetch_value();
}

//
//
//
function getpagetitle($page)
{
    $sql = new SQLSelectStatement(PAGES_TABLE, 'title_page', array('page_id'), array($page), 'i');
    return $sql->fetch_value();
}

//
//
//
function getnavtitle($page)
{
    $sql = new SQLSelectStatement(PAGES_TABLE, 'title_navigator', array('page_id'), array($page), 'i');
    return $sql->fetch_value();
}


//
//
//
function getpageintro($page)
{
    $sql = new SQLSelectStatement(PAGES_TABLE, array('introtext', 'introimage', 'imagehalign', 'imageautoshrink', 'usethumbnail'), array('page_id'), array($page), 'i');
    return $sql->fetch_row();
}

//
//
//
function getpageintrotext($page)
{
    $sql = new SQLSelectStatement(PAGES_TABLE, 'introtext', array('page_id'), array($page), 'i');
    return $sql->fetch_value();
}


//
//
//
function getpageintroimage($page)
{
    $sql = new SQLSelectStatement(PAGES_TABLE, 'introimage', array('page_id'), array($page), 'i');
    return $sql->fetch_value();
}


//
//
//
function getnavposition($page)
{
    $sql = new SQLSelectStatement(PAGES_TABLE, 'position_navigator', array('page_id'), array($page), 'i');
    return $sql->fetch_value();
}

//
//
//
function getpageeditor($page)
{
    $sql = new SQLSelectStatement(PAGES_TABLE, 'editor_id', array('page_id'), array($page), 'i');
    return $sql->fetch_value();
}

//
// returns array of copyright, imagecopyright, permission
// permission is one of the constants PERMISSION_GRANTED, NO_PERMISSION
//
function getcopyright($page)
{
    $sql = new SQLSelectStatement(PAGES_TABLE, array('copyright', 'image_copyright', 'permission'), array('page_id'), array($page), 'i');
    return $sql->fetch_row();
}

//
// permission is one of the constants PERMISSION_GRANTED, NO_PERMISSION
//
function getpermission($page)
{
    $sql = new SQLSelectStatement(PAGES_TABLE, 'permission', array('page_id'), array($page), 'i');
    return $sql->fetch_value();
}


//
//
//
function geteditdate($page)
{
    $sql = new SQLSelectStatement(PAGES_TABLE, 'editdate', array('page_id'), array($page), 'i');
    return $sql->fetch_value();
}

//
//
//
function getparent($page)
{
    $sql = new SQLSelectStatement(PAGES_TABLE, 'parent_id', array('page_id'), array($page), 'i');
    return $sql->fetch_value();
}


//
//
//
function getsisters($page,$ascdesc="ASC")
{
    return getchildren(getparent($page), $ascdesc);
}

//
//
//
function getchildren($page,$ascdesc="ASC")
{
    $sql = new SQLSelectStatement(PAGES_TABLE, 'page_id', array('parent_id'), array($page), 'i');
    $sql->set_order(array('position_navigator' => $ascdesc));
    return $sql->fetch_column();
}

//
//
//
function ispublished($page)
{
    $sql = new SQLSelectStatement(PAGES_TABLE, 'ispublished', array('page_id'), array($page), 'i');
    return $sql->fetch_value();
}


//
//
//
function pageexists($page)
{
    $sql = new SQLSelectStatement(PAGES_TABLE, 'page_id', array('page_id'), array($page), 'i');
    $foundpage = $sql->fetch_value();
    return $foundpage > 0 && $foundpage == $page;
}


//
//
//
function isrootpage($page)
{
    return getparent($page)==0;
}

//
//
//
function getrootpages()
{
    return getchildren(0);
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
function ispagerestricted($page)
{
    $sql = new SQLSelectStatement(RESTRICTEDPAGES_TABLE, 'page_id', array('page_id'), array($page), 'i');
    return $sql->fetch_value();
}


//
//
//
function isthisexactpagerestricted($page)
{
    $sql = new SQLSelectStatement(RESTRICTEDPAGES_TABLE, 'page_id', array('masterpage'), array($page), 'i');
    return $sql->fetch_value();
}

//
//
//
function getpagerestrictionmaster($page)
{
    $sql = new SQLSelectStatement(RESTRICTEDPAGES_TABLE, 'masterpage', array('page_id'), array($page), 'i');
    return $sql->fetch_value();
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
