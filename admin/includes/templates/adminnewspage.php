<?php
$projectroot=dirname(__FILE__);

// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."functions/pages.php");
include_once($projectroot."includes/templates/template.php");
include_once($projectroot."includes/includes.php");
include_once($projectroot."includes/templates/forms.php");
include_once($projectroot."includes/templates/page.php");
include_once($projectroot."includes/templates/newspage.php");

/// TODO: Offset fr alle Eintrge kontrollieren!!!




//
//
//
class NewsitemSynopsisForm extends Template {

	function NewsitemSynopsisForm($page,$newsitem)
	{
		global $sid, $offset;

		$imageids = getnewsitemsynopsisimageids($newsitem);
		if(count($imageids)==0) $this->stringvars["image"]="";
		else
		{
			for($i=0;$i<count($imageids);$i++)
			{
				$this->listvars["image"][] = new NewsitemImagePropertiesForm($page,getnewsitemsynopsisimage($imageids[$i]),$newsitem,$imageids[$i],$offset);
			}
		}

		$contents=getnewsitemcontents($newsitem);

		$this->stringvars['sid']=$sid;
		$this->stringvars['page']=$page;
		$this->stringvars['offset']=$offset;
		$this->stringvars['newsitem']=$newsitem;

		$this->vars['synopsis'] = new EditTextButtons($page,$contents['synopsis'],"Edit Synopsis","newsitemsynopsis",$newsitem);

		$this->createTemplates();
	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/newsitemsynopsisform.tpl");
	}
}


//
//
//
class NewsitemSectionForm extends Template {

	var $isquotestart=false;
	var $isquoteend=false;

	function NewsitemSectionForm($page,$newsitem,$newsitemsection, $offset)
	{
		global $sid;

		$contents=getnewsitemsectioncontents($newsitemsection);

		if($contents['text']==="[quote]") $this->isquotestart = true;
		elseif($contents['text']==="[unquote]") $this->isquoteend = true;
		else
		{
			$this->stringvars['sid']=$sid;
			$this->stringvars['page']=$page;
			$this->stringvars['offset']=$offset;
			$this->stringvars['newsitem']=$newsitem;
			$this->stringvars['newsitemsection']=$newsitemsection;

			if(strlen($contents['sectiontitle'])>0)
			$this->stringvars['sectionheader']=title2html($contents['sectiontitle']);
			else
			$this->stringvars['sectionheader']="Section ID ".$newsitemsection;

			$this->stringvars['sectiontitle']=input2html($contents['sectiontitle']);
			$edittextbuttons = new EditTextButtons($page,$contents['text'],"Edit Text","newsitemsection",$newsitemsection);
			$imageform = new ImagePropertiesForm($page,$contents['sectionimage'],$contents['imagealign'],$contents['imagevalign'],"Section","changeimage",'&newsitemsection='.$newsitemsection);
			$this->vars['sectioncontents']= new TextWithImageForm("Section Text",$edittextbuttons,$imageform);
		}
		$this->vars['insertnewsitemsectionform']=new InsertNewsItemSectionForm($page,$newsitem,$newsitemsection);
		$this->createTemplates();
	}


	function isquotestart()
	{
		return $this->isquotestart;
	}

	function isquoteend()
	{
		return $this->isquoteend;
	}

	// assigns templates
	function createTemplates()
	{
		if($this->isquotestart()) $this->addTemplate("admin/newsitemsectionquotestart.tpl");
		elseif($this->isquoteend()) $this->addTemplate("admin/newsitemsectionquoteend.tpl");
		else $this->addTemplate("admin/newsitemsectionform.tpl");
	}
}


//
//
//
class InsertNewsItemSectionForm extends Template {
	function InsertNewsItemSectionForm($page,$newsitem,$newsitemsection)
	{
		global $sid, $offset;


		$this->stringvars['sid']=$sid;
		$this->stringvars['offset']=$offset;
		$this->stringvars['page']=$page;
		$this->stringvars['newsitem']=$newsitem;
		$this->stringvars['newsitemsection']=$newsitemsection;

		$this->createTemplates();
	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/insertnewsitemsectionform.tpl");
	}
}


//
//
//
class DeleteNewsItemConfirm extends Template {
	function DeleteNewsItemConfirm($page,$newsitem)
	{
		global $sid, $offset;

		$this->stringvars['sid']=$sid;
		$this->stringvars['offset']=$offset;
		$this->stringvars['page']=$page;
		$this->stringvars['newsitem']=$newsitem;

		$this->vars['header'] = new HTMLHeader("Deleting newsitem","Webpage Building","On page: ".title2html(getpagetitle($page)));
		$this->vars['footer']=new HTMLFooter();

		$this->vars['item'] = new Newsitem($newsitem, $page, $offset,true,true,false);

		$this->createTemplates();
	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/deletenewsitemconfirm.tpl");
	}
}


