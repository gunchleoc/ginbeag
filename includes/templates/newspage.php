<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));

include_once($projectroot."functions/pages.php");
include_once($projectroot."includes/templates/template.php");
include_once($projectroot."includes/templates/elements.php");
include_once($projectroot."includes/templates/forms.php");
include_once($projectroot."includes/includes.php");

//
// Templating for Newsitemsections
//
class Newsitemsection extends Template {

    var $isquotestart=false;
    var $isquoteend=false;

    function Newsitemsection($newsitemsection_id,$isquoted=false,$imagefactor=2,$showrefused=false,$showhidden=false) {
    
    	$this->stringvars['l_quote']=getlang("section_quote");

        $sectioncontents=getnewsitemsectioncontents($newsitemsection_id);
        if($sectioncontents['text']==="[quote]") $this->isquotestart = true;
        elseif($sectioncontents['text']==="[unquote]") $this->isquoteend = true;
        else
        {
          if(strlen($sectioncontents['sectiontitle'])>0)
            $this->stringvars['title'] =title2html($sectioncontents['sectiontitle']);

//todo                    mayshowimage($sectioncontents['sectionimage'],$page,$showhidden)
          if(strlen($sectioncontents['sectionimage'])>0 && ($showhidden || !imagepermissionrefused($sectioncontents['sectionimage'])))
            $this->vars['image'] = new CaptionedImage($sectioncontents['sectionimage'],$imagefactor,$showrefused,$showhidden);
          else $this->stringvars['no_image']="no_image";

          if($sectioncontents['imagealign']==="right")
            $this->stringvars['image_align_right'] ="right";
          elseif($sectioncontents['imagealign']==="center")
            $this->stringvars['image_align_center'] ="center";
          else
            $this->stringvars['image_align_left'] ="left";

          if($sectioncontents['imagevalign']==="bottom")
            $this->stringvars['image_valign_bottom'] ="bottom";
          else
            $this->stringvars['image_valign_top'] ="top";

          $this->stringvars['image_align'] =$sectioncontents['imagealign'];
          $this->stringvars['image_valign'] =$sectioncontents['imagevalign'];
          $this->stringvars['text']=text2html($sectioncontents['text']);
          
          if($isquoted) $this->stringvars['class']="quote";
          else $this->stringvars['class']="gen";
        }
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
      if($this->isquotestart()) $this->addTemplate("newsitemsectionquotestart.tpl");
      elseif($this->isquoteend()) $this->addTemplate("newsitemsectionquoteend.tpl");
      else $this->addTemplate("newsitemsection.tpl");
    }
}


//
// Templating for Newsitems
//
class Newsitem extends Template {

    function Newsitem($newsitem_id,$page,$offset,$showrefused,$showhidden=false,$showtoplink=true) {
      global $_GET;

      if(!isset($_GET['printview']))
        $this->vars['printviewbutton']= new PrintviewButton($newsitem_id);

      $contents=getnewsitemcontents($newsitem_id);

      if(strlen($contents['title'])>0)
        $this->stringvars['title'] =title2html($contents['title']);

      if(strlen($contents['date'])>0 || strlen($contents['location'])>0)
        $this->stringvars['location_date'] ="locationdate";

      $this->stringvars['date'] =formatdatetime($contents['date']);
      $this->stringvars['location'] =$contents['location'];
      
      if(strlen($contents['sourcelink'])>0)
        $this->stringvars['source_link'] =$contents['sourcelink'];

      if(strlen($contents['source'])>0)
        $this->stringvars['source'] =title2html($contents['source']);

      if(strlen($contents['contributor'])>0)
        $this->stringvars['contributor'] =$contents['contributor'];

      if(strlen($contents['synopsis'])>0)
        $this->stringvars['synopsis_image']="synopsis_image";

      $this->stringvars['text']=text2html($contents['synopsis']);
      $this->stringvars['copyright']=makecopyright($contents);
      
      if($showhidden)
        $this->stringvars['editor']=title2html(getusername($contents['editor_id']));

      if($showtoplink) $this->stringvars["show_toplink"]="showtoplink";

      // synopsis
      $images=getnewsitemsynopsisimages($newsitem_id);

      $noofimages=count($images);

      $this->listvars['image']= array();
      if($noofimages)
      {
        for($i=0;$i<$noofimages;$i++)
        {
          if(mayshowimage($images[$i],$page,$showhidden))
          {
            $image = new Image($images[$i],$noofimages);
            $this->listvars['image'][] = $image;
          }
        }
        $this->stringvars['synopsis_image']="synopsis_image";
      }

      // sections
      $sections=getnewsitemsections($newsitem_id);
      $noofsections=count($sections);
      $this->listvars['section']=array();
      if($noofsections)
      {
        $isquote=false;
        for($i=0;$i<$noofsections;$i++)
        {
          $newsitemsection = new Newsitemsection($sections[$i],$isquote,2,$showrefused,$showhidden);
          $this->listvars['section'][] = $newsitemsection;
          $isquote == $newsitemsection->isquotestart();
        }
      }
      $this->createtemplates();
    }
    
