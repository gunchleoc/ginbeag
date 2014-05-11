<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprüft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."functions/pages.php");
include_once($projectroot."admin/functions/pagecontent/newspagesmod.php");
include_once($projectroot."admin/includes/objects/messages.php");
include_once($projectroot."functions/pagecontent/newspages.php");
include_once($projectroot."includes/objects/template.php");
include_once($projectroot."includes/includes.php");
include_once($projectroot."includes/objects/forms.php");
include_once($projectroot."includes/objects/elements.php");
include_once($projectroot."includes/objects/images.php");
include_once($projectroot."includes/objects/categories.php");

//
//
//
class AddImageForm extends Template {

	function AddImageForm($filename="",$caption="",$source="",$sourcelink="",$copyright="",$permission="")
	{
		parent::__construct();
		
		// parameters from last image
		$this->stringvars['caption']=utf8_decode(input2html($caption));
		$this->stringvars['source']=utf8_decode(input2html($source));
		$this->stringvars['sourcelink']=utf8_decode(input2html($sourcelink));
		$this->stringvars['copyright']=utf8_decode(input2html($copyright));
		$this->stringvars['permission']=$permission;
		
		// set permissions radio buttons
		$this->vars['permission_granted'] = new RadioButtonForm("","permission",PERMISSION_GRANTED,"Permission granted",$this->stringvars['permission'] == PERMISSION_GRANTED,"right");
		$this->vars['no_permission'] = new RadioButtonForm("","permission",NO_PERMISSION,"No permission",$this->stringvars['permission'] == NO_PERMISSION,"right");
		
		// make category selection
		$selectedcats=getcategoriesforimage($filename);
		$this->vars['categoryselection']= new CategorySelectionForm(true,"",CATEGORY_IMAGE,15,$selectedcats);
		
		// action vars
		$actionvars=array_merge($_GET, $_POST);
		$actionvars["action"]="addimage";
		$this->stringvars['actionvars']=makelinkparameters($actionvars);
		
		// display storage path
		$this->stringvars['imagelinkpath']=getimagelinkpath("",getimagesubpath(basename($filename)));
		
		$this->stringvars['thumbnailsize']=getproperty("Thumbnail Size");
	}
	
	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/imagelist/addimageform.tpl");
	}
}


//
// if deletethumbnail, then delete thumbnail
// else delete image
//
class DeleteImageConfirmForm extends Template {

	function DeleteImageConfirmForm($filename)
	{
		parent::__construct();
		$image=getimage($filename);
		$this->vars['image']=new AdminImage($filename, $image['uploaddate'],$image['editor_id'],getthumbnail($filename));
		
		$this->stringvars['filename']=$filename;
		
		$actionvars=array_merge($_GET, $_POST);
		$actionvars["action"] = "executedelete";
		$this->stringvars['actionvars']=makelinkparameters($actionvars);
		$this->stringvars['hiddenvars'] = $this->makehiddenvars(array("filename" => $filename));
	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/imagelist/deleteimageconfirmform.tpl");
	}
}

//
// if deletethumbnail, then delete thumbnail
// else delete image
//
class DeleteThumbnailConfirmForm extends Template {

	function DeleteThumbnailConfirmForm($filename)
	{
	    parent::__construct();
	    
	    $image=getimage($filename);
	    $this->vars['image']=new AdminImage($filename, $image['uploaddate'],$image['editor_id'],getthumbnail($filename));
	
	    $this->stringvars['filename']=$filename;

		$actionvars=array_merge($_GET, $_POST);
		$actionvars["action"] = "executethumbnaildelete";
		$this->stringvars['actionvars']=makelinkparameters($actionvars);
		$this->stringvars['hiddenvars'] = $this->makehiddenvars(array("filename" => $filename));
	}
	
	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/imagelist/deletethumbnailconfirmform.tpl");
	}
}


//
//
//
class EditImageForm extends Template {

