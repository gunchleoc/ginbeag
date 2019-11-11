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
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));

require_once $projectroot."functions/pagecontent/newspages.php";
require_once $projectroot."includes/objects/template.php";
require_once $projectroot."includes/objects/categories.php";
require_once $projectroot."includes/objects/elements.php";
require_once $projectroot."includes/objects/images.php";
require_once $projectroot."includes/objects/forms.php";
require_once $projectroot."includes/objects/page.php";
require_once $projectroot."includes/functions.php";
require_once $projectroot."includes/includes.php";

//
// Templating for Newsitemsections
//
class NewsitemSection extends Template
{

    var $isquotestart=false;
    var $isquoteend=false;

    function __construct($newsitem, $newsitemsection, $contents, $isquoted=false, $showhidden=false)
    {
        $this->stringvars['l_quote']=getlang("section_quote");

        if ($contents['text'] === "[quote]") {
            $this->isquotestart = true;
        } elseif ($contents['text'] === "[unquote]") {
            $this->isquoteend = true;
        } else {
            if (!empty($contents['sectiontitle'])) {
                $this->stringvars['title'] = title2html($contents['sectiontitle']);
                if (!Page::has_metadata('title')) {
                    Page::set_metadata('title', $contents['sectiontitle']);
                }
            }

            if (!empty($contents['image_filename'])) {
                if (!Page::has_metadata('image')) {
                    Page::set_metadata('image', $contents['image_filename']);
                }
                $this->vars['image'] = new CaptionedImage($contents, array("newsitem" => $newsitem), $showhidden);
            } else {
                if (!Page::has_metadata('image')) {
                    Page::set_metadata('image', extract_image_from_text($contents['text']));
                }
                $this->stringvars['image'] = "";
            }

            if (!Page::has_metadata('description')) {
                Page::set_metadata('description', $contents['text']);
            }
            $this->stringvars['text'] = text2html($contents['text']);
        }
        parent::__construct();
    }

    // assigns templates
    function createTemplates()
    {
        if($this->isquotestart) { $this->addTemplate("pages/news/newsitemsectionquotestart.tpl");
        } elseif($this->isquoteend) { $this->addTemplate("pages/news/newsitemsectionquoteend.tpl");
        } else { $this->addTemplate("pages/news/newsitemsection.tpl");
        }
    }
}


//
// Templating for Newsitems
//
class Newsitem extends Template
{