//
//
//
class DeleteNewsItemSectionConfirm extends Template {
	function DeleteNewsItemSectionConfirm($page,$newsitem,$newsitemsection)
	{
		global $sid, $offset;

		$this->stringvars['sid']=$sid;
		$this->stringvars['offset']=$offset;
		$this->stringvars['page']=$page;
		$this->stringvars['newsitem']=$newsitem;
		$this->stringvars['newsitemsection']=$newsitemsection;

		$this->vars['header'] = new HTMLHeader("Deleting newsitem section","Webpage Building","On page: ".title2html(getpagetitle($page)));
		$this->vars['footer']=new HTMLFooter();

		$this->vars['section'] = new Newsitemsection($newsitemsection, false,2,true,true);

		$this->createTemplates();
	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/deletenewsitemsectionconfirm.tpl");
	}
}


//
//
//
class NewsItemAddForm extends Template {

	function NewsItemAddForm($page)
	{
		global $sid;

		$this->stringvars['sid']=$sid;
		$this->stringvars['page']=$page;

		$this->createTemplates();
	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/newsitemaddform.tpl");
	}
}


//
//
//
class NewsItemArchiveForm extends Template {

	function NewsItemArchiveForm($page)
	{
		global $sid;

		$this->stringvars['sid']=$sid;
		$this->stringvars['page']=$page;

		$this->createTemplates();
	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/newsitemarchiveform.tpl");
	}
}


//
//
//
class NewsItemRSSForm extends Template {
	function NewsItemRSSForm($page)
	{
		global $sid, $offset;

		$this->stringvars['sid']=$sid;
		$this->stringvars['page']=$page;
		$this->stringvars['offset']=$offset;
		if(hasrssfeed($page))
		{
			$this->stringvars['buttontext']='Disable RSS-Feed';
			$this->stringvars['fieldname']='disablerss';
		}
		else
		{
			$this->stringvars['buttontext']='Enable RSS-Feed';
			$this->stringvars['fieldname']='enablerss';
		}
		$this->createTemplates();
	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/newsitemrssform.tpl");
	}
}


//
//
//
class NewsItemDisplayOrderForm extends Template {

	function NewsItemDisplayOrderForm($page)
	{
		global $sid;

		$this->stringvars['sid']=$sid;
		$this->stringvars['page']=$page;
		if(displaynewestnewsitemfirst($page)) $this->stringvars['newestfirst'] = 'true';
		elseif(!displaynewestnewsitemfirst($page)) $this->stringvars['oldestfirst'] = 'true';

		$this->createTemplates();
	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/newsitemdisplayorderform.tpl");
	}
}


//
//
//
class NewsItemSearchForm extends Template {
	function NewsItemSearchForm($page)
	{
		global $sid;

		$this->stringvars['sid']=$sid;
		$this->stringvars['page']=$page;

		$this->createTemplates();
	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/newsitemsearchform.tpl");
	}
}


//
//
//
class NewsItemSearchResults extends Template {
	function NewsItemSearchResults($page,$searchtitle)
	{
		global $sid;

		$this->stringvars['sid']=$sid;
		$this->stringvars['page']=$page;
		
		$this->vars['header'] = new HTMLHeader("Searching newsitems","Webpage Building","On page: ".title2html(getpagetitle($page)).'<br />'.$message);
		$this->vars['footer']=new HTMLFooter();
		$this->stringvars['backbuttons']=generalsettingsbuttons($page);

		$this->vars['searchform']= new NewsItemSearchForm($page);

		$this->stringvars['searchtitle']=title2html($searchtitle);

		$newsitems=searchnewsitemtitles($searchtitle,$page,true);
		$noofnewsitems=count($newsitems);
		if(!$noofnewsitems>0)
		{
			$this->stringvars['searchresult']='<p class="highlight">No newsitems found!</p><hr>';
		}
		else
		{
			for($i=0;$i<$noofnewsitems;$i++)
			{
				$offset=getnewsitemoffset($page,1,$newsitems[$i],true);
				$this->listvars['searchresult'][]=new NewsItemSearchResult($page,$newsitems[$i],$offset);
			}
		}


		$this->createTemplates();
	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/newsitemsearchresults.tpl");
	}
}

//
//
//
class NewsItemSearchResult extends Template {
	function NewsItemSearchResult($page,$newsitem,$offset)
	{
		global $sid;

		$this->stringvars['sid']=$sid;
		$this->stringvars['page']=$page;
		$this->stringvars['offset']=$offset;

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

		$this->createTemplates();
	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/newsitemsearchresult.tpl");
	}
}


