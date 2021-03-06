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

require_once $projectroot."functions/cookies.php";
require_once $projectroot."admin/functions/pagesmod.php";
require_once $projectroot."functions/users.php";
require_once $projectroot."includes/includes.php";
require_once $projectroot."includes/functions.php";
require_once $projectroot."includes/objects/elements.php";

//
// returns array with sid and message
//
function login($username, $password)
{
    $password=md5($password);

    $user=getuserid($username);

    $result=array();
    $proceed=true;

    $sql = new SQLSelectStatement(USERS_TABLE, 'retries', array('username'), array($username), 's');
    $retries = $sql->fetch_value();

    if($retries>=3) {
        $sql = new SQLSelectStatement(USERS_TABLE, 'last_login', array('user_id'), array($user), 'i');
        $lastlogin = $sql->fetch_value();

        $time=date(DATETIMEFORMAT, strtotime('-15 minutes'));
        if($lastlogin>=$time) {
            $result['message']="You have entered the wrong password too often, so your account is locked for now. Please try again later.";
            $proceed=false;
        }

    }

    if($proceed) {
        if(checkpassword($username, $password)) {
            $result['sid']=createsession($user);
            if($result['sid']) {
                $result['message']="login successful";
            }
            else
            {
                $result['message']="Failed to create session";
            }
            updatelogindate($username, 0);
        }
        else
        {
            $result['message']="Wrong username or password";
            updatelogindate($username, $retries + 1);
        }
    }
    return $result;
}

//
//
//
function checkpassword($username,$md5password)
{
    $sql = new SQLSelectStatement(USERS_TABLE, 'password', array('username'), array($username), 's');
    return $sql->fetch_value() === $md5password;
}


//
// returns sid
//
function logout()
{
    global $projectroot;

    // Log out
    set_session_cookie(true, "", "");
    unlockuserpages();
    $sid = getsid();
    if ($sid) {
        $sql = new SQLDeleteStatement(SESSIONS_TABLE, array('session_id'), array($sid), 's');
        $sql->run();
    }

    // Optimize tables
    $tables_to_optimize = array (
        LOCKS_TABLE,
        SESSIONS_TABLE,
    );

    foreach ($tables_to_optimize as $table) {
        $sql = new RawSQLStatement("OPTIMIZE TABLE $table");
        $sql->fetch_Value();
    }

    // General cleanup
    require_once $projectroot."cleanup.php";
}

//
//
//
function updatelogindate($username, $retries)
{
    $sql = new SQLUpdateStatement(
        USERS_TABLE,
        array('retries', 'last_login'), array('username'),
        array($retries, date(DATETIMEFORMAT, strtotime('now')), $username), 'iss'
    );
    $sql->run();
}


//
//
//
function createsession($user)
{
    clearsessions();

    $sql = new SQLSelectStatement(SESSIONS_TABLE, 'session_id', array('session_user_id'), array($user), 'i');
    $lastsession = $sql->fetch_value();
    if ($lastsession) {
        $sql = new SQLDeleteStatement(SESSIONS_TABLE, array('session_id'), array($lastsession), 's');
        $sql->run();
    }

    mt_srand(make_seed());
    $sid = md5("".mt_rand());

    $sql = new SQLInsertStatement(
        SESSIONS_TABLE,
        array('session_id', 'session_user_id', 'session_time', 'browseragent'),
        array($sid, $user, date(DATETIMEFORMAT, strtotime('now')), substr($_SERVER["HTTP_USER_AGENT"], 0, 255)),
        'siss'
    );
    $sql->insert();

    set_session_cookie(true, $sid, $user);
    return $sid;
}


//
//
//
function clearsessions()
{
    $sql = new SQLDeleteStatement(
        SESSIONS_TABLE, array(),
        array(date(DATETIMEFORMAT, strtotime('-1 hours'))), 's', 'session_time < ?'
    );
    $sql->run();
}

