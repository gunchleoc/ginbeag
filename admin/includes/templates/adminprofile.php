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
class ProfilePage extends Template {

  function ProfilePage($userid,$message="")
  {
    global $sid, $_GET;
    
    $this->vars['header'] = new HTMLHeader("Edit user profile","Webpage Building",$message);
    $this->vars['footer'] = new HTMLFooter();

    $this->stringvars['username']=getusername($userid);
    $this->stringvars['email']=getuseremail($userid);
    $this->stringvars['contactfunction']=getcontactfunction($userid);

    $this->stringvars['sid']=$sid;
    $this->stringvars['page']=$_GET['page'];
    
    
    $this->vars['is_contact']= new CheckBoxForm("iscontact","Is Contact","",getiscontact($userid));

    $this->createTemplates();
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/profilepage.tpl");
  }
}

//
//
//
class RegisterPage extends Template {

  function RegisterPage($user, $email,$message,$showform=true)
  {

    $this->vars['header'] = new HTMLHeader("Register an account","Webpage Building - Register",$message);
    $this->vars['footer'] = new HTMLFooter();

    $this->stringvars['user']=input2html($user);
    $this->stringvars['email']=$email;
    if($showform)
      $this->stringvars['showform']="show form";

    $this->createTemplates();
  }

  // assigns templates
  function createTemplates()
  {
    $this->addTemplate("admin/registerpage.tpl");
  }
}

?>
