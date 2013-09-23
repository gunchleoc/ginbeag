<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."functions/pagecontent/linklistpages.php");
include_once($projectroot."includes/objects/template.php");
include_once($projectroot."admin/includes/objects/elements.php");
include_once($projectroot."admin/includes/objects/editor.php");
//
//
//
class AddLinklistLinkForm extends Template {
  function AddLinklistLinkForm()
  {
    parent::__construct();
    
    $this->stringvars['actionvars']= "?sid=".$this->stringvars['sid']."&page=".$this->stringvars['page']."&action=editcontents";
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/edit/addlinklistlinkform.tpl");
  }
}


//
//
//
class EditLinkListLinkForm extends Template {
  function EditLinkListLinkForm($linkid,$linktitle,$link,$image,$description)
  {
    parent::__construct($linkid);
    
    $this->stringvars['javascript']="&nbsp;".prepareJavaScript($this->stringvars['jsid'], "admin/includes/javascript/messageboxes.js");
   	$this->stringvars['javascript'].=prepareJavaScript($this->stringvars['jsid'], "admin/includes/javascript/editlinklist.js");
   	
   	$this->stringvars['actionvars']= "?sid=".$this->stringvars['sid']."&page=".$this->stringvars['page']."&link=".$linkid."&action=editcontents";
    $this->stringvars['imagelistpath']=getprojectrootlinkpath()."admin/editimagelist.php?sid=".$this->stringvars['sid']."&page=".$this->stringvars['page'];
    
    $this->stringvars['linkid']=$linkid;
    
    $this->stringvars['linktitle']=title2html($linktitle);
    if(!$linktitle)
    {
      $this->stringvars['linktitle'] = "New Link";
    }
    $this->stringvars['linkinputtitle']=input2html($linktitle);
    $this->stringvars['link']=$link;
    $this->stringvars['description']=text2html($description);
    
    $this->stringvars['imagefilename'] = $image;
    $this->vars['image'] = new CaptionedImageAdmin($image,$this->stringvars['page'],2);

    $this->vars['editdescription']= new Editor($this->stringvars['page'],$linkid,"link","Link Description");
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/edit/editlinklistlinkform.tpl");
  }
}


//
//
//
class EditLinklist extends Template {
  function EditLinklist($page)
  {
    parent::__construct($page,array(0=>"includes/javascript/jquery.js", 1=>"includes/javascript/jcaret.js"));
  		
  	$this->stringvars['javascript']="&nbsp;".prepareJavaScript($this->stringvars['jsid'], "admin/includes/javascript/messageboxes.js");
  	
  	$this->stringvars['actionvars']= "?sid=".$this->stringvars['sid']."&page=".$this->stringvars['page']."&action=editcontents";
    $this->stringvars['imagelistpath']=getprojectrootlinkpath()."admin/editimagelist.php?sid=".$this->stringvars['sid']."&page=".$this->stringvars['page'];

    $this->vars['intro']= new Editor($page,0,"linklist","Page Intro");
    
    $image=getpageintroimage($page);
    
    
    $this->stringvars['imagefilename']=$image;
    
	if(strlen($image)>0)
    {
    	$this->vars['image'] = new CaptionedImageAdmin($image,$page,2);
    }

    $linkids=getlinklistitems($page);
    if(count($linkids<1)) $this->stringvars['linkform']="";

    for($i=0;$i<count($linkids);$i++)
    {
      $contents=getlinkcontents($linkids[$i]);
      if($contents['title'])
      {
        $linktitle=title2html($contents['title']);
      }
      else
      {
        $linktitle='New Link';
      }
      $this->listvars['linkform'][] = new EditLinkListLinkForm($linkids[$i],$contents['title'],$contents['link'],$contents['image'],$contents['description']);
    }

    $this->vars['addform'] = new AddLinklistLinkForm();
    
    $this->vars['backbuttons']=new GeneralSettingsButtons();
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/edit/editlinklist.tpl");
  }
}

?>