	function EditImageForm($filename)
	{
		parent::__construct(str_replace ( ".", "-", $filename), array(), array(0 => "admin/includes/javascript/editimageform.js"));
		$this->stringvars['javascript']=$this->getScripts();
		$this->stringvars['hiddenvars'] = $this->makehiddenvars(array("filename" => $filename));
		
		$actionvars=array_merge($_GET, $_POST);

		$this->stringvars['actionvarsreplace'] = makelinkparameters(array_merge($actionvars, array("action" => "replaceimage")));
		$this->stringvars['actionvarsdelete'] = makelinkparameters(array_merge($actionvars, array("action" => "delete")));
		$this->stringvars['actionvarscat'] = makelinkparameters(array_merge($actionvars, array("action" => "cat")));
		$this->stringvars['actionvarsaddthumb'] = makelinkparameters(array_merge($actionvars, array("action" => "addthumb")));
		$this->stringvars['actionvarsreplacethumb'] = makelinkparameters(array_merge($actionvars, array("action" => "replacethumb")));
		$this->stringvars['actionvarsdeletethumbnail'] = makelinkparameters(array_merge($actionvars, array("action" => "deletethumbnail")));
		$this->stringvars['actionvarsdescription'] = makelinkparameters(array_merge($actionvars, array("action" => "description")));
		$this->stringvars['actionvarspermission'] = makelinkparameters(array_merge($actionvars, array("action" => "permssion")));
	    $this->stringvars['hiddenvars'] = $this->makehiddenvars(array("filename" => $filename));
		
		
		$image=getimage($filename);
		$this->stringvars['filename']=$filename;
		$this->stringvars['caption']=input2html($image['caption']);
		$this->stringvars['source']=input2html($image['source']);
		$this->stringvars['sourcelink']=input2html($image['sourcelink']);
		$this->stringvars['copyright']=input2html($image['copyright']);
		$this->stringvars['permission']=$image['permission'];
		$this->stringvars['filepath']=getimagelinkpath($filename,getimagesubpath(basename($filename)));
		
		$this->vars['permission_granted'] = new RadioButtonForm($this->stringvars['jsid'],"permission",PERMISSION_GRANTED,"Permission granted",$this->stringvars['permission'] == PERMISSION_GRANTED,"right");
		$this->vars['no_permission'] = new RadioButtonForm($this->stringvars['jsid'],"permission",NO_PERMISSION,"No permission",$this->stringvars['permission'] == NO_PERMISSION,"right");
		
		$thumbnail = getthumbnail($filename);
		$this->vars['image']= new AdminImage($filename, $image['uploaddate'], $image['editor_id'], $thumbnail,true);
		
		if(!$thumbnail)
			$this->stringvars['no_thumbnail']="no thumbnail";
		else
			$this->stringvars['thumbnail']=getthumbnail($filename);
	
		$this->vars['categoryselection']= new CategorySelectionForm(true,$this->stringvars['jsid'],CATEGORY_IMAGE);
	    $this->vars['categorylist']=new Categorylist(getcategoriesforimage($filename), CATEGORY_IMAGE);
	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/imagelist/editimageform.tpl");
	}
}


//
//
//
class EditImageFormUsage extends Template {

	function EditImageFormUsage($filename)
  	{
  		parent::__construct();

	  	$pages=pagesforimage($filename);
	  	$newsitems=newsitemsforimage($filename);
	
		if((count($pages)>0) || (count($newsitems)>0))
		{
			if(count($pages)>0)
			{
				$this->stringvars['pagelinks']="";
				for($i=0;$i<count($pages);$i++)
				{
					$this->stringvars['pagelinks'].='<a href="admin.php'.makelinkparameters(array("page" => $pages[$i])).'" target="_blank" class="smalltext">#'.$pages[$i].'</a>. ';
				}
			}
			if(count($newsitems)>0)
			{
				$this->stringvars['newsitemlinks']="";
				for($i=0;$i<count($newsitems);$i++)
				{
					$newspage=getpagefornewsitem($newsitems[$i]);

					$linkparams = array();
					$linkparams["page"] = $newspage;
					$linkparams["offset"] = getnewsitemoffset($newspage,1,$newsitems[$i],true);
					$linkparams["action"] = "editcontents";
					$this->stringvars['newsitemlinks'].='<a href="edit/newsedit.php'.makelinkparameters($linkparams).'" target="_blank" class="smalltext">#'.$newsitems[$i].' on page #'.$newspage.'</a>. ';
				}
			}
		}
		else
		{
			$this->stringvars['not_used']= "not used";
		}
  	}

