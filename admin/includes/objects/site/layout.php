<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."functions/pagecontent/externalpages.php");
include_once($projectroot."includes/objects/template.php");
include_once($projectroot."includes/objects/forms.php");
include_once($projectroot."includes/objects/elements.php");
include_once($projectroot."admin/includes/objects/forms.php");


//
//
//
class SiteLayout extends Template {

	function SiteLayout()
	{
		global $projectroot;
		parent::__construct();
		
		$this->stringvars['actionvars']='?page='.$this->stringvars['page'].'&action=sitelayout&postaction=savesite';
		
		$this->vars['submitrow']= new SubmitRow("submit","Submit",true);
		
		$properties=getproperties();
		$potdcats=explode(",",$properties["Picture of the Day Categories"]);
		$potdcatnames=array();
		if(!count($potdcats))
		{
			$potdcatlistoutput="All Categories";
		}
		else
		{
			for($j=0;$j<count($potdcats);$j++)
			{
		  		array_push($potdcatnames,getcategoryname($potdcats[$j]));
			}
			sort($potdcatnames);
			$potdcatlistoutput=implode(", ",$potdcatnames);
		}
		
		$linksonsplashpage=explode(",",$properties['Links on Splash Page']);
		
		$this->stringvars['sitename']=input2html($properties["Site Name"]);
		$this->stringvars['sitedescription']=input2html($properties["Site Description"]);
		
		$this->stringvars['uploadpath']='http://'.getproperty('Domain Name').'/'.getproperty('Local Path').'/img';
		
		$this->stringvars['leftimage']=$properties["Left Header Image"];
		$this->stringvars['leftlink']=$properties["Left Header Link"];
		$this->stringvars['rightimage']=$properties["Right Header Image"];
		$this->stringvars['rightlink']=$properties["Right Header Link"];
		
		// Default Template
		$defaulttemplate=$properties["Default Template"];
		
		$templatedir = $projectroot."templates/";
		$templates = array();
		
		// Open a known directory, and proceed to read its contents
		if (is_dir($templatedir))
		{
			if ($dh = opendir($templatedir))
			{
		    	while (($file = readdir($dh)) !== false)
		    	{
		    		if($file != "." && $file != ".." && is_dir ($templatedir . $file))
		        	$templates[] = $file;
		    	}
		    	closedir($dh);
			}
		}
		
		$this->vars['default_template']= new OptionForm($defaulttemplate,$templates,$templates,$name="defaulttemplate", "Choose a template: ",$size=1);
		
		
		// Footer    
		$this->stringvars['footermessage']=input2html($properties["Footer Message"]);
		$this->stringvars['footermessagedisplay']=text2html($properties["Footer Message"]);
		
		$this->stringvars['newsperpage']=$properties["News Items Per Page"];
		$this->stringvars['galleryimagesperpage']=$properties["Gallery Images Per Page"];
		
		// page links on splash page
		$rootpages=getrootpages();
		$rootpagetitles = array();
		$noofrootpages = count($rootpages);
		for($i=0;$i<$noofrootpages;$i++)
		{
			$rootpagetitles[$i]=title2html(getpagetitle($rootpages[$i]));
		}
		
		$linksonsplashpagedisplay = "";
		for($i=0;$i<$noofrootpages;$i++)
		{
			if(in_array($rootpages[$i],$linksonsplashpage) && ispublished($rootpages[$i]))
			{
		  		if ($i>0) $linksonsplashpagedisplay .= ' - ';
		  		if(getpagetype($rootpages[$i])==="external")
		  		{
		    		$linksonsplashpagedisplay .= '<a href="'.getexternallink($rootpages[$i]).'" target="_blank">';
		  		}
		  		else
		  		{
		    		$linksonsplashpagedisplay .= '<a href="../pagedisplay.php?page='.$rootpages[$i].'" target="_blank">';
		  		}
		  		$linksonsplashpagedisplay .= $rootpagetitles[$i].'</a> ';
			}
		}
		
		$this->stringvars['linksonsplashpagedisplay']=$linksonsplashpagedisplay;
		
		$this->vars['linksonsplashpage'] = new OptionFormMultiple($linksonsplashpage,$rootpages,$rootpagetitles,"linksonsplashpage","Links on Splashpage", $size=10);
		
		$this->stringvars['alllinksonsplash']="";
		$this->stringvars['not_alllinksonsplash']="";
		if($properties["Show All Links on Splash Page"]) $this->stringvars['alllinksonsplash']=" checked";
		else $this->stringvars['not_alllinksonsplash']=" checked";
		
		$this->stringvars['sitedescriptiononsplash']="";
		$this->stringvars['not_sitedescriptiononsplash']="";
		if($properties["Display Site Description on Splash Page"]) $this->stringvars['sitedescriptiononsplash']=" checked";
		else $this->stringvars['not_sitedescriptiononsplash']=" checked";
		
		$this->stringvars['fontnormal']="";
		$this->stringvars['fontitalic']="";
		$this->stringvars['fontbold']="";
		if($properties["Splash Page Font"] === "normal") $this->stringvars['fontnormal']=" checked";
		elseif($properties["Splash Page Font"] === "italic") $this->stringvars['fontitalic']=" checked";
		elseif($properties["Splash Page Font"] === "bold") $this->stringvars['fontbold']=" checked";
		
		$this->stringvars['splashtext1']=input2html($properties["Splash Page Text 1 - 1"].$properties["Splash Page Text 1 - 2"]);
		$this->stringvars['splashimage']=$properties["Splash Page Image"];
		$this->stringvars['splashtext2']=input2html($properties["Splash Page Text 2 - 1"].$properties["Splash Page Text 2 - 2"]);
	}
	
	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/site/layout.tpl");
	}
}
?>