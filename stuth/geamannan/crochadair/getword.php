<?php

require_once "config.php";
require_once "../../../functions/db.php";

// Database tables allowed in queries
$legal_tables = array(
    'crochadair_aiteachan',
    'crochadair_faclan',
    'crochadair_faclan_beag'
);

// Database columns allowed in queries
$legal_columns = array(
    'id',
    'facal',
    'gaidhlig',
    'imt',
    'aaa',
);


$db->quiet_mode = true;

$mode = isset($_GET['mode']) ? $_GET['mode'] : "words";

switch ($mode) {
    case "placenames":
        makeXML("aiteachan", PLACENAMES_TABLE, 'id');
        break;
    case "wordssmall":
        makeXML("faclanbeag", WORDS_SMALL_TABLE, 'id');
        break;
    case "words":
        makeXML("faclan", WORDS_TABLE, 'id');
        break;
}

//
// get random entry form database and print as XML for AJAX
//
function makeXML($wrapper,$dbtable,$key)
{
    global $db;
    $query
        = "SELECT * FROM " . $dbtable
        . ", (SELECT FLOOR(MAX(".$dbtable.".".$key
        . ") * RAND()) AS randId FROM " . $dbtable
        . ") AS someRandId WHERE " . $dbtable . "." . $key . " = someRandId.randId";
    $sql = new RawSQLStatement($query);

    $entry = "";

    $row = $sql->fetch_row();
    foreach ($row as $key => $element) {
        $entry .= "<$key>" . $element . "</$key>";
    }

    header('Content-type: text/xml;	charset=utf-8');
    '<?xml version="1.0" encoding="UTF-8"?>';

    if (empty($entry)) {
        print('<error>No word found</error>');
    }
    if (empty($db->error_report)) {
        print("<".$wrapper."><entry>" . $entry. "</entry></".$wrapper.">");
    } else     {
        print('<error>' . $db->error_report . '</error>');
    }
}
?>
