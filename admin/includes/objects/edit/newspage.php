<?php
$projectroot=dirname(__FILE__);

// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."functions/pagecontent/newspages.php");
include_once($projectroot."admin/functions/pagecontent/newspagesmod.php");
include_once($projectroot."includes/objects/template.php");
include_once($projectroot."admin/includes/objects/elements.php");
include_once($projectroot."admin/includes/objects/forms.php");
include_once($projectroot."includes/objects/newspage.php");
include_once($projectroot."includes/objects/categories.php");
include_once($projectroot."admin/includes/objects/editor.php");

/// TODO: Offset fr alle Eintrge kontrollieren!!!


//
//
//
class NewsitemImagePropertiesForm extends Template {

  function NewsitemImagePropertiesForm($image,$newsitem,$imageid,$offset=0)
  {
    global $offset;
    parent::__construct();
    $this->stringvars['actionvars']= "?sid=".$this->stringvars['sid']."&page=".$this->stringvars['page']."&imageid=".$imageid."&offset=".$offset."&newsitem=".$newsitem."&action=editcontents";
    $this->stringvars['imagelistpath']=getprojectrootlinkpath()."admin/editimagelist.php?sid=".$this->stringvars['sid']."&page=".$this->stringvars['page'];

    $this->stringvars['imageid']=$imageid;
    $this->stringvars['imagefilename']=$image;

    if(strlen($image)>0 && imageexists($image))
    {
        $this->vars['image'] = new CaptionedImageAdmin($image,$this->stringvars['page'],2);
    }
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/edit/newsitemimagepropertiesform.tpl");
  }
}


//
//
//
class NewsitemSynopsisForm extends Template {

	function NewsitemSynopsisForm($newsitem)
	{
		global $offset;
		parent::__construct();
		
		$this->stringvars['actionvars']="?sid=".$this->stringvars["sid"]."&page=".$this->stringvars["page"]."&offset=".$offset."&newsitem=".$newsitem."&action=editcontents#synopsis";

		$imageids = getnewsitemsynopsisimageids($newsitem);
		if(count($imageids)==0) $this->stringvars["image"]="";
		else
		{
			for($i=0;$i<count($imageids);$i++)
			{
				$this->listvars["image"][] = new NewsitemImagePropertiesForm(getnewsitemsynopsisimage($imageids[$i]),$newsitem,$imageids[$i],$offset);
			}
		}

		$contents=getnewsitemcontents($newsitem);

		//$this->stringvars['offset']=$offset;
		//$this->stringvars['newsitem']=$newsitem;
		
	    $this->vars['synopsis']= new Editor($this->stringvars["page"],$newsitem,"newsitemsynopsis","Synopsis Text");		
	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/edit/newsitemsynopsisform.tpl");
	}
}


//
//
//
class NewsitemSectionForm extends Template {

	function NewsitemSectionForm($newsitem,$newsitemsection, $offset)
	{
		parent::__construct($newsitemsection);
    
    	$this->stringvars['javascript']="&nbsp;".prepareJavaScript($this->stringvars['jsid'], "admin/includes/javascript/messageboxes.js");
    	$this->stringvars['javascript'].=prepareJavaScript($this->stringvars['jsid'], "admin/includes/javascript/editnewsitemsection.js");
    
		$contents=getnewsitemsectioncontents($newsitemsection);

		if($contents['text']==="[quote]") $this->stringvars['quotestart']="quotestart";
		elseif($contents['text']==="[unquote]") $this->stringvars['quoteend']="quoteend";
		else
		{
	    	$this->stringvars['actionvars']="?sid=".$this->stringvars["sid"]."&page=".$this->stringvars["page"]."&offset=".$offset."&newsitem=".$newsitem."&newsitemsection=".$newsitemsection."&action=editcontents";

			$this->stringvars['notquote']="notquote";
			$this->stringvars['newsitemsection']=$newsitemsection;

			if(strlen($contents['sectiontitle'])>0)
			$this->stringvars['sectionheader']=title2html($contents['sectiontitle']);
			else
			$this->stringvars['sectionheader']="Section ID ".$newsitemsection;

			$this->stringvars['sectiontitle']=input2html($contents['sectiontitle']);
			
			$this->vars['sectioneditor'] = new Editor($this->stringvars["page"],$newsitemsection,"newsitemsection","Edit Text");
			$this->vars['imageform'] = new ImagePropertiesForm($this->stringvars["page"],$contents['sectionimage'],$contents['imagealign'],"Section","changeimage",'&newsitemsection='.$newsitemsection);
		}
		$this->vars['insertnewsitemsectionform']=new InsertNewsItemSectionForm($newsitem,$newsitemsection);
	}


	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/edit/newsitemsectionform.tpl");
	}
}


