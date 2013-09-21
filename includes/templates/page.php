<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));

include_once($projectroot."functions/pages.php");
include_once($projectroot."functions/referrers.php");
include_once($projectroot."functions/banners.php");
include_once($projectroot."includes/templates/template.php");
include_once($projectroot."includes/includes.php");

include_once($projectroot."includes/templates/forms.php");
include_once($projectroot."includes/templates/elements.php");




//
// container for editdata
//
class Editdata extends Template {
    // vars that are simple strings
    var $stringvars=array();


    function Editdata($page,$showhidden=false)
    {
    	
      $editdate= geteditdate($page);
      $editor=  getusername(getpageeditor($page));
      $permissions=getcopyright($page);
      if($showhidden)
      {
      	$this->stringvars['footerlastedited']=sprintf(getlang("footer_lasteditedauthor"),formatdatetime($editdate),$editor);
      }
      else
      {
		$this->stringvars['footerlastedited']=sprintf(getlang("footer_lastedited"),formatdatetime($editdate));
      }
      $this->stringvars['copyright']=makecopyright($permissions);
      
      $this->stringvars['topofthispage']=getlang("page_topofthispage");
      

      $this->createTemplates();
    }

    // assigns templates
    function createTemplates()
    {
      $this->addTemplate("editdata.tpl");
    }
}


//
// Templating for Banners
//
class Banner extends Template {

    function Banner($banner_id,$showheader=false) {

      $contents=getbannercontents($banner_id);
      if($showheader)
      {
         if(strlen($contents['header'])>0)
          $this->stringvars['header']=title2html($contents['header']);
      }
      if(strlen($contents['code'])>0)
      {
        $this->stringvars['complete_banner']=stripslashes(utf8_encode($contents['code']));
      }
      else
      {
        $this->stringvars['link']=$contents['link'];
        $this->stringvars['image']=getbannerlinkpath($contents['image']);
        $this->stringvars['description']=title2html($contents['description']);
      }

      $this->createTemplates();
    }

    // assigns templates
    function createTemplates()
    {
      $this->addTemplate("banner.tpl");
    }
}


//
// Templating for Banners
//
class BannerList extends Template {

    function BannerList() {

      $banners=getbanners();
      $header="";
      for($i=0;$i<count($banners);$i++)
      {
        if(isbannercomplete($banners[$i]))
        {
          $contents=getbannercontents($banners[$i]);
          $showheader=false;
          if($contents['header']!==$header)
          {
            $header=$contents['header'];
            $showheader=true;
          }
          else $header="";
          $this->listvars['banner'][] = new Banner($banners[$i],$showheader);
        }
      }

      $this->createTemplates();
    }

    // assigns templates
    function createTemplates()
    {
      $this->addTemplate("bannerlist.tpl");
    }
}



//
// Templating for Navigator
//
class ArticleInfo extends Template {

    function ArticleInfo($page) {

      $contents= getarticlepageoverview($page);
      $articleinfo="";
      if($contents['article_author'])
      {
       $articleinfo.= 'By '.title2html($contents['article_author']);
      }
      if($contents['source'])
      {
        if($articleinfo)
        {
          $articleinfo.=', ';
        }
        $articleinfo.=title2html($contents['source']);
      }
      $date=makearticledate($contents['day'],$contents['month'],$contents['year']);
      if($date)
      {
        if($articleinfo)
        {
          $articleinfo.=', ';
        }
        $articleinfo.=$date;
      }
  
      $this->stringvars['articleinfo']=$articleinfo;
      $this->stringvars['categorylist']=makecategorylist(getcategoriesforpage($page),false);

      $this->createTemplates();
    }

    // assigns templates
    function createTemplates()
    {
      $this->addTemplate("articleinfo.tpl");
    }
}




//
// Templating for Linklist in Linklistmenu Navigator
//
class ShortLink extends Template {

    function ShortLink($page_id, $link_id) {
       global $sid;

      $contents=getlinkcontents($link_id);
      
      if(strlen($contents['link'])<=1)
      {
      	$this->stringvars['link']="?sid=".$sid."&page=".$page_id;
      }
      else
      {
      	$this->stringvars['link']=$contents['link'];   	
      }
      $this->stringvars['title']=title2html($contents['title']);
      
      $text=text2html($contents['description']);
      $paragraphs=explode ('<br />', $text);
      $text=$paragraphs[0];

      if (array_key_exists(1, $paragraphs)) $text.=' <a href="?sid='.$sid.'&page='.$page_id.'">[...]</a>';

      // todo: can this be stripped while keeping tags intact?
/*      if(strlen($text)>0)
      {
        if(strlen($text)>200)
        {
          $text=substr($text,0,200);
          $position=strrpos($text," ");
          if($position) $text=substr($text,0,$position);
        }
        //$text="- ".$text;
        if(strlen($text)<strlen($paragraphs[0])) $text.=" ...";
      }
*/
      $this->stringvars['description']=$text;
      
        $this->stringvars['link_class']="gen";
        $this->stringvars['title_class']="gen";
        $this->stringvars['link_attributes']="";


      $this->createTemplates();
    }

