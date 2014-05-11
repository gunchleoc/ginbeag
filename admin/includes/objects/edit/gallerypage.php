<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."functions/pagecontent/gallerypages.php");
include_once($projectroot."includes/objects/template.php");
include_once($projectroot."admin/includes/objects/imageeditor.php");
include_once($projectroot."admin/includes/objects/editor.php");


//
//
//
class ShowAllImagesButton extends Template {
	function ShowAllImagesButton($isshowall=true,$noofimages,$imagesperpage)
	{
		parent::__construct();

		$linkparams["page"] = $this->stringvars['page'];
		$linkparams["noofimages"] = $noofimages;
		$linkparams["action"] = "editcontents";
		$this->stringvars['actionvars']= makelinkparameters($linkparams);
		
		if($isshowall)
		{
			$this->stringvars['name']="showall";
			$this->stringvars['value']="Show all images (".$noofimages.")";
		}
		else
		{
			$this->stringvars['name']="dontshowall";
			$this->stringvars['value']="Show ".$imagesperpage." images per page";
		}
	}
	
	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/edit/showallimagesbutton.tpl");
	}
}


//
//
//
class GalleryImageForm extends Template {
	function GalleryImageForm($imageid,$offset,$pageposition,$noofimages,$showall)
	{
		parent::__construct($imageid, array(), array(0 => "admin/includes/javascript/editgallery.js"));
		$this->stringvars['javascript']=$this->getScripts();

		$linkparams["page"] = $this->stringvars['page'];
		$this->stringvars['imagelistpath']=getprojectrootlinkpath()."admin/editimagelist.php".makelinkparameters($linkparams);

		$linkparams["noofimages"] = $noofimages;
		$linkparams["offset"] = $offset;
		$linkparams["pageposition"] = $pageposition;
		$linkparams["action"] = "editcontents";
		$this->stringvars['actionvars']= makelinkparameters($linkparams);
		
		$this->stringvars['imageid']=$imageid;

		$hiddenvars["galleryitemid"] = $imageid;
		if($showall) $hiddenvars["showall"] = "true";
		$this->stringvars['hiddenvars'] = $this->makehiddenvars($hiddenvars);

		$this->stringvars['imagefilename']=getgalleryimage($imageid);
		$this->vars['image'] = new CaptionedImageAdmin($this->stringvars['imagefilename'], $this->stringvars['page']);
		
		if(!getthumbnail($this->stringvars['imagefilename']))
			$this->stringvars['no_thumbnail']="This image has no thumbnail";

		$this->vars['removeconfirmform']= new CheckboxForm("removeconfirm","removeconfirm","Confirm remove",false, "right");
	}
	
	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/edit/galleryimageform.tpl");
	}
}


//
//
//
class AddGalleryImageForm extends Template {
	function AddGalleryImageForm($offset,$pageposition,$noofimages,$showall)
	{
		parent::__construct();

		$linkparams["page"] = $this->stringvars['page'];
		$this->stringvars['imagelistpath']=getprojectrootlinkpath()."admin/editimagelist.php".makelinkparameters($linkparams);

		$linkparams["noofimages"] = $noofimages+1;
		$linkparams["offset"] = $offset;
		$linkparams["pageposition"] = $pageposition+1;
		$linkparams["action"] = "editcontents";
		$this->stringvars['actionvars']= makelinkparameters($linkparams);

		$hiddenvars = array();
		if($showall) $hiddenvars["showall"] = "true";
		$this->stringvars['hiddenvars'] = $this->makehiddenvars($hiddenvars);
	}
	
	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/edit/addgalleryimageform.tpl");
	}
}


//
//
//
class ReindexGalleryForm extends Template {
	function ReindexGalleryForm($showall)
	{
		parent::__construct();
		
		$linkparams["page"] = $this->stringvars['page'];
		$linkparams["action"] = "editcontents";
		$this->stringvars['actionvars']= makelinkparameters($linkparams);
		
		$hiddenvars = array();
		if($showall) $hiddenvars["showall"] = "true";
		$this->stringvars['hiddenvars'] = $this->makehiddenvars($hiddenvars);
	}
	
	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/edit/reindexgalleryform.tpl");
	}
}



//
//
//
class EditGallery extends Template {

	function EditGallery($page,$offset,$imagesperpage,$showall)
	{
		parent::__construct($page,array(0 => "includes/javascript/jcaret.js"));
		$this->stringvars['javascript']=$this->getScripts();
		
		$imageids=getgalleryimages($page);
		
		$noofimages=count($imageids);
		if(!$offset) $offset=0;
			$noofdisplayedimages=$imagesperpage;
		
		if ($showall)
			$noofdisplayedimages=$noofimages;

		$this->vars['showallbutton'] = new ShowAllImagesButton(!$showall,$noofimages,$imagesperpage);
		$this->vars['pagemenu'] = new PageMenu($offset,$noofdisplayedimages,$noofimages);
		
		if($noofimages > 0)
		{
			for($i=$offset;$i<($offset+$noofdisplayedimages)&&$i<$noofimages;$i++)
			{
				$pageposition = getgalleryimageposition($imageids[$i]);
				$this->listvars['imageform'][] = new GalleryImageForm($imageids[$i],$offset,$pageposition,$noofimages,$showall);
			}
		}
		else
		{
			$this->stringvars['imageform']="";
			$message .='There are no images in this gallery';
		}
		
		$this->vars['addform'] = new AddGalleryImageForm($offset,$noofimages,$noofimages,$showall);
		$this->vars['reindexform'] = new ReindexGalleryForm($showall);
		$this->vars['navigationbuttons']= new PageEditNavigationButtons(new GeneralSettingsButton(),new EditPageIntroSettingsButton());
	}
	
	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/edit/editgallery.tpl");
	}
}

?>