//
//
//
function timeout($sid)
{
    $sql = new SQLSelectStatement(SESSIONS_TABLE, 'session_time', array('session_id'), array($sid), 's');
    $sessiontime = $sql->fetch_value();

    if(!$sessiontime) {
        return true;
    }

    $time=date(DATETIMEFORMAT, strtotime('-1 hours'));

    if($sessiontime < $time) {
        $sql = new SQLDeleteStatement(SESSIONS_TABLE, array('session_id'), array($sid), 's');
        $sql->run();
        return true;
    }

    $sql = new SQLUpdateStatement(
        SESSIONS_TABLE,
        array('session_time'), array('session_id'),
        array(date(DATETIMEFORMAT, strtotime('now')), $sid), 'ss'
    );
    $sql->run();
    return false;
}

//
//
//
function checksession()
{
    global $_GET;
    if(!isloggedin()) {
        $header = new HTMLHeader("Access restricted", "Webpage Building", "", getprojectrootlinkpath().'admin/login.php'.makelinkparameters($_GET), 'Click or tap here to log in', false);
        print($header->toHTML());

        $footer = new HTMLFooter();
        print($footer->toHTML());
        exit;
    }
}

//
//
//
function isloggedin()
{
    global $_COOKIE, $_SERVER;

    $cookieprefix = getproperty("Cookie Prefix");
    if (isset($_COOKIE[$cookieprefix."sid"])) {
         $sid = $_COOKIE[$cookieprefix."sid"];
    } else {
        return false;
    }

    $sql = new SQLSelectStatement(SESSIONS_TABLE, 'session_user_id', array('session_id'), array($sid), 's');
    $userid = $sql->fetch_value();

    if (!isset($_COOKIE[$cookieprefix."userid"]) || $_COOKIE[$cookieprefix."userid"]!=$userid) {
        return false;
    }

    if (timeout($sid)) {
        return false;
    }

    if (!checkagent($sid, $userid, $_SERVER["HTTP_USER_AGENT"])) {
        return false;
    }

    return true;
}


//
//
//
function getsid()
{
    global $_COOKIE;

    $cookieprefix = getproperty("Cookie Prefix");
    if(isset($_COOKIE[$cookieprefix."sid"])) { return $_COOKIE[$cookieprefix."sid"];
    } else { return "";
    }
}


//
//
//
function isadmin()
{
    $user = getsiduser();
    if (!$user) {
        return false;
    }
    $sql = new SQLSelectStatement(USERS_TABLE, 'userlevel', array('user_id'), array($user), 'i');
    $userlevel = $sql->fetch_value();
    return $userlevel == USERLEVEL_ADMIN;
}


//
//
//
function checkadmin()
{
    if(!isadmin()) {
        die('<p class="highlight">You have no permission for this area</p>');
    }
}


//
// compares browser agent to entry in the sessions table
//
function checkagent($sid, $userid, $browseragent)
{
    if($browseragent) {
        $sql = new SQLSelectStatement(SESSIONS_TABLE, 'browseragent', array('session_id'), array($sid), 's');
        $sessionagent = $sql->fetch_value();
        return (substr($sessionagent, 0, 255) === substr($browseragent, 0, 255));
    }
    return true;
}


//
//
//
function getsiduser()
{
    $sid = getsid();
    if (!$sid) {
        return false;
    }
    $sql = new SQLSelectStatement(SESSIONS_TABLE, 'session_user_id', array('session_id'), array($sid), 's');
    return $sql->fetch_value();
}

//
//
//
function getloggedinusers()
{
    $query="select username from ";
    $query.=USERS_TABLE." as users, ";
    $query.=SESSIONS_TABLE." as sessions";
    $query.=" where users.user_id = sessions.session_user_id";
    $query.=" order by users.username ASC";

    $sql = new RawSQLStatement($query);
    return $sql->fetch_column();
}

//
//
//
function isactive($user)
{
    $sql = new SQLSelectStatement(USERS_TABLE, 'user_active', array('user_id'), array($user), 'i');
    return $sql->fetch_value();
}
?>
