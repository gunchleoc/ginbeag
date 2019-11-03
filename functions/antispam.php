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
function createtoken($ip, $useragent)
{
    cleartokens();

    if (empty($useragent)) {
        return "";
    }
    $useragent = substr($useragent, 0, 255);

    if (!filter_var($ip, FILTER_VALIDATE_IP)) {
        return "";
    }
    $ip = inet_pton($ip);

    // Try to get existing token for IP
    $sql = new SQLSelectStatement(ANTISPAM_TOKENS_TABLE, 'token_id', array('session_ip'), array($ip), 's');
    $token = $sql->fetch_value();
    if (!empty($token)) {
        return $token;
    }

    // Create new token
    $now=strtotime('now');

    mt_srand(make_seed());
    $token = md5("".mt_rand());

    $sql = new SQLInsertStatement(
        ANTISPAM_TOKENS_TABLE,
        array('token_id', 'session_time', 'session_ip', 'browseragent'),
        array($token, date(DATETIMEFORMAT, strtotime('now')), $ip, $useragent),
        'ssss'
    );
    $sql->run();

    return $token;
}


//
//
//
function cleartokens()
{
    $sql = new SQLSelectStatement(ANTISPAM_TABLE, array('property_value'), array('property_name'), array('Maximum E-mails Per Minute'), 's');
    $interval = 2 * $sql->fetch_value() + 1;

    $sql = new SQLDeleteStatement(ANTISPAM_TOKENS_TABLE, array(), array(date(DATETIMEFORMAT, strtotime("-" . $interval . " minutes"))), 's', 'session_time < ?');
    return $sql->run();
}


//
//
//
function hastoken($token, $useragent)
{
    if (empty($useragent)) {
        return false;
    }
     if (empty($token)) {
        return false;
    }
    $useragent = substr($useragent, 0, 255);
    $sql = new SQLSelectStatement(ANTISPAM_TOKENS_TABLE, 'browseragent', array('token_id'), array($token), 's');
    return ($useragent === $sql->fetch_value());
}



function makerandomvariablename()
{
    $result = "";
    $letters = "aAbBcCdDeEfFgGhHiIjJkKlLmMnNoOpPqQrRsStTuUvVwWxXyYzZ";

    list($usec, $sec) = explode(' ', microtime());
    $randomlength = (((float) $sec + ((float) $usec * 100000)) % 25) + 6;

    for ($i=0; $i < $randomlength; $i++) {
        list($usec, $sec) = explode(' ', microtime());
        $position = ((float) $sec + ((float) $usec * 100000)) % strlen($letters);
        $result .= substr($letters, $position, 1);
    }
    return $result;
}

function rename_variables() {
    $newproperties['Math CAPTCHA Reply Variable'] = makerandomvariablename();
    $newproperties['Math CAPTCHA Answer Variable'] = makerandomvariablename();
    $newproperties['Message Text Variable'] = makerandomvariablename();
    $newproperties['Subject Line Variable'] = makerandomvariablename();
    $newproperties['E-Mail Address Variable'] = makerandomvariablename();
    return updateproperties(ANTISPAM_TABLE, $newproperties);
}
?>
