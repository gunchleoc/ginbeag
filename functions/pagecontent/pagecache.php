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
$projectroot=substr($projectroot, 0, strrpos($projectroot, "pagecontent"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "functions"));

require_once $projectroot."functions/db.php";
require_once $projectroot."functions/publicsessions.php";
require_once $projectroot."functions/treefunctions.php";
require_once $projectroot ."config.php";

//
//
//
function getcachedpage($page, $parameters)
{
    $result="";
    $fields=array(
    'page_id',
    'content_html',
    'lastmodified'
    );

    $sql = new SQLSelectStatement(PAGECACHE_TABLE, array('page_id', 'content_html', 'lastmodified'), array('cache_key'), array($page.$parameters), 's');
    $pagefields = $sql->fetch_many_rows();

    if(isset($pagefields[$page])) {
        if(!iscachedpagedatecurrent($page, $pagefields[$page]["lastmodified"])) {
            $sql = new SQLDeleteStatement(PAGECACHE_TABLE, array('page_id'), array($page), 'i');
            $sql->run();
        }
        else
        {
            $result = $pagefields[$page]["content_html"];
        }
    }
    return $result;
}



//
//
//
function makecachedpage($page, $parameters, $content_html)
{
    // create date
    $now=date(DATETIMEFORMAT, strtotime('now'));
    $key = $page.$parameters;

    if (strlen($key) <= 255) {
        // insert or update entries
        $sql = new SQLSelectStatement(PAGECACHE_TABLE, 'page_id', array('cache_key'), array($key), 'i');

        if($sql->fetch_value() == $page) {
            $sql = new SQLUpdateStatement(
                PAGECACHE_TABLE,
                array('content_html', 'lastmodified'), array($key),
                array($content_html, $now, 'cache_key'), 'sss'
            );
            $sql->run();
        } else {
            $sql = new SQLInsertStatement(
                PAGECACHE_TABLE,
                array('cache_key', 'page_id', 'content_html', 'lastmodified'),
                array($key, $page, $content_html, $now),
                'siss'
            );
            $sql->insert();
        }
    }
}


//
// compare to edit date and to current date
// date = last time cache was modified
//
function iscachedpagedatecurrent($page, $date)
{
    $sql = new SQLSelectStatement(PAGES_TABLE, 'editdate', array('page_id'), array($page), 'i');
    $pagedate = $sql->fetch_value();
    return $pagedate <= $date && $date > date(DATETIMEFORMAT, strtotime('-1 day'));
}

?>