  	// assigns templates
  	function createTemplates()
  	{
    	$this->addTemplate("admin/imagelist/editimageformusage.tpl");
  	}
}



//
//
//
class UnknownImageForm extends Template {

	function UnknownImageForm($filename, $subpath)
	{
		global $_GET, $_POST, $projectroot;
		parent::__construct();
		
		$actionvars=array_merge($_GET, $_POST);
		$actionvars["s_unknown"] = 1;

		$this->stringvars['actionvarsdeletefile'] = makelinkparameters(array_merge($actionvars, array("action" => "deleteunknownfile")));
		$this->stringvars['actionvarsaddunknownfile'] = makelinkparameters(array_merge($actionvars, array("action" => "addunknownfile")));
		$this->stringvars['hiddenvars'] = $this->makehiddenvars(array("filename" => $filename, "subpath" => $subpath));
		$this->stringvars['filename'] = $filename;
		$this->stringvars['image'] = getimagelinkpath($filename, $subpath);
		$this->stringvars['imagepath'] = getproperty("Image Upload Path").$subpath.'/'.$filename;
		$this->stringvars['imageproperties'] = imageproperties($projectroot.$this->stringvars['imagepath']);

		$dimensions=calculateimagedimensions($projectroot.getproperty("Image Upload Path").$subpath.'/'.$filename, true);
		$this->stringvars['width']=$dimensions["width"];
		$this->stringvars['height']=$dimensions["height"];

		// set permissions radio buttons
		$this->vars['permission_granted'] = new RadioButtonForm("","permission",PERMISSION_GRANTED,"Permission granted",false,"right");
		$this->vars['no_permission'] = new RadioButtonForm("","permission",NO_PERMISSION,"No permission",true,"right");
		$this->vars['categoryselection']= new CategorySelectionForm(true,"",CATEGORY_IMAGE);
		$this->vars['deletefileconfirmform']= new CheckboxForm("deletefileconfirm","deletefileconfirm","Confirm delete",false, "right");
	}
	
	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/imagelist/unknownimageform.tpl");
	}
}


//
// displays a list of editimageforms, depending on display options and page number
//
class ImageList extends Template {

