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
require_once $projectroot."functions/users.php";

//
//
//
function register($user,$pass,$email)
{
    list($usec, $sec) = explode(' ', microtime());
    $activationkey= (float) $sec + ((float) $usec * 100000);
    $activationkey=md5($activationkey);

    $sql = new SQLInsertStatement(
        USERS_TABLE,
        array('user_active', 'username', 'displayname', 'password', 'email', 'userlevel', 'iscontact',
        'activationkey', 'last_login', 'retries'),
        array(0, mb_strtolower($user, 'UTF-8'), $user, md5($pass), $email, USERLEVEL_USER, 0, $activationkey, date(DATETIMEFORMAT, strtotime('now')), 0),
        'isdssiissi'
    );
    $sql->insert();
    $sql = new SQLSelectStatement(USERS_TABLE, 'activationkey', array('username'), array($user), 's');
    if ($activationkey === $sql->fetch_value()) { return $activationkey;
    }
    return false;
}

//
//
//
function changeuserpassword($userid,$oldpass,$newpass,$confirmpass)
{
    $result["message"] = "Failed to change password";
    $result["error"] = false;
    if(checkpassword(getusername($userid), md5($oldpass))) {
        if(strlen($newpass)>7) {
            if($newpass===$confirmpass) {
                $sql = new SQLUpdateStatement(
                    USERS_TABLE,
                    array('password'), array('user_id'),
                    array(md5($newpass), $userid), 'si'
                );
                if ($sql->run()) {
                    $result["message"] = "Password changed successfully";
                }
            }
            else
            {
                $result["message"] = "Passwords did not match.";
                $result["error"] = true;
            }
        }
        else
        {
            $result["message"] = "Your password must be at least 8 digits long.";
            $result["error"] = true;
        }
    }
    else
    {
        $result["message"] = "Wrong password.";
        $result["error"] = true;
    }
    return $result;
}


//
//
//
function changeuserpasswordadmin($userid,$newpass,$confirmpass)
{
    $result="Failed to change password";
    if(isadmin()) {
        if(strlen($newpass)>7) {
            if($newpass===$confirmpass) {
                $sql = new SQLUpdateStatement(
                    USERS_TABLE,
                    array('password'), array('user_id'),
                    array(md5($newpass), $userid), 'si'
                );
                if ($sql->run()) {
                    $result= "Password changed successfully";
                }
            }
            else
            {
                $result="Passwords did not match";
            }
        }
        else
        {
            $result="Your password must be at least 8 digits long";
        }
    }
    else
    {
        $result="Please hack someone else.";
    }
    return $result;
}



//
//
//
function makepassword($userid)
{
    $letters=array(0=>0,1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9,
    10=>"a",11=>"b",12=>"c",13=>"d",14=>"e",15=>"f",16=>"g",17=>"h",
    18=>"i",19=>"j",20=>"k",21=>"l",22=>"m",22=>"n",23=>"o",24=>"p",
    25=>"q",26=>"r",27=>"s",28=>"t",29=>"u",30=>"v",31=>"w",32=>"x",
    33=>"y",34=>"z",
    35=>"A",36=>"B",37=>"C",38=>"D",39=>"E",40=>"F",41=>"G",42=>"H",
    43=>"I",44=>"J",45=>"K",46=>"L",47=>"M",48=>"N",49=>"O",50=>"P",
    51=>"Q",52=>"R",53=>"S",54=>"T",55=>"U",56=>"V",57=>"W",58=>"X",
    59=>"Y",60=>"Z",
    );

    list($usec, $sec) = explode(' ', microtime());
    $seed = (float) $sec + ((float) $usec * 100000);
    srand($seed);

    $newpass="";
    for($i=0;$i<8;$i++)
    {
        $newpass.=$letters[rand(0, 60)];
    }

    $sql = new SQLUpdateStatement(
        USERS_TABLE,
        array('password'), array('user_id'),
        array(md5($newpass), $userid), 'si'
    );
    $sql->run();
    return $newpass;
}


//
//
//
function changeuseremail($userid,$email)
{
    $sql = new SQLUpdateStatement(
        USERS_TABLE,
        array('email'), array('user_id'),
        array($email, $userid), 'si'
    );
    $sql->run();
}

//
//
//
function setuserlevel($userid,$userlevel)
{
    $sql = new SQLUpdateStatement(
        USERS_TABLE,
        array('userlevel'), array('user_id'),
        array($userlevel, $userid), 'ii'
    );
    $sql->run();
}

//
//
//
function getuserlevel($userid)
{
    $sql = new SQLSelectStatement(USERS_TABLE, 'userlevel', array('user_id'), array($userid), 'i');
    return $sql->fetch_value();
}

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
function changeiscontact($userid,$iscontact)
{
    $sql = new SQLUpdateStatement(
        USERS_TABLE,
        array('iscontact'), array('user_id'),
        array($iscontact, $userid), 'ii'
    );
    $sql->run();
}


//
//
//
function changecontactfunction($userid,$contactfunction)
{
    $sql = new SQLUpdateStatement(
        USERS_TABLE,
        array('contactfunction'), array('user_id'),
        array($contactfunction, $userid), 'si'
    );
    $sql->run();
}

//
//
//
function activateuser($userid)
{
    $sql = new SQLUpdateStatement(
        USERS_TABLE,
        array('user_active', 'activationkey'), array('user_id'),
        array(1, '', $userid), 'isi'
    );
    return $sql->run();
}

//
//
//
function deactivateuser($userid)
{
    $sql = new SQLUpdateStatement(
        USERS_TABLE,
        array('user_active'), array('user_id'),
        array(0, $userid), 'ii'
    );
    return $sql->run();
}

//
//
//
function hasactivationkey($username,$activationkey)
{
    $sql = new SQLSelectStatement(USERS_TABLE, 'activationkey', array('username'), array($username), 's');
    return $activationkey === $sql->fetch_value();
}

//
//
//
function userexists($username)
{
    $sql = new SQLSelectStatement(USERS_TABLE, 'username', array('username'), array($username), 's');
    return $sql->fetch_value();
}

//
//
//
function emailexists($email,$user=false)
{
    $sql = new SQLSelectStatement(USERS_TABLE, 'user_id', array('email'), array($email), 's');
    $emailuser = $sql->fetch_value();
    if($user) {
        return ($emailuser && ($user !== $emailuser));
    }
    else
    {
        return ($emailuser);
    }
}

//
//
//
function getallusers()
{
    $sql = new SQLSelectStatement(USERS_TABLE, 'user_id');
    $sql->set_order(array('username' => 'ASC'));
    return $sql->fetch_column();
}

?>
