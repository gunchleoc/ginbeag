<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."functions/pages.php");
include_once($projectroot."includes/templates/template.php");
include_once($projectroot."includes/includes.php");
include_once($projectroot."includes/templates/forms.php");


//
//
//
class ImageModeSelection extends Template {

  function ImageModeSelection($mode="Simple Mode", $number=5)
  {
    global $sid;
    
    if($mode=="Simple Mode")
      $this->stringvars['mode']="Advanced Mode";
    else
      $this->stringvars['mode']="Simple Mode";

    $this->stringvars['number']=$number;
    
    $this->stringvars['hiddenvarsnumber']=$this->makehiddenvars("get",array("number"=>"number","offset"=>"offset"));

    $this->stringvars['hiddenvarsmode']=$this->makehiddenvars("get",array("mode"=>"mode"));

    $this->createTemplates();

  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/imagemodeselection.tpl");
  }
}


//
//
//
class AddImageForm extends Template {

  function AddImageForm($filename="",$caption="",$source="",$sourcelink="",$copyright="",$permission="")
  {
    global $sid, $mode, $number;

	// parameters from last image
    $this->stringvars['caption']=input2html($caption);
    $this->stringvars['source']=input2html($source);
    $this->stringvars['sourcelink']=input2html($sourcelink);
    $this->stringvars['copyright']=input2html($copyright);
    $this->stringvars['permission']=$permission;

	// set permissions radio buttons
    $this->stringvars['permission_granted']=PERMISSION_GRANTED;
    if($this->stringvars['permission'] == PERMISSION_GRANTED)
      $this->stringvars['permission_granted_checked']="checked";
    else
      $this->stringvars['permission_granted_checked']="";

    $this->stringvars['no_permission']=NO_PERMISSION;
    if($this->stringvars['permission'] == NO_PERMISSION)
      $this->stringvars['no_permission_checked']="checked";
    else
      $this->stringvars['no_permission_checked']="";

    $this->stringvars['permission_refused']=PERMISSION_REFUSED;
    if($this->stringvars['permission'] == PERMISSION_REFUSED)
      $this->stringvars['permission_refused_checked']="checked";
    else
      $this->stringvars['permission_refused_checked']="";

	// make category selection
    $selectedcats=getcategoriesforimage($filename);
    $this->vars['categoryselection']= new CategorySelectionForm(true,15,$selectedcats);
    
    // action vars
    $this->stringvars['actionvars']=$this->makeactionvars("get",array(),array("action" => "addimage"));
    
    // display storage path
    $this->stringvars['imagelinkpath']=getimagelinkpath("");
    
    $this->createTemplates();
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/addimageform.tpl");
  }
}


//
// if deletethumbnail, then delete thumbnail
// else delete image
//
class DeleteImageConfirmForm extends Template {

  var $deletethumbnail;

  function DeleteImageConfirmForm($filename,$deletethumbnail=false)
  {
    global $sid, $mode, $number;
    
    $this->deletethumbnail=$deletethumbnail;
    
    $image=getimage($filename);
    $this->vars['image']=new AdminImage($filename, $image['uploaddate'],$image['editor_id'],getthumbnail($filename));

    $this->stringvars['filename']=$filename;

    
    // action vars
    if($deletethumbnail)
    {
    	$this->stringvars['actionvars']=$this->makeactionvars("get",array(),array("action" => "executethumbnaildelete"));
    }
    else
    {
    	$this->stringvars['actionvars']=$this->makeactionvars("get",array(),array("action" => "executedelete"));
    }

    $this->createTemplates();
  }

  // assigns templates
  function createTemplates()
  {
    if($this->deletethumbnail)
      $this->addTemplate("admin/deletethumbnailconfirmform.tpl");
    else
      $this->addTemplate("admin/deleteimageconfirmform.tpl");
  }
}


//
//
//
class EditImageForm extends Template {