	function ImageList($offset)
  	{
    	global $_GET, $projectroot, $number, $ascdesc, $order, $filter;

    	parent::__construct();

		$filteredfilenames=array();
    	$filenames=array();
    	$message="";

		if(isset($_GET['s_missing']))
		{
			$filteredfilenames = $this->getfilteredimagesfromgetvars();
			$filteredfilenames = getmissingimages($order, $ascdesc, $filteredfilenames);

			$noofimages = count($filteredfilenames);
			if($noofimages > 1)
				$message = $noofimages.' image files are missing!';
			elseif($noofimages > 0)
				$message = $noofimages.' image file is missing!';
			else
				$message='No missing image files';

			for($i=$offset; $i < ($offset + $number) && $i < count($filteredfilenames); $i++)
			{
				$filenames[$i - $offset] = $filteredfilenames[$i];
			}
		}
		elseif(isset($_GET['s_missingthumb']))
		{
			$filteredfilenames = $this->getfilteredimagesfromgetvars();
			$filteredfilenames = getmissingthumbnails($order, $ascdesc, $filteredfilenames);

			$noofimages = count($filteredfilenames);
			if($noofimages > 1)
				$message = $noofimages.' thumbnail files are missing!';
			elseif($noofimages > 0)
				$message = $noofimages.' thumbnail file is missing!';
			else
				$message='No missing thumbnail files';

			for($i=$offset; $i < ($offset + $number) && $i < count($filteredfilenames); $i++)
			{
				$filenames[$i - $offset] = $filteredfilenames[$i];
			}
		}
		elseif(isset($_GET['s_nothumb']))
		{
			$filteredfilenames = $this->getfilteredimagesfromgetvars();
			$filteredfilenames = getimageswithoutthumbnails($order, $ascdesc, $filteredfilenames);

			$noofimages = count($filteredfilenames);
			if($noofimages > 1)
				$message = $noofimages.' images have no thumbnail!';
			elseif($noofimages > 0)
				$message = $noofimages.' image has no thumbnail!';
			else
				$message='There are no images without thumbhail';

			for($i=$offset; $i < ($offset + $number) && $i < count($filteredfilenames); $i++)
			{
				$filenames[$i - $offset] = $filteredfilenames[$i];
			}
		}
		elseif(isset($_GET['s_unknown']))
		{
			$filteredfilenames=getunknownimages();

			$noofimages = count($filteredfilenames);
			if($noofimages > 1)
				$message = $noofimages.' unknown images found in file system!';
			elseif($noofimages > 0)
				$message = $noofimages.' unknown image found in file system!';
			else
				$message='No unknown images found in file system.';

			for($i = $offset; $i < ($offset + $number) && $i < count($filteredfilenames); $i++)
			{
				$filenames[$i - $offset] = $filteredfilenames[$i]["filename"];
				$subpaths[$i - $offset] = $filteredfilenames[$i]["subpath"];
			}
		}
		elseif(isset($_GET['s_unused']))
		{
			$filteredfilenames = $this->getfilteredimagesfromgetvars();
			$filteredfilenames = getunusedimages($order, $ascdesc, $filteredfilenames);

			$noofimages = count($filteredfilenames);
			if($noofimages > 1)
				$message = $noofimages.' unused images found in database!';
			elseif($noofimages > 0)
				$message = $noofimages.' unused image found in database!';
			else
				$message='No unused images found in database.';

			for($i = $offset; $i < ($offset + $number) && $i < count($filteredfilenames); $i++)
			{
				$filenames[$i - $offset] = $filteredfilenames[$i];
			}
		}
		elseif($filter)
		{
			$filteredfilenames = $this->getfilteredimagesfromgetvars();

			$noofimages = count($filteredfilenames);
			if($noofimages > 1)
				$message='Found '.$noofimages.' images';
			elseif($noofimages > 0)
				$message='Found '.$noofimages.' image';
			else
				$message='No images found';

			for($i=$offset; $i < ($offset + $number) && $i < count($filteredfilenames); $i++)
			{
				$filenames[$i - $offset] = $filteredfilenames[$i];
			}
		}
		else
		{
			$filenames = getsomefilenames($offset, $number, $order, $ascdesc);
			$noofimages=countimages();
		}

		$nooffilenames = count($filenames);
		if(isset($_GET['s_unknown']))
		{
			for($i=0;$i<$nooffilenames;$i++)
			{
				$this->listvars['imageform'][] = new UnknownImageForm($filenames[$i], $subpaths[$i]);
			}
		}
		else
		{
			for($i=0;$i<$nooffilenames;$i++)
			{
				$this->listvars['imageform'][] = new EditImageForm($filenames[$i]);;
			}
		}
   		
   		// get images per page
   		// todo make default value configurable?
   		if(isset($_GET['imagesperpage'])) $imagesperpage = $_GET['imagesperpage'];
   		else $imagesperpage = 5;

    	$this->vars['filterform'] = new ImageFilterForm($offset,$imagesperpage,$noofimages,$message);
    	$this->vars['pagemenu'] = getpagemenu($offset,$number,$noofimages);
	}
  
  
  
