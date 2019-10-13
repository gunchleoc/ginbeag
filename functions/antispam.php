<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "functions"));

require_once $projectroot."functions/db.php";

//
//
//
function createtoken() 
{
    $success = false;
    $token = "";
    $useragent = substr($_SERVER["HTTP_USER_AGENT"], 0, 255);

    if(strlen($useragent) > 0) {
        $now=strtotime('now');

        mt_srand(make_seed());
        $token = md5("".mt_rand());

        cleartokens();

        $sql = new SQLInsertStatement(
            ANTISPAM_TOKENS_TABLE,
            array('token_id', 'session_time', 'browseragent'),
            array($token, date(DATETIMEFORMAT, $now), $useragent),
            'sss'
        );
        $sql->insert();
    }
    else { $token = "";
    }
    return $token;
}


//
//
//
function cleartokens() 
{
    $sql = new SQLDeleteStatement(ANTISPAM_TOKENS_TABLE, array(), array(date(DATETIMEFORMAT, strtotime('-1 hours'))), 's', 'session_time < ?');
    return $sql->run();
}


//
//
//
function checktoken($token) 
{
    global $SERVER;
    $useragent = substr($_SERVER["HTTP_USER_AGENT"], 0, 255);
    $sql = new SQLSelectStatement(ANTISPAM_TOKENS_TABLE, 'browseragent', array('token_id'), array($token), 's');
    return ($useragent === $sql->fetch_value());
}
?>
