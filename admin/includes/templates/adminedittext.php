<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

//include_once($projectroot."functions/categories.php");
include_once($projectroot."includes/templates/template.php");
//include_once($projectroot."includes/includes.php");
include_once($projectroot."includes/templates/forms.php");


//
// Temmplating for TEst Edit Window
//
class EditText extends Template {
  function EditText()
  {
    global $_GET, $_POST;
    

    $page=$_GET['page'];
    $item=$_GET['item'];
    $elementtype=$_GET['elementtype'];
    // two text variables needed for UTF8
    $text=$_POST['text'];
    $text2=$_POST['text2'];


    if(isset($_POST['submit']))
    {
      if($elementtype=="articlesynopsis")
      {
        updatearticlesynopsis($page, $text2);
      }
      elseif($elementtype=="articlesection")
      {
        updatearticlesectiontext($item, $text2);
      }
      elseif($elementtype=="gallery")
      {
        updategalleryintro($page, $text2);
      }
      elseif($elementtype=="linklist")
      {
        updatelinklistintro($page, $text2);
      }
      elseif($elementtype=="link")
      {
        updatelinkdescription($item, $text2);
      }
      elseif($elementtype=="menu")
      {
        updatemenuintro($page, $text2);
      }
      elseif($elementtype=="newsitemsynopsis")
      {
        updatenewsitemsynopsistext($item, $text2);
      }
      elseif($elementtype=="newsitemsection")
      {
        updatenewsitemsectiontext($item, $text2);
      }
      updateeditdata($page, $sid);
    }
    elseif(!isset($_POST['preview']))
    {
      if($elementtype=="articlesynopsis")
      {
        $text=getarticlesynopsis($page);
      }
      elseif($elementtype=="articlesection")
      {
        $text=getarticlesectiontext($item);
      }
      if($elementtype=="gallery")
      {
        $text=getgalleryintro($page);
      }
      if($elementtype=="linklist")
      {
        $text=getlinklistintro($page);
      }
      if($elementtype=="link")
      {
        $text=getlinkdescription($item);
      }
      elseif($elementtype=="menu")
      {
        $text=getmenuintro($page);
      }
      elseif($elementtype=="newsitemsynopsis")
      {
        $text=getnewsitemsynopsistext($item);
      }
      elseif($elementtype=="newsitemsection")
      {
        $text=getnewsitemsectiontext($item);
      }
    }

    $this->vars['header']=new HTMLHeader("Editing text","Editing text");
    $this->vars['footer']=new HTMLFooter();
    
    $this->stringvars['sid']=$_GET['sid'];
    $this->stringvars['page']=$page;
    $this->stringvars['item']=$item;
    $this->stringvars['elementtype']=$elementtype;
    $this->stringvars['pagetitle']=title2html(getpagetitle($page));

   	if(isset($_POST['text2']))
   	{
   		$this->stringvars['text']=utf8_decode(text2html($text2));
   	}
   	else
   	{
   		$this->stringvars['text']=text2html($text);
   	}
    //$this->stringvars['text']=$text;

    if(isset($_POST['submit']))
    {
      $this->stringvars['submit']="Submit";
    }
    else
    {
    	if(isset($_POST['text2']))
    	{
    		$this->stringvars['edittext']=utf8_decode(input2html($text2));
    	}
    	else
    	{
      		$this->stringvars['edittext']=input2html($text);
      	}
    }
//      print_r($this->stringvars);

    $this->createTemplates();

  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/edittext.tpl");
  }
}

?>
