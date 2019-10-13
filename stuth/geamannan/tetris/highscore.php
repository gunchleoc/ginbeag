<?php

require_once "config.php";

if (isset($_GET['savescore']) && isset($_COOKIE['tetris-maysavehighscore'])) {
    $cookie=$_SERVER["HTTP_COOKIE"];
    $cookie=substr($cookie, strrpos($cookie, "tetris-maysavehighscore=")+24);
    savescore($_GET['score'], rawurldecode($_GET['name']), $cookie);
}

elseif (isset($_GET['checkscore'])) {

    mayaddscore($_GET['checkscore']);
}
elseif (isset($_GET['print'])) {
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

    if(getmin("score", DBTABLE, '1') < $score || countelements("score", DBTABLE)  < HIGHSCORES_NUMBER) {
        print("1");
    }
    else
    {
        print("0");
    }
    print("</mayaddscore>");
}


//
// saves a highscore
// compares with cookie against hacking
//
function savescore($score, $name, $cookie)
{
    //    global $_COOKIE;
    header('Content-type: text/xml;	charset=utf-8');
    echo '<?xml version="1.0" encoding="UTF-8"?>';

    print("<mayaddscore>");
    print('saving: '.$name.$score.' with cookie:'.$cookie);
    //        print_r($_COOKIE):
    print("</mayaddscore>");
    //    if($score==$cookie)
    {
    //    print('saving now!');
    insertentry(DBTABLE, array(0=> 0, 1=> setinteger($score), 2 => utf8_decode(setstring($name))));

    // delete superfluous highscores from database
    $scores= getmultiplefields(DBTABLE, 'key', '1', array(0 => '*'), $orderby = "score", $ascdesc = "DESC");
    $entries = array_values($scores);
    $count = count($entries);
    $i=HIGHSCORES_NUMBER;

    for(;$i<$count;$i++)
    {
        $entry=$entries[$i];
        deleteentry(DBTABLE, "score ='".$entry['score']."' and name = '".$entry['name']."'");
    }
    }
}



//
// Prints list of highscores and deletes superfluous entries
//
function printscore()
{
    $scores= getmultiplefields(DBTABLE, 'key', '1', array(0 => '*'), $orderby = "score", $ascdesc = "DESC");
    $entries = array_values($scores);
    $count = count($entries);
    $i=0;

    $xml="";

    header('Content-type: text/xml;	charset=utf-8');
    echo '<?xml version="1.0" encoding="UTF-8"?>';

    $xml .= "<highscores>";
    $xml .= "<noofentries>".$count."</noofentries>";


    // display predefined number of highscores
    for(;$i<HIGHSCORES_NUMBER && $i<$count;$i++)
    {
        $j=$i+1;
        $name=utf8_encode($entries[$i]['name']);


        $name=str_replace("/", "", $name);

        //        "   &quot;
        //'   &apos;
        //<   &lt;
        //>   &gt;
        //&   &amp;


        $xml .= "<entry>";
        $xml .= "<id>".$j."</id>";
        $xml .= "<name>".$name."</name>";
        $xml .= "<score>".$entries[$i]['score']."</score>";

        $xml .= "</entry>";
    }

    $xml .= "</highscores>";
    echo $xml;


    for(;$i<$count;$i++)
    {
        $entry=$entries[$i];
        deleteentry(DBTABLE, "score ='".$entry['score']."' and name = '".$entry['name']."'");
    }
}

// *************************** db convenience functions ********************* //


//
// security, use with all user input
//
function setinteger($var)
{
    if(!(@is_numeric($var) || @ctype_digit($var))) { return @settype($var, "int");
    } else { return $var;
    }
}

//
// security, use with all user input
//
function setstring($var)
{
    global $dbhost,$dbuser,$dbpasswd;

    $db=@new mysqli($dbhost, $dbuser, $dbpasswd, $dbname);
    if (!$db) {
        echo "Can't connect to database. Please try again later." . PHP_E
        exit();
    }

    $result= @$db->real_escape_string($var);
    @$db->close();
    return $result;
}



//
//
//
function getdbresultsingle($query)
{
    $sql=$db->singlequery($query);
    if($sql) {
        return $sql->fetch_row()[0];
    }
    else { return false;
    }
}

//
//
//
function countelements($keyname, $table)
{
    $result="";

    $query="select count(".$keyname.") from ".$table.";";
    return getdbresultsingle($query);
}


//
//
//
function getmin($fieldname, $table, $condition)
{
    $result="";

    $query="select min(".$fieldname.") from ".$table;
    $query.=" where ".$condition.";";
    return getdbresultsingle($query);
}


//
//
//
function deleteentry($table,$condition)
{
    $query="DELETE FROM ".$table;
    $query.=(" where ".$condition.";");
    $sql=singlequery($query);
    return $sql;
}



//
//  array values have to be in the right order for table
//
function insertentry($table,$values)
{
    $query="insert into ";
    $query.=$table." values(";
    for($i=0;$i<count($values)-1;$i++)
    {
        $query.="'".$values[$i]."', ";
    }
    $query.="'".$values[count($values)-1]."');";
    $sql=singlequery($query);
    return $sql;
}




//
// $keyname: for result array
//
function getmultiplefields($table, $keyname, $condition, $fieldnames = array(0 => '*'), $orderby="", $ascdesc="ASC")
{
    $result=array();

    $query="select ";
    $nooffields=count($fieldnames);
    for($i=0; $i<$nooffields-1;$i++)
    {
        $query.=$fieldnames[$i].", ";
    }
    $query.=$fieldnames[$nooffields-1];
    $query.=" from ".$table." where ".$condition;
    if(strlen($orderby)>0) {
        $query.=" order by ".$orderby." ".$ascdesc;
    }
    //  print($query);
    $sql=singlequery($query);
    if($sql) {
        $fields = $sql->field_count;

        // get index for field name
        $found=false;
        for($field=0;!$found && $field<$fields;$field++)
        {
            if ($sql->fetch_field_direct($field)->name == $keyname) {
                $fieldindex=$field;
                $found=true;
            }
        }

        // get column
        while ($row = $sql->fetch_row()) {
            // make associative array
            for($field=0;$field<$fields;$field++)
            {
                $result[$row[$fieldindex]][$sql->fetch_field_direct($field)->name] = $row[$field];
            }
        }
    }
    //  print_r($result);
    return $result;
}




//
//
//
function singlequery($query)
{
    global $dbname,$dbhost,$dbuser,$dbpasswd;

    $result=$query;

    $db=@new mysqli($dbhost, $dbuser, $dbpasswd, $dbname);
    if (!$db) {
        echo "Can't connect to database. Please try again later." . PHP_E
        exit();
    }

    $result = @$db->query($query);
    if (!$result) {
        print("Can't get data from database. Please notify the admin." . PHP_EOL);
        exit();
    }

    if (preg_match("/insert/i", $query)) {
        $result= $db->insert_id;
    }

    @$db->close();
    return $result;
}
?>
