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

//
//
//
function getusername($user)
{
    $sql = new SQLSelectStatement(USERS_TABLE, 'username', array('user_id'), array($user), 'i');
    return $sql->fetch_value();
}

//
//
//
function getuseremail($user)
{
    $sql = new SQLSelectStatement(USERS_TABLE, 'email', array('user_id'), array($user), 'i');
    return $sql->fetch_value();
}

//
//
//
function getuserid($username)
{
    $sql = new SQLSelectStatement(USERS_TABLE, 'user_id', array('username'), array($username), 's');
    return $sql->fetch_value();
}


//
//
//
function getallcontacts()
{
    $sql = new SQLSelectStatement(USERS_TABLE, 'user_id', array('iscontact'), array(1), 'i');
    $sql->set_order(array('username' => 'ASC'));
    return $sql->fetch_column();
}

//
//
//
function getiscontact($user)
{
    $sql = new SQLSelectStatement(USERS_TABLE, 'iscontact', array('user_id'), array($user), 'i');
    return $sql->fetch_value();
}

//
//
//
function getcontactfunction($user)
{
    $sql = new SQLSelectStatement(USERS_TABLE, 'contactfunction', array('user_id'), array($user), 'i');
    return $sql->fetch_value();
}

?>