	//
	//
	//
	function getfilteredimagesfromgetvars()
	{
  		global $_GET, $order, $ascdesc;

		if(isset($_GET["s_filename"])) $filename= $_GET["s_filename"];
		else $filename="";

		if(isset($_GET["s_caption"])) $caption= $_GET["s_caption"];
		else $caption="";

		if(isset($_GET["s_source"])) $source= $_GET["s_source"];
		else $source="";

		if(isset($_GET["s_selectedcat"])) $selectedcat= $_GET["s_selectedcat"];
		else $selectedcat=array();

		if(isset($_GET["s_copyright"])) $copyright= $_GET["s_copyright"];
		else $copyright="";

		if(isset($_GET["s_uploader"])) $uploader= $_GET["s_uploader"];
		else $uploader=-1;

		return getfilteredimages($filename, $caption, $source,isset($_GET['s_sourceblank']), $uploader,
			$copyright, isset($_GET['s_copyrightblank']), $selectedcat, isset($_GET['s_categoriesblank']), $order, $ascdesc);
		}
  

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/imagelist/imagelist.tpl");
	}
}


//
//
//
class AdminImage extends Template {

	function AdminImage($filename, $uploaddate, $uploader, $thumbnail="",$showcaption=true)
	{
		global $projectroot;
		parent::__construct();
		$this->stringvars['imagefile']=$filename;
		
		if($showcaption)
		{
			$this->vars['caption']= new ImageCaption($filename);
		}
		
		$filepath =	$projectroot.getproperty("Image Upload Path").getimagesubpath(basename($filename)).'/'.$filename;
		
		if(file_exists($filepath))
		{
			$this->stringvars['image']="image";
			$this->stringvars['imagepath']=getimagelinkpath($filepath,getimagesubpath(basename($filepath)));
			
			$imageproperties = imageproperties($filepath, $uploaddate, $uploader);
			if(strlen($imageproperties)>0)
				$this->stringvars['imageproperties']=$imageproperties;
			
			$dimensions=calculateimagedimensions($filepath, true);
			$this->stringvars['width']=$dimensions["width"];
			$this->stringvars['height']=$dimensions["height"];
		}
		else
		{
			$this->stringvars['no_image']="no image";
		}
    
		if($thumbnail)
		{
			$this->stringvars['thumbnail']="thumbnail";
			$this->stringvars['thumbnailpath']=getimagelinkpath($thumbnail,getimagesubpath(basename($filename)));
			$thumbnailpath = getthumbnailpath($filepath, $thumbnail);
			$dimensions=getimagedimensions($thumbnailpath);
			
			if(file_exists($thumbnailpath))
			{

				$this->stringvars['width']=$dimensions["width"];
				$this->stringvars['height']=$dimensions["height"];
				$thumbnailproperties = imageproperties($thumbnailpath);
				if(strlen($thumbnailproperties)>0)
					$this->stringvars['thumbnailproperties']=$thumbnailproperties;
			}
		}
		else
		{
			$this->stringvars['no_thumbnail']="no thumbnail";
			if(isset($dimensions["resized"]) && $dimensions["resized"])
				$this->stringvars['resized']=$dimensions["resized"];
		}
	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/imagelist/adminimage.tpl");
	}
  

}


//
//
//
class ImageFilterForm extends Template {