//
//
//
class InsertNewsItemSectionForm extends Template {
	function InsertNewsItemSectionForm($newsitem,$newsitemsection)
	{
		global $offset;
		parent::__construct();
		
		$this->stringvars['actionvars']="?sid=".$this->stringvars["sid"]."&page=".$this->stringvars["page"]."&offset=".$offset."&newsitem=".$newsitem."&newsitemsection=".$newsitemsection."&action=editcontents";

	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/edit/insertnewsitemsectionform.tpl");
	}
}


//
//
//
class DeleteNewsItemConfirm extends Template {
	function DeleteNewsItemConfirm($newsitem)
	{
		global $offset;
		parent::__construct();
		
		$this->stringvars['actionvars']="?sid=".$this->stringvars["sid"]."&page=".$this->stringvars["page"]."&offset=".$offset."&newsitem=".$newsitem."&action=editcontents";

		$this->stringvars['pagetitle']=title2html(getpagetitle($this->stringvars["page"]));

		$this->vars['item'] = new Newsitem($newsitem, $offset,true,true,false);
	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/edit/deletenewsitemconfirm.tpl");
	}
}


//
//
//
class DeleteNewsItemSectionConfirm extends Template {
	function DeleteNewsItemSectionConfirm($newsitem,$newsitemsection)
	{
		global $offset;
		parent::__construct();
		
		$this->stringvars['actionvars']="?sid=".$this->stringvars["sid"]."&page=".$this->stringvars["page"]."&offset=".$offset."&newsitem=".$newsitem."&newsitemsection=".$newsitemsection."&action=editcontents";

		$contents=getnewsitemcontents($newsitem);
		$this->stringvars['newsitemtitle']=title2html($contents['title']);

		$this->vars['section'] = new Newsitemsection($newsitemsection, false,2,true,true);
	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/edit/deletenewsitemsectionconfirm.tpl");
	}
}


//
//
//
class NewsItemAddForm extends Template {

	function NewsItemAddForm()
	{
		parent::__construct();
		$this->stringvars['actionvars']="?sid=".$this->stringvars["sid"]."&page=".$this->stringvars["page"]."&action=editcontents";
	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/edit/newsitemaddform.tpl");
	}
}


//
//
//
class NewsItemArchiveForm extends Template {

	function NewsItemArchiveForm()
	{
		parent::__construct();
		$this->stringvars['actionvars']="?sid=".$this->stringvars["sid"]."&page=".$this->stringvars["page"]."&action=editcontents";
	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/edit/newsitemarchiveform.tpl");
	}
}


//
//
//
class NewsItemDisplayOrderForm extends Template {

	function NewsItemDisplayOrderForm()
	{
		parent::__construct();
		
		$this->stringvars['actionvars']= "?sid=".$this->stringvars['sid']."&page=".$this->stringvars['page']."&action=editcontents";
		
		if(displaynewestnewsitemfirst($this->stringvars['page'])) $this->stringvars['newestfirst'] = 'true';
		elseif(!displaynewestnewsitemfirst($this->stringvars['page'])) $this->stringvars['oldestfirst'] = 'true';
	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/edit/newsitemdisplayorderform.tpl");
	}
}


