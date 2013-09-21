<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/dbmod.php");
include_once($projectroot."functions/users.php");
include_once($projectroot."functions/images.php");

################################################################################
##                                                                            ##
##        Functions                                                           ##
##                                                                            ##
################################################################################

//
// returns false if image already exists
//
function addimage($filename,$caption,$source,$sourcelink,$copyright,$permission, $sid)
{
  $now=date(DATETIMEFORMAT, strtotime('now'));
  
  $filename=setstring($filename);
  $caption=setstring($caption);
  $source=setstring($source);
  $sourcelink=stripsid($sourcelink);
  $copyright=setstring($copyright);
  $permission=setinteger($permission);
  $sid=setstring($sid);

  $result=true;
  if(imageexists($filename))
  {
    $result=false;
  }
  else
  {
    $values[]=$filename;
    $values[]=$caption;
    $values[]=$source;
    $values[]=$sourcelink;
    $values[]=$now;
    $values[]=getsiduser($sid);
    $values[]=$copyright;
    $values[]=$permission;
    $result= insertentry(IMAGES_TABLE,$values);
  }
  return $result;
}


//
//
//
function addthumbnail($image,$thumbnail)
{
  $values[0]=setstring($image);
  $values[1]=setstring($thumbnail);
  $result= insertentry(THUMBNAILS_TABLE,$values);
}

//
// delete thumbnail file from file system first!!!
// this function only deletes the database entry.
//
function deletethumbnail($imagefilename)
{
  deleteentry(THUMBNAILS_TABLE,"image_filename='".setstring($imagefilename)."'");
}

//
// delete image and thumbnail files from file system first!!!
// this function only deletes the database entries.
//
function deleteimage($filename)
{
  if(!imageisused($filename))
  {
    deleteentry(IMAGES_TABLE,"image_filename='".setstring($filename)."'");
    deleteentry(THUMBNAILS_TABLE,"image_filename='".setstring($filename)."'");
    deleteentry(IMAGECATS_TABLE,"image_filename='".setstring($filename)."'");
  }
}

//
//
//
function renamecaption($filename,$caption)
{
  updatefield(IMAGES_TABLE,"caption",setstring($caption),"image_filename = '".setstring($filename)."'");
}

//
//
//
function renamesource($filename,$source)
{
  updatefield(IMAGES_TABLE,"source",setstring($source),"image_filename = '".setstring($filename)."'");
}

//
//
//
function renamesourcelink($filename,$sourcelink)
{
  updatefield(IMAGES_TABLE,"sourcelink",stripsid($sourcelink),"image_filename = '".setstring($filename)."'");
}

//
//
//
function setimagecopyright($filename,$copyright)
{
  updatefield(IMAGES_TABLE,"copyright",setstring($copyright),"image_filename = '".setstring($filename)."'");
}

//
//
//
function setimagepermission($filename,$permission)
{
  updatefield(IMAGES_TABLE,"permission",setinteger($permission),"image_filename = '".setstring($filename)."'");
}

// *************************** filtering functions ************************** //

//
// $filterimages: Images to be filtered. If this is empty, get all images
//
function getmissingimages($order,$ascdesc,$filterimages=array())
{
  global $projectroot;
  $imagedir=$projectroot.getproperty("Image Upload Path");
  if(count($filterimages)>0)
  {
    $allfiles=$filterimages;
  }
  else
  {
    $allfiles=getallfilenames($order,$ascdesc);
  }
  $result=array();

  for($i=0;$i<count($allfiles);$i++)
  {
    $path=$imagedir."/".$allfiles[$i];
    if(!file_exists($path))
    {
      array_push($result,$allfiles[$i]);
    }
  }
  return $result;
}


//
//
//
function getunknownimages($path)
{
  $result=array();
//  echo "Folder: ".$path."<br/>";
  //using the opendir function
  $dir_handle = @opendir($path) or die("Unable to open path");

  while($file = readdir($dir_handle))
  {
    if($file!="." && $file!=".."
      && !strpos(strtolower($file),".php")
      && !strpos(strtolower($file),".htm"))
    {
      $compareme=getdbelement("image_filename",IMAGES_TABLE, "image_filename", $file);
      if(strlen($compareme)<1)
      {
        $compareme=getdbelement("thumbnail_filename",THUMBNAILS_TABLE, "thumbnail_filename", $file);
      }
      if(strlen($compareme)<1)
      {
        array_push($result,$file);
      }
    }
  }
  return $result;
}

//
// $filterimages: Images to be filtered. If this is empty, get all images
//
function getunusedimages($order,$ascdesc,$filterimages=array())
{
  if(count($filterimages)>0)
  {
    $allfiles=$filterimages;
  }
  else
  {
    $allfiles=getallfilenames($order,$ascdesc);
  }
  $result=array();

  for($i=0;$i<count($allfiles);$i++)
  {
    if(!imageisused($allfiles[$i]))
    {
      array_push($result,$allfiles[$i]);
    }
  }
  return $result;
}

