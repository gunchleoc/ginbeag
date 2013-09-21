<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."functions/pages.php");
include_once($projectroot."includes/templates/template.php");
include_once($projectroot."includes/includes.php");
//include_once($projectroot."includes/templates/forms.php");
//include_once($projectroot."includes/templates/page.php");

//
//
//
class AddLinklistLinkForm extends Template {
  function AddLinklistLinkForm($page)
  {
    global $sid;

    $this->stringvars['sid']=$sid;
    $this->stringvars['page']=$page;

    $this->createTemplates();
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/addlinklistlinkform.tpl");
  }
}


//
//
//
class EditLinkListLinkForm extends Template {
  function EditLinkListLinkForm($page,$linkid,$linktitle,$link,$image,$description)
  {
    global $sid;
    
    $this->stringvars['sid']=$sid;
    $this->stringvars['page']=$page;
    $this->stringvars['linkid']=$linkid;
    $this->stringvars['linktitle']=title2html($linktitle);
    if(!$linktitle)
    {
      $this->stringvars['linktitle'] = "New Link";
    }
    $this->stringvars['linkinputtitle']=input2html($linktitle);
    $this->stringvars['link']=$link;
    $this->stringvars['description']=text2html($description);
    
    $this->stringvars['imagefilename']=$image;
    $this->vars['image'] = new CaptionedImage($this->stringvars['imagefilename'],2,true);
    $this->stringvars['imagelinkpath']=getimagelinkpath($this->stringvars['imagefilename']);

    $this->vars['editdescription']= new EditTextButtons($page,$description,"Edit Link Description","link",$linkid);
    
    $this->createTemplates();
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/editlinklistlinkform.tpl");
  }
}


//
//
//
class EditLinklist extends Template {
  function EditLinklist($page,$message="")
  {
    global $sid;
    
    $this->stringvars['sid']=$sid;
    $this->stringvars['page']=$page;

    $this->vars['header'] = new HTMLHeader("Editing linklist page contents","Webpage Building",title2html(getpagetitle($page)).'<br />'.$message);
    $this->vars['footer']=new HTMLFooter();


    $this->vars['intro']= new EditTextButtons($page,getlinklistintro($page),"Edit Page Intro","linklist",0,0,"");
    
    $image=getlinklistimage($page);
    $this->stringvars['imagefilename']=$image;
    $this->vars['image'] = new CaptionedImage($this->stringvars['imagefilename'],2,true);
    $this->stringvars['imagelinkpath']=getimagelinkpath($this->stringvars['imagefilename']);

    $linkids=getlinklistitems($page);
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
      $this->listvars['linkform'][] = new EditLinkListLinkForm($page,$linkids[$i],$contents['title'],$contents['link'],$contents['image'],$contents['description']);
    }

    $this->vars['addform'] = new AddLinklistLinkForm($page);
    
    $this->stringvars['backbuttons']=generalsettingsbuttons($page);

    $this->createTemplates();
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/editlinklist.tpl");
  }
}

?>
