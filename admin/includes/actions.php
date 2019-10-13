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

// checks if an action variable of a HTTP request is a site action
function issiteaction($action)
{
    $result=false;
    $actions = array();
    $actions["site"]=1;
    $actions["sitestats"]=1;
    $actions["sitereferrers"]=1;
    $actions["sitepagetype"]=1;
    $actions["sitepagerestrict"]=1;
    $actions["sitelayout"]=1;
    $actions["siteiotd"]=1;
    $actions["sitespam"]=1;
    $actions["siteguest"]=1;
    $actions["sitepolicy"]=1;
    $actions["sitebanner"]=1;
    $actions["sitetech"]=1;
    $actions["sitedb"]=1;
    $actions["siteind"]=1;
    $actions["siteuserman"]=1;
    $actions["siteuserperm"]=1;
    $actions["siteuserlist"]=1;
    $actions["siteusercreate"]=1;
    $actions["siteipban"]=1;
    $actions["siteonline"]=1;

    if(array_key_exists($action, $actions)) { $result=true;
    }

    return $result;
}
?>
