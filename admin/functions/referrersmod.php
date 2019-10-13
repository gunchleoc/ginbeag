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
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."functions/db.php";
require_once $projectroot."functions/referrers.php";

//
//
//
function getblockedreferrers()
{
    $sql = new SQLSelectStatement(BLOCKEDREFERRERS_TABLE, 'referrerurl');
    $sql->set_order(array('referrerurl' => 'ASC'));
    return $sql->fetch_column();
}

//
//
//
function addblockedreferrer($referrer)
{
    if(!isreferrerblocked($referrer) && strlen($referrer) > 1) {
        $sql = new SQLInsertStatement(BLOCKEDREFERRERS_TABLE, array('referrerurl'), array($referrer), 's');
        return $sql->insert();
    } else {
        print($referrer." is already blocked");
        return false;
    }
}



//
//
//
function deleteblockedreferrer($referrer)
{
    $sql = new SQLDeleteStatement(BLOCKEDREFERRERS_TABLE, array('referrerurl'), array($referrer), 's');
    return $sql->run();
}

?>
