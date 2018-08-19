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

		$linkparams["page"] = $this->stringvars['page'];
		$linkparams["postaction"] = "savesite";
		$linkparams["action"] = "sitelayout";
		$this->stringvars['actionvars'] = makelinkparameters($linkparams);

		$this->vars['submitrow']= new SubmitRow("submit","Submit",true);

		$properties=getproperties();

		$linksonsplashpage=explode(",",$properties['Links on Splash Page']);

		$this->stringvars['sitename']=input2html($properties["Site Name"]);
		$this->stringvars['sitedescription']=input2html($properties["Site Description"]);

		$this->stringvars['uploadpath']=$properties['Server Protocol'].$properties['Domain Name'].'/'.$properties['Local Path'].'/img';

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
					$linksonsplashpagedisplay .= '<a href="../pagedisplay.php'.makelinkparameters(array("page" => $rootpages[$i])).'" target="_blank">';
		  		}
		  		$linksonsplashpagedisplay .= $rootpagetitles[$i].'</a> ';
			}
		}

		$this->stringvars['linksonsplashpagedisplay']=$linksonsplashpagedisplay;

		$this->vars['linksonsplashpage'] = new OptionFormMultiple($linksonsplashpage,$rootpages,$rootpagetitles,"linksonsplashpage","Links on Splashpage", $size=10);

		$this->vars['alllinksonsplashpage_yes'] = new RadioButtonForm($this->stringvars['jsid'], "alllinksonsplashpage", 1, "Show All Links on Splash Page", $properties["Show All Links on Splash Page"], "right");
	    $this->vars['alllinksonsplashpage_no'] = new RadioButtonForm($this->stringvars['jsid'], "alllinksonsplashpage", 0, "Show the above selection only", !$properties["Show All Links on Splash Page"], "right");

	    $this->vars['showsitedescription_yes'] = new RadioButtonForm($this->stringvars['jsid'], "showsd", 1, "Yes", $properties["Display Site Description on Splash Page"], "right");
	    $this->vars['showsitedescription_no'] = new RadioButtonForm($this->stringvars['jsid'], "showsd", 0, "No", !$properties["Display Site Description on Splash Page"], "right");

		$this->vars['spfont_normal'] = new RadioButtonForm($this->stringvars['jsid'], "spfont", "normal", "Normal", $properties["Splash Page Font"] === "normal", "right");
		$this->vars['spfont_italic'] = new RadioButtonForm($this->stringvars['jsid'], "spfont", "italic", "Italic", $properties["Splash Page Font"] === "italic", "right");
		$this->vars['spfont_bold'] = new RadioButtonForm($this->stringvars['jsid'], "spfont", "bold", "Bold", $properties["Splash Page Font"] === "bold", "right");

		$this->stringvars['splashtext1']=input2html(getdbelement("text",SPECIALTEXTS_TABLE,"id","splashpage1"));
		$this->stringvars['splashimage']=$properties["Splash Page Image"];
		$this->stringvars['splashtext2']=input2html(getdbelement("text",SPECIALTEXTS_TABLE,"id","splashpage2"));
	}

	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/site/layout.tpl");
	}
}
?>
