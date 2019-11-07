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

require_once $projectroot."admin/functions/moveitems.php";
require_once $projectroot."admin/functions/sessions.php";
require_once $projectroot."functions/users.php";
require_once $projectroot."functions/pages.php";

// *************************** edit ***************************************** //

//
//
//
function renamepage($page, $title_navigator, $title_page)
{
    $sql = new SQLUpdateStatement(
        PAGES_TABLE,
        array('title_page', 'title_navigator'), array('page_id'),
        array($title_page, $title_navigator, $page), 'ssi'
    );
    return $sql->run();
}

//
//
//
function getpageintroimage($page)
{
    $sql = new SQLSelectStatement(PAGES_TABLE, array('introimage', 'imagehalign', 'imageautoshrink', 'usethumbnail'), array('page_id'), array($page), 'i');
    return $sql->fetch_row();
}

//
//
//
function updatepageintro($page, $introtext)
{
    $sql = new SQLUpdateStatement(
        PAGES_TABLE,
        array('introtext'), array('page_id'),
        array($introtext, $page), 'si'
    );
    return $sql->run();
}


//
//
//
function updatepageintroimagealign($page,$imagealign)
{
    $sql = new SQLUpdateStatement(
        PAGES_TABLE,
        array('imagehalign'), array('page_id'),
        array($imagealign, $page), 'si'
    );
    return $sql->run();
}


//
//
//
function updatepageintroimagesize($page,$autoshrink, $usethumbnail)
{
    $sql = new SQLUpdateStatement(
        PAGES_TABLE,
        array('imageautoshrink', 'usethumbnail'), array('page_id'),
        array($autoshrink, $usethumbnail, $page), 'iii'
    );
    return $sql->run();
}


//
//
//
function updatepageintroimagefilename($page,$imagefilename)
{
    $sql = new SQLUpdateStatement(
        PAGES_TABLE,
        array('introimage'), array('page_id'),
        array($imagefilename, $page), 'si'
    );
    return $sql->run();
}


//
//
//
function movepage($page, $direction, $positions=1)
{
    $parent = getparent($page);
    if ($direction === "top" || $direction === "bottom") {
        $sql = new SQLSelectStatement(PAGES_TABLE, 'position_navigator', array('parent_id'), array($parent), 'i');
        $sql->set_operator('count');
        $positions = $sql->fetch_value();
    }
    if ($positions > 0) {
        $direction =  ($direction === "down" || $direction === "bottom") ? "down" : "up";

        $sql = new SQLSelectStatement(PAGES_TABLE, 'page_id', array('parent_id'), array($parent), 'i');
        $sql->set_order(array('position_navigator' => ($direction === "down" || $direction === "bottom" ? 'ASC' : 'DESC')));
        return move_item(PAGES_TABLE, 'position_navigator', 'page_id', $page, $sql->fetch_column(), $positions, $direction);
    }
    return false;
}


//
//
//
function sortsubpagesbyname($page)
{
    $sql = new SQLSelectStatement(PAGES_TABLE, 'page_id', array('parent_id'), array($page), 'i');
    $sql->set_order(array('title_page' => 'ASC'));
    $pages = $sql->fetch_column();

    if (empty($sql->fetch_column())) {
        return true;
    }

    // Bring into shape for the database call
    $values = array();
    for ($i=0; $i<count($pages); $i++) {
        array_push($values, array($i, $pages[$i]));
    }
    // Write
    $sql = new SQLUpdateStatement(
        PAGES_TABLE,
        array('position_navigator'), array('page_id'),
        array(), 'ii'
    );
    $sql->set_values($values);
    return $sql->run();
}

//
//
//
function getallsubpagenavtitles($page)
{
    $sql = new SQLSelectStatement(PAGES_TABLE, 'title_navigator', array('parent_id'), array($page), 'i');
    $sql->set_order(array('position_navigator' => 'ASC'));
    return $sql->fetch_column();
}

