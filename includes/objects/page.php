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
include_once($projectroot."includes/objects/images.php");


//
// container for editdata
//
class Editdata extends Template {

    function Editdata($showhidden=false)
    {
    	parent::__construct();
    	
		$editdate= geteditdate($this->stringvars['page']);
		$editor=  getusername(getpageeditor($this->stringvars['page']));
		$permissions=getcopyright($this->stringvars['page']);
		if($showhidden)
			$this->stringvars['footerlastedited']=sprintf(getlang("footer_lasteditedauthor"),formatdatetime($editdate),$editor);

		else
			$this->stringvars['footerlastedited']=sprintf(getlang("footer_lastedited"),formatdatetime($editdate));

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

    function Banner($banner,$showheader=false)
    {
		global $projectroot;
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
			$dimensions = getimagedimensions($projectroot."img/banners/".$contents['image']);
			$this->stringvars['width'] = $dimensions["width"];
			$this->stringvars['height'] = $dimensions["height"];
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

    function BannerList()
    {
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
class NavigatorLink extends Template {

	var $style="";

	function NavigatorLink($page,$style="simple", $level=0, $speciallink="" ,$showhidden=false)
	{
		global $_GET;
		$this->style=$style;
		if($level==0) $class="navtitle";
	    else $class="navlink";

		// layout parameters
		$this->stringvars['link_class']=$class;
		$this->stringvars['title_class']="";

		parent::__construct();

		$linkparams = array();
		if(isset($_GET['m'])) $linkparams["m"] = "on";
		
		// for special pages like, contact, guestbook etc
		if($page==0)
		{
			if($speciallink==="guestbook")
			{
				$this->stringvars['linktooltip']=getlang("navigator_guestbook");
				$this->stringvars['title']=getlang("navigator_guestbook");
				$this->stringvars['link']=getprojectrootlinkpath()."guestbook.php".makelinkparameters($linkparams);
				$this->stringvars['link_attributes']='';
				if(basename($_SERVER['PHP_SELF'])==="guestbook.php")
				{
					$this->stringvars['title_class']="navhighlight";
				}
			}
			elseif($speciallink==="contact")
			{
				$this->stringvars['linktooltip']=getlang("navigator_contact");
				$this->stringvars['title']=getlang("navigator_contact");
				$this->stringvars['link']=getprojectrootlinkpath()."contact.php".makelinkparameters($linkparams);
				$this->stringvars['link_attributes']='';
				if(basename($_SERVER['PHP_SELF'])==="contact.php")
				{
					$this->stringvars['title_class']="navhighlight";
				}
			}
			elseif($speciallink==="sitemap")
			{
				$this->stringvars['linktooltip']=getlang("navigator_sitemap");
				$this->stringvars['title']=getlang("navigator_sitemap");
				$linkparams["page"] = "0";
				$linkparams["sitemap"] = "on";
				$this->stringvars['link']=getprojectrootlinkpath()."index.php".makelinkparameters($linkparams);
				$this->stringvars['link_attributes']='';
				if(isset($_GET['sitemap']))
				{
					$this->stringvars['title_class']="navhighlight";
				}
			}
			elseif($speciallink==="home")
			{
				$this->stringvars['linktooltip']=getlang("navigator_home");
				$this->stringvars['title']=getlang("navigator_home");
				$this->stringvars['link']=getprojectrootlinkpath().makelinkparameters($linkparams);
				$this->stringvars['link_attributes']='';
			}
			else
			{
				$this->stringvars['linktooltip']=getlang("navigator_notfound");
				$this->stringvars['title']=getlang("navigator_notfound");
				$this->stringvars['link']=$linkparams;
				$this->stringvars['link_class']=$class;
				$this->stringvars['link_attributes']='';
			}
		}
		// for normal pages
		else
		{
			$this->pagetype=getpagetypearray($page);

			$this->stringvars['linktooltip']=striptitletags(getpagetitlearray($page));

			if($this->style=="splashpage") $this->stringvars['title']= title2html(str_replace(" ","&nbsp;",getnavtitlearray($page)));
			else $this->stringvars['title']=title2html(getnavtitlearray($page));
			
			
			
			if(isset($_GET['m']))
			{
				$this->stringvars['title']="&#x25BA; ".$this->stringvars['title'];
			}
			
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
			}
			else
			{
				if($showhidden) $path=getprojectrootlinkpath()."admin/pagedisplay.php";
				else $path=getprojectrootlinkpath()."index.php";
				$linkparams["page"] = $page;
				$this->stringvars['link']=$path.makelinkparameters($linkparams);
				$this->stringvars['link_attributes']="";
			}
			
			if(isset($_GET['page']) && $_GET['page']==$page) $this->stringvars['title_class']="navhighlight";
			else $this->stringvars['title_class']="";
		} 
      
		//$this->stringvars['title']= "XXXXXXXXXXX test".$level." ------ ".$this->stringvars['title'];
	}

    // assigns templates
    function createTemplates()
    {
		if($this->style=="splashpage")
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

    function NavigatorBranch($page,$style="simple",$depth,$level=0,$speciallink="",$showhidden=false)
    {
        $this->style=$style;
        parent::__construct();

        if($level==0) $this->stringvars['wrapper_class'] = "navrootlinkwrapper";
        else $this->stringvars['wrapper_class'] = "navlinkwrapper";
        
        if(hasaccesssession($page) || $showhidden)
        {
        	$this->listvars['link'][]= new NavigatorLink($page, $style, $level,$speciallink, $showhidden);
        }

        $this->stringvars['margin_left']=$level;

        if($depth>0)
        {
			$pages=getchildrenarray($page);
			for($i=0;$i<count($pages);$i++)
			{
				if(displaylinksforpagearray($pages[$i]) || $showhidden)
				{
					$this->listvars['link'][]= new NavigatorBranch($pages[$i],$style,$depth-1, $level+1,$speciallink, $showhidden);
				}
			}
        }
    }

    // assigns templates
    function createTemplates()
    {
		if($this->style=="splashpage")
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
// todo remove global GET?
//
class Navigator extends Template {

	var $displaytype;

	function Navigator($page,$sistersinnavigator,$depth,$displaytype="page",$showhidden=false)
	{
	    global $_GET;
	    
	    $this->displaytype=$displaytype;
	    parent::__construct();

		$linkparams = "";
		if(isset($_GET['m']))
		{
			$linkparams = makelinkparameters(array("m" => "on"));
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
				if(displaylinksforpagearray($currentroot) || $showhidden)
				{
					$this->listvars['link'][]=new NavigatorBranch($currentroot,"splashpage",0,0,"",$showhidden);
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
				$this->listvars['link'][]=new NavigatorBranch($parentpage,"printview",0,$i+1,"",$showhidden);
			}
			// display page
			$this->listvars['link'][]=new NavigatorBranch($page,"printview",$depth,0,"",$showhidden);
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
					$this->vars['potd_image']=new Image($potd, true, true, array(), $showhidden);
					$this->stringvars['l_potd']=getlang("navigator_potd");
					$homelink=false;
				}
			}
			if(getproperty('Display Article of the Day'))
			{
				$aotd=getarticleoftheday();
				if($aotd)
				{
					if($linkparams) $this->stringvars['aotd_link']=getprojectrootlinkpath().'index.php'.$linkparams.'&page='.$aotd;
					else $this->stringvars['aotd_link']=getprojectrootlinkpath().'index.php?page='.$aotd;
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
						$this->listvars['chainlink'][]=new NavigatorLink($parentpage,"mobile",$navdepth, false, "" ,$showhidden);
						//$this->listvars['chainlink'][]=new NavigatorBranch($parentpage,$style,0,$level,"",$showhidden);
					}

					// display sisters for non-root pages
					if(!isrootpagearray($page))
					{
						$sisterids=getsisters($page);
						while(count($sisterids))
						{
							$currentsister=array_shift($sisterids);
							if(displaylinksforpagearray($currentsister) || $showhidden)
							{
								$this->listvars['sisterlink'][]=new NavigatorBranch($currentsister,"mobile",0,$level,"",$showhidden);
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
						if(displaylinksforpagearray($currentroot) || $showhidden)
						{
							$this->listvars['link'][]=new NavigatorBranch($currentroot,$style,0,0,"",$showhidden);
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
							if(displaylinksforpagearray($currentroot) || $showhidden)
							{
								$this->listvars['link'][]=new NavigatorBranch($currentroot,$style,0,0,"",$showhidden);
							}
							$currentroot=array_shift($roots);
						}
						// display root page
						$this->listvars['link'][]=new NavigatorBranch($page,$style,$depth,0,"",$showhidden);
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
							if(displaylinksforpagearray($currentroot) || $showhidden)
							{
								$this->listvars['link'][]=new NavigatorBranch($currentroot,$style,0,0,"",$showhidden);
							}
							$currentroot=array_shift($roots);
						}
						if(displaylinksforpagearray($currentroot) || $showhidden)
						{
							$this->listvars['link'][]=new NavigatorBranch($currentroot,$style,0,0,"",$showhidden);
						}
	
						// display parent chain
						$navdepth=count($parentpages); // for closing table tags
						for($i=0;$i<$navdepth;$i++)
						{
							$parentpage=array_pop($parentpages);
							$this->listvars['link'][]=new NavigatorBranch($parentpage,$style,0,$i+1,"",$showhidden);
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
								if(displaylinksforpagearray($currentsister) || $showhidden)
								{
									$this->listvars['link'][]=new NavigatorBranch($currentsister,$style,0,$level,"",$showhidden);
								}
								$currentsister=array_shift($sisterids);
							}
							// display page
							$this->listvars['link'][]=new NavigatorBranch($page,$style,$depth,$level,"",$showhidden);
							
							// display lower sister pages
							while(count($sisterids))
							{
								$currentsister=array_shift($sisterids);
								if(displaylinksforpagearray($currentsister) || $showhidden)
								{
									$this->listvars['link'][]=new NavigatorBranch($currentsister,$style,0,$level,"",$showhidden);
								}
							}
						}
						else
						{
							$this->listvars['link'][]=new NavigatorBranch($page,$style,$depth,0,"",$showhidden);
						}
					}
					// display lower root pages
					while(count($roots))
					{
						$currentroot=array_shift($roots);
						if(displaylinksforpagearray($currentroot) || $showhidden)
						{
							$this->listvars['link'][]=new NavigatorBranch($currentroot,$style,0,0,"",$showhidden);
						}
					}
				}
				// special links
				if(getproperty("Enable Guestbook"))
					$this->listvars['link'][]=new NavigatorBranch(0,$style,0,0,"guestbook",$showhidden);
				
				$this->listvars['link'][]=new NavigatorBranch(0,$style,0,0,"contact",$showhidden);
				$this->listvars['link'][]=new NavigatorBranch(0,$style,0,0,"sitemap",$showhidden);
			}
		}
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
// intro/synopsis for all pages
//
class PageIntro extends Template {

	function PageIntro($title, $text, $image="", $imageautoshrink=true, $usethumbnail=true, $imagealign="left",$showhidden=false)
	{
		parent::__construct();
		$this->stringvars['pagetitle']=title2html($title);
		$this->stringvars['text']=text2html($text);
		if($image && strlen($image) > 0)
			$this->vars['image'] = new CaptionedImage($image, $imageautoshrink, $usethumbnail, $imagealign, array("page" => $this->stringvars['page']), $showhidden);
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
		global $projectroot;
		$this->displaytype=$displaytype;
		parent::__construct();

		$this->stringvars['logoutlink'] = makelinkparameters(array("page" => $this->stringvars['page'], "logout" => "on"));
		
		$this->stringvars['keywords']="";
		if($page>0)
		{
			$categories=getcategoriesforpage($page);
			for($i=0;$i<count($categories);$i++)
			{
				$this->stringvars['keywords'].= title2html(getcategoryname($categories[$i], CATEGORY_ARTICLE)).', ';
			}
		}
		$this->stringvars['keywords'].=title2html(getproperty('Google Keywords'));
		
		$this->stringvars['stylesheet']= getCSSPath("main.css");
		$this->stringvars['sitename']=title2html(getproperty("Site Name"));
		$this->stringvars['browsertitle']=striptitletags($title);
		$this->stringvars['title']=title2html($title);
		
		if($displaytype!="splashpage")
			$this->stringvars['site_description']=title2html(getproperty("Site Description"));
		elseif(getproperty("Display Site Description on Splash Page"))
			$this->stringvars['site_description']=title2html(getproperty("Site Description"));
		
		$image=getproperty("Left Header Image");
		if(strlen($image)>0)
		{
			$this->stringvars['left_image']=getprojectrootlinkpath().'img/'.$image;
			$dimensions = getimagedimensions($projectroot."img/".$image);
			$this->stringvars['left_width'] = $dimensions["width"];
			$this->stringvars['left_height'] = $dimensions["height"];
		}
		
		$image=getproperty("Right Header Image");
		if(strlen($image)>0)
		{
			$this->stringvars['right_image']=getprojectrootlinkpath().'img/'.$image;
			$dimensions = getimagedimensions($projectroot."img/".$image);
			$this->stringvars['right_width'] = $dimensions["width"];
			$this->stringvars['right_height'] = $dimensions["height"];
		}
		
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

	function PageFooter()
	{
		parent::__construct();
		if(getproperty("Display Site Policy"))
		{
			$this->stringvars['site_policy_link']=getprojectrootlinkpath().'index.php'.makelinkparameters(array("page" => 0, "sitepolicy" => "on"));
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
	    $this->makeheader($this->stringvars['page'], $showhidden);
	

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
				$this->makecontents($this->stringvars['page'], $pagetype, $showhidden);
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
	function makeheader($page, $showhidden)
	{
		global $_GET;
		$title="";
		if(!$showhidden)
		{
			if(ispagerestrictedarray($page))
			{
				checkpublicsession($page);
			}

			if(ispublished($page))
				$title=$this->getmaintitle($page);
			elseif(isset($_GET["sitepolicy"]))
				$title=getproperty("Site Policy Title");
			elseif(isset($_GET["sitemap"]))
				$title=utf8_decode(getlang("pagetitle_sitemap"));
			else
				$title=utf8_decode(getlang("error_pagenotfound"));
		}
		else
		{
			if(@strlen($page<1) || $page<0)
			{
				$title ="Welcome to the webpage editing panel";
			}
			else
			{
				$title="Displaying ".getpagetype($page)." page#".$page." - ".$this->getmaintitle($page);
			
				if(getpagetype($page)==="external")
					$url=getexternallink($page);
				else
					$url=getprojectrootlinkpath()."index.php".makelinkparameters($_GET);
				
				$this->vars['message'] = new AdminPageDisplayMessage();
			}
		}
		$this->vars['header'] = new PageHeader($page, $title,$this->displaytype);

	}
  
	//
	//
	//
	function makecontents($page, $pagetype, $showhidden)
	{
		global $_GET, $offset, $projectroot;
    
		// init
		if(isset($_GET['articlepage']))
			$articlepage=$_GET['articlepage'];
		elseif(isset($_GET['offset']))
			$articlepage=$_GET['offset']+1;
		elseif(!isset($_GET['articlepage']) || @strlen($_GET['articlepage'])<1)
			$articlepage=1;
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
			$this->listvars['bottomlink'][]=new NavigatorBranch(0,"splashpage",0,0,"sitemap",$showhidden);
			if(getproperty("Enable Guestbook"))
				$this->listvars['bottomlink'][]=new NavigatorBranch(0,"splashpage",0,0,"guestbook",$showhidden);
			$this->listvars['bottomlink'][]=new NavigatorBranch(0,"splashpage",0,0,"contact",$showhidden);
		}

		// reroute to guide for webpage editors
		elseif($showhidden && @strlen($page<1) || $page<0)
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
			if($showhidden || ispublished($page))
			{
				if(isset($_GET['offset'])) $offset=$_GET['offset'];
				else $offset=0;
				
				if($pagetype==="article")
				{
					include_once($projectroot."includes/objects/articlepage.php");
					$this->vars['contents'] = new ArticlePage($articlepage,$showhidden);
				}
				elseif($pagetype==="articlemenu")
				{
					include_once($projectroot."includes/objects/menupage.php");
					$this->vars['contents'] = new ArticleMenuPage($page,$showhidden);
				}
				elseif($pagetype==="menu" || $pagetype=="linklistmenu")
				{
					include_once($projectroot."includes/objects/menupage.php");
					$this->vars['contents'] = new MenuPage($page,$showhidden);
				}
				elseif($pagetype==="external")
				{
					$this->stringvars['contents'] ='<a href="'.getexternallink($page).'" target="_blank">External page</a>';
				}
				elseif($pagetype==="gallery")
				{
					include_once($projectroot."includes/objects/gallerypage.php");
					$this->vars['contents'] = new GalleryPage($offset,$showhidden);
				}
				elseif($pagetype==="linklist")
				{
					include_once($projectroot."includes/objects/linklistpage.php");
					$this->vars['contents']  = new LinklistPage($offset,$showhidden);
				}
				elseif($pagetype==="news")
				{
					include_once($projectroot."includes/objects/newspage.php");
					$this->vars['contents']  = new NewsPage($page,$offset,$showhidden);
				}
			}
			elseif(isset($_GET["sitepolicy"]))
			{
				$this->vars['contents']  = new PageIntro(title2html(getproperty("Site Policy Title")),getdbelement("sitepolicytext",SITEPOLICY_TABLE,"policy_id",0));
			}
			elseif(isset($_GET["sitemap"]))
			{
				include_once($projectroot."includes/objects/sitemap.php");
				$this->vars['contents']  = new Sitemap($showhidden);
			}
			else
			{
				// todo why encoding problem?
				$this->vars['contents']  = new PageIntro(utf8_decode(getlang("error_pagenotfound")),utf8_decode(sprintf(getlang("error_pagenonotfound"),$page)));
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
//
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
			$pagetype=getpagetype($this->stringvars['page']);
		
			if(!$showhidden)
			{
				if(ispagerestrictedarray($this->stringvars['page']))
				{
					checkpublicsession($this->stringvars['page']);
				}
			}
		
			// contents
			$this->makecontents($this->stringvars['page'], $pagetype, $showhidden);
			
			// navigator
			$this->vars['navigator'] = new Navigator($this->stringvars['page'],0,0,"printview",false);
		}
		
		$this->stringvars['url']=getprojectrootlinkpath().makelinkparameters(array("page" => $this->stringvars['page']));
	}

	//
	//
	//
	function makeheader($page)
	{
		$title="";
		if(ispagerestrictedarray($page))
		{
			checkpublicsession($page);
		}
		if(ispublished($page))
		{
			$title=$this->getmaintitle($page);
		}
		else
		{
			$title="Page not found";
		}
		$this->stringvars['site_name']=title2html(getproperty("Site Name"));
		$this->stringvars['header_title']=getnavtitle($this->stringvars["page"]);
		$this->stringvars['title'] =  striptitletags($title);
		$this->stringvars['stylesheet'] = getCSSPath("printview.css");
	}

	//
	//
	//
	function makecontents($page, $pagetype)
	{
		global $projectroot,$_GET        ;
		
		if(ispublished($page))
		{
			if($pagetype==="article")
			{
				include_once($projectroot."includes/objects/articlepage.php");
				$this->vars['contents'] = new ArticlePagePrintview();
			}
			elseif($pagetype==="linklist")
			{
				include_once($projectroot."includes/objects/linklistpage.php");
				$this->vars['contents']  = new LinklistPagePrintview(false);
			}
			elseif($pagetype==="news")
			{
				include_once($projectroot."includes/objects/newspage.php");
				$this->vars['contents']  = new Newsitem($_GET['newsitem'],0,false,false);
			}
		}
		else
		{
			$this->vars['contents']  = new PageIntro("Page not found","Could not find page ".$page.".");
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

	function AdminPageDisplayMessage()
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
        $this->stringvars['editlink']=getprojectrootlinkpath()."admin/pageedit.php".makelinkparameters($_GET).'&page='.$this->stringvars['page'].'&action=edit';
      
        if(ispagerestrictedarray($this->stringvars['page']))
        {
        	$this->stringvars['isrestricted']="true";
		}
    }

    // assigns templates
    function createTemplates()
    {
      	$this->addTemplate("admin/pagedisplaymessage.tpl");
    }
}


?>