//
//
//
class NewsItemSearchForm extends Template {
	function NewsItemSearchForm()
	{
		parent::__construct();
		$this->stringvars['actionvars']="?sid=".$this->stringvars["sid"]."&page=".$this->stringvars["page"]."&action=editcontents";
	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/edit/newsitemsearchform.tpl");
	}
}


//
//
//
class NewsItemSearchResults extends Template {
	function NewsItemSearchResults($searchtitle)
	{
		parent::__construct();
		
		$this->stringvars['actionvars']="?sid=".$this->stringvars["sid"]."&page=".$this->stringvars["page"]."&offset=0&action=editcontents";
		
		$this->vars['backbuttons']=new GeneralSettingsButtons();

		$this->vars['searchform']= new NewsItemSearchForm();

		$this->stringvars['searchtitle']=title2html($searchtitle);

		$newsitems=searchnewsitemtitles($searchtitle,$this->stringvars["page"],true);
		$noofnewsitems=count($newsitems);
		if(!$noofnewsitems>0)
		{
			$this->stringvars['searchresult']='No newsitems found!';
		}
		else
		{
			for($i=0;$i<$noofnewsitems;$i++)
			{
				$offset=getnewsitemoffset($this->stringvars["page"],1,$newsitems[$i],true);
				$this->listvars['searchresult'][]=new NewsItemSearchResult($newsitems[$i],$offset);
			}
		}
	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/edit/newsitemsearchresults.tpl");
	}
}

//
//
//
class NewsItemSearchResult extends Template {
	function NewsItemSearchResult($newsitem,$offset)
	{
		parent::__construct();
		
		$this->stringvars['actionvars']="?sid=".$this->stringvars["sid"]."&page=".$this->stringvars["page"]."&offset=".$offset."&action=editcontents";

		$contents=getnewsitemcontents($newsitem);

		$this->stringvars['sectiontitle']=title2html($contents['title']);
		$this->stringvars['synopsis']=text2html($contents['synopsis']);
		
	    $this->stringvars['contributor']= input2html($contents['contributor']);
    	$this->stringvars['source']= input2html($contents['source']);
    	
    	if($contents['sourcelink']!="")
    	{
    		$this->stringvars['sourcelink']= $contents['sourcelink'];
    	}
		$this->stringvars['location']=title2html($contents['location']);
		$this->stringvars['date']=formatdatetime($contents['date']);
		$this->stringvars['editor']=title2html(getusername($contents['editor_id']));
		$this->stringvars['copyright']=makecopyright(getnewsitemcopyright($newsitem));
	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/edit/newsitemsearchresult.tpl");
	}
}


//
//
//
class ArchiveNewsItemsForm extends Template {
	function ArchiveNewsItemsForm()
	{
		parent::__construct();
		
		$this->stringvars['actionvars']= "?sid=".$this->stringvars['sid']."&page=".$this->stringvars['page']."&action=editcontents";

		$oldestdate=getoldestnewsitemdate($this->stringvars['page']);
		$date=getnewestnewsitemdate($this->stringvars['page']);

		$this->stringvars['day']=$oldestdate['mday'];
		$this->stringvars['month']=$oldestdate['month'];
		$this->stringvars['year']=$oldestdate['year'];

		$this->vars['dayform']= new DayOptionForm($oldestdate['mday']);
		$this->vars['monthform']= new MonthOptionForm($oldestdate['mon']);
		$this->vars['yearform']= new YearOptionForm($date['year'],$oldestdate['year'],$date['year']);

		$this->vars['backbuttons']=new EditContentsButtons("Back to editing newsitems");
	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/edit/archivenewsitemsform.tpl");
	}
}