//
//
//
class ArchiveNewsItemsForm extends Template {
	function ArchiveNewsItemsForm($page, $message="")
	{
		global $sid;

		$this->stringvars['sid']=$sid;
		$this->stringvars['page']=$page;
		
		$this->vars['header'] = new HTMLHeader("Archiving Newsitems","Webpage Building","On page: ".title2html(getpagetitle($page)).'<br />'.$message);
		$this->vars['footer']=new HTMLFooter();


		$oldestdate=getoldestnewsitemdate($page);
		$date=getnewestnewsitemdate($page);

		$this->stringvars['day']=$oldestdate['mday'];
		$this->stringvars['month']=$oldestdate['month'];
		$this->stringvars['year']=$oldestdate['year'];

		$this->vars['dayform']= new DayOptionForm($oldestdate['mday']);
		$this->vars['monthform']= new MonthOptionForm($oldestdate['mon']);
		$this->vars['yearform']= new YearOptionForm($date['year'],$oldestdate['year'],$date['year']);

		$this->stringvars['backbuttons']=editcontentsbuttons($page,"Back");
		
		$this->createTemplates();
	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/archivenewsitemsform.tpl");
	}
}

class FakeTheDateForm extends Template {
	function FakeTheDateForm($page, $newsitem, $contents, $offset)
	{
		global $sid;

		$this->stringvars['sid']=$sid;
		$this->stringvars['page']=$page;
		$this->stringvars['offset']=$offset;
		$this->stringvars['newsitem']=$newsitem;

		$this->stringvars['date']=formatdatetime($contents['date']);

		$date=getnewsitemdate($newsitem);

		$this->vars['dayform']= new DayOptionForm($date['mday']);
		$this->vars['monthform']= new MonthOptionForm($date['mon']);
		$this->stringvars['year']= $date['year'];

		$this->vars['hoursform']= new NumberOptionForm($date['hours'],0,23,false,"hours","hours");
		$this->vars['minutesform']= new NumberOptionForm($date['minutes'],0,59,false,"minutes","minutes");
		$this->vars['secondsform']= new NumberOptionForm($date['seconds'],0,59,false,"seconds","seconds");


		$this->createTemplates();
	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/fakethedateform.tpl");
	}
}

class NewsItemSourceForm extends Template {
	function NewsItemSourceForm($page, $newsitem, $contents, $offset)
	{
		global $sid;

		$this->stringvars['sid']=$sid;
		$this->stringvars['page']=$page;
		$this->stringvars['offset']=$offset;
		$this->stringvars['newsitem']=$newsitem;


		$this->stringvars['contributor']=input2html($contents['contributor']);
		$this->stringvars['location']=input2html($contents['location']);
		$this->stringvars['source']=input2html($contents['source']);
		$this->stringvars['sourcelink']=$contents['sourcelink'];

		$this->createTemplates();
	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/newsitemsourceform.tpl");
	}
}

// todo: code duplication with adminpage???
// page or newsitem
//
class NewsItemPermissionsForm extends Template {

	function NewsItemPermissionsForm($page, $newsitem, $permissions, $offset)
	{
		global $sid;

		$this->stringvars['sid']=$sid;
		$this->stringvars['page']=$page;
		$this->stringvars['offset']=$offset;
		$this->stringvars['newsitem']=$newsitem;

		$this->stringvars['copyright']=input2html($permissions['copyright']);
		$this->stringvars['image_copyright']=input2html($permissions['image_copyright']);

		$this->vars['permission_granted']= new RadioButtonForm("permission",PERMISSION_GRANTED,"Permission granted",$permissions['permission']==PERMISSION_GRANTED);
		$this->vars['no_permission']= new RadioButtonForm("permission",NO_PERMISSION,"No permission",$permissions['permission']==NO_PERMISSION);
		$this->vars['permission_refused']= new RadioButtonForm("permission",PERMISSION_REFUSED,"Permission refused",$permissions['permission']==PERMISSION_REFUSED);

		$this->createTemplates();
	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/newsitempermissionsform.tpl");
	}
}

class NewsItemPublishForm extends Template {

	function NewsItemPublishForm($page, $newsitem, $permissions, $offset)
	{
		global $sid;

		$this->stringvars['sid']=$sid;
		$this->stringvars['page']=$page;
		$this->stringvars['offset']=$offset;
		$this->stringvars['newsitem']=$newsitem;

		$this->stringvars['previewpath']=getprojectrootlinkpath().'admin/includes/preview.php';

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

		$this->createTemplates();
	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/newsitempublishform.tpl");
	}
}

