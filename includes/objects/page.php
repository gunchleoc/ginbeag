<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));

include_once($projectroot."functions/pagecontent/linklistpages.php");
include_once($projectroot."functions/pagecontent/externalpages.php");
include_once($projectroot."functions/pagecontent/menupages.php");
include_once($projectroot."functions/pages.php");
include_once($projectroot."functions/referrers.php");
include_once($projectroot."functions/banners.php");
include_once($projectroot."functions/treefunctions.php");
include_once($projectroot."includes/objects/template.php");
include_once($projectroot."includes/includes.php");

include_once($projectroot."includes/objects/forms.php");
include_once($projectroot."includes/objects/elements.php");
include_once($projectroot."includes/objects/categories.php");


//
// container for editdata
//
class Editdata extends Template {
    // vars that are simple strings
    var $stringvars=array();


    function Editdata($showhidden=false)
    {
    	parent::__construct();
    	
      $editdate= geteditdate($this->stringvars['page']);
      $editor=  getusername(getpageeditor($this->stringvars['page']));
      $permissions=getcopyright($this->stringvars['page']);
      if($showhidden)
      {
      	$this->stringvars['footerlastedited']=sprintf(getlang("footer_lasteditedauthor"),formatdatetime($editdate),$editor);
      }
      else
      {
		$this->stringvars['footerlastedited']=sprintf(getlang("footer_lastedited"),formatdatetime($editdate));
      }
      $this->stringvars['copyright']=makecopyright($permissions);
      
      $this->stringvars['topofthispage']=getlang("pagemenu_topofthispage");
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

    function Banner($banner,$showheader=false) {
    
    	parent::__construct();

      $contents=getbannercontents($banner);
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
    	parent::__construct();

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
    	parent::__construct();

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
      $this->vars['categorylist']=new Categorylist(getcategoriesforpage($this->stringvars['page']),false);
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

    function ShortLink($link) {
       parent::__construct();

      $contents=getlinkcontents($link);
      
      if(strlen($contents['link'])<=1)
      {
      	$this->stringvars['link']="?sid=".$this->stringvars['sid']."&page=".$this->stringvars['page'];
      }
      else
      {
      	$this->stringvars['link']=$contents['link'];   	
      }
      $this->stringvars['title']=title2html($contents['title']);
      
      $text=text2html($contents['description']);
      $paragraphs=explode ('<br />', $text);
      $text=$paragraphs[0];

      if (array_key_exists(1, $paragraphs)) $text.=' <a href="?sid='.$this->stringvars['sid'].'&page='.$this->stringvars['page'].'">[...]</a>';

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

    function ShortLinkList($linkids) {
    
    parent::__construct();

      for($i=0;$i<count($linkids);$i++)
      {
        $this->listvars['link'][]=new ShortLink($linkids[$i]);
      }
    }

    // assigns templates
    function createTemplates()
    {
      $this->addTemplate("navigatorbulletbranch.tpl");
    }
}



//
// Templating for Navigator
// todo remove parameter $isroot
//
class NavigatorLink extends Template {

    var $style="";

    function NavigatorLink($page,$style="simple",$linktype="contentnavlink", $level=0, $class="navtitle", $isroot=false, $speciallink="" ,$showhidden=false) {

      global $_GET;
      $this->style=$style;
      if(isset($_GET['sid'])) $sid=$_GET['sid'];
      else $sid="";
      
      $linkparams="?sid=".$sid;
      if(isset($_GET['m'])) $linkparams.="&m=on";

      // layout parameters
      $this->stringvars['link_class']=$class;
      $this->stringvars['title_class']="";


      // for special pages like, contact, guestbook etc
      if($page==0)
      {
        if($speciallink==="guestbook")
        {
        	$this->stringvars['linktooltip']=getlang("navigator_guestbook");
          $this->stringvars['title']=getlang("navigator_guestbook");
          $this->stringvars['link']=getprojectrootlinkpath()."guestbook.php".$linkparams;
          $this->stringvars['link_attributes']='';
          $this->stringvars['description']="";
          if(basename($_SERVER['PHP_SELF'])==="guestbook.php")
          {
            $this->stringvars['title_class']="navhighlight";
          }
        }
        elseif($speciallink==="contact")
        {
        	$this->stringvars['linktooltip']=getlang("navigator_contact");
          $this->stringvars['title']=getlang("navigator_contact");
          $this->stringvars['link']=getprojectrootlinkpath()."contact.php".$linkparams;
          $this->stringvars['link_attributes']='';
          $this->stringvars['description']="";
          if(basename($_SERVER['PHP_SELF'])==="contact.php")
          {
            $this->stringvars['title_class']="navhighlight";
          }
        }
        elseif($speciallink==="sitemap")
        {
            $this->stringvars['linktooltip']=getlang("navigator_sitemap");
          $this->stringvars['title']=getlang("navigator_sitemap");
          $this->stringvars['link']=getprojectrootlinkpath()."index.php".$linkparams."&page=0&sitemap=on";
          $this->stringvars['link_attributes']='';
          $this->stringvars['description']="";
          if(isset($_GET['sitemap']))
          {
            $this->stringvars['title_class']="navhighlight";
          }
        }
        elseif($speciallink==="home")
        {
          $this->stringvars['linktooltip']=getlang("navigator_home");
          $this->stringvars['title']=getlang("navigator_home");
          $this->stringvars['link']=getprojectrootlinkpath().$linkparams;
          $this->stringvars['link_attributes']='';
          $this->stringvars['description']="";
        }
        else
        {
        	$this->stringvars['linktooltip']=getlang("navigator_notfound");
          $this->stringvars['title']=getlang("navigator_notfound");
          $this->stringvars['link']=$linkparams;
          $this->stringvars['link_class']=$class;
          $this->stringvars['link_attributes']='';
          $this->stringvars['description']="";
        }
      }
      // for normal pages
      else
      {
//        print('<p class="gen">link '.$page_id.$style.'</p>');
        $this->pagetype=getpagetypearray($page);

        if($linktype=="contentnavlink")
        {
          $this->stringvars['title']=title2html(getnavtitlearray($page));
        }
        elseif($linktype=="navigatorchainlink")
        {
          $this->stringvars['title']="> ".title2html(getnavtitlearray($page));
        }
        else
        {
          $this->stringvars['title']=title2html(getpagetitlearray($page));
        }
        $this->stringvars['linktooltip']=striptitletags(getpagetitlearray($page));
        
        if($showhidden)
        {
          if(isthisexactpagerestricted($page)) $this->stringvars['title']=$this->stringvars['title'].' (R)';
          if(!ispublished($page)) $this->stringvars['title']='<i>'.$this->stringvars['title'].'</i>';
        }

        if($this->pagetype==="external")
        {
          $this->stringvars['link']=getexternallink($page);
          if(str_startswith($this->stringvars['link'], getprojectrootlinkpath())
          	 || str_startswith($this->stringvars['link'], "?")
          	  || str_startswith($this->stringvars['link'], "index.php"))
          {
          	$this->stringvars['link_attributes']='';
          }
          else
          {
          	$this->stringvars['link_attributes']=' target="_blank"';
          }
          $this->stringvars['description']="";
        }
        else
        {
          if($this->pagetype==="article" && $linktype!=="contentnavlink")
          {
            $this->vars['description']=new ArticleInfo($page);
          }
          elseif($this->pagetype==="linklist" && $linktype!=="contentnavlink")
          {
            $linkids=getlinklistitems($page);
            if(count($linkids)>0)
            {
              $this->vars['description']=new ShortLinkList($linkids);
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
          $this->stringvars['link']=$path.$linkparams.'&page='.$page;
          $this->stringvars['link_attributes']="";
        }

        if(isset($_GET['page']) && $_GET['page']==$page)
        {
          $this->stringvars['title_class']="navhighlight";
        }
        else
        {
          $this->stringvars['title_class']="";
        }
      } 
      
      //$this->stringvars['title']= "XXXXXXXXXXX test".$level." ------ ".$this->stringvars['title'];
      $this->createTemplates();
    }

    // assigns templates
    function createTemplates()
    {
      if($this->style=="bullet")
        $this->addTemplate("navigatorbulletlink.tpl");
      elseif($this->style=="splashpage")
        $this->addTemplate("navigatorlinksplashpage.tpl");
      elseif($this->style=="mobile")
        $this->addTemplate("mobile/navigatorlink.tpl");
      elseif($this->style=="printview")
        $this->addTemplate("navigatorlinkprintview.tpl");
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

    function NavigatorBranch($page,$style="simple",$substyle="simple",$linktype="contentnavlink",$depth,$startwithroot=false,$level=0,$speciallink="",$showhidden=false)
    {
    	global $_GET;
    	if(isset($_GET['sid'])) $sid=$_GET['sid'];
    	else $sid="";
    	
        $this->style=$style;
        //parent::__construct();
        if($startwithroot && $level==0)
        {
          $class="navtitle";
          $this->stringvars['wrapper_class'] = "navrootlinkwrapper";
        }
        else
        {
          $class="navlink";
          $this->stringvars['wrapper_class'] = "navlinkwrapper";
        }
        
        $isroot=false;
        if(isrootpagearray($page)  || ($style=="simple" && $substyle=="bullet"))
          $isroot=true;
          
        if(hasaccesssession($sid, $page) || $showhidden)
        {
        	$this->listvars['link'][]= new NavigatorLink($page, $style, $linktype, $level, $class,$isroot,$speciallink, $showhidden);
        }

        $this->stringvars['margin_left']=$level;

        if($depth>0)
        {
          $pages=getchildrenarray($page);
          for($i=0;$i<count($pages);$i++)
          {
            if(displaylinksforpagearray($sid,$pages[$i]) || $showhidden)
            {
				$this->listvars['link'][]= new NavigatorBranch($pages[$i],$substyle,$substyle,$linktype, $depth-1, $startwithroot, $level+1,$speciallink, $showhidden);
            }
          }
        }
        $this->createTemplates();
    }

    // assigns templates
    function createTemplates()
    {
      if($this->style=="bullet")
        $this->addTemplate("navigatorbulletbranch.tpl");
      elseif($this->style=="splashpage")
        $this->addTemplate("navigatorbranchsplashpage.tpl");
      elseif($this->style=="mobile")
        $this->addTemplate("mobile/navigatorbranch.tpl");
      elseif($this->style=="printview")
        $this->addTemplate("navigatorbranchprintview.tpl");
      else
        $this->addTemplate("navigatorbranch.tpl");
    }
}



//
// Templating for Navigator
// iterate over branch and create links
//
class ContentNavigatorBranch extends Template {

    var $style="";
    
    function ContentNavigatorBranch($page,$style="simple",$substyle="simple",$linktype="contentnavlink",$depth,$startwithroot=false,$level=0,$speciallink="",$showhidden=false)
    {
    	global $_GET;
    	
    	//parent::__construct();
    	
    	if(isset($_GET['sid'])) $sid=$_GET['sid'];
    	else $sid="";
    	
        $this->style=$style;

        if($startwithroot && $level==0)
        {
          $class="contentnavtitle";
          $this->stringvars['wrapper_class'] = "contentnavrootlinkwrapper";
        }
        else
        {
          $class="contentnavlink";
          $this->stringvars['wrapper_class'] = "contentnavlinkwrapper";
        }
        
        $isroot=false;
        if(isrootpagearray($page)  || ($style=="simple" && $substyle=="bullet"))
          $isroot=true;
          
        if(hasaccesssession($sid, $page) || $showhidden)
        {
        	$this->listvars['link'][]= new NavigatorLink($page, $style, $linktype, $level, $class,$isroot,$speciallink, $showhidden);
        }

        $this->stringvars['margin_left']=$level;

        if($depth>0)
        {
          $pages=getchildrenarray($page);
          for($i=0;$i<count($pages);$i++)
          {
            if(displaylinksforpagearray($sid,$pages[$i]) || $showhidden)
            {
           		$this->listvars['link'][]= new ContentNavigatorBranch($pages[$i],$substyle,$substyle,$linktype, $depth-1, $startwithroot, $level+1,$speciallink, $showhidden);
            }
          }
        }
      $this->createTemplates();
    }

    // assigns templates
    function createTemplates()
    {
      if($this->style=="bullet")
        $this->addTemplate("navigatorbulletbranch.tpl");
      elseif($this->style=="printview")
        $this->addTemplate("navigatorbranchprintview.tpl");
      else
        $this->addTemplate("navigatorbranch.tpl");
    }
}






//
// Templating for Navigator
// todo remove global GET?
//
class Navigator extends Template {

	var $displaytype;

	function Navigator($page,$sistersinnavigator,$depth,$displaytype="page",$showhidden=false) {

	    global $_GET;
	    //parent::__construct();
	    
		if(isset($_GET['sid'])) $sid=$_GET['sid'];
		else $sid="";

	    $this->displaytype=$displaytype;
		
		$linkparams="?sid=".$sid;
		if(isset($_GET['m']))
		{
			$linkparams.="&m=on";
			$this->displaytype="mobile";
		}
	    
	    
	    if($displaytype=="splashpage" || $displaytype=="splashpagemobile")
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
				if(displaylinksforpagearray($sid, $currentroot) || $showhidden)
				{
					$this->listvars['link'][]=new NavigatorBranch($currentroot,"splashpage","splashpage","contentnavlink",0,true,0,"",$showhidden);
				}
			}
		}
		elseif($displaytype=="printview")
		{
			$this->stringvars['sitename']=title2html(getproperty("Site Name"));
			$this->stringvars['home_link']=getprojectrootlinkpath().'index.php'.$linkparams;

			// get parent chain
			$parentpages=array();
			$level=0;
			$currentpage=$page;
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
				$this->listvars['link'][]=new NavigatorBranch($parentpage,"printview","printview","contentnavlink",0,false,$i+1,"",$showhidden);
			}
			// display page
			$this->listvars['link'][]=new NavigatorBranch($page,"printview","printview","contentnavlink",$depth,false,0,"",$showhidden);
		}
	    else
	    {
	    	if($displaytype=="mobile")
	    	{
	    		$style="mobile";
	      	}
	      	else
	      	{
	      		$style="simple";
	      	}
	
			// items of the day
			$homelink=true;
			if(getproperty('Display Picture of the Day'))
			{
				$potd=getpictureoftheday();
				if($potd)
				{
					$this->vars['potd_image']=new Image($potd);
					$this->stringvars['l_potd']=getlang("navigator_potd");
					$homelink=false;
				}
			}
			if(getproperty('Display Article of the Day'))
			{
				$aotd=getarticleoftheday();
				if($aotd)
				{
					$this->stringvars['aotd_link']=getprojectrootlinkpath().'index.php?page='.$aotd."&sid=".$this->stringvars['sid'];
					$this->stringvars['l_aotd']=getlang("navigator_aotd");
					$homelink=false;
				}
			}
			if($homelink)
			{
				$this->stringvars['home_link']=getprojectrootlinkpath().'index.php'.$linkparams;
				$this->stringvars['l_home']=getlang("navigator_home");
			}
			
			
			// mobile navigator
			if($displaytype=="mobile")
	    	{
				if($page!=0 && pageexists($page))
				{
					// get parent chain
					$parentpages=array();
					$level=0;
					$currentpage=$page;
					array_push($parentpages,$page);
					while(!isrootpagearray($currentpage))
					{
						$parent= getparentarray($currentpage);
						array_push($parentpages,$parent);
						$currentpage=$parent;
						$level++;
					}
					
					// display parent chain
					$navdepth=count($parentpages); // for closing table tags
					//$this->stringvars['chainlink'] =count($parentpages);
					for($i=0;$i<$navdepth;$i++)
					{
						$parentpage=array_pop($parentpages);
						$this->listvars['chainlink'][]=new NavigatorLink($parentpage,"mobile","navigatorchainlink",$navdepth, "navlink", false, "" ,$showhidden);
						//$this->listvars['chainlink'][]=new NavigatorBranch($parentpage,$style,$style,"contentnavlink",0,false,$level,"",$showhidden);
					}

					// display sisters for non-root pages
					if(!isrootpagearray($page))
					{
						$sisterids=getsisters($page);
						while(count($sisterids))
						{
							$currentsister=array_shift($sisterids);
							if(displaylinksforpagearray($sid, $currentsister) || $showhidden)
							{
								$this->listvars['sisterlink'][]=new NavigatorBranch($currentsister,"mobile","mobile","contentnavlink",0,false,$level,"",$showhidden);
							}
						}
					}

				}
				

	    	}
			// navigator
	    	else
	    	{
				if($page==0 || !pageexists($page))
				{
					$roots=getrootpages();
					while(count($roots))
					{
						$currentroot=array_shift($roots);
						if(displaylinksforpagearray($sid,$currentroot) || $showhidden)
						{
							$this->listvars['link'][]=new NavigatorBranch($currentroot,$style,$style,"contentnavlink",0,true,0,"",$showhidden);
						}
					}
				}
				else
				{
				
					if(isrootpagearray($page))
					{
						$roots=getrootpages();
						$currentroot=array_shift($roots);
						$navposition=getnavpositionarray($page);
						// display upper root pages
						while(getnavpositionarray($currentroot)<$navposition)
						{
							if(displaylinksforpagearray($sid,$currentroot) || $showhidden)
							{
								$this->listvars['link'][]=new NavigatorBranch($currentroot,$style,$style,"contentnavlink",0, true,0,"",$showhidden);
							}
							$currentroot=array_shift($roots);
						}
						// display root page
						$this->listvars['link'][]=new NavigatorBranch($page,$style,$style,"contentnavlink",$depth, true,0,"",$showhidden);
					}
					else
					{
						// get parent chain
						$parentpages=array();
						$level=0;
						$currentpage=$page;
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
							if(displaylinksforpagearray($sid,$currentroot) || $showhidden)
							{
								$this->listvars['link'][]=new NavigatorBranch($currentroot,$style,$style,"contentnavlink",0, true,0,"",$showhidden);
							}
							$currentroot=array_shift($roots);
						}
						if(displaylinksforpagearray($sid,$currentroot) || $showhidden)
						{
							$this->listvars['link'][]=new NavigatorBranch($currentroot,$style,$style,"contentnavlink",0,true,0,"",$showhidden);
						}
	
						// display parent chain
						$navdepth=count($parentpages); // for closing table tags
						for($i=0;$i<$navdepth;$i++)
						{
							$parentpage=array_pop($parentpages);
							$this->listvars['link'][]=new NavigatorBranch($parentpage,$style,$style,"contentnavlink",0,false,$i+1,"",$showhidden);
						}
						// display page
						if($sistersinnavigator)
						{
							// get sisters then display 1 level only.
							$sisterids=getsisters($page);
							$currentsister=array_shift($sisterids);
							$pagenavposition=getnavpositionarray($page);
							// display upper sister pages
							while(getnavpositionarray($currentsister)<$pagenavposition)
							{
								if(displaylinksforpagearray($sid,$currentsister) || $showhidden)
								{
									$this->listvars['link'][]=new NavigatorBranch($currentsister,$style,$style,"contentnavlink",0,false,$level,"",$showhidden);
								}
								$currentsister=array_shift($sisterids);
							}
							// display page
							$this->listvars['link'][]=new NavigatorBranch($page,$style,$style,"contentnavlink",$depth,false,$level,"",$showhidden);
							
							// display lower sister pages
							while(count($sisterids))
							{
								$currentsister=array_shift($sisterids);
								if(displaylinksforpagearray($sid,$currentsister) || $showhidden)
								{
									$this->listvars['link'][]=new NavigatorBranch($currentsister,$style,$style,"contentnavlink",0,false,$level,"",$showhidden);
								}
							}
						}
						else
						{
							$this->listvars['link'][]=new NavigatorBranch($page,$style,$style,"contentnavlink",$depth,false,0,"",$showhidden);
						}
					}
					// display lower root pages
					while(count($roots))
					{
						$currentroot=array_shift($roots);
						if(displaylinksforpagearray($sid,$currentroot) || $showhidden)
						{
							$this->listvars['link'][]=new NavigatorBranch($currentroot,$style,$style,"contentnavlink",0,true,0,"",$showhidden);
						}
					}
				}
				// special links
				if(getproperty("Enable Guestbook"))
					$this->listvars['link'][]=new NavigatorBranch(0,$style,$style,"contentnavlink",0,true,0,"guestbook",$showhidden);
				
				$this->listvars['link'][]=new NavigatorBranch(0,$style,$style,"contentnavlink",0,true,0,"contact",$showhidden);
				$this->listvars['link'][]=new NavigatorBranch(0,$style,$style,"contentnavlink",0,true,0,"sitemap",$showhidden);
			}
		}
		$this->createTemplates();
	}
  
	// assigns templates
	function createTemplates()
	{
		if($this->displaytype==="splashpage")
		  $this->addTemplate("navigatorsplashpage.tpl");
		elseif($this->displaytype==="splashpagemobile")
		  $this->addTemplate("mobile/navigatorsplashpage.tpl");
		elseif($this->displaytype==="printview")
		  $this->addTemplate("navigatorprintview.tpl");
		elseif($this->displaytype==="mobile")
		  $this->addTemplate("mobile/navigator.tpl");
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
  	parent::__construct();
    $this->stringvars['pagetitle']=$title;

    $this->stringvars['text']=text2html($text);

    if(strlen($image)>0)
      $this->stringvars['image']=$image;
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
  	parent::__construct();
  	
  	$this->stringvars['logoutlink']="?sid=".$this->stringvars['sid']."&page=".$this->stringvars['page']."logout=on";
    
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
    
    $this->stringvars['stylesheet']= getCSSPath("main.css");
    $this->stringvars['sitename']=title2html(getproperty("Site Name"));
    $this->stringvars['browsertitle']=striptitletags($title);
    $this->stringvars['title']=title2html($title);
    
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
  }

  // assigns templates
  function createTemplates()
  {
    if($this->displaytype=="splashpage" || $this->displaytype=="splashpagemobile")
      $this->addTemplate("splashpageheader.tpl");
    elseif($this->displaytype=="mobile")
      $this->addTemplate("mobile/pageheader.tpl");
    else
      $this->addTemplate("pageheader.tpl");
  }
}


//
// page footer for all pages
//
class PageFooter extends Template {

  var $displaytype;

  function PageFooter()
  {
  	parent::__construct();
    if(getproperty("Display Site Policy"))
    {
      $this->stringvars['site_policy_link']=getprojectrootlinkpath().'index.php?page=0&sitepolicy=on';
      $title=getproperty("Site Policy Title");
      if(strlen($title)>0)
        $this->stringvars['site_policy_title']=title2html($title);
    }

    $this->stringvars['footer_message']=text2html(getproperty("Footer Message"));
  }

  // assigns templates
  function createTemplates()
  {
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
    global $_SERVER, $_GET, $projectroot;
    
    $this->displaytype=$displaytype;
    parent::__construct();
    
    // header
    $showrefused =showpermissionrefusedimages($this->stringvars['page']);

    $this->makeheader($this->stringvars['page'], $showrefused, $showhidden);


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
      $showrefused=showpermissionrefusedimages($this->stringvars['page']);
      $pagetype=getpagetype($this->stringvars['page']);
      
      if(!$showhidden)
      {
        if(ispagerestrictedarray($this->stringvars['page']))
        {
          checkpublicsession($this->stringvars['page']);
        }

        updatepagestats($this->stringvars['page']);
      }
      
      // contents
      	if(isset($_GET['newsitem']))
		{
			include_once($projectroot."includes/objects/newspage.php");
			$this->vars['contents'] = new Newsitempage($_GET['newsitem'],$this->stringvars['page'],0,false);
			//print($newsitem->toHTML()); 
		}
		else
		{
      		$this->makecontents($this->stringvars['page'], $pagetype, $showrefused, $showhidden);
      	}
      
      
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
        $displaysisters=getsisters($this->stringvars['page']);
        $navigatordepth=getmenunavigatordepth($this->stringvars['page']);
      }
      else
      {
        $displaysisters=1;
        $navigatordepth=2;
      }

      $this->vars['navigator'] = new Navigator($this->stringvars['page'],$displaysisters,$navigatordepth-1,$displaytype,$showhidden);
    }
    
    // area labels for screen readers
    $this->stringvars['l_navigator']=getlang("title_navigator");
	$this->stringvars['l_content']=getlang("title_content");

    // footer
    $this->vars['footer']= new PageFooter();
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
      	//todo why encoding problem?
        $title=utf8_decode(getlang("pagetitle_sitemap"));
      }
      else
      {
      	//todo why encoding problem?
        $title=utf8_decode(getlang("error_pagenotfound"));
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

        $this->vars['message'] = new AdminPageDisplayMessage($showrefused);
      }
    }
    $this->vars['header'] = new PageHeader($page_id, $title,$this->displaytype);

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
    if($this->displaytype=="splashpage" || $this->displaytype==="splashpage")
    {
      $contents="";
      if(getproperty("Splash Page Font")==="italic") $contents.='<i>';
      elseif(getproperty("Splash Page Font")==="bold") $contents.='<b>';
      $text= getproperty("Splash Page Text 1 - 1");
      if(strlen($text)>0)
      {
        $contents.='<p>'.$text.getproperty("Splash Page Text 1 - 2").'</p><p>&nbsp;</p>';
      }
      $image=getproperty("Splash Page Image");
      if(strlen($image)>0)
      {
        $contents.='<p><img src="'.getprojectrootlinkpath().'img/'.$image.'" border="0" /></p><p>&nbsp;</p>';
      }
      $text= getproperty("Splash Page Text 2 - 1");
      if(strlen($text)>0)
      {
        $contents.='<p>'.$text.getproperty("Splash Page Text 2 - 2").'</p>';
      }
      if(getproperty("Splash Page Font")==="italic") $contents.='</i>';
      elseif(getproperty("Splash Page Font")==="bold") $contents.='</b>';
      $contents = text2html($contents);
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
      $messagetext.='<p class="gen">This site needs JavaScript for some editing functions and cookies to keep the editing session.</p>';
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
          include_once($projectroot."includes/objects/articlepage.php");
          $this->vars['contents'] = new ArticlePage($articlepage,$showrefused,$showhidden);
        }
        elseif($pagetype==="articlemenu")
        {
          include_once($projectroot."includes/objects/menupage.php");
          $this->vars['contents'] = new ArticleMenuPage($page_id,$showrefused,$showhidden);
        }
        elseif($pagetype==="menu" || $pagetype=="linklistmenu")
        {
          include_once($projectroot."includes/objects/menupage.php");
          $this->vars['contents'] = new MenuPage($page_id,$showrefused,$showhidden);
        }
        elseif($pagetype==="external")
        {
          $this->stringvars['contents'] ='<a href="'.getexternallink($page_id).'" target="_blank">External page</a>';
        }
        elseif($pagetype==="gallery")
        {
          include_once($projectroot."includes/objects/gallerypage.php");
          $this->vars['contents'] = new GalleryPage($offset,$showrefused,$showhidden);
        }
        elseif($pagetype==="linklist")
        {
          include_once($projectroot."includes/objects/linklistpage.php");
          $this->vars['contents']  = new LinklistPage($offset,$showrefused,$showhidden);
        }
        elseif($pagetype==="news")
        {
          include_once($projectroot."includes/objects/newspage.php");
          $this->vars['contents']  = new NewsPage($page_id,$offset,$showrefused,$showhidden);
        }
      }
      elseif(isset($_GET["sitepolicy"]))
      {
        $this->vars['contents']  = new PageIntro(title2html(getproperty("Site Policy Title")),getdbelement("sitepolicytext",SITEPOLICY_TABLE,"policy_id",0),"");
      }
      elseif(isset($_GET["sitemap"]))
      {
        include_once($projectroot."includes/objects/sitemap.php");
        $this->vars['contents']  = new Sitemap($showhidden);
      }
      else
      {
      	// todo why encoding problem?
        $this->vars['contents']  = new PageIntro(getlang("error_pagenotfound"),utf8_decode(sprintf(getlang("error_pagenonotfound"),$page_id)),"");
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
    elseif($this->displaytype=="splashpagemobile")
      $this->addTemplate("splashpage.tpl");
    elseif($this->displaytype=="mobile")
      $this->addTemplate("mobile/page.tpl");
    else
      $this->addTemplate("page.tpl");
  }
}