  function EditImageForm($filename)
  {
    global $sid, $mode, $number;
    
    $actionvars = $this->makeactionvars("get",array(),array("sid"=>$sid, "mode"=>$mode, "number"=>$number));
    $this->stringvars['actionvarsreplace']=$actionvars."&action=replaceimage";
    $this->stringvars['actionvarsdelete']=$actionvars."&action=delete";
    $this->stringvars['actionvarscat']=$actionvars."&action=cat";
    $this->stringvars['actionvarsrename']=$actionvars."&action=rename";
    $this->stringvars['actionvarsaddthumb']=$actionvars."&action=addthumb";
    $this->stringvars['actionvarsreplacethumb']=$actionvars."&action=replacethumb";
    $this->stringvars['actionvarsdeletethumbnail']=$actionvars."&action=deletethumbnail";
    $this->stringvars['actionvarssource']=$actionvars."&action=source";
    $this->stringvars['actionvarssourcelink']=$actionvars."&action=sourcelink";
    $this->stringvars['actionvarscopyright']=$actionvars."&action=copyright";
    $this->stringvars['actionvarspermission']=$actionvars."&action=permission";
    $this->stringvars['hiddenvars'] = '<input type="hidden" name="filename" value="'.$filename.'" />';
    

    $image=getimage($filename);
    $this->stringvars['filename']=$filename;
    $this->stringvars['caption']=input2html($image['caption']);
    $this->stringvars['source']=input2html($image['source']);
    $this->stringvars['sourcelink']=input2html($image['sourcelink']);
    $this->stringvars['copyright']=input2html($image['copyright']);
    $this->stringvars['permission']=$image['permission'];

    $this->stringvars['permission_granted']=PERMISSION_GRANTED;
    if($this->stringvars['permission'] == PERMISSION_GRANTED)
      $this->stringvars['permission_granted_checked']="checked";
    else
      $this->stringvars['permission_granted_checked']="";

    $this->stringvars['no_permission']=NO_PERMISSION;
    if($this->stringvars['permission'] == NO_PERMISSION)
      $this->stringvars['no_permission_checked']="checked";
    else
      $this->stringvars['no_permission_checked']="";

    $this->stringvars['permission_refused']=PERMISSION_REFUSED;
    if($this->stringvars['permission'] == PERMISSION_REFUSED)
      $this->stringvars['permission_refused_checked']="checked";
    else
      $this->stringvars['permission_refused_checked']="";
      
    $thumbnail = getthumbnail($filename);
    $this->vars['image']= new AdminImage($filename, $image['uploaddate'], $image['uploader'], $thumbnail,true);

    if(!$thumbnail)
      $this->stringvars['no_thumbnail']="no thumbnail";
    else
      $this->stringvars['thumbnail']=getthumbnail($filename);

    $this->vars['categoryselection']= new CategorySelectionForm(true);
    $this->stringvars['categorylist']=makecategorylist(getcategoriesforimage($filename));

    if($mode==="Advanced Mode")
    {
      $this->stringvars['advanced_mode']= "Advanced Mode";
      $pages=pagesforimage($filename);
      $newsitems=newsitemsforimage($filename);

      if((count($pages)>0) || (count($newsitems)>0))
      {
        if(count($pages)>0)
        {
          $this->stringvars['pagelinks']="";
          for($i=0;$i<count($pages);$i++)
          {
            $this->stringvars['pagelinks'].='<a href="admin.php'.$actionvars.'&page='.$pages[$i].'" target="_blank" class="gensmall">#'.$pages[$i].'</a>. ';
          }
        }
        if(count($newsitems)>0)
        {
          $this->stringvars['newsitemlinks']="";
          for($i=0;$i<count($newsitems);$i++)
          {
            $newspage=getpagefornewsitem($newsitems[$i]);
            $offset=getnewsitemoffset($newspage,1,$newsitems[$i],true);
            $this->stringvars['newsitemlinks'].='<a href="admin.php?'.$actionvars.'&page='.$newspage.'&offset='.$offset.'&action=news" target="_blank" class="gensmall">#'.$newsitems[$i].' on page #'.$newspage.'</a>. ';
          }
        }
      }
      else
      {
        $this->stringvars['not_used']= "not used";
      }
    }
    $this->createTemplates();
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/editimageform.tpl");
  }
}


//
//
//
class UnknownImageForm extends Template {