class FakeTheDateForm extends Template {
	function FakeTheDateForm($newsitem, $contents,$offset)
	{
		parent::__construct($newsitem);
		
		$this->stringvars['date']=formatdatetime($contents['date']);

		$date=getnewsitemdate($newsitem);

		$this->vars['dayform']= new DayOptionForm($date['mday'],false,$this->stringvars['jsid']);
		$this->vars['monthform']= new MonthOptionForm($date['mon'],false,$this->stringvars['jsid']);
		$this->stringvars['year']= $date['year'];

		$this->vars['hoursform']= new NumberOptionForm($date['hours'],0,23,false,$this->stringvars['jsid'],"hours","hours");
		$this->vars['minutesform']= new NumberOptionForm($date['minutes'],0,59,false,$this->stringvars['jsid'],"minutes","minutes");
		$this->vars['secondsform']= new NumberOptionForm($date['seconds'],0,59,false,$this->stringvars['jsid'],"seconds","seconds");
	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/edit/fakethedateform.tpl");
	}
}

class NewsItemSourceForm extends Template {
	function NewsItemSourceForm($newsitem, $contents)
	{
		parent::__construct($newsitem);

		$this->stringvars['contributor']=input2html($contents['contributor']);
		$this->stringvars['location']=input2html($contents['location']);
		$this->stringvars['source']=input2html($contents['source']);
		$this->stringvars['sourcelink']=$contents['sourcelink'];
	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/edit/newsitemsourceform.tpl");
	}
}

// todo: code duplication with adminpage???
// page or newsitem
//
class NewsItemPermissionsForm extends Template {

	function NewsItemPermissionsForm($newsitem, $permissions)
	{
		parent::__construct($newsitem);

		$this->stringvars['copyright']=input2html($permissions['copyright']);
		$this->stringvars['image_copyright']=input2html($permissions['image_copyright']);
		$this->vars['permission_granted']= new RadioButtonForm($this->stringvars['jsid']."permission",PERMISSION_GRANTED,"Permission granted",$permissions['permission']==PERMISSION_GRANTED,"right");
		$this->vars['no_permission']= new RadioButtonForm($this->stringvars['jsid']."permission",NO_PERMISSION,"No permission",$permissions['permission']==NO_PERMISSION,"right");
		$this->vars['permission_refused']= new RadioButtonForm($this->stringvars['jsid']."permission",PERMISSION_REFUSED,"Permission refused",$permissions['permission']==PERMISSION_REFUSED,"right");
	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/edit/newsitempermissionsform.tpl");
	}
}

class NewsItemPublishForm extends Template {

	function NewsItemPublishForm($newsitem, $permissions, $offset)
	{
		parent::__construct($newsitem);
		
		$this->stringvars['previewlink']=getprojectrootlinkpath()."admin/includes/preview.php?sid=".$this->stringvars["sid"]."&page=".$this->stringvars["page"]."&offset=".$offset."&newsitem=".$newsitem."&action=editcontents";

		if(!$permissions['permission']== PERMISSION_REFUSED)
		{
			$this->stringvars['notpermissionrefused']="true";
		}
		else
		{
			$this->stringvars['permissionrefused']="true";
		}
		if(isnewsitempublished($newsitem))
		{
			$this->stringvars['ispublished']="true";
		}
		else
		{
			$this->stringvars['isnotpublished']="true";
		}
	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/edit/newsitempublishform.tpl");
	}
}

class NewsItemDeleteForm extends Template {

	function NewsItemDeleteForm($newsitem, $offset)
	{
		parent::__construct();
		
		$this->stringvars['actionvars']="?sid=".$this->stringvars["sid"]."&page=".$this->stringvars["page"]."&offset=".$offset."&newsitem=".$newsitem."&action=editcontents";
	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/edit/newsitemdeleteform.tpl");
	}
}


class EditNewsItemForms extends Template {