//
// page footer for all pages
//
class Printview extends Template {

  function Printview($showhidden=false)
  {
    global $_SERVER, $_GET;
    parent::__construct();

    // header
    $this->makeheader($this->stringvars['page']);

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
      $showrefused=showpermissionrefusedimages($this->stringvars['page']);
      $pagetype=getpagetype($this->stringvars['page']);

      if(!$showhidden)
      {
        if(ispagerestrictedarray($this->stringvars['page']))
        {
          checkpublicsession($this->stringvars['page']);
        }
      }

      // contents
      $this->makecontents($this->stringvars['page'], $pagetype, $showrefused, $showhidden);

      // navigator
      $this->vars['navigator'] = new Navigator($this->stringvars['page'],0,0,"printview",false);
    }
    
    $this->stringvars['url']=getprojectrootlinkpath().$_SERVER['PHP_SELF']."?page=".$this->stringvars['page'];

    // footer
    $this->vars['footer']= new HTMLFooter();
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
    // todo why encoding?
    $this->vars['header'] = new HTMLHeader("",striptitletags($title),"","","",false,"printview.css");
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

        include_once($projectroot."includes/objects/articlepage.php");
        $this->vars['contents'] = new ArticlePagePrintview();
      }
      //todo
      elseif($pagetype==="linklist")
      {
        include_once($projectroot."includes/objects/linklistpage.php");
        $this->vars['contents']  = new LinklistPagePrintview();
      }
      elseif($pagetype==="news")
      {
        include_once($projectroot."includes/objects/newspage.php");
        $this->vars['contents']  = new Newsitem($_GET['newsitem'],0,$showrefused,false,false);
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


//
// container for editdata
//
class AdminPageDisplayMessage extends Template {

	function AdminPageDisplayMessage($showrefused)
    {
    	global $_GET;
    	parent::__construct();
    	
    	if(isset($_GET["show"])) unset($_GET["show"]);
    	
    	if(getpagetype($this->stringvars['page'])==="external")
        {
          $this->stringvars['publiclink']=getexternallink($this->stringvars['page']);
        }
        else
        {
          $this->stringvars['publiclink']=getprojectrootlinkpath()."index.php".makelinkparameters($_GET);
        }
        
        $this->stringvars['navtitle']= title2html(getnavtitle($this->stringvars['page']));
        $this->stringvars['editlink']=getprojectrootlinkpath()."admin/pageedit.php".makelinkparameters($_GET).'&sid='.$this->stringvars['sid'].'&page='.$this->stringvars['page'].'&action=edit';
      
        if(ispagerestrictedarray($this->stringvars['page']))
        {
        	$this->stringvars['isrestricted']="true";
          	if($showrefused)
          	{
            	$this->stringvars['showrefused']="true";
          	}
          }
    }

    // assigns templates
    function createTemplates()
    {
      	$this->addTemplate("admin/pagedisplaymessage.tpl");
    }
}


?>