<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."functions/pages.php");
include_once($projectroot."includes/templates/template.php");
include_once($projectroot."includes/includes.php");
include_once($projectroot."includes/templates/forms.php");


//
// admin table with header
// tablerows must be an array of class AdminTableRow
// or a string of <tr>s
//
class AdminTable extends Template {

  function AdminTable($tablerows,$header,$headercolspan=1)
  {
    if(is_string($tablerows))
    {
      $this->stringvars['tablerows']=$tablerows;
    }
    elseif(is_array($tablerows))
    {
      for($i=0;$i<count($tablerows);$i++)
      {
        $this->listvars['tablerow'][]=$tablerows[$i];
      }
    }
    $this->stringvars['header']=$header;
    $this->stringvars['header_colspan']=$headercolspan;
    
    $this->createTemplates();
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/admintable.tpl");
  }
}

//
// admin table with header
// tabledata must be an array of class AdminTableData
// or a string of <td>s
//
class AdminTableRow extends Template {

  function AdminTableRow($tabledata,$isheaderrow=false)
  {
    if($isheaderrow)
      $this->stringvars['is_header']="header";
    else
      $this->stringvars['not_header']="not header";
      
    if(is_string($tabledata))
    {
      $this->stringvars['tabledata']=$tabledata;
    }
    elseif(is_array($tabledata))
    {
      for($i=0;$i<count($tabledata);$i++)
      {
        $this->listvars['tabledata'][]=$tabledata[$i];
      }
    }
      
    $this->createTemplates();
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/admintablerow.tpl");
  }
}


//
// admin table with header
// tabledata must be of class Template or a string
//
class AdminTableData extends Template {

  function AdminTableData($tabledata="",$attributes="")
  {
    if(is_string($tabledata))
      $this->stringvars['tabledata']=$tabledata;
    elseif(is_subclass_of ($tabledata,"Template"))
      $this->vars['tabledata']=$tabledata;
    else
      $this->stringvars['tabledata']="";

    $this->stringvars['attributes']=$attributes;

    $this->createTemplates();
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/admintabledata.tpl");
  }
}

//
//
//
class DoneButton extends Template {

  function DoneButton($page,$params="&action=edit",$link="pageedit.php",$buttontext="Done",$class="mainoption")
  {
    global $sid;
    $this->stringvars['sid']=$sid;
    $this->stringvars['page']=$page;
    $this->stringvars['params']=$params;
    $this->stringvars['link']=$link;
    $this->stringvars['buttontext']=$buttontext;
    $this->stringvars['class']=$class;

    if(str_endswith($link,"admin.php"))
      $this->stringvars['target']="_top";
    else
      $this->stringvars['target']="_self";

    $this->createTemplates();
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/donebutton.tpl");
  }
}


//
//
//
class DonePage extends Template {

  function DonePage($page,$title,$message="",$params="&action=edit",$link="pageedit.php",$buttontext="Done")
  {
    $this->vars['donebutton'] = new DoneButton($page,$params,$link,$buttontext);

    $this->vars['header'] = new HTMLHeader($title,"Webpage Building",$message);
    $this->vars['footer']= new HTMLFooter();

    $this->createTemplates();
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/donepage.tpl");
  }
}

?>
