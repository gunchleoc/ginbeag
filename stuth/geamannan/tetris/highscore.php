<?php

require_once "config.php";
require_once "../../../functions/db.php";

if (isset($_GET['savescore']) && isset($_COOKIE['tetris-maysavehighscore'])) {
    $cookie=$_SERVER["HTTP_COOKIE"];
    $cookie=substr($cookie, strrpos($cookie, "tetris-maysavehighscore=")+24);
    savescore($_GET['score'], rawurldecode($_GET['name']), $cookie);
} elseif (isset($_GET['checkscore'])) {
    mayaddscore($_GET['checkscore']);
} elseif (isset($_GET['print'])) {
    printscore();
}


//
// 1 if eligible for high score list, 0 otherwise
//
function mayaddscore($score)
{
    header('Content-type: text/xml;	charset=utf-8');
    echo '<?xml version="1.0" encoding="UTF-8"?>';

    print("<mayaddscore>");

    $sql = new SQLSelectStatement(DBTABLE, 'score');
    $sql->set_operator('count');

    if ($sql->fetch_value() < HIGHSCORES_NUMBER) {
        print("1");
    } else {
        $sql = new SQLSelectStatement(DBTABLE, 'score');
        $sql->set_operator('min');

        if ($sql->fetch_value() < $score) {
            print("1");
        } else {
            print("0");
        }
    }
    print("</mayaddscore>");
}


//
// saves a highscore
// compares with cookie against hacking
//
function savescore($score, $name, $cookie)
{
    header('Content-type: text/xml;	charset=utf-8');
    echo '<?xml version="1.0" encoding="UTF-8"?>';

    print("<mayaddscore>");
    //print('saving: '.$name.$score.' with cookie:'.$cookie);
    print('saving...');
    print("</mayaddscore>");
    if ($score === $cookie) {
        $sql = new SQLInsertStatement(DBTABLE, array('score', 'name'), array($score, utf8_decode($name)), 'is');
        $sql->insert();
        prune_highscores();
    }
}


//
// Prints list of highscores and deletes superfluous entries
//
function printscore()
{
    prune_highscores();

    $sql = new SQLSelectStatement(DBTABLE, array('key', 'name', 'score'));
    $sql->set_order(array('score' => 'DESC'));
    $entries = $sql->fetch_many_rows();

    header('Content-type: text/xml;	charset=utf-8');
    echo '<?xml version="1.0" encoding="UTF-8"?>';

    $xml = "<highscores>";
    $xml .= "<noofentries>" . count($entries) . "</noofentries>";

    $counter = 0;
    foreach ($entries as $entry) {
        $name=utf8_encode($entry['name']);
        $name=str_replace("/", "", $name);

        $xml .= "<entry>";
        $xml .= "<rank>" . ++$counter . "</rank>";
        $xml .= "<name>" . $name . "</name>";
        $xml .= "<score>" . $entry['score'] . "</score>";
        $xml .= "</entry>";
    }

    $xml .= "</highscores>";
    echo $xml;
}

// delete superfluous highscores from database
function prune_highscores() {
    $sql = new SQLSelectStatement(DBTABLE, 'key');
    $sql->set_order(array('score' => 'DESC'));
    $entries = $sql->fetch_column();

    for ($i = HIGHSCORES_NUMBER; $i < count($entries); $i++) {
        $sql = new SQLDeleteStatement(DBTABLE, array('key'), array($entries[$i]), 'i');
        $sql->run();
    }
}

?>