    // assigns templates
    function createTemplates()
    {
      $this->addTemplate("shortlink.tpl");
    }
}

//
// Templating for Linklist in Linklistmenu Navigator
//
class ShortLinkList extends Template {

    function ShortLinkList($page_id, $linkids) {

      for($i=0;$i<count($linkids);$i++)
      {
        $this->listvars['link'][]=new ShortLink($page_id, $linkids[$i]);
      }
      $this->createTemplates();
    }

    // assigns templates
    function createTemplates()
    {
      $this->addTemplate("navigatorbulletbranch.tpl");
    }
}



//
// Templating for Navigator
//
class NavigatorLink extends Template {

    var $style="";

    function NavigatorLink($page_id,$style="simple",$linktype="nav", $level=0, $class="navtitle", $isroot=false, $speciallink="" ,$showhidden=false) {

      global $_GET;
      $this->style=$style;

      // layout parameters
      $this->stringvars['margin_left']=$level;
      $this->stringvars['margin_top']=1;
      $this->stringvars['link_class']=$class;
      $this->stringvars['title_class']="";

      if($isroot)
        $this->stringvars['is_root']="is_root";
      else
        $this->stringvars['no_root']="no_root";

      // for special pages like, contact, guestbook etc
      if($page_id==0)
      {
        if($speciallink==="guestbook")
        {
          $this->stringvars['title']=getlang("navigator_guestbook");
          $this->stringvars['link']=getprojectrootlinkpath()."guestbook.php";
          $this->stringvars['link_attributes']='';
          $this->stringvars['description']="";
          if(basename($_SERVER['PHP_SELF'])==="guestbook.php")
          {
            $this->stringvars['title_class']="navhighlight";
          }
          if(isset($_GET['sid']))
            $this->stringvars['link'].="?sid=".$_GET['sid'];
        }
        elseif($speciallink==="contact")
        {
          $this->stringvars['title']=getlang("navigator_contact");
          $this->stringvars['link']=getprojectrootlinkpath()."contact.php";
          $this->stringvars['link_attributes']='';
          $this->stringvars['description']="";
          if(basename($_SERVER['PHP_SELF'])==="contact.php")
          {
            $this->stringvars['title_class']="navhighlight";
          }
          if(isset($_GET['sid']))
            $this->stringvars['link'].="?sid=".$_GET['sid'];
        }
        elseif($speciallink==="sitemap")
        {
          $this->stringvars['title']=getlang("navigator_sitemap");
          $this->stringvars['link']=getprojectrootlinkpath()."index.php?page=0&sitemap=on";
          $this->stringvars['link_attributes']='';
          $this->stringvars['description']="";
          if(isset($_GET['sitemap']))
          {
            $this->stringvars['title_class']="navhighlight";
          }
          if(isset($_GET['sid']))
            $this->stringvars['link'].="&sid=".$_GET['sid'];
        }
        elseif($speciallink==="home")
        {
          $this->stringvars['title']=getlang("navigator_home");
          $this->stringvars['link']=getprojectrootlinkpath();
          $this->stringvars['link_attributes']='';
          $this->stringvars['description']="";
          if(isset($_GET['sid']))
            $this->stringvars['link'].="?sid=".$_GET['sid'];
        }
        else
        {
          $this->stringvars['title']="Link not found";
          $this->stringvars['link']="";
          $this->stringvars['link_class']=$class;
          $this->stringvars['link_attributes']='';
          $this->stringvars['description']="";
        }
      }
      // for normal pages
      else
      {
//        print('<p class="gen">link '.$page_id.$style.'</p>');
        $this->pagetype=getpagetypearray($page_id);

        if($linktype=="nav")
        {
          $this->stringvars['title']=title2html(getnavtitlearray($page_id));
        }
        else
        {
          $this->stringvars['title']=title2html(getpagetitlearray($page_id));
        }
        
        if($showhidden)
        {
          if(isthisexactpagerestricted($page_id)) $this->stringvars['title']=$this->stringvars['title'].' (R)';
          if(!ispublished($page_id)) $this->stringvars['title']='<i>'.$this->stringvars['title'].'</i>';
        }

        if($this->pagetype==="external")
        {
          $this->stringvars['link']=getexternallink($page_id);
          // todo: no blank if local page
          $this->stringvars['link_attributes']='" target="_blank"';
          $this->stringvars['description']="";
        }
        else
        {
          if($this->pagetype==="article" && $linktype!=="nav")
          {
            $this->vars['description']=new ArticleInfo($page_id);
          }
          elseif($this->pagetype==="linklist" && $linktype!=="nav")
          {
            $linkids=getlinklistitems($page_id);
            if(count($linkids)>0)
            {
              $this->vars['description']=new ShortLinkList($page_id, $linkids);
            }
            else
            {
              $this->stringvars['description']="";
            }
          }

          else
          {
            $this->stringvars['description']="";
          }
          if($showhidden) $path=getprojectrootlinkpath()."admin/pagedisplay.php";
          else $path=getprojectrootlinkpath()."index.php";
          $this->stringvars['link']=$path.'?page='.$page_id;
          if(isset($_GET["sid"]))
            $this->stringvars['link'].="&sid=".$_GET["sid"];

          $this->stringvars['link_attributes']="";
        }

        if($_GET['page']==$page_id)
        {
          $this->stringvars['title_class']="navhighlight";
        }
        else
        {
          $this->stringvars['title_class']="";
        }
      }
      $this->createTemplates();
    }

