<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

// check legal vars
include($projectroot."admin/includes/legalimagevars.php");

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/functions/imagesmod.php");
include_once($projectroot."admin/functions/categoriesmod.php");
include_once($projectroot."admin/functions/usersmod.php");
include_once($projectroot."admin/functions/files.php");
include_once($projectroot."includes/includes.php");
include_once($projectroot."includes/functions.php");
include_once($projectroot."admin/includes/objects/imagelist.php");

//print("post: ");
//print_r($_POST);
//print("<br />get: ");
//print_r($_GET);

//todo: test!!!

$sid=$_GET['sid'];
checksession($sid);

// clear filter
if(isset($_GET['clear']))
{
	unset($_GET['filter']);
	unset($_GET['filename']);
	unset($_GET['caption']);
	unset($_GET['source']);
	unset($_GET['copyright']);
	unset($_GET['uploader']);
	unset($_GET['selectedcat']);
}
$filter=false;
if(isset($_GET['filter']))
{
	$filter=true;
	unset($_GET['filter']);
}

$offset=0;
if(isset($_GET['offset'])) $offset=$_GET['offset'];

$number=5;
if(isset($_GET['number']) && $_GET['number']>0)
  $number=$_GET['number'];

$page=0;
if(isset($_GET['page'])) $page=$_GET['page'];
  
$action="";
if(isset($_GET['action'])) $action=$_GET['action'];
elseif(isset($_POST['action'])) $action=$_POST['action'];

unset($_GET['action']);
unset($_POST['action']);

$order="filename";
if(isset($_GET['order'])) $order=$_GET['order'];

$ascdesc="asc";
if(isset($_GET['ascdesc'])) $ascdesc=$_GET['ascdesc'];

$filename="";
if(isset($_POST['filename'])) $filename=$_POST['filename'];
elseif(isset($_GET['filename'])) $filename=$_GET['filename'];

$caption="";
if(isset($_POST['caption'])) $caption=fixquotes($_POST['caption']);

$source="";
if(isset($_POST['source'])) $source=fixquotes($_POST['source']);

$sourcelink="";
if(isset($_POST['sourcelink'])) $sourcelink=$_POST['sourcelink'];

$copyright="";
if(isset($_POST['copyright'])) $copyright=fixquotes($_POST['copyright']);

$permission=NO_PERMISSION;
if(isset($_POST['permission'])) $permission=$_POST['permission'];

$selectedcats=array();
if(isset($_POST['selectedcat'])) $selectedcats=$_POST['selectedcat'];
//elseif(isset($GET['selectedcat'])) $selectedcats=$GET['selectedcat'];

$form=false;
$messagetitle="";
$message="";
$displayeditform=false;
$success=false;

