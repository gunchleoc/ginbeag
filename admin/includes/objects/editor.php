<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"objects"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."includes/functions.php");
include_once($projectroot."includes/objects/template.php");
include_once($projectroot."includes/objects/forms.php");
include_once($projectroot."functions/pages.php");
include_once($projectroot."functions/pagecontent/articlepages.php");
include_once($projectroot."functions/pagecontent/linklistpages.php");
include_once($projectroot."functions/pagecontent/newspages.php");
include_once($projectroot."includes/objects/elements.php");


//
// Templating for Editor
//
class Editor extends Template {

    function Editor($page,$item, $elementtype, $title="Text", $iscollapsed=true) {
    	parent::__construct($page.'-'.$item);
    
		$this->stringvars['item']=$item;
		$this->stringvars['elementtype']=$elementtype;
		$this->stringvars['title']=$title;
				
		$this->stringvars['javascript']="&nbsp;".prepareJavaScript($this->stringvars['jsid'], "admin/includes/javascript/messageboxes.js");
		$this->stringvars['javascript']=prepareJavaScript($this->stringvars['jsid'], "admin/includes/javascript/editor.js");
		
		if($iscollapsed)
			$this->vars['editorcontents']= new EditorContentsCollapsed($page,$item, $elementtype,"Edit ".$title);
		else
			$this->vars['editorcontents']= new EditorContentsExpanded($page,$item, $elementtype,"Edit ".$title);

      	$this->stringvars['previewtext']=text2html(geteditortext($page,$item, $elementtype));
    }

    // assigns templates
    function createTemplates()
    {
      $this->addTemplate("admin/editor.tpl");
    }
}

//
// expanded editor contents
//
class EditorContentsExpanded extends Template {

    function EditorContentsExpanded($page,$item, $elementtype,$title="Edit text", $edittext=false) {
    	parent::__construct($page.'-'.$item);
    
		$this->stringvars['item']=$item;
		$this->stringvars['elementtype']=$elementtype;
		$this->stringvars['title']=$title;
				
		if($edittext!=false)
		{
			$edittext = stripslashes(stripslashes($edittext));
			$this->stringvars['text']=$edittext;
			$this->stringvars['previewtext']=text2html($edittext);
		}
		else
		{
			//$this->stringvars['text']="Text  ".$elementtype.", page ".$page.", item ".$item.".";
			
			$text = geteditortext($page,$item, $elementtype);
			$this->stringvars['text']=input2html($text);
	      	$this->stringvars['previewtext']=text2html($text);
		}
      	
      	$this->vars['styleform']=new OptionForm("0",array(0=>"0", 1=>"en"),array(0=>"-- Style --", 1=>"English"),$this->stringvars['jsid']."styleform","",1);
    }

    // assigns templates
    function createTemplates()
    {
      $this->addTemplate("admin/editorcontentsexpanded.tpl");
    }
}


//
// expanded editor contents
//
class EditorContentsCollapsed extends Template {

    function EditorContentsCollapsed($page,$item, $elementtype, $title="Edit text") {
    	parent::__construct($page.'-'.$item);
    
		$this->stringvars['item']=$item;
		$this->stringvars['elementtype']=$elementtype;
		$this->stringvars['title']=$title;
    }

    // assigns templates
    function createTemplates()
    {
      $this->addTemplate("admin/editorcontentscollapsed.tpl");
    }
}

//
// expanded editor contents
//
class EditorContentsSaveDialog extends Template {

    function EditorContentsSaveDialog($page,$item, $elementtype, $edittext,$title="Edit text") {
    	parent::__construct($page.'-'.$item);
    
		$this->stringvars['item']=$item;
		$this->stringvars['elementtype']=$elementtype;
		$this->stringvars['edittext']=htmlspecialchars($edittext); // for quotes etc
		$this->stringvars['title']=$title;
    }

    // assigns templates
    function createTemplates()
    {
      $this->addTemplate("admin/editorcontentssavedialog.tpl");
    }
}




//
// helper function to get text for editor from database
//
function geteditortext($page,$item, $elementtype) {

	$text="Text could not be loaded for ".$elementtype.", page ".$page.", item ".$item.".";
	
	if($elementtype=="articlesynopsis" || $elementtype=="gallery" || $elementtype=="linklist" || $elementtype=="menu")
  	{
    	$text=getpageintro($page);
  	}
  	elseif($elementtype=="articlesection")
  	{
    	$text=getarticlesectiontext($item);
  	}
  	if($elementtype=="link")
  	{
    	$text=getlinkdescription($item);
  	}
  	elseif($elementtype=="newsitemsynopsis")
  	{
    	$text=getnewsitemsynopsistext($item);
  	}
  	elseif($elementtype=="newsitemsection")
  	{
    	$text=getnewsitemsectiontext($item);
  	}
  	elseif($elementtype=="sitepolicy")
  	{
    	$text=getdbelement("sitepolicytext",SITEPOLICY_TABLE,"policy_id",0);
  	}
  	return stripslashes($text);
}


?>