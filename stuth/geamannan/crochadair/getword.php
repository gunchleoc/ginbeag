<?php

require_once "config.php";
require_once "../../../functions/db.php";

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
    $query
        = "SELECT * FROM " . $dbtable
        . ", (SELECT FLOOR(MAX(".$dbtable.".".$key
        . ") * RAND()) AS randId FROM " . $dbtable
        . ") AS someRandId WHERE " . $dbtable . "." . $key . " = someRandId.randId";
    $sql = new RawSQLStatement($query);

    $xml = "<".$wrapper."><entry>";

    if ($wrapper === "aiteachan") {
        $row = $sql->fetch_row();
        foreach ($row as $key => $element) {
            $xml .= "<$key>".utf8_encode($element)."</$key>";
        }
    } else {
        $row = $sql->fetch_two_columns();
        $key = array_key_first($row);
        $xml .= utf8_encode($row[$key]);
    }
    $xml .= "</entry></".$wrapper.">";

    header('Content-type: text/xml;	charset=utf-8');
    '<?xml version="1.0" encoding="UTF-8"?>';

    print($xml);
}
?>
