<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/pagecontent/gallerypagesmod.php");
include_once($projectroot."functions/pagecontent/gallerypages.php");
include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/functions/pagesmod.php");
include_once($projectroot."admin/includes/objects/edit/gallerypage.php");
include_once($projectroot."includes/objects/elements.php");
include_once($projectroot."includes/objects/images.php");
include_once($projectroot."admin/includes/objects/adminmain.php");

checksession();

if(isset($_GET['page'])) $page=$_GET['page'];
else $page=0;

if(isset($_GET['offset'])) $offset=$_GET['offset'];
else $offset=0;

if(isset($_GET['showall']) || isset($_POST['showall'])) $showall=true;
else $showall=false;

$imagesperpage=6;

//print_r($_POST);
//print_r($_GET);

// *************************** actions ************************************** //

// page content actions
if(!$page)
{
	$editpage = noPageSelectedNotice();
	$message = "Please select a page first";
	$error = true;
}
else
{
	$message = getpagelock($page);
	$error = false;
	if(!$message)
	{
		// update gallery
		if(isset($_POST['addgalleryimage']))
		{
			$filename = trim($_POST['imagefilename']);
			if(imageexists($filename))
			{
				addgalleryimage($page, $filename);
				updateeditdata($page);
				$message = 'Added image';
				if(!getthumbnail($filename))
				{
					$message .= ', but please create a thumbnail for this image!';
					$error = true;
				}
			}
			else
			{
				$message = 'Image <i>'.$filename.'</i> does not exist.';
				$error = true;
			}
			$noofimages = countgalleryimages($page);
			$offset = (ceil($noofimages / $imagesperpage) - 1) * $imagesperpage;

		}
		elseif(isset($_POST['removegalleryimage']))
		{
			$message = 'Removed image <i>'.getgalleryimage($_POST['galleryitemid']).'</i>';
			if(isset($_POST['removeconfirm']))
			{
				removegalleryimage($_POST['galleryitemid'], $page);
				updateeditdata($page);
				$noofimages = countgalleryimages($page);
				if($offset >= $noofimages)
				{
					$offset = (ceil($noofimages / $imagesperpage) - 1) * $imagesperpage;
				}
			}
			else
			{
				$message = 'In order to remove an image, you have to check "Confirm remove".';
				$error = true;
			}
		}
		elseif(isset($_POST['moveimageup']))
		{
			$message = 'Moved image <i>'.$_POST['imagefilename'].'</i> up';
			movegalleryimage($_POST['galleryitemid'], "up", $_POST['positions']);
			$offset = (ceil(getgalleryimageposition($_POST['galleryitemid']) / $imagesperpage) - 1) * $imagesperpage;
			updateeditdata($page);
		}
		elseif(isset($_POST['moveimagedown']))
		{
			$message = 'Moved image <i>'.$_POST['imagefilename'].'</i> down';
			movegalleryimage($_POST['galleryitemid'], "down", $_POST['positions']);
			$offset = (ceil(getgalleryimageposition($_POST['galleryitemid']) / $imagesperpage) - 1) * $imagesperpage;
			updateeditdata($page);
		}
		elseif(isset($_POST['reindex']))
		{
			reindexgallerypositions($page);
			$message = 'Reindexed Gallery';
			updateeditdata($page);
		}
		$editpage = new EditGallery($page, $offset, $imagesperpage, $showall);
	}
	else
	{
		$editpage = new pageBeingEditedNotice($message);
	}
}
$content = new AdminMain($page, "editcontents", new AdminMessage($message, $error), $editpage);
print($content->toHTML());
$db->closedb();
?>
