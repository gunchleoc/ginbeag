<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."functions/pages.php");
include_once($projectroot."includes/templates/template.php");
include_once($projectroot."includes/includes.php");
include_once($projectroot."includes/templates/forms.php");
include_once($projectroot."includes/templates/page.php");


//
//
//
class DeletePageConfirmForm extends Template {
  function DeletePageConfirmForm($page)
  {
    global $sid;
    
    $this->vars['header'] = new HTMLHeader("Deleting ".getpagetype($page)." page","Webpage Building",title2html(getpagetitle($page)));

    $children=getchildren($page);
    if(count($children))
    {
      $this->stringvars['deletemessage']="Are you sure you want to delete these pages?";
      for($i=0;$i<count($children);$i++)
      {
        $this->listvars['subpages'][]= new NavigatorBranch($children[$i],"bullet","bullet","contents",50000000000,false,0,"",true);
      }
    }
    else
    {
      $this->stringvars['deletemessage']="Are you sure you want to delete this page?";
    }
    
    
    $this->stringvars['sid']=$sid;
    $this->stringvars['page']=$page;
    
    $this->vars['footer']=new HTMLFooter();

    $this->createTemplates();

  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/deletepageconfirmform.tpl");
  }
}

//
//
//
class FindNewParentForm extends Template {
  function FindNewParentForm($page)
  {
    global $sid;

    $this->stringvars['sid']=$sid;
    $this->stringvars['page']=$page;

    $this->createTemplates();

  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/findnewparentform.tpl");
  }
}



//
//
//
class SelectNewParentForm extends Template {
  function SelectNewParentForm($page)
  {
    global $sid;

    $this->stringvars['sid']=$sid;
    $this->stringvars['page']=$page;
    
    $this->vars['header']= new HTMLHeader("Select destination for moving this page:","Webpage Building",title2html(getpagetitle($page)));
    $this->vars['footer']= new HTMLFooter();

    $values=array();
    $descriptions=array();
    
    $allpages= getmovetargets($page);
    $i=0;
    if(array_key_exists(0,$allpages))
    {
      $values[]=0;
      $descriptions[]="Site Root";
      $i=1;
    }

    for(;$i<count($allpages);$i++)
    {
      $values[]=$allpages[$i];
      $descriptions[]=$allpages[$i].': '.title2html(getnavtitle($allpages[$i]));
    }

    $this->vars['targetform']= new OptionForm(0,$values,$descriptions,"parentnode",20);
    
    $location='?action=edit&sid='.$sid.'&page='.$page;
    $this->stringvars['cancelbutton']=locationbutton("Cancel", $location, "liteoption");

    $this->createTemplates();

  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/selectnewparentform.tpl");
  }
}




//
//
//
class RestrictAccessForm extends Template {

  function RestrictAccessForm($page)
  {
    global $sid;

    $accessrestricted=isthisexactpagerestricted($page);

    $this->stringvars['sid']=$sid;
    $this->stringvars['page']=$page;
    
    
    $this->vars['restrict_yes']= new RadioButtonForm("restrict","1","Yes",$accessrestricted);
    $this->vars['restrict_no']= new RadioButtonForm("restrict","0","No",!$accessrestricted);
    
    if($accessrestricted)
    {
      $this->stringvars['accessrestricted']="Access restricted";
      $accessusers=getallpublicuserswithaccessforpage($page);
      if(count($accessusers)==0)
      {
        $this->stringvars['restricteduserlist']='<i>No users have access to this page</i>';
      }
      else
      {
        $this->stringvars['restricteduserlist']='The following users have access to this page:<br /><i>';
        for($i=0;$i<count($accessusers);$i++)
        {
          $this->stringvars['restricteduserlist'].=input2html(getpublicusername($accessusers[$i]))." ";
        }
        $this->stringvars['restricteduserlist'].='</i>';
      }


      $values=array();
      $descriptions=array();
      $allpublicusers=getallpublicusers();

      for($i=0;$i<count($allpublicusers);$i++)
      {
        $values[]=$allpublicusers[$i];
        $descriptions[]=title2html(getpublicusername($allpublicusers[$i]));
      }
      $this->vars['selectusers']= new OptionForm(0,$values,$descriptions,"selectusers[]",5, " multiple");
    }

    $this->createTemplates();
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/restrictaccessform.tpl");
  }
}

// todo: code duplication with adminnewspage
//
//
class PermissionsForm extends Template {