//
// todo return error states
//
function movetonewparentpage($page, $newparent)
{
    $result="";

    $sql = new SQLSelectStatement(PAGES_TABLE, 'position_navigator', array('parent_id'), array($newparent), 'i');
    $sql->set_operator('max');
    $navposition = $sql->fetch_value() + 1;

    $sql = new SQLUpdateStatement(
        PAGES_TABLE,
        array('position_navigator', 'parent_id'), array('page_id'),
        array($navposition, $newparent, $page), 'iii'
    );
    $sql->run();

    if(!isthisexactpagerestricted($page)) {
        $sql = new SQLSelectStatement(RESTRICTEDPAGES_TABLE, 'page_id', array('page_id'), array($newparent), 'i');
        if($sql->fetch_value()==$newparent && $newparent!=0) {
            if(!ispagerestricted($page)) {
                $result="This page now has restricted access.";
                $sql = new SQLInsertStatement(
                    RESTRICTEDPAGES_TABLE,
                    array('page_id', 'masterpage'),
                    array($page, getpagerestrictionmaster($newparent)),
                    'ii'
                );
                $sql->insert();
            } else {
                $sql = new SQLUpdateStatement(
                    RESTRICTEDPAGES_TABLE,
                    array('masterpage'), array('page_id'),
                    array(getpagerestrictionmaster($newparent), $page), 'ii'
                );
                $sql->run();
            }
        }
        elseif(ispagerestricted($page)) {
            $result="Access restriction to this page removed";
            $sql = new SQLDeleteStatement(RESTRICTEDPAGES_TABLE, array('page_id'), array($page), 'i');
            $sql->run();
        }
    }
    return $result;
}

//
//
//
function getmovetargets($page)
{
    $parent=getparent($page);
    $pagetype=getpagetype($page);

    $legaltypes=getlegalparentpagetypes($pagetype);

    $allpages=getallpages(array(0 => 'page_id', 1 => 'pagetype'));

    $result=array();
    if($legaltypes['root']) {
        $result=array(0 => 0);
    }

    $keys = array_keys($allpages);
    foreach ($allpages as $key => $currentpage) {
        if(array_key_exists($currentpage['pagetype'], $legaltypes) && $key != $page && $key != $parent) {
            array_push($result, $key);
        }
    }
    return $result;
}

//
// the types of pages that can be parentpages of a page with $pagetype
// returns an associative array of legal page types
//
function getlegalparentpagetypes($pagetype)
{
    $result=array();
    $restrictions = getrestrictions($pagetype);

    if($restrictions['allow_root']) {
        $result["root"] = true;
    }
    if($restrictions['allow_simplemenu']) {
        $result["menu"] = true;
    }
    if($restrictions['allow_self']) {
        $result[$pagetype] = true;
    }

    // special menu types
    if($pagetype==="article") {
        $result["articlemenu"] = true;
    }
    elseif($pagetype==="linklist") {
        $result["linklistmenu"] = true;
    }
    elseif($pagetype==="external") {
        $result["linklistmenu"] = true;
        $result["articlemenu"] = true;
        $result["news"] = true;
    }
    return $result;
}

//
// can a page of $pagetype be a direct subpage of $parentpage?
//
function islegalparentpage($pagetype, $parentpage)
{
    $result=false;

    if($parentpage==0) {
        $parentpagetype="root";
    }
    else
    {
        $parentpagetype=getpagetype($parentpage);
    }
    $legaltypes=getlegalparentpagetypes($pagetype);

    if(array_key_exists($parentpagetype, $legaltypes)) { $result=true;
    }
    return $result;
}

//
//
//
function getrestrictions($pagetype)
{
    $sql = new SQLSelectStatement(
        PAGETYPES_TABLE,
        array('allow_root', 'allow_simplemenu', 'allow_self'),
        array('type_key'), array($pagetype), 's'
    );
    return $sql->fetch_row();
}

//
//
//
function updaterestrictions($pagetype, $allowroot, $allowsimplemenu)
{
    $sql = new SQLUpdateStatement(
        PAGETYPES_TABLE,
        array('allow_root', 'allow_simplemenu'), array('type_key'),
        array($allowroot, $allowsimplemenu, $pagetype), 'iis'
    );
    return $sql->run();
}

//
//
//
function publish($page)
{
    if(ispublishable($page)) {
        $sql = new SQLUpdateStatement(
            PAGES_TABLE,
            array('ispublished'), array('page_id'),
            array(1, $page), 'ii'
        );
        return $sql->run();
    }
    else { return false;
    }
}