//
// $filterimages: Images to be filtered. If this is empty, get all images
//
function getmissingthumbnails($order,$ascdesc,$filterimages=array())
{
  global $projectroot;
  $imagedir=$projectroot.getproperty("Image Upload Path");
  if(count($filterimages)>0)
  {
    $allfiles=$filterimages;
  }
  else
  {
    $allfiles=getallfilenames($order,$ascdesc);
  }
  $result=array();

  for($i=0;$i<count($allfiles);$i++)
  {
    if(hasthumbnail($allfiles[$i]))
    {
      $path=$imagedir."/".getthumbnail($allfiles[$i]);

      if(!file_exists($path))
      {
        array_push($result,$allfiles[$i]);
      }
    }
  }
  return $result;
}

//
// $filterimages: Images to be filtered. If this is empty, get all images
//
function getimageswithoutthumbnails($order,$ascdesc,$filterimages=array())
{
  global $projectroot;
  $imagedir=$projectroot.getproperty("Image Upload Path");
  if(count($filterimages)>0)
  {
    $allfiles=$filterimages;
  }
  else
  {
    $allfiles=getallfilenames($order,$ascdesc);
  }
  $result=array();

  for($i=0;$i<count($allfiles);$i++)
  {
    if(!hasthumbnail($allfiles[$i]))
    {
      array_push($result,$allfiles[$i]);
    }
  }
  return $result;
}

//
//
//
function getfilteredimages($filename,$caption,$source,$sourceblank,$uploader,$copyright,$copyrightblank,$selectedcats,$categoriesblank,$order,$ascdesc)
{
  $filename=setstring($filename);
  $caption=setstring($caption);
  $source=trim(setstring($source));
  $uploader=setinteger($uploader);
  $copyright=trim(setstring($copyright));
  $order=setstring($order);
  $ascdesc=setstring($ascdesc);
  
  $result=array();

  // get all category children
  if(count($selectedcats)>0 && !$categoriesblank)
  {
    $result=getfilteredimageshelper($filename,$caption,$source,$sourceblank,$uploader,$copyright,$copyrightblank,array_pop($selectedcats),$categoriesblank,$order,$ascdesc);
    while(count($selectedcats))
    {
      $filenames= getfilteredimageshelper($filename,$caption,$source,$sourceblank,$uploader,$copyright,$copyrightblank,array_pop($selectedcats),$categoriesblank,$order,$ascdesc);
      $result=array_intersect($result,$filenames);
    }
  }
  else
  {
    $result=getfilteredimageshelper($filename,$caption,$source,$sourceblank,$uploader,$copyright,$copyrightblank,-1,$categoriesblank,$order,$ascdesc);
  }
  return $result;
}

//
//
//
function getfilteredimageshelper($filename,$caption,$source,$sourceblank,$uploader,$copyright,$copyrightblank,$selectedcat,$categoriesblank,$order,$ascdesc)
{
  $result=array();
  $categories=array();
  if($selectedcat>=0)
  {
    $pendingcategories=array(0 => $selectedcat);
    while(count($pendingcategories))
    {
      $selectedcat=array_pop($pendingcategories);
      array_push($categories,$selectedcat);
      $pendingcategories=array_merge($pendingcategories,getcategorychildren($selectedcat));
    }
  }

  $query="SELECT DISTINCTROW images.image_filename FROM ";
  $query.=IMAGES_TABLE." as images";

  if(count($categories)>0)
  {
    $query.=", ".IMAGECATS_TABLE." AS cat";
    $query.=" WHERE cat.image_filename = images.image_filename";
  }
  else
  {
    $query.=" WHERE '1'";
  }

  if($filename)
  {
    $query.=" AND images.image_filename LIKE '%".$filename."%'";
  }
  if($caption)
  {
    $query.=" AND caption LIKE '%".$caption."%'";
  }
  if($sourceblank)
  {
    $query.=" AND source = ''";
  }
  elseif($source)
  {
    $query.=" AND source LIKE '%".$source."%'";
  }
  if($copyrightblank)
  {
    $query.=" AND copyright = ''";
  }
  elseif($copyright)
  {
    $query.=" AND copyright LIKE '%".$copyright."%'";
  }
  if($uploader)
  {
    $query.=" AND editor_id = '".$uploader."'";
  }
  if(count($categories)>0)
  {
    $query.=" AND cat.category IN (";
    for($i=0;$i<count($categories);$i++)
    {
      $query.="'".$categories[$i]."',";
    }
    $query=substr($query,0,strlen($query)-1);
    $query.=")";
  }
  if($order)
  {
    if($order=="uploader") $order="editor_id";
    elseif($order=="filename") $order="image_filename";
    $query.=" ORDER BY ".$order." ".$ascdesc;
  }

//  print('Some debugging info: '.$query.'<p>');

  if($query)
  {
    $sql=singlequery($query);
  }
  if($sql)
  {
    // get column
    while($row=mysql_fetch_row($sql))
    {
      array_push($result,$row[0]);
    }
  }
  if($categoriesblank)
  {
    $temp=$result;
    $result=array();
    for($i=0;$i<count($temp);$i++)
    {
      $query="SELECT DISTINCTROW image_filename FROM ";
      $query.=IMAGECATS_TABLE;
      $query.=" WHERE image_filename = '".$temp[$i]."';";
      $sql=singlequery($query);
      if(!mysql_fetch_row($sql))
      {
        array_push($result,$temp[$i]);
      }
    }
  }
  return $result;
}


?>
