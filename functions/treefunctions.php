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
require_once $projectroot."functions/pages.php";
require_once $projectroot."functions/publicsessions.php";
require_once $projectroot ."config.php";

if(isset($_GET["sid"])) { $user=getpublicsiduser($_GET["sid"]);
} else { $user=0;
}

$sql = new SQLSelectStatement(
    PAGES_TABLE,
    array(
        'page_id',
        'parent_id',
        'title_navigator',
        'title_page',
        'position_navigator',
        'pagetype',
        'ispublished'
    )
);
$sql->set_order(array('parent_id' => 'ASC', 'position_navigator' => 'ASC'));
$allpages = $sql->fetch_many_rows();

$sql = new SQLSelectStatement(RESTRICTEDPAGES_TABLE, '*');
$sql->set_order(array('page_id' => 'ASC'));
$allrestrictedpages = $sql->fetch_two_columns();

//
//
//
function getpagetype($page)
{
    global $allpages;
    if (ispageknown($page)) {
        return $allpages[$page]['pagetype'];
    }
    return "";
}

//
//
//
function getpagetitle($page)
{
    global $allpages;
    if (ispageknown($page)) {
        return $allpages[$page]['title_page'];
    }
    return "";
}

//
//
//
function getnavtitle($page)
{
    global $allpages;
    if (ispageknown($page)) {
        return $allpages[$page]['title_navigator'];
    }
    return "";
}

//
//
//
function getnavposition($page)
{
    global $allpages;
    if (ispageknown($page)) {
        return $allpages[$page]['position_navigator'];
    }
    return 0;
}

//
//
//
function getparent($page)
{
    global $allpages;
    if (ispageknown($page)) {
        return $allpages[$page]['parent_id'];
    }
    return 0;
}

//
//
//
function isrootpage($page)
{
    return getparent($page) == 0;
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
function getchildren($page)
{
    global $allpages;
    $result=array();
    reset($allpages);
    foreach ($allpages as $key => $checkpage) {
        if ($checkpage['parent_id'] == $page) {
            array_push($result, $key);
        }
    }
    return $result;
}

//
//
//
function ispageknown($page)
{
    global $allpages;
    return array_key_exists($page, $allpages);
}

//
//
//
function ispublished($page)
{
    global $allpages;
    return ispageknown($page) && $allpages[$page]['ispublished'] == 1;
}


//
//
//
function displaylinksforpage($page)
{
    return (ispublished($page) && (!ispagerestricted($page) || hasaccesssession($page)));
}

//
//
//
function ispagerestricted($page)
{
    global $allrestrictedpages;
    return array_key_exists($page, $allrestrictedpages);
}

//
//
//
function isthisexactpagerestricted($page)
{
    global $allrestrictedpages;
    return in_array($page, $allrestrictedpages);
}

//
//
//
function getpagerestrictionmaster($page)
{
    global $allrestrictedpages;
    if (array_key_exists($page, $allrestrictedpages)) {
        return $allrestrictedpages[$page];
    }
    return 0;
}
?>
