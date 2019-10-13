<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "language"));

require_once $projectroot."includes/functions.php";

if(isset($defaultlanguage) && file_exists($projectroot."language/".$defaultlanguage.".php")) { include_once $projectroot."language/".$defaultlanguage.".php";
} else { include_once $projectroot."language/en.php";
}


// get lang for key string
function getlang($element)
{
    global $lang;
    if(array_key_exists($element, $lang)) { return $lang[$element];
    } else { return "[".$element."]";
    }
}

// get lang from array of key strings
function getlangarray($element,$index)
{
    global $lang;
    return $lang[$element][$index];
}

?>