if(isset($_POST["addimage"]))
{
	unset($_POST["addimage"]);
	$filename=$_FILES['filename']['name'];
  	$thumbnail=$_FILES['thumbnail']['name'];

  	$messagetitle="Adding image";
  	if(!$filename)
  	{
		$message='Please select an image for upload';
  	}
  	else
  	{
	    $newname=$_POST['newname'];
    
	    // make new path for each month to avoid directory that is too full
	    $date = getdate();
	  	if ($date["mon"]<10) $date["mon"]="0".$date["mon"];
	    $subpath ="/".$date["year"].$date["mon"];
	
	    // create path in file system if necessary and set permissions
	    $imagedir=$projectroot.getproperty("Image Upload Path").$subpath;
	    if(!file_exists($imagedir))
	    {
	    	mkdir($imagedir, 0757);
	    }
	    $copyindexsuccess = @copy($projectroot.getproperty("Image Upload Path")."/index.html", $imagedir."/index.html");
	    $copyindexsuccess = $copyindexsuccess & @copy($projectroot.getproperty("Image Upload Path")."/index.php", $imagedir."/index.php");
	    if(!$copyindexsuccess )
	    {
	    	$messagetitle.=" - SECURITY WARNING";
	    	$message.='<div class="highlight">WARNING: unable to create index files in '.$imagedir.'. Please use FTP to copy these files from <em>'.$projectroot.getproperty("Image Upload Path").'</em> for security reasons!</div>';
	    }
	    if(strlen($newname)>0)
	    {
			$extension=substr($filename,strrpos($filename,"."),strlen($filename));
			$newname.=$extension;
			$filename=$newname;
	    }
	    $filename=cleanupfilename($filename);
	    $filename=str_replace("_thn.",".",$filename);
	
	    if(imageexists($filename))
	    {
			$message.='Image already exists: '.$filename.'';
	    }
	    else
	    {
			$success= uploadfile($_FILES,getproperty("Image Upload Path").$subpath,"filename",$filename);
	    }
	    if($success)
	    {
			addimage($filename,$subpath,$caption,$source,$sourcelink,$copyright,$permission,$sid);
			addimagecategories($filename,$selectedcats);
			$filename=basename($filename);
			
			if($thumbnail)
			{
				$extension=substr($thumbnail,strrpos($thumbnail,"."),strlen($thumbnail));
				$imagename=substr($filename,0,strrpos($filename,"."));
				$newthumbname=$imagename.'_thn'.$extension;
				$thsuccess=uploadfile($_FILES,getproperty("Image Upload Path").$subpath,"thumbnail",$newthumbname);
				$thumbnail=$newthumbname;
				
				if($thsuccess)
				{
					addthumbnail($filename,$thumbnail);
					$thumbnail=basename($thumbnail);
					$message="Thumbnail for <em>".$filename."</em> uploaded successfully.";
				}
				else
				{
					$message.="<br />Failed to upload thumbnail";
				}
			}
	    }
	    if($success)
	    {
			$message.="Added Image";
			$displayeditform=true;
	    }
	    else
	    {
			$message.="<br />Failed to upload image";
	    }
	}
}
elseif($action==="replaceimage")
{
	$newfilename=$_FILES['newfilename']['name'];
	$messagetitle="Replacing image";

	if(!$newfilename)
	{
		$message="Please select an image for upload";
	}
	elseif(!imageexists($filename))
	{
		$message="The image you wish to replace does not exist: ".$filename;
	}
	else
	{
		$success=checkextension($filename,$newfilename);
		if($success)
		{
			$success= replacefile($_FILES,getproperty("Image Upload Path").getimagesubpath($filename),"newfilename",$filename);
		}
		if($success)
		{
			$message="Replaced Image";
			$displayeditform=true;
		}
		else
		{
			$message="failed to upload image";
		}
	}
}
elseif($action==="addthumb")
{
	$thumbnail=$_FILES['thumbnail']['name'];
	$messagetitle="Adding thumbnail";
	if($thumbnail)
	{
		$extension=substr($thumbnail,strrpos($thumbnail,"."),strlen($thumbnail));
		$imagename=substr($filename,0,strrpos($filename,"."));
		$imageextension=substr($filename,strrpos($filename,"."),strlen($filename));
		if($extension === $imageextension)
		{
			$newthumbname=$imagename.'_thn'.$extension;
			$success=uploadfile($_FILES,getproperty("Image Upload Path").getimagesubpath($filename),"thumbnail",$newthumbname);
			$thumbnail=$newthumbname;
		}
		else
		{
			$message.="Wrong file extension <em>".$extension."</em>. The thumbnail file must be of type <em>".$imageextension."</em>. ";
		}
		
		if($success)
		{
			addthumbnail($filename,$newthumbname);
		}
		else
		{
			$message.="Failed to upload thumbnail";
		}
	}
	else
	{
		$message="Please select a file before upload";
	}
	$displayeditform=true;
}
elseif($action==="replacethumb")
{
	$thumbnail=$_FILES['thumbnail']['name'];
	$messagetitle="Replacing thumbnail";
	if(!$thumbnail)
	{
		$message="Please select an image for upload";
	}
	else
	{
		
		$thumbnailfilename=getthumbnail($filename);
		$extension=substr($thumbnail,strrpos($thumbnail,"."),strlen($thumbnail));
		$imageextension=substr($filename,strrpos($filename,"."),strlen($filename));
		if($extension === $imageextension)
		{
			$success= replacefile($_FILES,getproperty("Image Upload Path").getimagesubpath($filename),"thumbnail",$thumbnailfilename);
		}
		else
		{
			$message.="Wrong file extension <em>".$extension."</em>. The thumbnail file must be of type <em>".$imageextension."</em>. ";
		}

		if($success)
		{
			$message="Replaced Thumbnail";
		}
		else
		{
			$message.="Failed to upload thumbnail";
		}
	}
	$displayeditform=true;
}
elseif($action==="addunknownfile")
{
	$filename=$_POST['filename'];
	$messagetitle="Adding existing image";
	
	if(imageexists($filename))
	{
		$message="Image already exists: ".$filename;
	}
	else
	{
		addimage($filename,$caption,$source,$sourcelink,$copyright,$permission,$sid);
		addimagecategories($filename,$selectedcats);
		$message="Added Image";
	}
	$displayeditform=true;
}
elseif($action==="delete")
{
	$form = new DeleteImageConfirmForm($filename);
}
elseif($action==="deletethumbnail")
{
	$form=new DeleteThumbnailConfirmForm($filename);
}
elseif($action==="deletefile")
{
	$messagetitle="Deleting file <i>".$filename."</i>";
	if(isset($_POST['deletefileconfirm']))
	{
		deletefile(getproperty("Image Upload Path").getimagesubpath(basename($filename)),$filename);
		$message="File deleted.";
	}
	else
	{
		$message="File delete not confirmed!";
	}
}
elseif($action==="executedelete")
{
	if(isset($_POST['delete']))
	{
	    $messagetitle="Deleting image <i>".$filename."</i></i>";
	    $pages=pagesforimage($filename);
	    $newsitems=newsitemsforimage($filename);
	    if(!((count($pages)>0) || (count($newsitems)>0)))
	    {
			if(hasthumbnail($filename))
			{
				$thumbnail=getthumbnail($filename);
				$success=deletefile(getproperty("Image Upload Path").getimagesubpath(basename($filename)),$thumbnail);
				if($success)
				{
					deletefile(getproperty("Image Upload Path").getimagesubpath(basename($filename)),$filename);
					if(!file_exists($filename))
					{
						deleteimage($filename);
					}
				}
				else
				{
					$message="Failed to delete file <i>".$filename."</i>";
					$displayeditform=true;
				}
			}
			else
			{
				deletefile(getproperty("Image Upload Path").getimagesubpath(basename($filename)),$filename);
				if(!file_exists($filename))
				{
					deleteimage($filename);
				}
				else
				{
					$message="Failed to delete file <i>".$filename."</i>";
					$displayeditform=true;
				}
			}
	    }
		else
		{
			$message="Could not delete image, because it is still used in the following page(s): ";
			$displayeditform=true;
			for($i=0;$i<count($pages);$i++)
			{
				$message.='<a href="admin.php?sid='.$sid.'&page='.$pages[$i].'" target="_blank">#'.$pages[$i].'</a>';
			}
			$message.='<br />And in the following Newsitem(s): ';
			for($i=0;$i<count($newsitems);$i++)
			{
				$newspage=getpagefornewsitem($newsitems[$i]);
				$offset=getnewsitemoffset($newspage,1,$newsitems[$i],true);
				$message.='<a href="admin.php?sid='.$sid.'&page='.$newspage.'&offset='.$offset.'&action=news" target="_blank">#'.$newsitems[$i].' on page #'.$newspage.'</a>. ';
			}
		}
	}
	else
	{
		$message="Deleting aborted";
		$displayeditform=true;
	}
}
elseif($action==="executethumbnaildelete")
{
	if(isset($_POST['delete']))
  	{
    	$messagetitle="Deleting thumbnail for image <em>".$filename."</em>";
    	if(hasthumbnail($filename))
    	{
      		$thumbnail=getthumbnail($filename);
      		$success=deletefile(getproperty("Image Upload Path"),$thumbnail);
      		if($success)
      		{
        		deletethumbnail($filename);
        		$message="Thumbnail for <em>".$filename."</em> deleted.";
      		}
      		else
      		{
        		$message="Failed to delete file <em>".$thumbnail."</em>";
      		}
    	}
    	else
    	{
      		$message="No thumbnail found!";
    	}
  	}
  	else
  	{
    	$message="Deleting aborted";
  	}
  	unset($_GET['action']);
	unset($_POST['action']);
  	$displayeditform=true;
}

if($form)
{
	$adminimagepage = new AdminImagePage($messagetitle,$message,$filename,false,$form);
}
else
{
    $addimageform = new AddImageForm($filename,$caption,$source,$sourcelink,$copyright,$permission);
	$form = new ImageList($offset);
	$adminimagepage = new AdminImagePage($messagetitle,$message,$filename,$addimageform,$form,$displayeditform);
}

print($adminimagepage->toHTML());
$db->closedb();


//
// when replacing an images, the extension must be the same
//
function checkextension($oldfile,$newfile)
{
	$oldextension=substr($oldfile,strrpos($oldfile,"."),strlen($oldfile));
	$newextension=substr($newfile,strrpos($newfile,"."),strlen($newfile));
	if($oldextension===$newextension)
	{
		return true;
	}
	else
	{
		print('<p class="highlight">Image must be of type <i>'.$oldextension.'</i></p>');
		return false;
	}
}

?>
