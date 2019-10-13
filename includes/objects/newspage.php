<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));

require_once $projectroot."functions/pagecontent/newspages.php";
require_once $projectroot."includes/objects/template.php";
require_once $projectroot."includes/objects/categories.php";
require_once $projectroot."includes/objects/elements.php";
require_once $projectroot."includes/objects/images.php";
require_once $projectroot."includes/objects/forms.php";
require_once $projectroot."includes/functions.php";
require_once $projectroot."includes/includes.php";

//
// Templating for Newsitemsections
//
class Newsitemsection extends Template
{

    var $isquotestart=false;
    var $isquoteend=false;

    function Newsitemsection($newsitemsection, $newsitem, $isquoted=false, $showhidden=false)
    {
        $this->stringvars['l_quote']=getlang("section_quote");

        $sectioncontents=getnewsitemsectioncontents($newsitemsection);
        if($sectioncontents['text']==="[quote]") { $this->isquotestart = true;
        } elseif($sectioncontents['text']==="[unquote]") { $this->isquoteend = true;
        } else
        {
            if(strlen($sectioncontents['sectiontitle'])>0) {
                $this->stringvars['title'] =title2html($sectioncontents['sectiontitle']);
            }

            if(strlen($sectioncontents['sectionimage']) > 0) {
                $this->vars['image'] = new CaptionedImage($sectioncontents['sectionimage'], $sectioncontents['imageautoshrink'], $sectioncontents['usethumbnail'], $sectioncontents['imagealign'], array("newsitem" => $newsitem), $showhidden);
            } else { $this->stringvars['image']="";
            }

            $this->stringvars['text']=text2html($sectioncontents['text']);
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

    function Newsitem($newsitem,$offset,$showhidden=false,$showtoplink=true)
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

        $contents=getnewsitemcontents($newsitem);

        $this->stringvars['title'] =title2html($contents['title']);

        if(strlen($contents['title'])>0) {
            $this->stringvars['title'] =title2html($contents['title']);
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
        }

        $this->stringvars['text']=text2html($contents['synopsis']);
        $this->stringvars['copyright']=makecopyright($contents);

        if($showhidden) {
            $this->stringvars['editor']=title2html(getusername($contents['editor_id']));
        }

        if($showtoplink) { $this->stringvars["show_toplink"]="showtoplink";
        }

        // synopsis
        $images=getnewsitemsynopsisimages($newsitem);

        $noofimages=count($images);

        $this->listvars['image']= array();
        if($noofimages) {
            if($noofimages==1) {
                $this->vars['image'] = new CaptionedImage($images[0], $contents['imageautoshrink'], $contents['usethumbnail'], "left", array("newsitem" => $newsitem), $showhidden);
            }
            else
            {
                $width=0;
                $this->stringvars['multiple_images']="".$noofimages;
                for($i=0;$i<$noofimages;$i++)
                {
                    $image = new Image($images[$i], true, true, array("newsitem" => $newsitem), $showhidden);
                    $this->listvars['image'][] = $image;

                    $thumbnail = getthumbnail($images[$i]);
                    $filepath = getimagepath($images[$i]);
                    $thumbnailpath = getthumbnailpath($images[$i], $thumbnail);

                    if(ismobile()) {
                        $usethumbnail = true;
                        $extension = substr($images[$i], strrpos($images[$i], "."), strlen($images[$i]));
                        $thumbname = substr($images[$i], 0, strrpos($images[$i], ".")).'_thn'.$extension;
                        $path = $projectroot.getproperty("Image Upload Path").getimagesubpath(basename($images[$i]))."/mobile/".$thumbname;

                        if(file_exists($path)) {
                            $thumbnailpath = $path;
                            $thumbnail = $thumbname;
                        }
                    }

                    if(thumbnailexists($thumbnail) && file_exists($thumbnailpath) && !is_dir($thumbnailpath)) {
                        $dimensions = getimagedimensions($thumbnailpath);
                        $width += $dimensions["width"];
                    }
                    else if(imageexists($images[$i]) && file_exists($filepath) && !is_dir($filepath)) {
                        $dimensions = calculateimagedimensions($images[$i]);
                        $width += $dimensions["width"];
                    }
                }
                $width+=20;
                $this->stringvars['width']=$width;
            }
            $this->stringvars['synopsis_image']="synopsis_image";
        }

        // sections
        $sections=getnewsitemsections($newsitem);
        //print_r($sections);
        $noofsections=count($sections);
        $this->listvars['section']=array();
        if($noofsections) {
            $isquote=false;
            for($i=0;$i<$noofsections;$i++)
            {
                $newsitemsection = new Newsitemsection($sections[$i], $newsitem, $isquote, $showhidden);
                $this->listvars['section'][] = $newsitemsection;
                $isquote == $newsitemsection->isquotestart;
            }
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

    function Newsitempage($newsitem,$page,$offset,$showhidden=false,$showtoplink=true)
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

        $this->vars['newsitem']= new Newsitem($newsitem, $offset, $showhidden, false);

        $contents=getnewsitemcontents($newsitem);

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
    function NewsPage($page,$offset,$showhidden)
    {
        global $_GET;

        parent::__construct();

        $pageintro = getpageintro($this->stringvars['page']);
        $this->vars['pageintro'] = new PageIntro(getpagetitle($this->stringvars['page']), $pageintro['introtext'], $pageintro['introimage'], $pageintro['imageautoshrink'], $pageintro['usethumbnail'], $pageintro['imagehalign'], $showhidden);

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
        if(count($newsitems)) {
            $this->listvars['newsitem'][] = new Newsitem($newsitems[0], $offset, $showhidden, false);
        }
        for($i=1;$i<count($newsitems);$i++)
        {
            $this->listvars['newsitem'][] = new Newsitem($newsitems[$i], $offset, $showhidden);
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
        $this->vars['editdata']= new Editdata($showhidden);
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

    function NewsOrderSelectionForm($order="")
    {
        parent::__construct();

        $this->stringvars['optionform_name'] = "order";
        $this->stringvars['optionform_label'] = getlang("news_filter_property");
        $this->stringvars['optionform_id'] ="order";
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
