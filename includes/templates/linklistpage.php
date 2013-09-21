<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));

include_once($projectroot."functions/pages.php");
include_once($projectroot."includes/templates/template.php");
include_once($projectroot."includes/templates/elements.php");
include_once($projectroot."includes/includes.php");


//
// a link in a linklist
//
class LinklistLink extends Template {
  function LinklistLink($title,$image,$description,$link,$showrefused,$showhidden)
  {
    // image permissions checked in LinkList
    $hasimage=strlen($image)>0;
    $text = text2html($description);
    if($hasimage)
    {
      $this->vars['image'] = new CaptionedImage($image,2,$showrefused,$showhidden);

      $lines=explode("\n", $description);
      $this->stringvars['text1'] = text2html($lines[0]);
      unset($lines[0]);
      $this->stringvars['text2'] = text2html(implode("\n", $lines));
    }
    else
    {
      $this->stringvars['text2'] = text2html($description);
    }

    $this->stringvars['title'] = title2html($title);
    $this->stringvars['link'] = $link;

    $this->createTemplates();

//    print_r($this->stringvars);
  }

    // assigns templates
    function createTemplates()
    {
      $this->addTemplate("linklistlink.tpl");
    }
}




//
// main class for linklistpages
//
class LinklistPage extends Template {

  function LinklistPage($page,$offset=0,$showrefused,$showhidden=false)
  {
    $linksperpage=getproperty("Links Per Page");
    $linkids=getlinklistitems($page);
    $noofids=count($linkids);
    if(!$offset) $offset=0;
    $image=getlinklistimage($page);
    $intro=getlinklistintro($page);
    
    $this->vars['printviewbutton']= new PrintviewButton();

    $this->stringvars['pagetitle']=title2html(getpagetitle($page));

    if(strlen($intro)>0)
      $this->stringvars['text']=text2html($intro);
    else $this->stringvars['text']="";
    
    if(strlen($image)>0 && mayshowimage($image,$page,$showhidden))
      $this->vars['image'] = new CaptionedImage($image,2,$showrefused,$showhidden);


    // pagemenu
    if($noofids/$linksperpage>1)
    {
      $this->vars['pagemenu'] = new Pagemenu($offset,$linksperpage,$noofids,'',$page);
    }

    // links
    if(!mayshowimage($contents['image'],$page,$showhidden))
     $contents['image']="";
     
    for($i=$offset;$i<($offset+$linksperpage)&&$i<$noofids;$i++)
    {
      $contents=getlinkcontents($linkids[$i]);
      $this->listvars['link'][]= new LinkListLink($contents['title'],$contents['image'],$contents['description'],$contents['link'],$showrefused,$showhidden);
    }

    $this->vars['editdata']= new Editdata($page,$showhidden);

    $this->createTemplates();
    
//    print_r($this->stringvars);
  }
  
    // assigns templates
    function createTemplates()
    {
      $this->addTemplate("linklistpage.tpl");
    }
}




//
// main class for linklistpages
//
class LinklistPagePrintview extends Template {
  function LinklistPagePrintview($page)
  {
    $linkids=getlinklistitems($page);
    $noofids=count($linkids);

    $intro=getlinklistintro($page);
    
    $this->stringvars['pagetitle']=title2html(getpagetitle($page));

    if(strlen($intro)>0)
      $this->stringvars['text']=text2html($intro);
    else $this->stringvars['text']="";


    for($i=0;$i<$noofids;$i++)
    {
      $contents=getlinkcontents($linkids[$i]);
      $this->listvars['link'][]= new LinkListLink($contents['title'],"",$contents['description'],$contents['link'],false,false);
    }

    $this->vars['editdata']= new Editdata($page,$showhidden);

    $this->createTemplates();
  }

    // assigns templates
    function createTemplates()
    {
      $this->addTemplate("linklistpage.tpl");
    }
}

?>