    function __construct($newsitem, $contents, $offset, $showhidden=false, $showtoplink=true)
    {
        global $_GET, $projectroot;

        parent::__construct();

        //print("<br>newsitem: ".$this->stringvars['page']." - ".$newsitem." - ".$offset);

        if(!isset($_GET['printview'])) {
            $linkparams["page"]=$this->stringvars['page'];
            $linkparams["newsitem"]=$newsitem;
            if(ismobile()) {
                $linkparams["m"] = "on";
                $this->stringvars['itemlink']= '<a href="'.makelinkparameters($linkparams).'" title="'.getlang("news_single_link").'" class="buttonlink">'.getlang("news_single_link_short").'</a>';
                $linkparams["printview"]="on";
                $this->stringvars['printviewbutton'] ='<a href="'.makelinkparameters($linkparams).'" title="'.getlang("pagemenu_printview").'" class="buttonlink">'.getlang("pagemenu_printview_short").'</a>';
            }
            else
            {
                $this->vars['itemlink']= new LinkButton(makelinkparameters($linkparams), getlang("news_single_link"), 'img/link.png');
                $linkparams["printview"]="on";
                $this->vars['printviewbutton']= new LinkButton(makelinkparameters($linkparams), getlang("pagemenu_printview"), "img/printview.png");
            }
        }

        $this->stringvars['title'] =title2html($contents['title']);

        if(strlen($contents['title'])>0) {
            $this->stringvars['title'] =title2html($contents['title']);
            if (!Page::has_metadata('title')) {
                Page::set_metadata('title', $contents['title']);
            }
        } else {
            $this->stringvars['title'] = sprintf(getlang("news_title_default"), formatdate($contents['date']));
        }

        if(strlen($contents['date'])>0 || strlen($contents['location'])>0) {
            $this->stringvars['location_date'] ="locationdate";
        }

        $this->stringvars['date'] =formatdatetime($contents['date']);
        $this->stringvars['location'] =title2html($contents['location']);


        if(strlen($contents['sourcelink'])>0) {
            $this->stringvars['source_link'] =$contents['sourcelink'];
        }

        if(strlen($contents['source'])>0) {
            $this->stringvars['source'] =title2html($contents['source']);
            $this->stringvars['l_source'] =getlang('news_source_source');
        }

        if(strlen($contents['contributor'])>0) {
            $this->stringvars['contributor'] =title2html($contents['contributor']);
            $this->stringvars['l_contributor'] =getlang('news_source_foundby');
        }

        $this->vars['categorylist']=new CategorylistLinks(getcategoriesfornewsitem($newsitem), $this->stringvars['page'], CATEGORY_NEWS);

        if(strlen($contents['synopsis'])>0) {
            $this->stringvars['synopsis_image']="synopsis_image";
            if (!Page::has_metadata('description')) {
                Page::set_metadata('description', $contents['synopsis']);
            }
        }

        $this->stringvars['text']=text2html($contents['synopsis']);
        $this->stringvars['copyright']=makecopyright($contents);

        if($showhidden) {
            $this->stringvars['editor']=title2html(getdisplayname($contents['editor_id']));
        }

        if($showtoplink) { $this->stringvars["show_toplink"]="showtoplink";
        }

        // synopsis
        $images = getnewsitemsynopsisimages($newsitem);

        $noofimages=count($images);

        $this->listvars['image'] = array();
        if ($noofimages > 0) {
            if (!Page::has_metadata('image')) {
                Page::set_metadata('image', array_values($images)[0]);
            }
            if ($noofimages == 1) {
                $contents['image_filename'] = array_shift($images);
                $this->vars['image'] = new CaptionedImage($contents, array("newsitem" => $newsitem), $showhidden);
            }
            else {
                $width = 0;
                $this->stringvars['multiple_images'] = "$noofimages";
                $imagedata = array('imagealign' => "float:left; ", 'imageautoshrink' => true, 'usethumbnail' => true);
                foreach ($images as $imagefilename) {
                    $imagedata['image_filename'] = $imagefilename;
                    $imagedata = Image::make_imagedata($imagedata);

                    $this->listvars['image'][] = new Image($imagefilename, $imagedata, array("newsitem" => $newsitem), $showhidden);
                    $width += $imagedata['width'] + 20;
                }
                $this->stringvars['width'] = $width;
            }
            $this->stringvars['synopsis_image']="synopsis_image";
        } elseif (!Page::has_metadata('image')) {
            Page::set_metadata('image', extract_image_from_text($contents['synopsis']));
        }

        // sections
        $sections = getnewsitemsectionswithcontent($newsitem);
        $this->listvars['section']=array();
        if (!empty($sections)) {
            $isquote=false;
            foreach ($sections as $id => $contents) {
                $newsitemsection = new NewsitemSection($newsitem, $id, $contents, $isquote, $showhidden);
                $this->listvars['section'][] = $newsitemsection;
                $isquote == $newsitemsection->isquotestart;
            }
        } else {
            $this->stringvars['nosections']="true";
            $this->stringvars['newsitemsectionform'] = "";
        }
        $this->stringvars["l_topofthispage"] = getlang("pagemenu_topofthispage");
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("pages/news/newsitem.tpl");
    }
}



//
// Templating for stand alone Newsitems
//
class Newsitempage extends Template
{

    function __construct($newsitem,$page,$offset,$showhidden=false,$showtoplink=true)
    {
        global $_GET;

        parent::__construct();

        $linkparams["page"]=$this->stringvars['page'];
        $this->stringvars['returnlink']= makelinkparameters($linkparams);
        $this->stringvars["l_returnbutton"] = getlang("newsitem_returnbutton");

        if(!isset($_GET['printview'])) {
            $linkparams["newsitem"]=$newsitem;
            $linkparams["printview"]="on";
            $this->vars['printviewbutton']= new LinkButton(makelinkparameters($linkparams), getlang("pagemenu_printview"), "img/printview.png");
        }
        $this->stringvars['l_single']=getlang('news_single_showing');

        $this->vars['newsitem']= new Newsitem($newsitem, getnewsitemcontents($newsitem), $offset, $showhidden, false);

        $this->stringvars["l_topofthispage"] = getlang("pagemenu_topofthispage");
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("pages/news/newsitempage.tpl");
    }

}