	function EditNewsItemForms($page, $offset)
	{
		parent::__construct($page,array(0=>"includes/javascript/jquery.js", 1=>"includes/javascript/jcaret.js"));
		
		$this->stringvars['actionvars']="?sid=".$this->stringvars["sid"]."&page=".$this->stringvars["page"]."&offset=".$offset."&action=editcontents";
  		
		$this->vars['backbuttons']=new GeneralSettingsButtons();

		$this->vars['newsitemaddform'] = new NewsItemAddForm();
		$this->vars['newsitemarchiveform'] = new NewsItemArchiveForm();

		$this->vars['newsitemdisplayorderform'] = new NewsItemDisplayOrderForm();
		
		$noofnewsitems=countnewsitems($page);
		$offset=getoffsetforjumppage($noofnewsitems,1,$offset);
		if(!$offset)
		{
			$offset=0;
		}

		$newsitems=getnewsitems($page,1,$offset);
		

		$this->vars['pagemenu']= new PageMenu($offset,1,$noofnewsitems,'action=editcontents');

		if(hasrssfeed($page))
		{
			$this->stringvars['rssbutton']='<a href="'.getprojectrootlinkpath().'rss.php?page='.$page.'" target="_blank"><img src="'.getprojectrootlinkpath().'img/rss.gif"></a>';
			$this->stringvars['buttontext']='Disable RSS-Feed';
			$this->stringvars['fieldname']='disablerss';
		}
		else
		{
			$this->stringvars['buttontext']='Enable RSS-Feed';
			$this->stringvars['fieldname']='enablerss';
		}
		
		if($noofnewsitems>0)
		{
			$newsitem=$newsitems[0];
			
			$this->stringvars['jsid']=$newsitem;
		
			$this->stringvars['hasnewsitems']="true";
			$this->vars['jumptopageform'] = new JumpToPageForm("",array("page" => $page, "action" => "editcontents"));
			$this->vars['newsitemsearchform'] = new NewsItemSearchForm();

			$contents=getnewsitemcontents($newsitem);
			$permissions=getnewsitemcopyright($newsitem);
			$this->stringvars['newsitem']=$newsitem;

			if($contents['title'])
			{
				$this->stringvars['newsitemtitle']=title2html($contents['title']);
			}
			else
			{
				$this->stringvars['newsitemtitle']="New Newsitem";
			}

			$this->stringvars['authorname']=getusername($contents['editor_id']);
			
			$this->stringvars['title']=input2html($contents['title']);		
			
			$this->vars['newsitemdeleteform']= new NewsItemDeleteForm($newsitem, $offset);
			$this->vars['newsitempublishform']= new NewsItemPublishForm($newsitem, $permissions, $offset);
			$this->vars['newsitempermissionsform']= new NewsItemPermissionsForm($newsitem, $permissions);
			$this->vars['newsitemsynopsisform']= new NewsitemSynopsisForm($newsitem);

			// sections

			$sections=getnewsitemsections($newsitem);
			$noofsections=count($sections);
			for($i=0;$i<$noofsections;$i++)
			{
				$this->listvars['newsitemsectionform'][] = new NewsitemSectionForm($newsitem,$sections[$i], $offset);
			}
			if($noofsections<1)
			{
				$this->stringvars['nosections']="true";
				$this->vars['newsitemsectionform'] = new InsertNewsItemSectionForm($newsitem,0);
			}

			$this->vars['newsitemsourceform'] = new NewsItemSourceForm($newsitem, $contents);
			$this->vars['fakethedateform'] = new FakeTheDateForm($newsitem, $contents,$offset);

			$this->vars['categorylist']=new Categorylist(getcategoriesfornewsitem($newsitem));
			$this->vars['categoryselection']= new CategorySelectionForm(true,$this->stringvars['jsid']);
		}
		else
		{
			$this->stringvars['jsid']="";
			$this->stringvars['newsitem']="";
			$this->stringvars['newsitemtitle']="This page has no items";
		}
		$this->stringvars['javascript']="&nbsp;".prepareJavaScript($this->stringvars['jsid'], "admin/includes/javascript/messageboxes.js");
    	$this->stringvars['javascript'].=prepareJavaScript($this->stringvars['jsid'], "admin/includes/javascript/editnewsitem.js");
	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/edit/editnewsitemforms.tpl");
	}
}


?>
