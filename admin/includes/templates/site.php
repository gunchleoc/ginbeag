<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."functions/pages.php");
include_once($projectroot."includes/templates/template.php");
include_once($projectroot."includes/includes.php");
include_once($projectroot."includes/templates/forms.php");
include_once($projectroot."admin/includes/templates/adminforms.php");


//
//
//
class SiteLayout extends Template {

  function SiteLayout($message="")
  {
  	global $sid, $projectroot;
    
	$this->vars['header'] = new HTMLHeader("Site Layout","Webpage Building", $message);
	$this->vars['footer'] = new HTMLFooter();
    $this->stringvars['actionvars']=$this->makeactionvars("get",array(),array("action" => "savesite"));
    

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
  	
   
    $this->stringvars['sitename']=title2html($properties["Site Name"]);
    $this->stringvars['sitedescription']=title2html($properties["Site Description"]);
    
    $this->stringvars['uploadpath']='http://'.getproperty('Domain Name').'/'.getproperty('Local Path').'/img';
    
    $this->stringvars['leftimage']=$properties["Left Header Image"];
    $this->stringvars['leftlink']=$properties["Left Header Link"];
    $this->stringvars['rightimage']=$properties["Right Header Image"];
    $this->stringvars['rightlink']=$properties["Right Header Link"];
    
    $this->stringvars['footermessage']=title2html($properties["Footer Message"]);
    $this->stringvars['footermessagedisplay']=text2html($properties["Footer Message"]);
    
    $this->stringvars['linksperpage']=$properties["Links Per Page"]; // todo NumberOptionForm
    $this->stringvars['newsperpage']=$properties["News Items Per Page"]; // todo NumberOptionForm

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
      		if ($i>0) $linksonsplashpagedisplay .= '<span class="gen"> - </span>';
      		if(getpagetype($rootpages[$i])==="external")
      		{
        		$linksonsplashpagedisplay .= '<a href="'.getexternallink($rootpages[$i]).'" target="_blank" class="gen">';
      		}
      		else
      		{
        		$linksonsplashpagedisplay .= '<a href="../pagedisplay.php?page='.$rootpages[$i].'" target="_blank" class="gen">';
      		}
      		$linksonsplashpagedisplay .= $rootpagetitles[$i].'</a> ';
    	}
  	}
    
    $this->stringvars['linksonsplashpagedisplay']=$linksonsplashpagedisplay;

	$this->vars['linksonsplashpage'] = new OptionFormMultiple($linksonsplashpage,$rootpages,$rootpagetitles,$name="linksonsplashpage", $size=10);
    
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
    
    $this->stringvars['splashtext1']=title2html($properties["Splash Page Text 1 - 1"].$properties["Splash Page Text 1 - 2"]);
    $this->stringvars['splashimage']=$properties["Splash Page Image"];
    $this->stringvars['splashtext2']=title2html($properties["Splash Page Text 2 - 1"].$properties["Splash Page Text 2 - 2"]);

    $this->vars['cancelbutton']= new LocationButton("Cancel",'pagedisplay.php?sid='.$sid,false,$class="liteoption");

    $this->createTemplates();

  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/sitelayout.tpl");
  }
}


//
//
//
class SiteAntispam extends Template {

  function SiteAntispam($message="")
  {
  	global $sid, $projectroot;
    
	$this->vars['header'] = new HTMLHeader("Site Layout","Webpage Building", $message);
	$this->vars['footer'] = new HTMLFooter();
    $this->stringvars['actionvars']=$this->makeactionvars("get",array(),array("action" => "savesite"));

	$variables=getmultiplefields(ANTISPAM_TABLE, "property_name", "1", array(0 => 'property_name', 1 => 'property_value'));
     
     
    if($variables['Use Math CAPTCHA']['property_value'])
    	$this->stringvars['usemathcaptcha']=" checked";
    else
    	$this->stringvars['not_usemathcaptcha']=" checked";

    $this->vars['cancelbutton']= new LocationButton("Cancel",'pagedisplay.php?sid='.$sid,false,$class="liteoption");

    $this->createTemplates();

  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/siteantispam.tpl");
  }
}

?>
