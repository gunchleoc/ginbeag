<?php
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
