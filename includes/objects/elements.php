<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));

include_once($projectroot."includes/objects/template.php");


//
// a general header
//
class HTMLHeader extends Template {

    function HTMLHeader($title,$headertitle,$message="", $redirecturl="",$urltext="If the page does not load, use this link", $isredirect=false,$stylesheet="main.css",$scriptpaths=array())
    {
    	parent::__construct();
		$this->stringvars['stylesheet']=getCSSPath($stylesheet);
		$this->stringvars['site_name']=title2html(getproperty("Site Name"));
		$this->stringvars['header_title']=$headertitle;
		if(strlen($title)>0) $this->stringvars['title']=$title;
		if(strlen($message)>0) $this->stringvars['message']=$message;
		if(strlen($isredirect)>0) $this->stringvars['is_redirect']="redirect";
		if(strlen($redirecturl)>0) $this->stringvars['url']=$redirecturl;
		$this->stringvars['url_text']=$urltext;
		
		if (count($scriptpaths))
		{
			$this->stringvars['script']="";
			for($i=0;$i<count($scriptpaths);$i++)
			{
				$this->stringvars['script'].='<script type="text/javascript" src="'.getprojectrootlinkpath().$scriptpaths[$i].'"></script>';
			}
		}
	}

    // assigns templates
    function createTemplates()
    {
       $this->addTemplate("htmlheader.tpl");
    }
}

//
// a general footer
//
class HTMLFooter extends Template {

    function HTMLFooter()
    {
    	parent::__construct();
    }

    // assigns templates
    function createTemplates()
    {
       $this->addTemplate("htmlfooter.tpl");
    }
}


//
// container for a highlighted message
//
class Message extends Template {

    function Message($message)
    {
		parent::__construct();
		$this->stringvars['message']=$message;
    }

    // assigns templates
    function createTemplates()
    {
		$this->addTemplate("message.tpl");
    }
}

?>