    // assigns templates
    function createTemplates()
    {
      $this->addTemplate("newsitem.tpl");
    }

}

//
// main class for newspages
//
class NewsPage extends Template {
  function NewsPage($page,$offset,$showrefused,$showhidden)
  {
    global $sid,$_GET;

    // searching & filtering
    $filter=isset($_GET['filter']);
    $filterpage=isset($_GET['filterpage']);
    $search=isset($_GET['search']);
    if($filter || $filterpage)
    {
      $selectedcat=$_GET['selectedcat'];
      $from=array("day" => $_GET['fromday'], "month" => $_GET['frommonth'], "year" => $_GET['fromyear']);
      $to=array("day" => $_GET['today'], "month" => $_GET['tomonth'], "year" => $_GET['toyear']);
      $order=$_GET['order'];
      $ascdesc=$_GET['ascdesc'];
      $newsitemsperpage=getproperty("News Items Per Page");
      $noofnewsitems=count(getfilterednewsitems($page,$selectedcat,$from,$to,$order,$ascdesc,0,0));

      if(!$filterpage) $offset=0;
      else $offset=getoffsetforjumppage($noofnewsitems,$newsitemsperpage,$offset);

      $newsitems=getfilterednewsitems($page,$selectedcat,$from,$to,$order,$ascdesc,$newsitemsperpage,$offset);
    }
    elseif($search)
    {
      $newsitems=searchnewsitems($search,$_GET['searchpage'],$_GET['all'],$showhidden);
      $noofnewsitems=count($newsitems);
      $newsitemsperpage=$noofnewsitems;
      if(!($newsitemsperpage>0)) $newsitemsperpage = 5;
    }
    // no searching or filtering
    else
    {
      $newsitemsperpage=getproperty("News Items Per Page");
      if(!($newsitemsperpage>0)) $newsitemsperpage = 5;

      $noofnewsitems=countpublishednewsitems($page);
      $offset=getoffsetforjumppage($noofnewsitems,$newsitemsperpage,$offset);

      $newsitems=getpublishednewsitems($page,$newsitemsperpage,$offset);
  //    print_r($newsitems);
    }
    // end searching and filtering
    
    // page title
    $this->stringvars['pagetitle']=title2html(getpagetitle($page));
    
    // rss
    if(hasrssfeed($page))
      $this->stringvars['rss']= '<a href="'.getprojectrootlinkpath().'rss.php?page='.$page.'" target="_blank" class="genmed"><img src="'.getprojectrootlinkpath().'img/rss.gif"></a>';

    // jumpform & pagemenu
    if($noofnewsitems/$newsitemsperpage>1)
    {
//      $this->stringvars['paging']="paging";
//      $this->stringvars['paging_rss']="paging_rss";
      $filterparams=array();
      if($filter ||$filterpage)
      {
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
        $filterparams["page"]=$page;
        $this->vars['jumpform'] = new JumpToPageForm("",$filterparams);
      }
      else
      {
        $this->vars['jumpform'] = new JumpToPageForm("",array("page" => $page));
      }
      $params="";
      if(count($filterparams))
      {
        $keys=array_keys($filterparams);
        $key=current($keys);
        $params.=$key."=".$filterparams[$key];
        next($keys);
        while($key=current($keys))
        {
          $params.="&".$key."=".$filterparams[$key];
          next($keys);
        }
      }
      $this->vars['pagemenu'] = new Pagemenu($offset,$newsitemsperpage,$noofnewsitems,$params,$page);
    }

    // search result message
    if($search || $filter || $filterpage)
    {
      $this->stringvars['search_result']="searchresult";
      if(!count($newsitems))
      {
        $this->stringvars['message']=getlang("news_filter_nomatch");
      }
      else
      {
        $this->stringvars['message']=getlang("news_filter_result");
      }
      $this->stringvars['page'] =$page;
      $this->stringvars['sid'] =$sid;
      $this->stringvars['l_showall'] =sprintf(getlang("news_filter_showall"),$this->stringvars['pagetitle']);
      
    }

    // get items
    if(count($newsitems))
    {
      $this->listvars['newsitem'][] = new Newsitem($newsitems[0],$page,$offset,$showrefused,$showhidden,false);
    }
    for($i=1;$i<count($newsitems);$i++)
    {
      $this->listvars['newsitem'][] = new Newsitem($newsitems[$i],$page,$offset,$showrefused,$showhidden);
    }


	$this->stringvars['l_displayoptions']=getlang("news_filter_displayoptions");
    $this->stringvars['l_categories']=getlang("news_filter_categories");
	$this->stringvars['l_from']=getlang("news_filter_from");
	$this->stringvars['l_to']=getlang("news_filter_to");
	$this->stringvars['l_go']=getlang("news_filter_go");
	$this->stringvars['l_orderby']=getlang("news_filter_orderby");


    if($filter || $filterpage)
    {
      $this->vars['filterform'] = $this->makenewsfilterform($page,$selectedcat,$from,$to,$order,$ascdesc);
    }
    else
    {
      $this->vars['filterform'] = $this->makenewsfilterform($page);
    }

    $this->vars['editdata']= new Editdata($page,$showhidden);
    $this->createTemplates();
  }
  