    // assigns templates
    function createTemplates()
    {
      if($this->style=="bullet")
        $this->addTemplate("navigatorbulletlink.tpl");
      elseif($this->style=="splashpage")
        $this->addTemplate("splashpagenavigatorlink.tpl");
      elseif($this->style=="printview")
        $this->addTemplate("printviewnavigatorlink.tpl");
      elseif($this->stringvars['title_class']=="navhighlight")
        $this->addTemplate("navigatornolink.tpl");
      else
        $this->addTemplate("navigatorlink.tpl");
    }
}

//
// Templating for Navigator
// iterate over branch and create links
//
class NavigatorBranch extends Template {

    var $style="";

    function NavigatorBranch($page_id,$style="simple",$substyle="simple",$linktype="nav",$depth,$startwithroot=false,$level=0,$speciallink="",$showhidden=false)
    {
        $this->style=$style;
//        print('<p class="gen">depth '.$depth.'</p>');
        if($startwithroot && $level==0)
        {
          $class="navtitle";
        }
        else
        {
          $class="forumlink";
        }
        
        $isroot=false;
        if(isrootpagearray($page_id)  || ($style=="simple" && $substyle=="bullet"))
          $isroot=true;
          
        $this->listvars['link'][]= new NavigatorLink($page_id, $style, $linktype, $level, $class,$isroot,$speciallink, $showhidden);

        if($depth>0)
        {
          $pageids=getchildrenarray($page_id);
          for($i=0;$i<count($pageids);$i++)
          {
            if(displaylinksforpagearray($pageids[$i]) || $showhidden)
            {
              $this->listvars['link'][]= new NavigatorBranch($pageids[$i],$substyle,$substyle,$linktype, $depth-1, $startwithroot, $level+1,$speciallink, $showhidden);
            }
          }
        }
//      print_r($this->listvars);
      $this->createTemplates();
    }

    // assigns templates
    function createTemplates()
    {
      if($this->style=="bullet")
        $this->addTemplate("navigatorbulletbranch.tpl");
      elseif($this->style=="splashpage")
        $this->addTemplate("splashpagenavigatorbranch.tpl");
      elseif($this->style=="printview")
        $this->addTemplate("printviewnavigatorbranch.tpl");
      else
        $this->addTemplate("navigatorbranch.tpl");
    }
}

//
// Templating for Navigator
//
class Navigator extends Template {

  var $displaytype;