//
//
//
function unpublish($page)
{
    $sql = new SQLUpdateStatement(
        PAGES_TABLE,
        array('ispublished'), array('page_id'),
        array(0, $page), 'ii'
    );
    return $sql->run();
}


//
//
//
function makepublishable($page)
{
    $sql = new SQLUpdateStatement(
        PAGES_TABLE,
        array('ispublishable'), array('page_id'),
        array(1, $page), 'ii'
    );
    return $sql->run();
}


//
//
//
function hide($page)
{
    if (!ispublished($page)) {
        $sql = new SQLUpdateStatement(
            PAGES_TABLE,
            array('ispublishable'), array('page_id'),
            array(0, $page), 'ii'
        );
        return $sql->run();
    }
    else { return false;
    }
}



//
//
//
function ispublishable($page)
{
    $sql = new SQLSelectStatement(PAGES_TABLE, 'ispublishable', array('page_id'), array($page), 'i');
    return $sql->fetch_value();
}



//
//
//
function updateeditdata($page)
{
    $sql = new SQLUpdateStatement(
        PAGES_TABLE,
        array('editdate', 'editor_id'), array('page_id'),
        array(date(DATETIMEFORMAT, strtotime('now')), getsiduser(), $page), 'sii'
    );
    return $sql->run();
}

//
// permission is one of the constants PERMISSION_GRANTED, NO_PERMISSION
//
function updatecopyright($page,$copyright,$imagecopyright,$permission)
{
    $sql = new SQLUpdateStatement(
        PAGES_TABLE,
        array('copyright', 'image_copyright', 'permission'), array('page_id'),
        array($copyright, $imagecopyright, $permission, $page), 'ssii'
    );
    return $sql->run();
}



// *************************** restricted access **************************** //

//
//
//
function restrictaccess($page)
{
    $result = false;
    if (ispagerestricted($page)) {
        $sql = new SQLUpdateStatement(
            RESTRICTEDPAGES_TABLE,
            array('masterpage'), array('page_id'),
            array($page, $page), 'ii'
        );
        $result = $sql->run();
    } else {
        $sql = new SQLInsertStatement(
            RESTRICTEDPAGES_TABLE,
            array('page_id', 'masterpage'),
            array($page, $page),
            'ii'
        );
        $result = $sql->insert() > 0;
    }
    rebuildaccessrestrictionindex();
    return $result;
}


//
//
//
function removeaccessrestriction($page)
{
    $sql = new SQLDeleteStatement(RESTRICTEDPAGES_TABLE, array('masterpage'), array($page), 'i');
    $result = $sql->run();
    $sql = new SQLDeleteStatement(RESTRICTEDPAGES_TABLE, array('page_id'), array($page), 'i');
    $result = $result & $sql->run();
    rebuildaccessrestrictionindex();
    return $result;
}

//
// must be called when editing the pages that are restricted
//
function rebuildaccessrestrictionindex()
{
    $result="";

    // get masterpages from access table
    $sql = new SQLSelectStatement(RESTRICTEDPAGESACCESS_TABLE, 'page_id');
    $masterpages = $sql->fetch_column();

    $sql = new SQLSelectStatement(RESTRICTEDPAGES_TABLE, 'masterpage');
    $sql->set_distinct();
    $masterpages2 = $sql->fetch_column();

    $masterpages=array_unique(array_merge($masterpages, $masterpages2));

    // clear masterpages
    $sql = new RawSQLStatement("TRUNCATE table ". RESTRICTEDPAGES_TABLE);
    $sql->run();

    // define masterpages
    foreach ($masterpages as $masterpage) {
        $result.=' '.$masterpage;
        $sql = new SQLInsertStatement(
            RESTRICTEDPAGES_TABLE,
            array('page_id', 'masterpage'),
            array($masterpage, $masterpage),
            'ii'
        );
        $sql->insert();
    }

    // iterate through subpages
    while(count($masterpages))
    {
        $masterpage=array_pop($masterpages);

        $children = getchildren($masterpage);
        while(count($children)>0)
        {
            $child=array_pop($children);
            if(!isthisexactpagerestricted($child)) {
                $result.=' '.$child;
                $sql = new SQLInsertStatement(
                    RESTRICTEDPAGES_TABLE,
                    array('page_id', 'masterpage'),
                    array($child, $masterpage),
                    'ii'
                );
                // TODO this fails if master page has a restricted parent
                $sql->insert();
                $children = array_merge($children, getchildren($child));
            }
        }
    }

    $tables_to_optimize = array (
    RESTRICTEDPAGESACCESS_TABLE,
    RESTRICTEDPAGES_TABLE,
    );

    foreach ($tables_to_optimize as $table) {
        $sql = new RawSQLStatement("OPTIMIZE TABLE $table");
        $sql->fetch_Value();
    }
    return $result;
}