  function UnknownImageForm($filename)
  {
    global $sid, $mode, $number;
    
    $actionvars = $this->makeactionvars("get",array(),array("sid"=>$sid, "mode"=>$mode, "number"=>$number, "filefilter"=>"filefilter"));
    $this->stringvars['actionvarsdeletefile']=$actionvars."&unknown=Unknown+Image+Files&action=deletefile";
    $this->stringvars['actionvarsaddunknownfile']=$actionvars."&unknown=Unknown+Image+Files&action=addunknownfile";
    $this->stringvars['hiddenvars'] = '<input type="hidden" name="filename" value="'.$filename.'" />';

    $this->stringvars['filename']=$filename;
    $this->vars['image']=new AdminImage($filename,0,0,"",false);

    $this->stringvars['permission_granted']=PERMISSION_GRANTED;
    $this->stringvars['no_permission']=NO_PERMISSION;
    $this->stringvars['permission_refused']=PERMISSION_REFUSED;

    $this->vars['categoryselection']= new CategorySelectionForm(true);

    $this->createTemplates();

  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/unknownimageform.tpl");
  }

}


//
// displays a list of editimageforms, depending on display options and page number
//
class ImageList extends Template {

	function ImageList($offset)
  	{
    	global $sid, $_GET, $projectroot, $mode, $number, $filter;

    	$imagedir=getproperty("Image Upload Path");
    	$allfilenames=array();
    	$filenames=array();
    	$message="";
    	$tempfilenames=array();
    	
    	// get filtered images
    	if(isset($_GET['unknown']))
   		{
   			$allfilenames=getunknownimages($projectroot.getproperty("Image Upload Path"));
       		if(count($filenames))
   			{
   				$message='Found '.$noofimages.' unknown file(s) in filesystem';
   			}
   		   	else
  			{
       			$message='No unknown image files found';
   			}
   		}
    	elseif($filter)
    	{
    		$tempfilenames=$this->getfilteredimagesfromgetvars();
    	}
   		if(isset($_GET['unused']))
   		{
       		$allfilenames=getunusedimages($_GET['order'],$_GET['ascdesc'],$tempfilenames);

       		if(count($filenames))
   			{
   				$message='Found '.$noofimages.' unused image(s)';
   			}
   		   	else
  			{
       			$message='No unused images';
   			}
   		}
  		elseif(isset($_GET['missing']))
   		{
   			$allfilenames=getmissingimages($_GET['order'],$_GET['ascdesc'],$tempfilenames);

       		if(count($filenames))
   			{
   				$message='Found '.$noofimages.' missing image file(s)';
   			}
   		   	else
  			{
       			$message='No missing image files';
   			}
   		}
   		elseif(isset($_GET['nothumb']))
   		{
    			$allfilenames=getimageswithoutthumbnails($_GET['order'],$_GET['ascdesc'],$tempfilenames);
        		if(count($filenames))
    			{
      				$message='Found '.$noofimages.' image(s) without a thumbnail';
      			}
      		   	else
    			{
        			$message='No images without thumbnails';
      			}
   		}
   		elseif(isset($_GET['missingthumb']))
   		{
			$allfilenames=getmissingthumbnails($_GET['order'],$_GET['ascdesc'],$tempfilenames);
       		if(count($filenames))
   			{
   				$message='Found '.$noofimages.' missing thumbnail file(s)';
   			}
   		   	else
   			{
       			$message='No missing thumbnail files';
   			}
   		}
   		else
   		{
       		if(count($filenames))
   			{
   				$message='Found '.$noofimages.' images';
   			}
   		   	else
  			{
       			$message='No images found';
   			}
   		}
   		if(isset($_GET['unused']) ||
       		isset($_GET['missing']) ||
       		isset($_GET['unknown']) ||
       		isset($_GET['nothumb']) ||
       		isset($_GET['missingthumb']) ||
       		$filter)
    	{
			for($i=$offset;$i<($offset+$number)&&$i<count($allfilenames);$i++)
   			{
      			$filenames[$i-$offset]=$allfilenames[$i];
   			}
   			$noofimages=count($allfilenames);
		}
    	// unfiltered images
    	else
    	{
      		$filenames=getsomefilenames($offset, $number);
      		$noofimages=countimages();
    	}

		$nooffilenames = count($filenames);
		
   		// special edit form for unknown images
   		if(isset($_GET['unknown']))
   		{
   			for($i=0;$i<$nooffilenames;$i++)
   			{
       			$this->listvars['imageform'][] = new UnknownImageForm($filenames[$i]);
   			}
   		}
   		// edit form for images
   		else
   		{
   			for($i=0;$i<$nooffilenames;$i++)
   			{
      			$this->listvars['imageform'][] = new EditImageForm($filenames[$i]);;
   			}
   		}

    	$this->vars['filterform'] = new ImageFilterForm($offset,$imagesperpage,$noofimages,$message);
    	$this->vars['pagemenu'] = getpagemenu($offset,$number,$noofimages);

    	$this->createTemplates();
  }
  
  
  