	function ImageFilterForm($offset,$imagesperpage,$noofimages,$message="")
	{
		global $_GET, $order, $ascdesc, $number;
		parent::__construct();
		
		$this->stringvars['message']=$message;
		$this->stringvars['number']=$number;
		
		if(isset($_GET["s_filename"])) $this->stringvars['filename']= $_GET["s_filename"];
		else $this->stringvars['filename']="";
		
		if(isset($_GET["s_caption"])) $this->stringvars['caption']= $_GET["s_caption"];
		else $this->stringvars['caption']="";
		
		if(isset($_GET["s_source"])) $this->stringvars['source']= $_GET["s_source"];
		else $this->stringvars['source']="";
		
		if(isset($_GET["s_copyright"])) $this->stringvars['copyright']= $_GET["s_copyright"];
		else $this->stringvars['copyright']="";
		
		if(isset($_GET["s_uploader"])) $this->stringvars['uploader']= $_GET["s_uploader"];
		else $this->stringvars['uploader']=-1;
		
		// make hidden fields
		if(isset($_GET["s_selectedcat"]) && is_array($_GET["s_selectedcat"])>0) $this->vars['categoryselection']= new CategorySelectionForm(true,"",CATEGORY_IMAGE,15,$_GET["s_selectedcat"], false, "s_selectedcat");
		else $this->vars['categoryselection']= new CategorySelectionForm(true,"",CATEGORY_IMAGE, 15, array(), false, "s_selectedcat");
    
		$this->vars['categoriesblankform'] =  new CheckboxForm("s_categoriesblank", 1, "Search for images without categories", isset($_GET["s_categoriesblank"]), "right");
		$this->vars['sourceblankform'] =  new CheckboxForm("s_sourceblank", 1, "Search for images with blank source", isset($_GET["s_sourceblank"]), "right");
		$this->vars['copyrightblankform'] =  new CheckboxForm("s_copyrightblank", 1, "Search for images with blank copyright", isset($_GET["s_copyrightblank"]), "right");
      
		$this->vars['usersselectionform']= new ImageUsersSelectionForm($this->stringvars['uploader']);
		$this->vars['imageorderselection']= new ImageOrderSelectionForm($order);
		$this->vars['ascdescselection']= new AscDescSelectionForm($ascdesc=="asc");
		$this->vars['pagemenu']= getpagemenu($offset, $number, $noofimages);
    
		// so $_GET can be restored after building hidden vars
		$tempget = $_GET;

		// reset previous searches
		unset($_GET['s_selectedcat']);
		unset($_GET['s_source']);
		unset($_GET['s_caption']);
		unset($_GET['s_filename']);
		unset($_GET['s_sourceblank']);
		unset($_GET['s_uploader']);
		unset($_GET['s_copyright']);
		unset($_GET['s_copyrightblank']);
		unset($_GET['s_categoriesblank']);
		unset($_GET['offset']);
		
		$hiddenvars=$_GET;
		unset($hiddenvars["number"]);
		unset($hiddenvars["order"]);
		unset($hiddenvars["ascdesc"]);
		$this->stringvars['orderselectionhiddenfields']=$this->makehiddenvars($hiddenvars);

		$hiddenvars["number"]=$number;
		$hiddenvars["order"]=$order;
		$hiddenvars["ascdesc"]=$ascdesc;
		$this->stringvars['hiddenfields']=$this->makehiddenvars($hiddenvars);
    
		// restore $_GET
		$_GET = $tempget;

	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/imagelist/imagefilterform.tpl");
	}
}

//
// for filterform
//
class ImageOrderSelectionForm  extends Template {

    function ImageOrderSelectionForm($order="")
    {
    	parent::__construct();
    	
        $this->stringvars['optionform_name'] = "order";
        $this->stringvars['optionform_id'] ="order";
        $this->stringvars['optionform_label'] ="Order by: ";
        $this->stringvars['jsid'] ="";

        $this->listvars['option'][]= new OptionFormOption("filename",$order==="filename","Filename");
        $this->listvars['option'][]= new OptionFormOption("caption",$order==="caption","Caption");
        $this->listvars['option'][]= new OptionFormOption("source",$order==="source","Source");
        $this->listvars['option'][]= new OptionFormOption("uploader",$order==="uploader","Uploader");
        $this->listvars['option'][]= new OptionFormOption("uploaddate",$order==="uploaddate","Upload Date");
    }

    // assigns templates
    function createTemplates()
    {
		$this->addTemplate("optionform.tpl");
    }
}


//
// for filterform
//
class ImageUsersSelectionForm  extends Template {

    function ImageUsersSelectionForm($selecteduser=0)
    {
    	parent::__construct();
    	
		$this->stringvars['optionform_name'] = "s_uploader";
		$this->stringvars['optionform_id'] ="s_uploader";
		$this->stringvars['optionform_label'] ="Uploader: ";
		$this->listvars['option'][]= new OptionFormOption(0,$selecteduser==0,"Anybody");
		
		$users=getallusers();
		for($i=0;$i<count($users);$i++)
		{
			$this->listvars['option'][]= new OptionFormOption($users[$i],$selecteduser==$users[$i],input2html(getusername($users[$i])));
		}
    }

    // assigns templates
    function createTemplates()
    {
      $this->addTemplate("optionform.tpl");
    }
}


//
// for filterform
//
class AdminImagePage  extends Template {

