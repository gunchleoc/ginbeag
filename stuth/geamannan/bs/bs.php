<?php

$items=25;

$liosta=file("bs.txt");

$keys = array_rand($liosta, $items);

shuffle($keys);

header('Content-type: text/xml;	charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';

echo "<bs>";
echo"<iomlan>".count($liosta)."</iomlan>";

for($i=0; $i<$items;$i++)
{
    echo  "<facal>".trim($liosta[$keys[$i]])."</facal>";
}

echo  "</bs>";
?>