	//
	//
	//
	function getfilteredimagesfromgetvars()
	{
  		global $_GET, $order, $ascdesc;
  
  		if(isset($_GET["filename"])) $filename= $_GET["filename"];
  		else $filename="";

  		if(isset($_GET["caption"])) $caption= $_GET["caption"];
  		else $caption="";

  		if(isset($_GET["source"])) $source= $_GET["source"];
  		else $source="";

  		if(isset($_GET["selectedcat"])) $selectedcat= $_GET["selectedcat"];
  		else $selectedcat=array();

  		if(isset($_GET["copyright"])) $copyright= $_GET["copyright"];
  		else $copyright="";

  		if(isset($_GET["uploader"])) $uploader= $_GET["uploader"];
  		else $uploader=-1;

  		return getfilteredimages($filename,$caption,
        	            	    $source,isset($_GET['sourceblank']),$uploader,
            	            	$copyright,isset($_GET['copyrightblank']),
                	        	$selectedcat,isset($_GET['categoriesblank']),
                    	    	$order,$ascdesc);
		}
  

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/imagelist.tpl");
  }

}


//
//
//
class AdminImage extends Template {

  function AdminImage($filename, $uploaddate, $uploader, $thumbnail="",$showcaption=true)
  {
    global $projectroot;

    $this->stringvars['imagefile']=$filename;
    
    if($showcaption)
    {
      $this->vars['caption']= new ImageCaption($filename, $factor,strlen($thumbnail)>0);
    }
  
    $imagedir=$projectroot.getproperty("Image Upload Path");
    $filename=$imagedir.'/'.$filename;

    if(file_exists($filename))
    {
      $this->stringvars['image']="image";
      $this->stringvars['imagepath']=getimagelinkpath($filename);
    
      $imageproperties = $this->imageproperties($filename, $uploaddate, $uploader);
      if(strlen($imageproperties)>0)
        $this->stringvars['imageproperties']=$imageproperties;
      
      $dimensions=calculateimagedimensions($filename,2);
      $this->stringvars['width']=$dimensions["width"];
      $this->stringvars['height']=$dimensions["height"];
      $this->stringvars['resized']=$dimensions["resized"];
    }
    else
    {
      $this->stringvars['no_image']="no image";
    }
    
    if($thumbnail)
    {
      $this->stringvars['thumbnail']="thumbnail";
      $this->stringvars['thumbnailpath']=getimagelinkpath($thumbnail);
      $thumbnail=$imagedir.'/'.$thumbnail;
      if(file_exists($thumbnail))
      {
        $thumbnailproperties = $this->imageproperties($thumbnail);
        if(strlen($thumbnailproperties)>0)
          $this->stringvars['thumbnailproperties']=$thumbnailproperties;
      }
    }
    else
    {
      $this->stringvars['no_thumbnail']="no thumbnail";
    }

    $this->createTemplates();

  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/adminimage.tpl");
  }
  