//
// main class for newspages
//
class NewsPage extends Template
{
    function __construct($page, $introcontents, $offset, $showhidden) {
        global $_GET;

        parent::__construct();

        $this->vars['pageintro'] = new PageIntro($introcontents['title_page'], $introcontents['introtext'], "introtext", $introcontents, $showhidden);

        $linkparams = array("page" => $this->stringvars['page']);
        if(ismobile()) { $linkparams["m"] = "on";
        }
        $this->stringvars['actionvars'] = makelinkparameters($linkparams);
        $this->stringvars['hiddenvars'] = $this->makehiddenvars($linkparams);

        $this->stringvars["l_topofthispage"] = getlang("pagemenu_topofthispage");

        // filtering
        $filter=isset($_GET['filter']);
        $filterpage=isset($_GET['filterpage']);
        if($filter || $filterpage) {
            $selectedcat=$_GET['selectedcat'];
            $from=array("day" => $_GET['fromday'], "month" => $_GET['frommonth'], "year" => $_GET['fromyear']);
            $to=array("day" => $_GET['today'], "month" => $_GET['tomonth'], "year" => $_GET['toyear']);
            $order=$_GET['order'];
            $ascdesc=$_GET['ascdesc'];
            $newsitemsperpage=getproperty("News Items Per Page");
            $noofnewsitems=count(getfilterednewsitems($this->stringvars['page'], $selectedcat, $from, $to, $order, $ascdesc, 0, 0));

            if(!$filterpage) { $offset=0;
            } else { $offset=getoffsetforjumppage($noofnewsitems, $newsitemsperpage, $offset);
            }

            $newsitems=getfilterednewsitems($this->stringvars['page'], $selectedcat, $from, $to, $order, $ascdesc, $newsitemsperpage, $offset);
        }
        // no filtering
        else
        {
            $newsitemsperpage=getproperty("News Items Per Page");
            if(!($newsitemsperpage>0)) { $newsitemsperpage = 5;
            }

            $noofnewsitems=countpublishednewsitems($this->stringvars['page']);
            $offset=getoffsetforjumppage($noofnewsitems, $newsitemsperpage, $offset);

            $newsitems=getpublishednewsitems($this->stringvars['page'], $newsitemsperpage, $offset);
        }
        // end filtering


        // rss
        if(hasrssfeed($this->stringvars['page'])) {
            $this->vars['rss']= new LinkButton(getprojectrootlinkpath().'rss.php'.makelinkparameters(array("page" => $this->stringvars['page'])), getlang("news_rss_feed"), "img/rss.png");
        }

        // jumpform & pagemenu
        if($noofnewsitems/$newsitemsperpage>1) {
            //      $this->stringvars['paging']="paging";
            //      $this->stringvars['paging_rss']="paging_rss";
            $filterparams=array();
            if($filter ||$filterpage) {
                $filterparams["filterpage"]="on";
                $filterparams["selectedcat"]=$selectedcat;
                $filterparams["fromday"]=$_GET['fromday'];
                $filterparams["frommonth"]=$_GET['frommonth'];
                $filterparams["fromyear"]=$_GET['fromyear'];
                $filterparams["today"]=$_GET['today'];
                $filterparams["tomonth"]=$_GET['tomonth'];
                $filterparams["toyear"]=$_GET['toyear'];
                $filterparams["order"]=$order;
                $filterparams["ascdesc"]=$ascdesc;
                $filterparams["page"]=$this->stringvars['page'];
                $this->vars['jumpform'] = new JumpToPageForm("", $filterparams);
            }
            else
            {
                $this->vars['jumpform'] = new JumpToPageForm("", array("page" => $this->stringvars['page']));
            }

            $this->vars['pagemenu'] = new Pagemenu($offset, $newsitemsperpage, $noofnewsitems, $filterparams);
        }

        // filter result message
        if($filter || $filterpage) {
            if(!count($newsitems)) {
                $this->stringvars['message']=getlang("news_filter_nomatch");
            }
            else
            {
                $this->stringvars['message']=getlang("news_filter_result");
            }
            $this->stringvars['l_showall'] =sprintf(getlang("news_filter_showall"), title2html(getpagetitle($this->stringvars['page'])));
        }

        // get items
        $isfirst = true;
        foreach ($newsitems as $id => $contents) {
            $this->listvars['newsitem'][] = new Newsitem($id, $contents, $offset, $showhidden, $isfirst);
            $isfirst = false;
        }

        $this->stringvars['l_displayoptions']=getlang("news_filter_displayoptions");
        $this->stringvars['l_categories']=getlang("news_filter_categories");
        $this->stringvars['l_from']=getlang("news_filter_from");
        $this->stringvars['l_to']=getlang("news_filter_to");
        $this->stringvars['l_go']=getlang("news_filter_go");
        $this->stringvars['l_orderby']=getlang("news_filter_orderby");

        if($filter || $filterpage) {
            $this->vars['filterform'] = $this->makenewsfilterform($page, $selectedcat, $from, $to, $order, $ascdesc);
        }
        else
        {
            $this->vars['filterform'] = $this->makenewsfilterform($page);
        }
        $this->vars['editdata']= new Editdata($introcontents, $showhidden);
    }

