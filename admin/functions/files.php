<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

//
//
//
function uploadfile($_FILES,$subdir,$paramname,$newname="")
{
  global $projectroot;
  $result=false;

  if (isset($_FILES[$paramname]) and ! $_FILES[$paramname]['error']
      and $_FILES[$paramname]['size']) {
    if(strlen($newname)>4)
    {
      $filename=$projectroot.$subdir.'/'.$newname;
    }
    else
    {
      $filename=$projectroot.$subdir.'/'.$_FILES[$paramname]['name'];
    }
    $result=move_uploaded_file($_FILES[$paramname]['tmp_name'], $filename);
    chmod($filename,0644);
/*    printf("Die Datei %s steht jetzt zur Verfgung.<br />\n",
      $_FILES[$paramname]['name']);
    printf("Sie ist %u Bytes gro und vom Typ %s.<br />\n",
      $_FILES[$paramname]['size'], $_FILES[$paramname]['type']);*/
  }
  return $result;
}

//
//
//
function replacefile($_FILES,$subdir,$paramname,$filename)
{
  global $projectroot;
  $result=false;

  if(file_exists($filename))
  {
    $success=deletefile($subdir,$filename);
  }
  else $success=true;
  if($success)
  {
    $success=uploadfile($_FILES,$subdir,$paramname,$filename);
  }
  return $success;
}

//
//
//
function deletefile($subdir,$filename)
{
  global $projectroot;

  //http://www.morrowland.com/apron/tutorials/web/php/writetextfile/index.php
  $filename = $projectroot.$subdir.'/'.basename($filename);
  
  $delete = @unlink($filename);
  if (@file_exists($filename))
  {
  	// todo deprecated eregi_replace() (use preg_replace() with the 'i' modifier instead) 
    $filesys = eregi_replace("/",chr(92),$filename);
    $delete = @system("del $filesys");
    if (@file_exists($filename))
    {
      $delete = @chmod ($filename, 0775);
      $delete = @unlink($filename);
  	  $delete = @system("del $filesys");
    }
  }
  return $delete;
}
 ?>

