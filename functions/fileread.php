<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"functions"));

$stopwordfile=$projectroot."language/stopwords-en.txt";

$stopwords=filetoassocarray($stopwordfile,0,0);
print_r($stopwords);
/*print("<html><head></head><body>");
displayarray($stopwords);
print("</body></html>");*/
// *************************************************************************
// CSV functions
// *************************************************************************

//
// returns an array[$col][$row] containing tabled data from a file. Separator=";"
//
function filetoarray($filename,$separator=";")
{
  $contents[0][0]="File not found";
  if(file_exists($filename))
  {
    $file=fopen($filename,"r");
    if($file)
    {
//      print('Found file: '.$filename.'<br>');
      $filesize = filesize($filename);
      $data=fgetcsv($file, $filesize,$separator);
      $row=0;
      $cols=count($data);
      while($data)
      {
        for($col=0;$col<$cols;$col++)
        {
          $contents[$row][$col]=trim($data[$col]);
        }
        $row++;
        $data=fgetcsv($file, $filesize,$separator);
      }
      fclose($file);
    }
  }
  else
  {
    print("File not found: ".$filename);
  }
  return $contents;

}

//
// returns an accociative array containing tabled data from a file. Separator=";"
// The file item in column $firstcol is used as row key for the data in $secondcol
//
function filetoassocarray($filename, $firstcol, $secondcol,$separator=";")
{
$contents[0]="File not found";
  if(file_exists($filename))
  {
    $file=fopen($filename,"r");
    if($file)
    {
      $filesize = filesize($filename);
      $data=fgetcsv($file, $filesize,$separator);
      $row=0;
      $cols=count($data);
      if($secondcol<$cols)
      {
        while($data)
        {
          $contents[trim($data[$firstcol])]=trim($data[$secondcol]);
          $row++;
          $data=fgetcsv($file, $filesize,$separator);
        }
      }
      fclose($file);
    }
  }
  else
  {
    print("File not found: ".$filename);
  }
  return $contents;
}

// *************************************************************************
// Testing functions
// *************************************************************************

//
// dislays an array[$row][$col] as table data
// ** for testing
//
function displayarray($data)
{
  print('<table border="1">');

  $rows=count($data);
  for($row=0;$row<$rows;$row++)
  {
    print('<tr>');

    $cols=count($data[$row]);
    for($col=0;$col<$cols;$col++)
    {
      print('<td>');
      $item=$data[$row][$col];
      if($item=="")
      {
        $item='&nbsp;';
      }
      print($item);
      print('</td>');
    }
    print('</tr>');
  }
  print('</table>');
}

?>