    // assigns templates
    function createTemplates()
    {
        if(ismobile()) { $this->addTemplate("mobile/newspage.tpl");
        } else { $this->addTemplate("pages/news/newspage.tpl");
        }
    }

    function makenewsfilterform($page,$selectedcat="",$from=array(),$to=array(),$order="date",$ascdesc="desc")
    {
        $oldestdate=getoldestnewsitemdate($page);
        $newestdate=getnewestnewsitemdate($page);
        if(!array_key_exists("day", $from)) {
            $from["day"]=$oldestdate["mday"];
            $from["month"]=$oldestdate["mon"];
            $from["year"]=$oldestdate["year"];
        }
        if(!array_key_exists("day", $to)) {
            $to["day"]=$newestdate["mday"];
            $to["month"]=$newestdate["mon"];
            $to["year"]=$newestdate["year"];
        }

        $this->vars["categoryselection"]= new CategorySelectionForm(false, "", CATEGORY_NEWS, 1, array($selectedcat => $selectedcat));
        $this->vars["from_day"]= new DayOptionForm($from["day"], true, "", "fromday", getlang("news_filter_fromday"));
        $this->vars["from_month"]=new MonthOptionForm($from["month"], true, "", "frommonth", getlang("news_filter_frommonth"));
        $this->vars["from_year"]=new YearOptionForm($from["year"], $oldestdate["year"], $newestdate["year"], "", "fromyear", getlang("news_filter_fromyear"));
        $this->vars["to_day"]= new DayOptionForm($to["day"], true, "", "today", getlang("news_filter_today"));
        $this->vars["to_month"]=new MonthOptionForm($to["month"], true, "", "tomonth", getlang("news_filter_tomonth"));
        $this->vars["to_year"]=new YearOptionForm($to["year"], $oldestdate["year"], $newestdate["year"], "", "toyear", getlang("news_filter_toyear"));
        $this->vars["order"]= new NewsOrderSelectionForm($order);
        $this->vars["ascdesc"]= new AscDescSelectionForm($ascdesc==="asc");
    }
}


//
// name may contain white spaces. They will be stripped for the form name,
// but left intact for display
//
class NewsOrderSelectionForm  extends Template
{

    function __construct($order="")
    {
        parent::__construct();

        $this->stringvars['optionform_name'] = "order";
        $this->stringvars['optionform_label'] = getlang("news_filter_property");
        $this->stringvars['optionform_id'] ="order";
        $this->stringvars['optionform_attributes'] = "";
        $this->stringvars['jsid'] ="";

        $this->listvars['option'][]= new OptionFormOption("date", $order==="date", getlang("news_filter_date"));
        $this->listvars['option'][]= new OptionFormOption("title", $order==="title", getlang("news_filter_title"));
        $this->listvars['option'][]= new OptionFormOption("source", $order==="source", getlang("news_filter_source"));
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("forms/optionform.tpl");
    }
}

?>