	function AdminImagePage($filename, $form, $message, $addimageform = NULL, $displayeditform = false)
	{
		global $number;
		
		parent::__construct($filename, array(0 => "includes/javascript/jquery.js"));

    	$this->stringvars['stylesheet']=getCSSPath("main.css");
		$this->stringvars['adminstylesheet']=getCSSPath("admin.css");
		$this->stringvars['headertitle']= title2html(getproperty("Site Name")).' - Webpage building';
		$this->stringvars['scriptlinks']=$this->getjspaths();
		
		$this->stringvars['pageeditinglink']= "admin.php".makelinkparameters(array("page" => $this->stringvars['page']));
    
		$this->vars['message'] = $message;

		if($addimageform != NULL)
			$this->vars['addimageform']=$addimageform;
		
		if($displayeditform)
			$this->vars['editimageform']=new EditImageForm($filename);
		
		$this->vars['form']=$form;
    }

    // assigns templates
    function createTemplates()
    {
		$this->addTemplate("admin/imagelist/adminimagepage.tpl");
    }
}


//
// returns a pagemenu with filter parameters
// no parameters for missing files etc. included
//
function getpagemenu($offset,$imagesperpage,$noofimages)
{
	global $_GET, $order, $ascdesc, $filter;
	
	$params["number"] = $imagesperpage;
	$params["order"] = $order;
	$params["ascdesc"] = $ascdesc;

	if(isset($_GET["s_missing"])) $params["s_missing"] = 1;
	if(isset($_GET["s_missingthumb"])) $params["s_missingthumb"] = 1;
	if(isset($_GET["s_nothumb"])) $params["s_nothumb"] = 1;
	if(isset($_GET["s_unknown"])) $params["s_unknown"] = 1;
	if(isset($_GET["s_unused"])) $params["s_unused"] = 1;
  	
  	if($filter)
  	{

		$params["filter"] = 1;

		if(isset($_GET["filename"]) && strlen($_GET["filename"]) > 0) $params["filename"] = urlencode($_GET["filename"]);
		if(isset($_GET["caption"]) && strlen($_GET["caption"]) > 0) $params["caption"] = urlencode($_GET["caption"]);
		if(isset($_GET["source"]) && strlen($_GET["source"]) > 0) $params["source"] = urlencode($_GET["source"]);
		if(isset($_GET["uploader"]) && strlen($_GET["uploader"]) > 0) $params["uploader"] = urlencode($_GET["uploader"]);
		if(isset($_GET["copyright"]) && strlen($_GET["copyright"]) > 0) $params["copyright"] = urlencode($_GET["copyright"]);
		if(isset($_GET["sourceblank"])) $params["sourceblank"] = 1;
		if(isset($_GET["copyrightblank"])) $params["copyrightblank"] = 1;

		if(isset($_GET["selectedcat"])) $selectedcats=$_GET["selectedcat"];
		else $selectedcats=array();

		for($i=0;$i<count($selectedcats);$i++)
		{
			$params["selectedcat%5B%5D"][] = $selectedcats[$i];
		}
		if(isset($_GET["categoriesblank"])) $params["categoriesblank"] = 1;
  	}
  	return new PageMenu($offset, $imagesperpage, $noofimages, $params);
}


//
// gets file dimensions and upload info as a string
//
function imageproperties($filename, $uploaddate="", $uploader="")
{
	$result="";
	if(file_exists($filename))
	{
		$dimensions = getimagedimensions($filename);
		$result.=basename($filename);
		$result.='&nbsp;- '.$dimensions["width"].'&nbsp;x&nbsp;'.$dimensions["height"].'&nbsp;pixel';
		$result.='&nbsp;- '.filesize($filename).'&nbsp;bytes.';
		if($uploaddate || $uploader)
		{
			$result.='<br />Uploaded&nbsp;'.$uploaddate.' by&nbsp;'.getusername($uploader).'.';
		}
	}
	else
	{
		$result.='<p class="highlight">File <i>'.basename($filename).'</i> not found</p>';
	}
	return $result;
}
?>
