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

require_once $projectroot."functions/db.php";
require_once $projectroot."admin/functions/pagecontent/newspagesmod.php";

//
// todo: reorganize position_navigator with page locking
// todo: return error state when needed
//
function deletepage($page)
{
    $deleteids = array();

    $pagestosearch = array($page);
    while (count($pagestosearch)) {
        $currentpage = array_pop($pagestosearch);
        array_push($deleteids, $currentpage);

        $sql = new SQLSelectStatement(PAGES_TABLE, 'page_id', array('parent_id'), array($currentpage), 'i');
        $sql->set_order(array('position_navigator' => 'ASC'));
        $pageids = $sql->fetch_column();

        foreach ($pageids as $id) {
            array_push($pagestosearch, $id);
        }
    }

    foreach ($deleteids as $id) {
        switch (getpagetype($id)) {
        case "article":
            $sql = new SQLDeleteStatement(ARTICLECATS_TABLE, array('page_id'), array($id), 'i');
            $sql->run();
            $sql = new SQLDeleteStatement(ARTICLESECTIONS_TABLE, array('article_id'), array($id), 'i');
            $sql->run();
            $sql = new SQLDeleteStatement(ARTICLES_TABLE, array('page_id'), array($id), 'i');
            $sql->run();
            break;
        case "external":
            $sql = new SQLDeleteStatement(EXTERNALS_TABLE, array('page_id'), array($id), 'i');
            $sql->run();
            break;
        case "gallery":
            $sql = new SQLDeleteStatement(GALLERYITEMS_TABLE, array('page_id'), array($id), 'i');
            $sql->run();
            break;
        case "linklist":
            $sql = new SQLDeleteStatement(LINKS_TABLE, array('page_id'), array($id), 'i');
            $sql->run();
            break;
        case "menu":
        case "articlemenu":
        case "linklistmenu":
            $sql = new SQLDeleteStatement(MENUS_TABLE, array('page_id'), array($id), 'i');
            $sql->run();
            break;
        case "news":
            $sql = new SQLSelectStatement(NEWSITEMS_TABLE, 'newsitem_id', array('page_id'), array($id), 'i');
            $newsitems = $sql->fetch_column();

            foreach($newsitems as $item) {
                $sql = new SQLDeleteStatement(NEWSITEMSECTIONS_TABLE, array('newsitem_id'), array($item), 'i');
                $sql->run();
                $sql = new SQLDeleteStatement(NEWSITEMSYNIMG_TABLE, array('newsitem_id'), array($item), 'i');
                $sql->run();
                $sql = new SQLDeleteStatement(NEWSITEMCATS_TABLE, array('newsitem_id'), array($item), 'i');
                $sql->run();
            }
            $sql = new SQLDeleteStatement(NEWSITEMS_TABLE, array('page_id'), array($id), 'i');
            $sql->run();
            $sql = new SQLDeleteStatement(NEWS_TABLE, array('page_id'), array($id), 'i');
            $sql->run();
            break;
        }

        removerssfeed($id);

        $sql = new SQLDeleteStatement(PAGES_TABLE, array('page_id'), array($id), 'i');
        $sql->run();
        $sql = new SQLDeleteStatement(RESTRICTEDPAGES_TABLE, array('page_id'), array($id), 'i');
        $sql->run();
        $sql = new SQLDeleteStatement(RESTRICTEDPAGESACCESS_TABLE, array('page_id'), array($id), 'i');
        $sql->run();
        $sql = new SQLDeleteStatement(PAGECACHE_TABLE, array('page_id'), array($id), 'i');
        $sql->run();
    }
    rebuildaccessrestrictionindex();
    return count($deleteids);
}
?>
