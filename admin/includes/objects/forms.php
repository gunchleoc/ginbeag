<?php
$projectroot=dirname(__FILE__);

// zweimal, weil nur auf "a" geprüft wird
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."includes/objects/template.php";

//
// $page: caller
// $moveid: Page to be moved
//
class MovePageForm extends Template
{

    function MovePageForm($page,$moveid)
    {
        parent::__construct($moveid);
        $this->stringvars['hiddenvars'] = $this->makehiddenvars(array("moveid" => $moveid));
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/movepageform.tpl");
    }
}

//
//
//
class DoneButton extends Template
{

    function DoneButton($page,$params=array("action" => "edit"),$link="pageedit.php",$buttontext="Done",$class="mainoption")
    {
        parent::__construct();
        $params["page"] = $page;
        $this->stringvars['link']=$link.makelinkparameters($params);
        $this->stringvars['buttontext']=$buttontext;
        $this->stringvars['class']=$class;

        if(str_endswith($link, "admin.php")) {
            $this->stringvars['target']="_top";
        } else {
            $this->stringvars['target']="_self";
        }
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
class DonePage extends Template
{

    function DonePage($title, $link="pageedit.php", $buttontext="Done")
    {
        parent::__construct();
        $this->vars['donebutton'] = new DoneButton($this->stringvars['page'], array("action" => "show"), $link, $buttontext);
        $this->stringvars['title'] =$title;
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/donepage.tpl");
    }
}


class pageBeingEditedNotice extends Template
{

    function pageBeingEditedNotice($message="")
    {
        parent::__construct();
        $this->vars['donebutton'] = new DoneButton($this->stringvars['page'], array("action" => "show"), "admin.php", "View this page");
        $this->stringvars['message'] = $message;
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/pagebeingeditednotice.tpl");
    }
}

function noPageSelectedNotice()
{
    return new DonePage("No Page Selected", "includes/pagelist.php", "Select page from list");
}


//
//
//
class DoneRedirect extends Template
{

    function DoneRedirect($page,$title,$params=array("action" => "edit"),$link="pageedit.php",$buttontext="Done")
    {
        parent::__construct();

        $this->vars['donebutton'] =new DoneButton($page, $params, $link, $buttontext, "mainoption");
        $params["page"] = $page;
        $this->stringvars['url'] =$link.makelinkparameters($params);
        $this->stringvars['title'] =$title;
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/donepage.tpl");
    }
}

function editedRedirect($page, $title)
{
    return new DoneRedirect($page, $title, array("action" => "edit"), "pageedit.php", "Edit this page");
}


/****
 * Buttons for navigator when editing a page 
 *******************************************/


//
//
//
class EditPageIntroSettingsButton extends Template
{

    function EditPageIntroSettingsButton()
    {
        parent::__construct();

        $pagetype = getpagetype($this->stringvars['page']);
        if($pagetype==="article") {
            $this->stringvars['buttontext']="Edit synopsis, source info & categories ...";
        }
        elseif($pagetype==="menu" || $pagetype==="articlemenu") {
            $this->stringvars['buttontext']="Edit synopsis & navigation options ...";
        }
        elseif($pagetype==="news") {
            $this->stringvars['buttontext']="Edit synopsis, rss & page order, or create archive ...";
        }
        else
        {
            $this->stringvars['buttontext']="Edit synopsis ...";
        }
        $this->stringvars['action']=getprojectrootlinkpath().'admin/edit/pageintrosettingsedit.php';
        $this->stringvars['hiddenvars'] = $this->makehiddenvars(array("action" => "editcontents"));
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/editpageintrosettingsbutton.tpl");
    }
}


//
//
//
class EditPageContentsButton extends Template
{

    function EditPageContentsButton()
    {
        parent::__construct();

        $pagetype = getpagetype($this->stringvars['page']);
        if($pagetype==="article") {
            $this->stringvars['action']=getprojectrootlinkpath().'admin/edit/articleedit.php';
            $this->stringvars['title']="Edit sections ...";
        }
        elseif($pagetype==="gallery") {
            $this->stringvars['action']=getprojectrootlinkpath().'admin/edit/galleryedit.php';
            $this->stringvars['title']="Edit images ...";
        }
        elseif($pagetype==="linklist") {
            $this->stringvars['action']=getprojectrootlinkpath().'admin/edit/linklistedit.php';
            $this->stringvars['title']="Edit links ...";
        }
        elseif($pagetype==="menu" || $pagetype==="articlemenu" || $pagetype==="linklistmenu") {
            $this->stringvars['action']=getprojectrootlinkpath().'admin/edit/menuedit.php';
            $this->stringvars['title']="Edit order of subpages ...";
        }
        elseif($pagetype==="news") {
            $this->stringvars['action']=getprojectrootlinkpath().'admin/edit/newsedit.php';
            $this->stringvars['title']="Edit newsitems ...";
        }
        else
        {
            $this->stringvars['action']="pageedit.php";
            $this->stringvars['title']="Edit page elements ...";
        }
        $this->stringvars['hiddenvars'] = $this->makehiddenvars(array("action" => "editcontents"));
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/editpagecontentsbutton.tpl");
    }
}


//
//
//
class GeneralSettingsButton extends Template
{

    function GeneralSettingsButton()
    {
        parent::__construct();

        $this->vars['button']= new DoneButton($this->stringvars['page'], array("action" => "edit"), getprojectrootlinkpath().'admin/pageedit.php', "General settings", "liteoption");
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/generalsettingsbutton.tpl");
    }
}



//
// firstbutton, secondbutton need to be of type Template
// otherwise, a stringvar is used
//
class PageEditNavigationButtons extends Template
{

    function PageEditNavigationButtons($firstbutton, $secondbutton)
    {
        parent::__construct();

        if($firstbutton instanceof Template) {
            $this->vars['firstbutton']= $firstbutton;
        } else {
            $this->stringvars['firstbutton']= $firstbutton;
        }

        if($secondbutton instanceof Template) {
            $this->vars['secondbutton']= $secondbutton;
        } else {
            $this->stringvars['secondbutton']= $secondbutton;
        }

        $this->vars['donebutton']= new DoneButton($this->stringvars['page'], array("action" => "show", "unlock" => "on"), getprojectrootlinkpath().'admin/admin.php');
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/pageeditnavigationbuttons.tpl");
    }
}



/****
 * Button row for subitting changes 
 *******************************************/


//
//
//
class SubmitRow extends Template
{

    function SubmitRow($submitname="submit",$submitlabel="Submit",$showreset=false,$showcancel=false,$cancellocation="",$jsid="")
    {
        parent::__construct($jsid);

        $this->stringvars['submit']=$submitname;
        $this->stringvars['submitlabel']=$submitlabel;
        if($showreset) {
            $this->stringvars['show_reset']="reset";
        }

        if($showcancel) {
            $this->stringvars['show_cancel']="cancel";

            if(strlen($cancellocation)>0) {
                $this->stringvars['cancellocation']=$cancellocation;
            } else {
                $this->stringvars['no_cancellocation']="true";
            }
        }
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/submitrow.tpl");
    }
}


//
//
//
class CancelConfirmButtons extends Template
{

    function CancelConfirmButtons($actionvars, $confirmvar, $cancelvar, $hiddenvars = "")
    {
        parent::__construct();

        $this->stringvars['actionvars']=$actionvars;
        $this->stringvars['hiddenvars']=$hiddenvars;
        $this->stringvars['confirmvar']=$confirmvar;
        $this->stringvars['cancelvar']=$cancelvar;
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/cancelconfirmbuttons.tpl");
    }
}

?>
