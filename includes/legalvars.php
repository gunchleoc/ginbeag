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


$LEGALVARS = array();
$LEGALVARS["action"]=1;
$LEGALVARS["all"]=1;
$LEGALVARS["articlepage"]=1;
$LEGALVARS["articlesection"]=1;
$LEGALVARS["ascdesc"]=1;
$LEGALVARS["caption"]=1;
$LEGALVARS["categoriesblank"]=1;
$LEGALVARS["clear"]=1;
$LEGALVARS["contents"]=1;
$LEGALVARS["copyright"]=1;
$LEGALVARS["copyrightblank"]=1;
$LEGALVARS["elementtype"]=1;
$LEGALVARS["filename"]=1;
$LEGALVARS["filter"]=1;
$LEGALVARS["filterpage"]=1;
$LEGALVARS["forgetful"]=1;
$LEGALVARS["from"]=1;
$LEGALVARS["fromday"]=1;
$LEGALVARS["frommonth"]=1;
$LEGALVARS["fromyear"]=1;
$LEGALVARS["image"]=1;
$LEGALVARS["imageid"]=1;
$LEGALVARS["item"]=1;
$LEGALVARS["jumppage"]=1;
$LEGALVARS["jump"]=1;
$LEGALVARS["key"]=1;
$LEGALVARS["link"]=1;
$LEGALVARS["logout"]=1;
$LEGALVARS["m"]=1;
$LEGALVARS["missing"]=1;
$LEGALVARS["missingthumb"]=1;
$LEGALVARS["mode"]=1;
$LEGALVARS["newsitem"]=1;
$LEGALVARS["newsitemsection"]=1;
$LEGALVARS["noofimages"]=1;
$LEGALVARS["nothumb"]=1;
$LEGALVARS["number"]=1;
$LEGALVARS["offset"]=1;
$LEGALVARS["order"]=1;
$LEGALVARS["override"]=1;
$LEGALVARS["page"]=1;
$LEGALVARS["pageposition"]=1;
$LEGALVARS["params"]=1;
$LEGALVARS["permission"]=1;
$LEGALVARS["printview"]=1;
$LEGALVARS["search"]=1;
$LEGALVARS["selectedcat"]=1;
$LEGALVARS["showall"]=1;
$LEGALVARS["sid"]=1;
$LEGALVARS["sitemap"]=1;
$LEGALVARS["sitepolicy"]=1;
$LEGALVARS["source"]=1;
$LEGALVARS["sourceblank"]=1;
$LEGALVARS["sourcelink"]=1;
$LEGALVARS["subpages"]=1;
$LEGALVARS["superforgetful"]=1;
$LEGALVARS["text"]=1;
$LEGALVARS["to"]=1;
$LEGALVARS["today"]=1;
$LEGALVARS["tomonth"]=1;
$LEGALVARS["toyear"]=1;
$LEGALVARS["unknown"]=1;
$LEGALVARS["unlock"]=1;
$LEGALVARS["unused"]=1;
$LEGALVARS["uploader"]=1;
$LEGALVARS["user"]=1;


$getkeys=array_keys($_GET);

while($key=current($getkeys))
{
    if(!array_key_exists($key, $LEGALVARS)) {
        header("HTTP/1.0 404 Not Found");
        print("HTTP 404: Sorry, but this page does not exist.");
        if(DEBUG) { print("<br />".$key." not registered with legalvars.");
        }
        exit;
    }
    next($getkeys);
}


?>
