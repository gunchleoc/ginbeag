<?php
/**
 * An Gineadair Beag is a content management system to run websites with.
 *
 * PHP Version 7
 *
 * Copyright (C) 2005-2019 GunChleoc
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 * @category Ginbeag
 * @package  Ginbeag
 * @author   gunchleoc <fios@foramnagaidhlig.net>
 * @license  https://www.gnu.org/licenses/agpl-3.0.en.html GNU AGPL
 * @link     https://github.com/gunchleoc/ginbeag/
 */

$projectroot=dirname(__FILE__);

// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."functions/pagecontent/newspages.php";
require_once $projectroot."admin/functions/pagecontent/newspagesmod.php";
require_once $projectroot."includes/objects/template.php";
require_once $projectroot."admin/includes/objects/images.php";
require_once $projectroot."includes/objects/categories.php";
require_once $projectroot."admin/includes/objects/editor.php";
require_once $projectroot."admin/includes/objects/imageeditor.php";
require_once $projectroot."includes/objects/newspage.php";
require_once $projectroot."admin/functions/pagesmod.php";

/// TODO: Offset für alle Eintrge kontrollieren!!!


//
//
//
class NewsitemImagePropertiesForm extends Template
{

    function __construct($image,$newsitem,$imageid,$offset=0)
    {
        global $offset;
        parent::__construct();

        $linkparams["page"] = $this->stringvars['page'];
        $this->stringvars['imagelistpath']=getprojectrootlinkpath()."admin/editimagelist.php".makelinkparameters($linkparams);

        $linkparams["imageid"] = $imageid;
        $linkparams["offset"] = $offset;
        $linkparams["newsitem"] = $newsitem;
        $linkparams["action"] = "editcontents";
        $this->stringvars['actionvars']= makelinkparameters($linkparams);

        $this->stringvars['imageid']=$imageid;
        $this->stringvars['imagefilename']=$image;

        if (!empty($image) && imageexists($image)) {
            $this->vars['image'] = new CaptionedImageAdmin(array('image_filename' => $image), $this->stringvars['page']);
        }
        $this->vars['removeconfirmform']= new CheckboxForm("removeconfirm", "removeconfirm", "Confirm remove", false, "right");
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
class NewsitemSynopsisForm extends Template
{

    function __construct($newsitem)
    {
        global $offset;
        parent::__construct();

        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["offset"] = $offset;
        $linkparams["newsitem"] = $newsitem;
        $linkparams["action"] = "editcontents";
        $this->stringvars['actionvars']= makelinkparameters($linkparams)."#synopsis";

        $images = getnewsitemsynopsisimages($newsitem);
        if (empty($images)) {
            $this->stringvars["image"] = "";
        } else {
            foreach ($images as $imageid => $imagefilename) {
                $this->listvars["image"][] = new NewsitemImagePropertiesForm($imagefilename, $newsitem, $imageid, $offset);
            }
        }

        $this->vars['synopsis']= new Editor($this->stringvars["page"], $newsitem, "newsitemsynopsis", "Synopsis Text");
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
class NewsitemSectionForm extends Template
{

    function __construct($newsitem, $newsitemsection, $contents, $offset)
    {
        parent::__construct($newsitemsection, array(), array(0 => "admin/includes/javascript/editnewsitemsection.js"));
        $this->stringvars['javascript']=$this->getScripts();
        $this->stringvars['hiddenvars'] = $this->makehiddenvars(array("newsitemsection" => $newsitemsection));

        if($contents['text']==="[quote]") { $this->stringvars['quotestart']="quotestart";
        } elseif($contents['text']==="[unquote]") { $this->stringvars['quoteend']="quoteend";
        } else
        {
            $linkparams["page"] = $this->stringvars['page'];
            $linkparams["offset"] = $offset;
            $linkparams["newsitem"] = $newsitem;
            $linkparams["newsitemsection"] = $newsitemsection;
            $linkparams["action"] = "editcontents";
            $this->stringvars['actionvars']= makelinkparameters($linkparams);

            $this->stringvars['notquote']="notquote";
            $this->stringvars['newsitemsection']=$newsitemsection;

            if(strlen($contents['sectiontitle'])>0) {
                $this->stringvars['sectionheader']=title2html($contents['sectiontitle']);
            } else {
                $this->stringvars['sectionheader']="Section ID ".$newsitemsection;
            }

            $this->stringvars['sectiontitle']=input2html($contents['sectiontitle']);

            $this->vars['sectioneditor'] = new Editor($this->stringvars["page"], $newsitemsection, "newsitemsection", "Section Text");
            $this->vars['imageeditor'] = new ImageEditor($this->stringvars["page"], $newsitemsection, "newsitemsection", $contents);
        }
        $this->vars['insertnewsitemsectionform']=new InsertNewsItemSectionForm($newsitem, $newsitemsection);
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
class InsertNewsItemSectionForm extends Template
{

    function __construct($newsitem,$newsitemsection)
    {
        global $offset;
        parent::__construct();

        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["newsitem"] = $newsitem;
        $linkparams["newsitemsection"] = $newsitemsection;
        $linkparams["action"] = "editcontents";
        $this->stringvars['actionvars']= makelinkparameters($linkparams);
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
class DeleteNewsItemConfirm extends Template
{

    function __construct($newsitem)
    {
        global $offset;
        parent::__construct();
        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["offset"] = $offset;
        $linkparams["newsitem"] = $newsitem;
        $linkparams["action"] = "editcontents";
        $this->stringvars['actionvars']= makelinkparameters($linkparams);

        $this->stringvars['pagetitle']=title2html(getpagetitle($this->stringvars["page"]));
        $this->vars['item'] = new Newsitem($newsitem, $offset, true, true, false);
        $this->vars['confirmbuttons'] = new CancelConfirmButtons($this->stringvars['actionvars'], "confirmdeleteitem", "nodeleteitem");
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
class DeleteNewsItemSectionConfirm extends Template
{

    function __construct($newsitem,$newsitemsection)
    {
        global $offset;
        parent::__construct();

        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["offset"] = $offset;
        $linkparams["newsitem"] = $newsitem;
        $linkparams["newsitemsection"] = $newsitemsection;
        $linkparams["action"] = "editcontents";
        $this->stringvars['actionvars']= makelinkparameters($linkparams);

        $contents=getnewsitemcontents($newsitem);
        $this->stringvars['newsitemtitle']=title2html($contents['title']);
        $this->vars['section'] = new NewsitemSection($newsitem, $newsitemsection, getnewsitemsectioncontents($newsitemsection), false, true);
        $this->vars['confirmbuttons'] = new CancelConfirmButtons($this->stringvars['actionvars'], "confirmdeletesection", "nodeletesection");
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
class NewsItemAddForm extends Template
{

    function __construct()
    {
        parent::__construct();

        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["action"] = "editcontents";
        $this->stringvars['actionvars']= makelinkparameters($linkparams);
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
class NewsItemArchiveForm extends Template
{

    function __construct()
    {
        parent::__construct();

        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["action"] = "editcontents";
        $this->stringvars['actionvars']= makelinkparameters($linkparams);
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
class NewsItemDisplayOrderForm extends Template
{

    function __construct()
    {
        parent::__construct();

        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["action"] = "editcontents";
        $this->stringvars['actionvars']= makelinkparameters($linkparams);

        if(displaynewestnewsitemfirst($this->stringvars['page'])) { $this->stringvars['newestfirst'] = 'true';
        } elseif(!displaynewestnewsitemfirst($this->stringvars['page'])) { $this->stringvars['oldestfirst'] = 'true';
        }
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
class NewsItemSearchForm extends Template
{

    function __construct()
    {
        parent::__construct();

        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["action"] = "editcontents";
        $this->stringvars['actionvars']= makelinkparameters($linkparams);
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
class NewsItemSearchResults extends Template
{

    function __construct($searchtitle)
    {
        parent::__construct();

        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["offset"] = 0;
        $linkparams["action"] = "editcontents";
        $this->stringvars['actionvars']= makelinkparameters($linkparams);

        $this->vars['navigationbuttons']= new PageEditNavigationButtons(new GeneralSettingsButton(), new EditPageIntroSettingsButton());
        $this->vars['searchform']= new NewsItemSearchForm();
        $this->stringvars['searchtitle']=title2html($searchtitle);

        $newsitems=searchnewsitemtitles($searchtitle, $this->stringvars["page"], true);
        $noofnewsitems=count($newsitems);
        if(!$noofnewsitems>0) {
            $this->stringvars['searchresult']='No newsitems found!';
        }
        else
        {
            for($i=0;$i<$noofnewsitems;$i++)
            {
                $offset=getnewsitemoffset($this->stringvars["page"], 1, $newsitems[$i], true);
                $this->listvars['searchresult'][]=new NewsItemSearchResult($newsitems[$i], $offset);
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
class NewsItemSearchResult extends Template
{

    function __construct($newsitem,$offset)
    {
        parent::__construct();

        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["offset"] = $offset;
        $linkparams["action"] = "editcontents";
        $this->stringvars['actionvars']= makelinkparameters($linkparams);

        $contents=getnewsitemcontents($newsitem);

        $this->stringvars['sectiontitle']=title2html($contents['title']);
        $this->stringvars['synopsis']=text2html($contents['synopsis']);
        $this->stringvars['contributor']= input2html($contents['contributor']);
        $this->stringvars['source']= input2html($contents['source']);

        if($contents['sourcelink']!="") {
            $this->stringvars['sourcelink']= $contents['sourcelink'];
        }
        $this->stringvars['location']=title2html($contents['location']);
        $this->stringvars['date']=formatdatetime($contents['date']);
        $this->stringvars['editor']=title2html(getdisplayname($contents['editor_id']));
        $this->stringvars['copyright']=makecopyright($contents);
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
class ArchiveNewsItemsForm extends Template
{
    function __construct()
    {
        parent::__construct();

        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["action"] = "editcontents";
        $this->stringvars['actionvars']= makelinkparameters($linkparams);

        $oldestdate=getoldestnewsitemdate($this->stringvars['page']);
        $date=getnewestnewsitemdate($this->stringvars['page']);

        $hiddenvars['oldestday']=$oldestdate['mday'];
        $hiddenvars['oldestmonth']=$oldestdate['mon'];
        $hiddenvars['oldestyear']=$oldestdate['year'];
        $this->stringvars['hiddenvars'] = $this->makehiddenvars($hiddenvars);
        $this->stringvars['olddate'] = makearticledate($oldestdate['mday'], $oldestdate['mon'], $oldestdate['year']);

        $this->vars['dayform']= new DayOptionForm($oldestdate['mday']);
        $this->vars['monthform']= new MonthOptionForm($oldestdate['mon']);
        $this->vars['yearform']= new YearOptionForm($date['year'], $oldestdate['year'], $date['year']);

        $this->vars['navigationbuttons']= new PageEditNavigationButtons(new GeneralSettingsButton(), new EditPageContentsButton());
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/edit/archivenewsitemsform.tpl");
    }
}

//
//
//
class FakeTheDateForm extends Template
{

    function __construct($newsitem, $contents, $offset)
    {
        parent::__construct($newsitem);

        $this->stringvars['date']=formatdatetime($contents['date']);

        $date = @getdate(strtotime($contents['date']));

        $this->vars['dayform']= new DayOptionForm($date['mday'], false, $this->stringvars['jsid']);
        $this->vars['monthform']= new MonthOptionForm($date['mon'], false, $this->stringvars['jsid']);
        $this->stringvars['year']= $date['year'];

        $this->vars['hoursform']= new NumberOptionForm($date['hours'], 0, 23, false, $this->stringvars['jsid'], "hours", "hours");
        $this->vars['minutesform']= new NumberOptionForm($date['minutes'], 0, 59, false, $this->stringvars['jsid'], "minutes", "minutes");
        $this->vars['secondsform']= new NumberOptionForm($date['seconds'], 0, 59, false, $this->stringvars['jsid'], "seconds", "seconds");
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/edit/fakethedateform.tpl");
    }
}

//
//
//
class NewsItemSourceForm extends Template
{

    function __construct($newsitem, $contents)
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
class NewsItemPermissionsForm extends Template
{

    function __construct($newsitem, $permissions)
    {
        parent::__construct($newsitem);

        $this->stringvars['copyright']=input2html($permissions['copyright']);
        $this->stringvars['image_copyright']=input2html($permissions['image_copyright']);
        $this->vars['permission_granted']= new RadioButtonForm($this->stringvars['jsid'], "permission", PERMISSION_GRANTED, "Permission granted", $permissions['permission']==PERMISSION_GRANTED, "right");
        $this->vars['no_permission']= new RadioButtonForm($this->stringvars['jsid'], "permission", NO_PERMISSION, "No permission", $permissions['permission']==NO_PERMISSION, "right");
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/edit/newsitempermissionsform.tpl");
    }
}

//
//
//
class NewsItemPublishForm extends Template
{

    function __construct($newsitem, $ispublished, $offset)
    {
        parent::__construct($newsitem);

        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["offset"] = $offset;
        $linkparams["newsitem"] = $newsitem;
        $linkparams["action"] = "editcontents";

        $this->stringvars['previewlink']=getprojectrootlinkpath()."admin/includes/preview.php".makelinkparameters($linkparams);

        if ($ispublished) {
            $this->stringvars['buttontitle']="Hide Newsitem";
        } else {
            $this->stringvars['buttontitle']="Publish Newsitem";
        }
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/edit/newsitempublishform.tpl");
    }
}

//
//
//
class NewsItemDeleteForm extends Template
{

    function __construct($newsitem, $offset)
    {
        parent::__construct();

        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["offset"] = $offset;
        $linkparams["newsitem"] = $newsitem;
        $linkparams["action"] = "editcontents";
        $this->stringvars['actionvars']= makelinkparameters($linkparams);
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/edit/newsitemdeleteform.tpl");
    }
}


//
//
//
class EditNewsItemForms extends Template
{

    function __construct($page, $offset)
    {
        $noofnewsitems=countnewsitems($page);
        if($noofnewsitems>0) {
            $newsitems = getnewsitems($page, 1, $offset);
            $this->stringvars["newsitem"] = $newsitems[0];
        }
        else {
            $this->stringvars["newsitem"] = "";
        }

        parent::__construct($this->stringvars["newsitem"], array(0 => "includes/javascript/jcaret.js"), array(0 => "admin/includes/javascript/editnewsitem.js"));
        $this->stringvars['javascript']=$this->getScripts();
        $this->stringvars['hiddenvars'] = $this->makehiddenvars(array("newsitem" => $this->stringvars["newsitem"]));

        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["offset"] = $offset;
        $linkparams["action"] = "editcontents";
        $this->stringvars['actionvars']= makelinkparameters($linkparams);

        $this->vars['navigationbuttons']= new PageEditNavigationButtons(new GeneralSettingsButton(), new EditPageIntroSettingsButton());
        $this->vars['newsitemaddform'] = new NewsItemAddForm();

        $offset=getoffsetforjumppage($noofnewsitems, 1, $offset);
        if(!$offset) {
            $offset=0;
        }

        $this->vars['pagemenu']= new PageMenu($offset, 1, $noofnewsitems, array("action" => "editcontents"));

        if($noofnewsitems>0) {
            $this->stringvars['hasnewsitems']="true";
            $this->vars['jumptopageform'] = new JumpToPageForm("", array("page" => $page, "action" => "editcontents"));
            $this->vars['newsitemsearchform'] = new NewsItemSearchForm();

            $contents=getnewsitemcontents($this->stringvars["newsitem"]);

            if($contents['title']) {
                $this->stringvars['newsitemtitle']=title2html($contents['title']);
            } else {
                $this->stringvars['newsitemtitle']="New Newsitem";
            }

            $this->stringvars['authorname']=getdisplayname($contents['editor_id']);
            $this->stringvars['title']=input2html($contents['title']);

            $this->vars['newsitemdeleteform']= new NewsItemDeleteForm($this->stringvars["newsitem"], $offset);
            $this->vars['newsitempublishform']= new NewsItemPublishForm($this->stringvars["newsitem"], $contents['ispublished'], $offset);
            $this->vars['newsitempermissionsform']= new NewsItemPermissionsForm($this->stringvars["newsitem"], $contents);
            $this->vars['newsitemsynopsisform']= new NewsitemSynopsisForm($this->stringvars["newsitem"]);

            // sections
            $sections = getnewsitemsectionswithcontent($this->stringvars["newsitem"]);
            if (!empty($sections)) {
                foreach ($sections as $id => $sectioncontents) {
                    $this->listvars['newsitemsectionform'][] = new NewsitemSectionForm($this->stringvars["newsitem"], $id, $sectioncontents, $offset);
                }
            } else {
                $this->stringvars['nosections']="true";
                $this->stringvars['newsitemsectionform'] = "";
            }
            $this->vars['insertnewsitemsectionform']=new InsertNewsItemSectionForm($this->stringvars["newsitem"], 0);

            $this->vars['newsitemsourceform'] = new NewsItemSourceForm($this->stringvars["newsitem"], $contents);
            $this->vars['fakethedateform'] = new FakeTheDateForm($this->stringvars["newsitem"], $contents, $offset);
            $this->vars['categorylist']=new Categorylist(getcategoriesfornewsitem($this->stringvars["newsitem"]), CATEGORY_NEWS);
            $this->vars['categoryselection']= new CategorySelectionForm(true, $this->stringvars['jsid'], CATEGORY_NEWS);
        }
        else
        {
            $this->stringvars['newsitemtitle']="This page has no items";
        }
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/edit/editnewsitemforms.tpl");
    }
}


//
//
//
class EditNews extends Template
{

    function __construct($page)
    {
        parent::__construct($page, array(0 => "includes/javascript/jcaret.js"), array(0 => "admin/includes/javascript/editnewsitem.js"));
        $this->stringvars['javascript']=$this->getScripts();

        $this->vars['intro']= new Editor($page, 0, "pageintro", "Synopsis");
        $this->vars['imageeditor'] = new ImageEditor($page, 0, "pageintro", getpageintroimage($page));

        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["action"] = "editcontents";
        $this->stringvars['actionvars']= makelinkparameters($linkparams);

        $this->vars['navigationbuttons']= new PageEditNavigationButtons(new GeneralSettingsButton(), new EditPageContentsButton());
        $this->vars['newsitemarchiveform'] = new NewsItemArchiveForm();
        $this->vars['newsitemdisplayorderform'] = new NewsItemDisplayOrderForm();

        if(hasrssfeed($page)) {
            $this->stringvars['rssbutton']='<a href="'.getprojectrootlinkpath().'rss.php'.makelinkparameters(array("page" => $page)).'" target="_blank"><img src="'.getprojectrootlinkpath().'img/rss.gif"></a>';
            $this->stringvars['buttontext']='Disable RSS-Feed';
            $this->stringvars['fieldname']='disablerss';
        }
        else
        {
            $this->stringvars['buttontext']='Enable RSS-Feed';
            $this->stringvars['fieldname']='enablerss';
        }
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/edit/editnews.tpl");
    }
}

?>