  function Navigator($page_id,$sistersinnavigator,$depth,$displaytype="page",$showhidden=false) {

    global $_GET;
    
    $this->displaytype=$displaytype;
    
    if($displaytype=="splashpage")
    {

      $linksonsplashpage=explode(",",getproperty('Links on Splash Page'));
      if(!getproperty('Show All Links on Splash Page') && $linksonsplashpage[0])
      {
        $roots=$linksonsplashpage;
      }
      else
      {
        $roots=getrootpages();
      }
      while(count($roots))
      {
        $currentroot=array_shift($roots);
        if(displaylinksforpagearray($currentroot) || $showhidden)
        {
          $this->listvars['link'][]=new NavigatorBranch($currentroot,"splashpage","splashpage","nav",0,true,0,"",$showhidden);
        }
      }
    }
    elseif($displaytype=="printview")
    {
      $this->stringvars['sitename']=getproperty("Site Name");
      // get parent chain
      $parentpages=array();
      $level=0;
      $currentpage=$page_id;
      while(!isrootpagearray($currentpage))
      {
        $parent= getparentarray($currentpage);
        array_push($parentpages,$parent);
        $currentpage=$parent;
        $level++;
      }
      // display parent chain
      $navdepth=count($parentpages); // for closing table tags
      for($i=0;$i<$navdepth;$i++)
      {
        $parentpage=array_pop($parentpages);
        $this->listvars['link'][]=new NavigatorBranch($parentpage,"printview","printview","nav",0,false,$i+1,"",$showhidden);
      }
      // display page
      $this->listvars['link'][]=new NavigatorBranch($page_id,"printview","printview","nav",$depth,false,0,"",$showhidden);
    }
    else
    {

      // items of the day
      $homelink=true;
      if(getproperty('Display Picture of the Day'))
      {
        $potd=getpictureoftheday();
        if($potd)
        {
          $this->vars['potd_image']=new Image($potd);
          $homelink=false;
        }
      }
      if(getproperty('Display Article of the Day'))
      {
        $aotd=getarticleoftheday();
        if($aotd)
        {
          $this->stringvars['aotd_link']=getprojectrootlinkpath().'index.php?page='.$aotd;
          if(isset($_GET["sid"])) $this->stringvars['aotd_link'].="&sid=".$_GET["sid"];
          $homelink=false;
        }
      }
      if($homelink)
      {
        $this->stringvars['home_link']=".";
        $this->stringvars['l_home']=getlang("navigator_home");
      }

    
      // navigator
      if($page_id==0 || !pageexists($page_id))
      {
        $roots=getrootpages();
        while(count($roots))
        {
          $currentroot=array_shift($roots);
          if(displaylinksforpagearray($currentroot) || $showhidden)
          {
            $this->listvars['link'][]=new NavigatorBranch($currentroot,"simple","simple","nav",0,true,0,"",$showhidden);
          }
        }
      }
      else
      {

        if(isrootpagearray($page_id))
        {
          $roots=getrootpages();
          $currentroot=array_shift($roots);
          $navposition=getnavpositionarray($page_id);
          // display upper root pages
          while(getnavpositionarray($currentroot)<$navposition)
          {
            if(displaylinksforpagearray($currentroot) || $showhidden)
            {
              $this->listvars['link'][]=new NavigatorBranch($currentroot,"simple","simple","nav",0, true,0,"",$showhidden);
            }
            $currentroot=array_shift($roots);
          }
          // display root page
          $this->listvars['link'][]=new NavigatorBranch($page_id,"simple","simple","nav",$depth, true,0,"",$showhidden);
        }
        else
        {
          // get parent chain
          $parentpages=array();
          $level=0;
          $currentpage=$page_id;
          while(!isrootpagearray($currentpage))
          {
            $parent= getparentarray($currentpage);
            array_push($parentpages,$parent);
            $currentpage=$parent;
            $level++;
          }
          $parentroot=array_pop($parentpages);
          $roots=getrootpages();
          $currentroot=array_shift($roots);
          $parentrootnavposition=getnavpositionarray($parentroot);
          // display upper root pages
          while(getnavpositionarray($currentroot)<$parentrootnavposition)
          {
            if(displaylinksforpagearray($currentroot) || $showhidden)
            {
              $this->listvars['link'][]=new NavigatorBranch($currentroot,"simple","simple","nav",0, true,0,"",$showhidden);
            }
            $currentroot=array_shift($roots);
          }
          if(displaylinksforpagearray($currentroot) || $showhidden)
          {
            $this->listvars['link'][]=new NavigatorBranch($currentroot,"simple","simple","nav",0,true,0,"",$showhidden);
          }

          // display parent chain
          $navdepth=count($parentpages); // for closing table tags
          for($i=0;$i<$navdepth;$i++)
          {
            $parentpage=array_pop($parentpages);
            $this->listvars['link'][]=new NavigatorBranch($parentpage,"simple","simple","nav",0,false,$i+1,"",$showhidden);

          }
          // display page
          if($sistersinnavigator)
          {
            // get sisters then display 1 level only.
            $sisterids=getsisters($page_id);
            $currentsister=array_shift($sisterids);
            $pagenavposition=getnavpositionarray($page_id);
            // display upper sister pages
            while(getnavpositionarray($currentsister)<$pagenavposition)
            {
              if(displaylinksforpagearray($currentsister) || $showhidden)
              {
                $this->listvars['link'][]=new NavigatorBranch($currentsister,"simple","simple","nav",0,false,$level,"",$showhidden);
              }
              $currentsister=array_shift($sisterids);
            }
            // display page
            $this->listvars['link'][]=new NavigatorBranch($page_id,"simple","simple","nav",$depth,false,$level,"",$showhidden);

            // display lower sister pages
            while(count($sisterids))
            {
              $currentsister=array_shift($sisterids);
              if(displaylinksforpagearray($currentsister) || $showhidden)
              {
                $this->listvars['link'][]=new NavigatorBranch($currentsister,"simple","simple","nav",0,false,$level,"",$showhidden);
              }
            }
          }
          else
          {
            $this->listvars['link'][]=new NavigatorBranch($page_id,"simple","simple","nav",$depth,false,0,"",$showhidden);
          }
        }
        // display lower root pages
        while(count($roots))
        {
          $currentroot=array_shift($roots);
          if(displaylinksforpagearray($currentroot) || $showhidden)
          {
            $this->listvars['link'][]=new NavigatorBranch($currentroot,"simple","simple","nav",0,true,0,"",$showhidden);
          }
        }
      }

      // special links
      if(getproperty("Enable Guestbook"))
        $this->listvars['link'][]=new NavigatorBranch(0,"simple","simple","nav",0,true,0,"guestbook",$showhidden);

      $this->listvars['link'][]=new NavigatorBranch(0,"simple","simple","nav",0,true,0,"contact",$showhidden);
      $this->listvars['link'][]=new NavigatorBranch(0,"simple","simple","nav",0,true,0,"sitemap",$showhidden);
    }

    $this->createTemplates();

  }