class NewsItemDeleteForm extends Template {

	function NewsItemDeleteForm($page, $newsitem, $offset)
	{
		global $sid;

		$this->stringvars['sid']=$sid;
		$this->stringvars['page']=$page;
		$this->stringvars['offset']=$offset;
		$this->stringvars['newsitem']=$newsitem;

		$this->createTemplates();
	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/newsitemdeleteform.tpl");
	}
}

class NewsItemTitleForm extends Template {

	function NewsItemTitleForm($page, $newsitem, $contents, $offset)
	{
		global $sid;

		$this->stringvars['sid']=$sid;
		$this->stringvars['page']=$page;
		$this->stringvars['offset']=$offset;
		$this->stringvars['newsitem']=$newsitem;
		$this->stringvars['title']=input2html($contents['title']);

		$this->createTemplates();
	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/newsitemtitleform.tpl");
	}
}

class EditNewsItemForms extends Template {

	function EditNewsItemForms($page, $offset, $message ="")
	{
		global $sid;

		$this->stringvars['sid']=$sid;
		$this->stringvars['page']=$page;
		$this->stringvars['offset']=$offset;
		$this->stringvars['newsitem']=$newsitem;
		
		
		$this->vars['header'] = new HTMLHeader("Editing Newsitem","Webpage Building","On page: ".title2html(getpagetitle($page)).'<br />'.$message);
		$this->vars['footer']=new HTMLFooter();
		$this->stringvars['backbuttons']=generalsettingsbuttons($page);

		$this->vars['newsitemaddform'] = new NewsItemAddForm($page);
		$this->vars['newsitemarchiveform'] = new NewsItemArchiveForm($page);

		$this->vars['newsitemrssform'] = new NewsItemRSSForm($page);
		$this->vars['newsitemdisplayorderform'] = new NewsItemDisplayOrderForm($page);


		$noofnewsitems=countnewsitems($page);
		$offset=getoffsetforjumppage($noofnewsitems,1,$offset);
		if(!$offset)
		{
			$offset=0;
		}

		$newsitems=getnewsitems($page,1,$offset);
		$newsitem=$newsitems[0];

		$this->vars['pagemenu']= new PageMenu($offset,1,$noofnewsitems,'action=editcontents',$page);

		if(hasrssfeed($page))
		{
			$this->stringvars['rssbutton']='<a href="'.getprojectrootlinkpath().'rss.php?page='.$page.'" target="_blank"><img src="'.getprojectrootlinkpath().'img/rss.gif"></a>';
		}
		
		if($noofnewsitems>0)
		{

			$this->stringvars['hasnewsitems']="true";
			$this->vars['jumptopageform'] = new JumpToPageForm("",array("page" => $page, "action" => "editcontents"));
			$this->vars['newsitemsearchform'] = new NewsItemSearchForm($page);

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
			$this->vars['newsitemtitleform']= new NewsItemTitleForm($page, $newsitem, $contents, $offset);
			$this->vars['newsitemdeleteform']= new NewsItemDeleteForm($page, $newsitem, $offset);
			$this->vars['newsitempublishform']= new NewsItemPublishForm($page, $newsitem, $permissions, $offset);
			$this->vars['newsitempermissionsform']= new NewsItemPermissionsForm($page, $newsitem, $permissions, $offset);
			$this->vars['newsitemsynopsisform']= new NewsitemSynopsisForm($page,$newsitem);

			// sections

			$sections=getnewsitemsections($newsitem);
			$noofsections=count($sections);
			for($i=0;$i<$noofsections;$i++)
			{
				$this->listvars['newsitemsectionform'][] = new NewsitemSectionForm($page,$newsitem,$sections[$i], $offset);
			}
			if($noofsections<1)
			{
				$this->stringvars['nosections']="true";
				$this->vars['insertnewsitemsectionform'] = new InsertNewsItemSectionForm($page,$newsitem,$newsitemsection);
			}
			else
			{
				$this->vars['insertnewsitemsectionform'] = new InsertNewsItemSectionForm($page,$newsitem,$sections[$noofsections-1]);
			}

			$this->vars['newsitemsourceform'] = new NewsItemSourceForm($page, $newsitem, $contents, $offset);
			$this->vars['fakethedateform'] = new FakeTheDateForm($page, $newsitem, $contents, $offset);

			$this->stringvars['categorylist']=makecategorylist(getcategoriesfornewsitem($newsitem));
			$this->vars['categoryselection']= new CategorySelectionForm(true);
		}
		$this->createTemplates();
	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/editnewsitemforms.tpl");
	}
}


?>