//
//
//
function getpageaccessforpublicuser($user)
{
    $sql = new SQLSelectStatement(RESTRICTEDPAGESACCESS_TABLE, 'page_id', array('publicuser_id'), array($user), 'i');
    return $sql->fetch_column();
}

//
//
//
function getrestrictedpages()
{
    $sql = new SQLSelectStatement(RESTRICTEDPAGES_TABLE, 'page_id', array('page_id'), array('masterpage'), 's');
    $sql->set_order(array('page_id' => 'ASC'));
    $sql->set_distinct();
    return $sql->fetch_column();
}

//
//
//
function hasaccess($user, $page)
{
    $masterpage = getpagerestrictionmaster($page);
    $sql = new SQLSelectStatement(RESTRICTEDPAGESACCESS_TABLE, 'publicuser_id', array('publicuser_id', 'page_id'), array($user, $masterpage), 'ii');
    return $sql->fetch_value();
}



// *************************** lock handling **************************************** //



//
// lock handling
// returns empty string when lock has been obtained
// else returns string containing reason for lock
//
function getpagelock($page)
{
    $result="";

    $lock=getlock($page);
    if (isset($lock['user_id']) && $lock['user_id'] !== getsiduser()) {
        // if session of lock owner has espired, clear lock
        $sql = new SQLSelectStatement(SESSIONS_TABLE, 'session_id', array('session_user_id'), array($lock['user_id']), 'i');
        if(timeout($sql->fetch_value())) {
            unlockpage($page);
        } else {
            $result = "This page has been locked by <i>";
            $result .= getdisplayname($lock['user_id']);
            $result .= "</i> on ";
            $result .= formatdatetime($lock['locktime']);
        }
    } else {
        lockpage(getsiduser(), $page);
    }
    return $result;
}


//
//
//
function lockpage($user, $page)
{
    $now=date(DATETIMEFORMAT, strtotime('now'));
    $sql = new SQLSelectStatement(LOCKS_TABLE, 'user_id', array('page_id'), array($page), 'i');
    if($sql->fetch_value()) {
        $sql = new SQLUpdateStatement(
            LOCKS_TABLE,
            array('locktime', 'user_id'), array('page_id'),
            array($now, $user, $page), 'sii'
        );
        return $sql->run();
    } else {
        $sql = new SQLInsertStatement(
            LOCKS_TABLE,
            array('page_id', 'user_id', 'locktime'),
            array($page, $user, $now),
            'iis'
        );
        return $sql->insert();
    }
}

//
//
//
function unlockpage($page)
{
    $sql = new SQLDeleteStatement(LOCKS_TABLE, array('page_id'), array($page), 'i');
    return $sql->run();
}

//
//
//
function unlockuserpages()
{
    $user = getsiduser();
    if (!$user) { return false;
    }
    $sql = new SQLDeleteStatement(LOCKS_TABLE, array('user_id'), array($user), 's');
    return $sql->run();
}

//
// array user_id, locktime
//
function getlock($page)
{
    // clear old locks
    $sql = new SQLDeleteStatement(
        LOCKS_TABLE, array(),
        array(date(DATETIMEFORMAT, strtotime('-30 minutes'))), 's', 'locktime < ?'
    );
    $sql->run();

    $sql = new SQLSelectStatement(LOCKS_TABLE, array('user_id', 'locktime'), array('page_id'), array($page), 'i');
    return $sql->fetch_row();
}

?>