  // assigns templates
  function createTemplates()
  {
    if($this->displaytype==="splashpage")
      $this->addTemplate("splashpagenavigator.tpl");
    elseif($this->displaytype==="printview")
      $this->addTemplate("printviewnavigator.tpl");
    else
      $this->addTemplate("navigator.tpl");
  }

}

//
// same intro for all pages
//
class PageIntro extends Template {
  function PageIntro($title,$text,$image)
  {
    $this->stringvars['pagetitle']=$title;

    $this->stringvars['text']=text2html($text);

    if(strlen($image)>0)
      $this->stringvars['image']=$image;

    $this->createTemplates();

  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("pageintro.tpl");
  }
}


//
// page header for all pages
// message should be an object of type Message or a string
//
class PageHeader extends Template {

  var $displaytype;

  function PageHeader($page, $title, $displaytype="page")
  {
    $this->displaytype=$displaytype;
    $this->stringvars['keywords']="";
    if($page>0)
    {
      $categories=getcategoriesforpage($page);
      for($i=0;$i<count($categories);$i++)
      {
        $this->stringvars['keywords'].= title2html(getcategoryname($categories[$i])).', ';
      }
    }
    $this->stringvars['keywords'].=title2html(getproperty('Google Keywords'));
    
    $this->stringvars['stylesheet']= getprojectrootlinkpath()."page.css";
    $this->stringvars['sitename']=title2html(getproperty("Site Name"));
    $this->stringvars['browsertitle']=utf8_decode(striptitletags($title));
    $this->stringvars['title']=$title;
    
    if(!$displaytype=="splashpage" || getproperty("Display Site Description on Splash Page"))
      $this->stringvars['site_description']=title2html(getproperty("Site Description"));

    $image=getproperty("Left Header Image");
    if(strlen($image)>0)
      $this->stringvars['left_image']=getprojectrootlinkpath().'img/'.$image;
      
    $image=getproperty("Right Header Image");
    if(strlen($image)>0)
      $this->stringvars['right_image']=getprojectrootlinkpath().'img/'.$image;

    $link=getproperty("Left Header Link");
    if(strlen($link)>0)
      $this->stringvars['left_link']=getprojectrootlinkpath().$link;

    $link=getproperty("Right Header Link");
    if(strlen($link)>0)
      $this->stringvars['right_link']=getprojectrootlinkpath().$link;

    if(ispublicloggedin())
      $this->stringvars['logged_in']="logged in";

    $this->createTemplates();

  }

  // assigns templates
  function createTemplates()
  {
    if($this->displaytype=="splashpage")
      $this->addTemplate("splashpageheader.tpl");
    else
      $this->addTemplate("pageheader.tpl");
  }
}


//
// page footer for all pages
//
class PageFooter extends Template {

  var $displaytype;

