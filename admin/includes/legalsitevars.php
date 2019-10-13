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

$LEGALVARS = array();
$LEGALVARS["action"]=1;
$LEGALVARS["ascdesc"]=1;
$LEGALVARS["backup"]=1;
$LEGALVARS["bannerid"]=1;
$LEGALVARS["changeaccess"]=1;
$LEGALVARS["changelevel"]=1;
$LEGALVARS["clearpagecache"]=1;
$LEGALVARS["contact"]=1;
$LEGALVARS["display"]=1;
$LEGALVARS["filterpermission"]=1;
$LEGALVARS["generate"]=1;
$LEGALVARS["holder"]=1;
$LEGALVARS["offset"]=1;
$LEGALVARS["order"]=1;
$LEGALVARS["page"]=1;
$LEGALVARS["postaction"]=1;
$LEGALVARS["profile"]=1;
$LEGALVARS["ref"]=1;
$LEGALVARS["search"]=1;
$LEGALVARS["sid"]=1;
$LEGALVARS["structure"]=1;
$LEGALVARS["type"]=1;
$LEGALVARS["userid"]=1;
$LEGALVARS["username"]=1;
$LEGALVARS["sitestats"]=1;
$LEGALVARS["serverprotocol"]=1;

$getkeys=array_keys($_GET);

while($key=current($getkeys))
{
    if(!array_key_exists($key, $LEGALVARS)) {
        header("HTTP/1.0 404 Not Found");
        print("HTTP 404: Sorry, but this page does not exist.");
        if(DEBUG) { print("<br />".$key." not registered with legalsitevars.");
        }
        exit;
    }
    next($getkeys);
}

?>
