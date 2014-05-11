<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/dbmod.php");
include_once($projectroot."admin/functions/sessions.php");
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
function addimage($filename,$subpath,$caption,$source,$sourcelink,$copyright,$permission)
{
	global $db;
  	$now=date(DATETIMEFORMAT, strtotime('now'));
  
  	$result=true;
  	if(imageexists($filename))
  	{
    	$result=false;
  	}
  	else
  	{
    	$values[]=$db->setstring($filename);
    	$values[]=$db->setstring($subpath);
    	$values[]=$db->setstring($caption);
    	$values[]=$db->setstring($source);
    	$values[]=$db->setstring($sourcelink);
    	$values[]=$now;
    	$values[]=getsiduser();
    	$values[]=$db->setstring($copyright);
    	$values[]=$db->setinteger($permission);
    	$result= insertentry(IMAGES_TABLE,$values);
  	}
  	return $result;
}


//
//
//
function addthumbnail($image,$thumbnail)
{
	global $db;
	$values[0]=$db->setstring($image);
	$values[1]=$db->setstring($thumbnail);
	return insertentry(THUMBNAILS_TABLE,$values);
}

//
// delete thumbnail file from file system first!!!
// this function only deletes the database entry.
//
function deletethumbnail($imagefilename)
{
	global $db;
  	return deleteentry(THUMBNAILS_TABLE,"image_filename='".$db->setstring($imagefilename)."'");
}

//
// delete image and thumbnail files from file system first!!!
// this function only deletes the database entries.
//
function deleteimage($filename)
{
	global $db;
	$result = true;
	if(!imageisused($filename))
	{
		$result = $result & deleteentry(IMAGES_TABLE,"image_filename='".$db->setstring($filename)."'");
		$result = $result & deleteentry(THUMBNAILS_TABLE,"image_filename='".$db->setstring($filename)."'");
		$result = $result & deleteentry(IMAGECATS_TABLE,"image_filename='".$db->setstring($filename)."'");
	}
	else $result = false;
	return $result;
}

//
//
//
function savedescription($filename,$caption,$source,$sourcelink,$copyright,$permission)
{
	global $db;
	
	$result = true;
  	$result = $result & updatefield(IMAGES_TABLE,"caption",$db->setstring($caption),"image_filename = '".$db->setstring($filename)."'");
  	$result = $result & updatefield(IMAGES_TABLE,"source",$db->setstring($source),"image_filename = '".$db->setstring($filename)."'");
  	$result = $result & updatefield(IMAGES_TABLE,"sourcelink",$db->setstring($sourcelink),"image_filename = '".$db->setstring($filename)."'");
  	$result = $result & updatefield(IMAGES_TABLE,"copyright",$db->setstring($copyright),"image_filename = '".$db->setstring($filename)."'");
  	$result = $result & updatefield(IMAGES_TABLE,"permission",$db->setinteger($permission),"image_filename = '".$db->setstring($filename)."'");
  	return $result;
}


// *************************** filtering functions ************************** //

//
// $files: Images to be filtered
//
function getmissingimages($order, $ascdesc, $files)
{
	global $projectroot;
	$result=array();

	$keys = array_keys($files);
	while($key = next($keys))
	{
		if(!file_exists($projectroot.getproperty("Image Upload Path").getimagesubpath($files[$key])."/".$files[$key]))
		{
			array_push($result, $files[$key]);
		}
	}
	return $result;
}


//
//
//
function getunknownimages()
{
	global $projectroot;
	$result = getunknownimageshelper("");

	$dir = new DirectoryIterator($projectroot.getproperty("Image Upload Path"));
	foreach ($dir as $fileinfo)
	{
	    if ($fileinfo->isDir() && !$fileinfo->isDot())
	    {
	        $result = array_merge($result, getunknownimageshelper("/".$fileinfo->getFilename()));
	    }
    }
	return $result;
}


//
//
//
function getunknownimageshelper($subpath)
{
	global $projectroot;
	$result=array();

	//using the opendir function
	$dir_handle = @opendir($projectroot.getproperty("Image Upload Path").$subpath)
		or die("Unable to open path");
	
	while($file = readdir($dir_handle))
	{
		if($file!="." && $file!=".." && !strpos(strtolower($file),".php") && !strpos(strtolower($file),".htm"))
		{
			$compareme=getdbelement("image_filename",IMAGES_TABLE, "image_filename", $file);
			if(strlen($compareme) < 1)
			{
				$compareme=getdbelement("thumbnail_filename",THUMBNAILS_TABLE, "thumbnail_filename", $file);
			}
			if(strlen($compareme) < 1)
			{
				array_push($result, array("filename" => $file, "subpath" => $subpath));
			}
		}
	}
	return $result;
}


//
// $files: Images to be filtered
//
function getunusedimages($order,$ascdesc,$files)
{
	global $projectroot;
	$result=array();

	$keys = array_keys($files);
	while($key = next($keys))
	{
		if(!imageisused($files[$key]))
		{
			array_push($result, $files[$key]);
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
	global $db;
	$filename=$db->setstring($filename);
	$caption=$db->setstring($caption);
	$source=trim($db->setstring($source));
	$uploader=$db->setinteger($uploader);
	$copyright=trim($db->setstring($copyright));
	$order=$db->setstring($order);
	$ascdesc=$db->setstring($ascdesc);
	
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
	global $db;
	$result=array();
	$categories=array();
	if($selectedcat>=0)
	{
		$pendingcategories=array(0 => $selectedcat);
		while(count($pendingcategories))
		{
			$selectedcat=array_pop($pendingcategories);
			array_push($categories,$selectedcat);
			$pendingcategories=array_merge($pendingcategories,getcategorychildren($selectedcat, CATEGORY_IMAGE));
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
	if(strlen($caption) > 0)
	{
		$query.=" AND caption LIKE '%".$caption."%'";
	}
	if($sourceblank)
	{
		$query.=" AND source = ''";
	}
	elseif(strlen($source) > 0)
	{
		$query.=" AND source LIKE '%".$source."%'";
	}
	if($copyrightblank)
	{
		$query.=" AND copyright = ''";
	}
	elseif(strlen($copyright) >0)
	{
		$query.=" AND copyright LIKE '%".$copyright."%'";
	}
	if($uploader > 0)
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
		$sql=$db->singlequery($query);
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
			$sql=$db->singlequery($query);
			if(!mysql_fetch_row($sql))
			{
				array_push($result,$temp[$i]);
			}
		}
	}
	return $result;
}

?>