  function PageFooter($displaytype="splashpage")
  {
    $this->displaytype=$displaytype;
    if(getproperty("Display Site Policy"))
    {
      $this->stringvars['site_policy_link']=getprojectrootlinkpath().'index.php?page=0&sitepolicy=on"';
      $title=getproperty("Site Policy Title");
      if(strlen($title)>0)
        $this->stringvars['site_policy_title']=title2html($title);
    }

    $this->stringvars['footer_message']=text2html(getproperty("Footer Message"));

    $this->createTemplates();

  }

  // assigns templates
  function createTemplates()
  {
    if($this->displaytype=="splashpage")
      $this->addTemplate("splashpagefooter.tpl");
    else
      $this->addTemplate("pagefooter.tpl");
  }
}


//
// page footer for all pages
//
class Page extends Template {

  var $displaytype;

  function Page($displaytype="page",$showhidden=false)
  {
    global $_SERVER, $_GET;
    
    $this->displaytype=$displaytype;

    // header
    $page_id=trim($_GET['page']);

    $this->makeheader($page_id, $showrefused, $showhidden);


    if(isset($_SERVER['HTTP_REFERER']) && isreferrerblocked($_SERVER['HTTP_REFERER']))
    {
      // todo: simple header class
      $this->stringvars['header']="<html><head></head><body>";
      $this->stringvars['navigator']="";
      $this->stringvars['banners']="";
      $this->vars['message']=new Message("Sorry, this link to our page was not authorized.");
      $this->stringvars['contents']="";
    }
    else
    {
      $showrefused=showpermissionrefusedimages($page_id);
      $pagetype=getpagetype($page_id);
      
      if(!$showhidden)
      {
        if(ispagerestrictedarray($page_id))
        {
          checkpublicsession($page_id);
        }

        updatepagestats($page_id);
      }
      

      // contents
      $this->makecontents($page_id, $pagetype, $showrefused, $showhidden);
      
      // banners
      if(getproperty('Display Banners'))
      {
        $this->vars['banners']=new BannerList();
      }
      else
      {
        $this->stringvars['banners']="";
      }

      // navigator
      if($pagetype==="menu" || $pagetype==="articlemenu" || $pagetype=="linklistmenu")
      {
        $displaysisters=getsisters($page_id);
        $navigatordepth=getmenunavigatordepth($page_id);
      }
      else
      {
        $displaysisters=1;
        $navigatordepth=2;
      }

      $this->vars['navigator'] = new Navigator($page_id,$displaysisters,$navigatordepth-1,$displaytype,$showhidden);
    }

    // footer
    $this->vars['footer']= new PageFooter();

    $this->createTemplates();
    
//    print_r($this->templates);
    
//    print_r($this->vars);

  }
  
  //
  //
  //
  function makeheader($page_id, $showrefused, $showhidden)
  {
    global $_GET;
    $title="";
    if(!$showhidden)
    {
      if(ispagerestrictedarray($page_id))
      {
        checkpublicsession($page_id);
      }
      if(ispublished($page_id))
      {
        $title=$this->getmaintitle($page_id);
      }
      elseif(isset($_GET["sitepolicy"]))
      {
        $title=getproperty("Site Policy Title");
      }
      elseif(isset($_GET["sitemap"]))
      {
        $title=getlang("pagetitle_sitemap");
      }
      else
      {
        $title="Page not found";
      }
    }
    else
    {
      if(@strlen($page_id<1) || $page_id<0)
      {
        $title ="Welcome to the webpage editing panel";
      }
      else
      {
        $title="Displaying ".getpagetype($page_id)." page#".$page_id." - ".$this->getmaintitle($page_id);

        if(getpagetype($page_id)==="external")
        {
          $url=getexternallink($page_id);
        }
        else
        {
          $url=getprojectrootlinkpath()."index.php".makelinkparameters($_GET);
        }
        $messagetext='<span class="highlight">Please link to this URL only in internal discussion!</span> ';
        $messagetext.='<span class="gen">Public link to this page: <a href="'.$url.'" target="_blank">'.title2html(getnavtitle($page_id)).'</a></span>';

        if(ispagerestrictedarray($page_id))
        {
          $messagetext.='<br /><span class="highlight">Restricted page';
          if($showrefused)
          {
            $messagetext.=' - Images with permission refused are shown';
          }
          $messagetext.="</span>";
        }
        $this->stringvars['message'] = $messagetext;
      }
    }
    if($this->displaytype==="splashpage")
      $this->vars['header'] = new PageHeader($page_id, title2html($title),true);
    else
      $this->vars['header'] = new PageHeader($page_id, title2html($title),false);
  }
  