    // assigns templates
    function createTemplates()
    {
      $this->addTemplate("newspage.tpl");
    }
    
    function makenewsfilterform($page,$selectedcat="",$from=array(),$to=array(),$order="date",$ascdesc="desc")
    {
      global $sid;
      $oldestdate=getoldestnewsitemdate($page);
      $newestdate=getnewestnewsitemdate($page);
      if(!array_key_exists("day",$from))
      {
        $from["day"]=$oldestdate["mday"];
        $from["month"]=$oldestdate["mon"];
        $from["year"]=$oldestdate["year"];
      }
      if(!array_key_exists("day",$to))
      {
        $to["day"]=$newestdate["mday"];
        $to["month"]=$newestdate["mon"];
        $to["year"]=$newestdate["year"];
      }

      $this->stringvars["page"]= $page;
      $this->stringvars["sid"]= $sid;
      $this->vars["categoryselection"]= new CategorySelectionForm(false,1,array($selectedcat => $selectedcat));
      $this->vars["from_day"]= new DayOptionForm($from["day"],true,"fromday",getlang("news_filter_fromday"));
      $this->vars["from_month"]=new MonthOptionForm($from["month"],true,"frommonth",getlang("news_filter_frommonth"));
      $this->vars["from_year"]=new YearOptionForm($from["year"],$oldestdate["year"],$newestdate["year"],"fromyear",getlang("news_filter_fromyear"));
      $this->vars["to_day"]= new DayOptionForm($to["day"],true,"today",getlang("news_filter_today"));
      $this->vars["to_month"]=new MonthOptionForm($to["month"],true,"tomonth",getlang("news_filter_tomonth"));
      $this->vars["to_year"]=new YearOptionForm($to["year"],$oldestdate["year"],$newestdate["year"],"toyear",getlang("news_filter_toyear"));
      $this->vars["order"]= new NewsOrderSelectionForm($order);
      $this->vars["ascdesc"]= new AscDescSelectionForm($ascdesc==="asc");

    }
}


//
// name may contain white spaces. They will be stripped for the form name,
// but left intact for display
//
class NewsOrderSelectionForm  extends Template {
    var $stringvars=array("optionform_name" => "",
                          "optionform_attributes" => "",
                          "optionform_size" => "1");
    var $listvars=array("option" => array());

    function NewsOrderSelectionForm($order="") {

        $this->stringvars['optionform_name'] = "order";

        $this->listvars['option'][]= new OptionFormOption("date",$order==="date",getlang("news_filter_date"));
        $this->listvars['option'][]= new OptionFormOption("title",$order==="title",getlang("news_filter_title"));
        $this->listvars['option'][]= new OptionFormOption("source",$order==="source",getlang("news_filter_source"));
        $this->createTemplates();
    }

    // assigns templates
    function createTemplates()
    {
      $this->addTemplate("optionform.tpl");
    }
}

?>