  //
  // gets file dimensions and upload info as a string
  //
  function imageproperties($filename, $uploaddate="", $uploader="")
  {
    $result="";
    if(file_exists($filename))
    {
      $imageproperties=@getimagesize($filename);
      $width=$imageproperties[0];
      $height=$imageproperties[1];
      $result.=basename($filename);
      $result.='&nbsp;- '.$width.'&nbsp;x&nbsp;'.$height.'&nbsp;pixel';
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
}


//
//
//
class ImageFilterForm extends Template {

  function ImageFilterForm($offset,$imagesperpage,$noofimages,$message="")
  {
    global $sid, $_GET, $mode, $order, $ascdesc, $number;

    $this->stringvars['message']=$message;

    if(isset($_GET["filename"])) $this->stringvars['filename']= $_GET["filename"];
    else $this->stringvars['filename']="";

    if(isset($_GET["caption"])) $this->stringvars['caption']= $_GET["caption"];
    else $this->stringvars['caption']="";

    if(isset($_GET["source"])) $this->stringvars['source']= $_GET["source"];
    else $this->stringvars['source']="";

    if(isset($_GET["copyright"])) $this->stringvars['copyright']= $_GET["copyright"];
    else $this->stringvars['copyright']="";

    if(isset($_GET["uploader"])) $this->stringvars['uploader']= $_GET["uploader"];
    else $this->stringvars['uploader']=-1;
    
    // make hidden fields
    // mode and number always in
    // includefilters for orderselectionhiddenfields only
    // includeorder for hiddenfields only
    $excludes = array("order" => "order","ascdesc" => "ascdesc","mode" => "mode","number" => "number","selectedcat" => "selectedcat");
    $newparams = array("mode" => $mode,"number" => $imagesperpage);
    
	// reset previous searches
    $excludes["source"]="source";
    $excludes["caption"]="caption";
    $excludes["filename"]="filename";
    $excludes["sourceblank"]="sourceblank";
    $excludes["uploader"]="uploader";
    $excludes["copyright"]="copyright";
    $excludes["copyrightblank"]="copyrightblank";
    $excludes["offset"]="offset";



    $this->stringvars['orderselectionhiddenfields']=$this->makehiddenvars("get",$excludes, $newparams);

    // for hiddenfields
    $newparams["order"]=$order;
    $newparams["ascdesc"]=$ascdesc;
    
    $excludes["missing"]="missing";
    $excludes["unused"]="unused";
    $excludes["nothumb"]="nothumb";
    $excludes["missingthumb"]="missingthumb";
    
    $this->stringvars['hiddenfields']=$this->makehiddenvars("get",$excludes, $newparams);
    

    $this->vars['categoryselection']= new CategorySelectionForm(true);
    //$selectedcats=getcategoriesforimage($filename);
    //$this->vars['categoryselection']= new CategorySelectionForm(true,15,$selectedcats);
    
    if(isset($_GET["categoriesblank"]))
      $this->stringvars['categoriesblank']="checked";
    else
      $this->stringvars['categoriesblank']="";

    if(isset($_GET["sourceblank"]))
      $this->stringvars['sourceblank']="checked";
    else
      $this->stringvars['sourceblank']="";
      

    if(isset($_GET["copyrightblank"]))
      $this->stringvars['copyrightblank']="checked";
    else
      $this->stringvars['copyrightblank']="";
      
    $this->vars['usersselectionform']= new ImageUsersSelectionForm($uploader);


    $this->vars['imageorderselection']= new ImageOrderSelectionForm($order);
    $this->vars['ascdescselection']= new AscDescSelectionForm($ascdesc=="asc");
    $this->vars['pagemenu']= getpagemenu($offset, $number, $noofimages);

    $this->createTemplates();
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/imagefilterform.tpl");
  }
  
  
  
}

//
// for filterform
//
class ImageOrderSelectionForm  extends Template {
    var $stringvars=array("optionform_name" => "",
                          "optionform_attributes" => "",
                          "optionform_size" => "1");
    var $listvars=array("option" => array());

    function ImageOrderSelectionForm($order="") {
        $this->stringvars['optionform_name'] = "order";

        $this->listvars['option'][]= new OptionFormOption("filename",$order==="filename","Filename");
        $this->listvars['option'][]= new OptionFormOption("caption",$order==="caption","Caption");
        $this->listvars['option'][]= new OptionFormOption("source",$order==="source","Source");
        $this->listvars['option'][]= new OptionFormOption("uploader",$order==="uploader","Uploader");
        $this->listvars['option'][]= new OptionFormOption("uploaddate",$order==="uploaddate","Upload Date");
        $this->createTemplates();
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
    var $stringvars=array("optionform_name" => "",
                          "optionform_attributes" => "",
                          "optionform_size" => "1");
    var $listvars=array("option" => array());

    function ImageUsersSelectionForm($selecteduser=0) {
      $this->stringvars['optionform_name'] = "uploader";
        
      $users=getallusers();

      $this->listvars['option'][]= new OptionFormOption(0,$selecteduser==0,"Anybody");
      for($i=0;$i<count($users);$i++)
      {
        $this->listvars['option'][]= new OptionFormOption($users[$i],$selecteduser==$users[$i],getusername($users[$i]));
      }
      $this->createTemplates();
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

    function AdminImagePage($messagetitle,$message,$filename,$addimageform,$form,$displayeditform=false) {
      global $sid,$mode,$number;

      $this->stringvars['sid'] = $sid;
      
      $this->vars['header'] = new HTMLHeader("Editing imagelist - ".$mode,"Webpage Building");
      $this->vars['imagemodeselection'] = new ImageModeSelection($mode, $number);

      if(strlen($messagetitle)>0)
      {
        $this->stringvars['messagetitle'] = $messagetitle;
      }

      if(strlen($message)>0)
      {
        $this->stringvars['message'] = $message;
        if(strlen($filename)>0)
        {
          $this->vars['editimageform'] = new EditImageForm($filename);
        }
      }
      if($addimageform)
        $this->vars['addimageform']=$addimageform;
        
      if($displayeditform)
        $this->vars['editimageform']=new EditImageForm($filename);
      
      $this->vars['form']=$form;
      
      $this->vars['footer'] = new HTMLFooter();

      $this->createTemplates();
    }

    // assigns templates
    function createTemplates()
    {
      $this->addTemplate("admin/adminimagepage.tpl");
    }
}


//
// returns a pagemenu with filter parameters
// no parameters for missing files etc. included
//
function getpagemenu($offset,$imagesperpage,$noofimages)
{
	global $_GET,$mode, $order, $ascdesc, $filter;
	
  	$params="number=".$imagesperpage."&mode=".urlencode($mode);
  	
  	if(isset($_GET['unused']) ||
       isset($_GET['missing']) ||
       isset($_GET['unknown']) ||
       isset($_GET['nothumb']) ||
       isset($_GET['missingthumb']) ||
       $filter)
  	{

    	if($filter)
    	{
      		$params.="&filter=1";
    	}
    	elseif(isset($_GET['unused'])) $params.="&unused=1";
    	elseif(isset($_GET['missing'])) $params.="&missing=1";
    	elseif(isset($_GET['unknown'])) $params.="&unknown=1";
    	elseif(isset($_GET['nothumb'])) $params.="&nothumb=1";
    	elseif(isset($_GET['missingthumb'])) $params.="&missingthumb=1";

    	if(isset($_GET["filename"])) $params.="&filename=".urlencode($_GET["filename"]);
    	if(isset($_GET["caption"])) $params.="&caption=".urlencode($_GET["caption"]);
    	if(isset($_GET["source"])) $params.="&source=".urlencode($_GET["source"]);
    	if(isset($_GET["sourceblank"])) $params.="&sourceblank=1";
    	$params.="&uploader=".urlencode($_GET["uploader"]);
    	$params.="&copyright=".urlencode($_GET["copyright"]);
    	if(isset($_GET["copyrightblank"])) $params.="&copyrightblank=1";
    	if(isset($_GET["selectedcat"])) $selectedcats=$_GET["selectedcat"];
    	else $selectedcats=array();

    	for($i=0;$i<count($selectedcats);$i++)
    	{
      		$params.="&selectedcat%5B%5D=".$selectedcats[$i];
    	}
    	if(isset($_GET["categoriesblank"])) $params.="&categoriesblank=1";
    	$params.="&order=".$order."&ascdesc=".$ascdesc;
  	}
  	return new PageMenu($offset, $imagesperpage, $noofimages, $params);
}




?>