  //
  //
  //
  function makecontents($page_id, $pagetype, $showrefused, $showhidden)
  {
    global $_GET, $offset, $projectroot;
    
    // init
    if(isset($_GET['articlepage']))
    {
      $articlepage=$_GET['articlepage'];
    }
    elseif(isset($_GET['offset']))
    {
      $articlepage=$_GET['offset']+1;
    }
    elseif(!isset($_GET['articlepage']) || @strlen($_GET['articlepage'])<1)
    {
      $articlepage=1;
    }
    else $articlepage=0;
    
    if($this->displaytype=="splashpage")
    {
      $contents="";
      if(getproperty("Splash Page Font")==="italic") $contents.='<i>';
      elseif(getproperty("Splash Page Font")==="bold") $contents.='<b>';
      $text= getproperty("Splash Page Text 1 - 1");
      if(strlen($text)>0)
      {
        $contents.='<p>'.text2html($text.getproperty("Splash Page Text 1 - 2")).'</p><p>&nbsp;</p>';
      }
      $image=getproperty("Splash Page Image");
      if(strlen($image)>0)
      {
        $contents.='<p><img src="'.getprojectrootlinkpath().'img/'.$image.'" border="0" /></p><p>&nbsp;</p>';
      }
      $text= getproperty("Splash Page Text 2 - 1");
      if(strlen($text)>0)
      {
        $contents.='<p>'.text2html($text.getproperty("Splash Page Text 2 - 2")).'</p>';
      }
      if(getproperty("Splash Page Font")==="italic") $contents.='</i>';
      elseif(getproperty("Splash Page Font")==="bold") $contents.='</b>';
      $this->stringvars['contents']=$contents;
      
      // bottom links
      $this->listvars['bottomlink'][]=new NavigatorBranch(0,"splashpage","splashpage","navtitle",0,true,0,"sitemap",$showhidden);
      if(getproperty("Enable Guestbook"))
      	$this->listvars['bottomlink'][]=new NavigatorBranch(0,"splashpage","splashpage","navtitle",0,true,0,"guestbook",$showhidden);
      $this->listvars['bottomlink'][]=new NavigatorBranch(0,"splashpage","splashpage","navtitle",0,true,0,"contact",$showhidden);
      
    }

    // rerout to guide for webpage editors
    elseif($showhidden && @strlen($page_id<1) || $page_id<0)
    {
      $messagetext='<table border="0" cellpadding="10" cellspacing="0" width="100%">';
      $messagetext.='<tr><td><p class="gen">Please check the <a href="http://www.noclockthing.de/minicms" target="_blank">';
      $messagetext.='Guide</a> to find your way around.</p>';
      $messagetext.='<p class="gen">This site needs JavaScript, but no cookies.</p>';
      $messagetext.='<p class="highlight">Since login sessions can always be lost,';
      $messagetext.=' it can\'t hurt to copy the texts you\'re editing to your computer\'s clipboard';
      $messagetext.=' before pressing any buttons.</p>';
      $messagetext.='<p class="gen">Please stay away from the Technical Setup in the Administration section, unless you know what you\'re doing ;)</p>';
      $messagetext.='<p class="gen">Please log out when you leave</p></td></tr></table>';
      $this->stringvars['contents']=$messagetext;
    }
    // create page content
    else
    {
      if($showhidden || ispublished($page_id))
      {
        if(isset($_GET['offset'])) $offset=$_GET['offset'];
        else $offset=0;

        if($pagetype==="article")
        {
          include_once($projectroot."includes/templates/articlepage.php");
          $this->vars['contents'] = new ArticlePage($page_id,$articlepage,$showrefused,$showhidden);
        }
        elseif($pagetype==="menu" || $pagetype==="articlemenu" || $pagetype=="linklistmenu")
        {
          include_once($projectroot."includes/templates/menupage.php");
          $this->vars['contents'] = new MenuPage($page_id,$showrefused,$showhidden);
        }
        elseif($pagetype==="external")
        {
          $this->stringvars['contents'] ='<a href="'.getexternallink($page_id).'" target="_blank">External page</a>';
        }
        elseif($pagetype==="gallery")
        {
          include_once($projectroot."includes/templates/gallerypage.php");
          $this->vars['contents'] = new GalleryPage($page_id,$offset,$showrefused,$showhidden);
        }
        elseif($pagetype==="linklist")
        {
          include_once($projectroot."includes/templates/linklistpage.php");
          $this->vars['contents']  = new LinklistPage($page_id,$offset,$showrefused,$showhidden);
        }
        elseif($pagetype==="news")
        {
          include_once($projectroot."includes/templates/newspage.php");
          $this->vars['contents']  = new NewsPage($page_id,$offset,$showrefused,$showhidden);
        }
      }
      elseif(isset($_GET["sitepolicy"]))
      {
        $this->vars['contents']  = new PageIntro(title2html(getproperty("Site Policy Title")),getdbelement("sitepolicytext",SITEPOLICY_TABLE,"policy_id",0),"");
      }
      elseif(isset($_GET["sitemap"]))
      {
        include_once($projectroot."includes/templates/sitemap.php");
        $this->vars['contents']  = new Sitemap($showhidden);
      }
      else
      {
        $this->vars['contents']  = new PageIntro("Page not found","Could not find page ".$page_id.".","");
      }
    }
  }