  function PermissionsForm($page, $permissions)
  {
    global $sid;
    $accessrestricted=ispagerestricted($page);

    $this->stringvars['sid']=$sid;
    $this->stringvars['page']=$page;
    
    $this->stringvars['copyright']=input2html($permissions['copyright']);
    $this->stringvars['image_copyright']=input2html($permissions['image_copyright']);

    $this->vars['permission_granted']= new RadioButtonForm("permission",PERMISSION_GRANTED,"Permission granted",$permissions['permission']==PERMISSION_GRANTED);
    $this->vars['no_permission']= new RadioButtonForm("permission",NO_PERMISSION,"No permission",$permissions['permission']==NO_PERMISSION);
    $this->vars['permission_refused']= new RadioButtonForm("permission",PERMISSION_REFUSED,"Permission refused",$permissions['permission']==PERMISSION_REFUSED);


    if($accessrestricted)
    {
      $showrefused=showpermissionrefusedimages($page);
      $this->stringvars['accessrestricted']="Access restricted";
      $this->vars['showrefused_yes']= new RadioButtonForm("show","1","Yes",$showrefused);
      $this->vars['showrefused_no']= new RadioButtonForm("show","0","No",!$showrefused);
    }
    else
      $this->stringvars['not_accessrestricted']="Access not restricted";

    $this->createTemplates();
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/permissionsform.tpl");
  }
}

//
//
//
class RenamePageForm extends Template {

  function RenamePageForm($page)
  {
    global $sid;

    $this->stringvars['sid']=$sid;
    $this->stringvars['page']=$page;
    
    $this->stringvars['navtitle']=input2html(getnavtitle($page));
    $this->stringvars['pagetitle']=input2html(getpagetitle($page));

    $this->createTemplates();
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/renamepageform.tpl");
  }
}


//
//
//
class SetPublishableForm extends Template {

  function SetPublishableForm($page)
  {
    global $sid;

    $this->stringvars['sid']=$sid;
    $this->stringvars['page']=$page;
    
    $permissionrefused=permissionrefused($page);

    if(!$permissionrefused)
    {
      $ispublishable=ispublishable($page);
      $this->stringvars['not_permissionrefused']="Permission not refused";
      $this->vars['publishable_yes']= new RadioButtonForm("ispublishable","public","Public page",$ispublishable);
      $this->vars['publishable_no']= new RadioButtonForm("ispublishable","internal","Internal page",!$ispublishable);
    }
    else
      $this->stringvars['permissionrefused']="Permission refused";


    $this->createTemplates();
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/setpublishableform.tpl");
  }
}


//
//
//
class ExternalForm extends Template {

  function ExternalForm($page)
  {
    global $sid;

    $this->stringvars['sid']=$sid;
    $this->stringvars['page']=$page;

    $this->stringvars['link']=getexternallink($page);

    $this->createTemplates();
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/externalform.tpl");
  }
}


//
//
//
class EditPage extends Template {

  function EditPage($page,$message="")
  {
    global $sid;

    $this->stringvars['sid']=$sid;
    $this->stringvars['page']=$page;
    if(strlen($message)>0)
      $this->stringvars['message']=$message;

    $pagetype=getpagetype($page);
    $this->vars['header']= new HTMLHeader("Editing ".$pagetype." page - General Settings","Webpage Building",title2html(getpagetitle($page)));
    $this->vars['footer']= new HTMLFooter();

    $permissions=getcopyright($page);

    if($pagetype==="external")
    {
      $this->vars['contentsform']= new ExternalForm($page);
    }
    else
    {
      $this->vars['contentsform']= new EditPageContentsForm($page);
    }

    $this->listvars['generalform'][]= new RenamePageForm($page);

    $this->listvars['generalform'][]= new SetPublishableForm($page);

    if($pagetype!=="external")
    {
      $this->listvars['generalform'][]= new PermissionsForm($page,$permissions);
    }
    if($pagetype!=="external")
    {
      $this->listvars['generalform'][]= new RestrictAccessForm($page);
    }
      
    $this->vars['movepageform']= new MovePageForm($page,$page);

    $this->vars['findnewparentform']= new FindNewParentForm($page);

    $this->vars['donebutton'] = new DoneButton($page,"&action=show","admin.php");

    $this->createTemplates();
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/editpage.tpl");
  }
}



//
//
//
class NewPageForm extends Template {

  function NewPageForm($parentpage,$title="",$navtitle="",$ispublishable=false,$isrootchecked=false)
  {
    global $sid;

    $this->stringvars['sid']=$sid;
    $this->stringvars['page']=$parentpage;
    $this->stringvars['pagetitle']=$title;
    $this->stringvars['navtitle']=$navtitle;
    if($isrootchecked)
      $this->stringvars['rootchecked']="checked";
    else
      $this->stringvars['rootchecked']="";

    $this->vars['is_publishable_yes']= new RadioButtonForm("ispublishable","public","Public page",$ispublishable);
    $this->vars['is_publishable_no']= new RadioButtonForm("ispublishable","internal","Internal page",!$ispublishable);
    

    $pagetypes=getpagetypes();
    $keys=array_keys($pagetypes);
  
    for($i=0;$i<count($keys);$i++)
    {
      $short=$keys[$i];
      $values[]=$short;
      $descriptions[]=$short.': '.input2html($pagetypes[$short]);
    }

    $this->vars['typeselection']= new OptionForm($keys[0],$values,$descriptions,"type",1);

    $this->createTemplates();
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/newpageform.tpl");
  }
}

?>