  //
  //
  //
  function getmaintitle($page)
  {
    $result="";
    $pagetype=getpagetype($page);
    if($pagetype=="article")
    {
      $parent=getparent($page);
      if(getpagetype($parent))
      {
        $result=getnavtitle($parent);
      }
      else
      {
        $result=getnavtitle($page);
      }
    }
    else
    {
      $result=getnavtitle($page);
    }
    return $result;
  }


  // assigns templates
  function createTemplates()
  {
    if($this->displaytype=="splashpage")
      $this->addTemplate("splashpage.tpl");
    else
      $this->addTemplate("page.tpl");
  }
}




//
// page footer for all pages
//
class Printview extends Template {

  function Printview()
  {
    global $_SERVER, $_GET;

    // header
    $page_id=$_GET['page'];

    $this->makeheader($page_id);

    if(isset($_SERVER['HTTP_REFERER']) && isreferrerblocked($_SERVER['HTTP_REFERER']))
    {
      // todo: simple header class
      $this->stringvars['header']="<html><head></head><body>";
      $this->stringvars['navigator']="";
      $this->stringvars['banners']="";
      $this->vars['message']=new Message("Sorry, this link to our page was not authorized.");
      $this->stringvars['contents']="";
    }
    else
    {
      $showrefused=showpermissionrefusedimages($page_id);
      $pagetype=getpagetype($page_id);

      if(!$showhidden)
      {
        if(ispagerestrictedarray($page_id))
        {
          checkpublicsession($page_id);
        }
      }

      // contents
      $this->makecontents($page_id, $pagetype, $showrefused, $showhidden);

      // navigator
      $this->vars['navigator'] = new Navigator($page_id,0,0,"printview",false);
    }
    
    $this->stringvars['url']=getprojectrootlinkpath().$_SERVER['PHP_SELF']."?page=".$page_id;

    // footer
    $this->vars['footer']= new HTMLFooter();

    $this->createTemplates();

//    print_r($this->templates);

//    print_r($this->vars);

  }

  //
  //
  //
  function makeheader($page_id)
  {
    $title="";
    if(ispagerestrictedarray($page_id))
    {
      checkpublicsession($page_id);
    }
    if(ispublished($page_id))
    {
      $title=$this->getmaintitle($page_id);
    }
    else
    {
      $title="Page not found";
    }
    $this->vars['header'] = new HTMLHeader("",$title,"","","",false,"templates/printview.css");
  }

  //
  //
  //
  function makecontents($page_id, $pagetype, $showrefused)
  {
    global $projectroot,$_GET        ;

    if(ispublished($page_id))
    {

      if($pagetype==="article")
      {

        include_once($projectroot."includes/templates/articlepage.php");
        $this->vars['contents'] = new ArticlePagePrintview($page_id);
      }
      //todo
      elseif($pagetype==="linklist")
      {
        include_once($projectroot."includes/templates/linklistpage.php");
        $this->vars['contents']  = new LinklistPagePrintview($page_id);
      }
      elseif($pagetype==="news")
      {
        include_once($projectroot."includes/templates/newspage.php");
        $this->vars['contents']  = new Newsitem($_GET['newsitem'],$page,0,$showrefused,false,false);
      }
    }
    else
    {
      $this->vars['contents']  = new PageIntro("Page not found","Could not find page ".$page_id.".","");
    }
  }

  //
  //
  //
  function getmaintitle($page)
  {
    $result="";
    $pagetype=getpagetype($page);
    if($pagetype=="article")
    {
      $parent=getparent($page);
      if(getpagetype($parent))
      {
        $result=getnavtitle($parent);
      }
      else
      {
        $result=getnavtitle($page);
      }
    }
    else
    {
      $result=getnavtitle($page);
    }
    return $result;
  }


  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("printview.tpl");
  }
}

?>
