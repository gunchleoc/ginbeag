<?php
/**
 * An Gineadair Beag is a content management system to run websites with.
 *
 * PHP Version 7
 *
 * Copyright (C) 2005-2019 GunChleoc
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 * @category Ginbeag
 * @package  Ginbeag
 * @author   gunchleoc <fios@foramnagaidhlig.net>
 * @license  https://www.gnu.org/licenses/agpl-3.0.en.html GNU AGPL
 * @link     https://github.com/gunchleoc/ginbeag/
 */

$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."admin/includes/objects/forms.php";
require_once $projectroot."functions/pagecontent/externalpages.php";
require_once $projectroot."functions/pages.php";
require_once $projectroot."includes/objects/template.php";
require_once $projectroot."includes/includes.php";
require_once $projectroot."includes/objects/forms.php";
require_once $projectroot."includes/objects/page.php";
require_once $projectroot."admin/includes/objects/editor.php"; // todo only imported for the header for now. Overkill?




//
//
//
class DeletePageConfirmForm extends Template
{

    function __construct()
    {
        parent::__construct();

        $this->stringvars['actionvars']= makelinkparameters(array("page" => $this->stringvars['page']));

        $this->stringvars['pagetitle']=title2html(getpagetitle($this->stringvars['page']));

        $children=getchildren($this->stringvars['page']);
        if(count($children)) {
            $this->stringvars['deletemessage']="Are you sure you want to delete all these pages?";
            for($i=0;$i<count($children);$i++)
            {
                $this->listvars['subpages'][]= new NavigatorBranch($children[$i], "simple", 5000, 0, "", true);
            }
        }
        else
        {
            $this->stringvars['deletemessage']="Are you sure you want to delete this page?";
        }
        $this->vars['confirmbuttons'] = new CancelConfirmButtons($this->stringvars['actionvars'], "executedelete", "nodelete");
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
class FindNewParentForm extends Template
{

    function __construct()
    {
        parent::__construct();
        $this->stringvars['actionvars']= makelinkparameters(array("page" => $this->stringvars['page'], "action" => "findnewparent"));
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
class SelectNewParentForm extends Template
{

    function __construct()
    {
        parent::__construct();

        $this->stringvars['actionvars']= makelinkparameters(array("page" => $this->stringvars['page'], "action" => "newparent"));
        $this->stringvars['pagetitle']=title2html(getpagetitle($this->stringvars['page']));

        $values=array();
        $descriptions=array();

        $allpages= getmovetargets($this->stringvars['page']);
        $i=0;
        if(array_key_exists(0, $allpages)) {
            $values[]=0;
            $descriptions[]="Site Root";
            $i=1;
        }

        for(;$i<count($allpages);$i++)
        {
            $values[]=$allpages[$i];
            $descriptions[]=$allpages[$i].': '.title2html(getnavtitle($allpages[$i]));
        }

        $this->vars['targetform']= new OptionForm(0, $values, $descriptions, "parentnode", "Move this page to:", 20);
        $this->stringvars['cancellocation']=makelinkparameters(array("page" => $this->stringvars['page'], "action" => "edit"));
        $this->vars['submitrow'] = new SubmitRow("newparent", "Select Destination", false, true, makelinkparameters(array("page" => $this->stringvars['page'], "action" => "edit")), $this->stringvars["jsid"]);

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
class RestrictAccessForm extends Template
{

    function __construct()
    {
        parent::__construct();

        $this->stringvars['actionvars']= makelinkparameters(array("page" => $this->stringvars['page'], "action" => "restrictaccess"));
        $this->stringvars['usersactionvars']= makelinkparameters(array("page" => $this->stringvars['page'], "action" => "restrictaccessusers"));

        $accessrestricted=isthisexactpagerestricted($this->stringvars['page']);

        $this->vars['restrict_yes']= new RadioButtonForm("", "restrict", "1", "Yes", $accessrestricted);
        $this->vars['restrict_no']= new RadioButtonForm("", "restrict", "0", "No", !$accessrestricted);

        $this->vars['submitrow']= new SubmitRow("restrictaccess", "Change Access Restriction", true);

        if($accessrestricted) {
            $this->stringvars['accessrestricted']="Access restricted";
            $accessusers=getallpublicuserswithaccessforpage($this->stringvars['page']);
            if(count($accessusers)==0) {
                $this->stringvars['restricteduserlist']='<em>No users have access to this page</em>';
            }
            else
            {
                $this->stringvars['restricteduserlist']='<span class="highlight">The following users have access to this page:</span><br /><em>';
                for($i=0;$i<count($accessusers);$i++)
                {
                    $this->stringvars['restricteduserlist'].=input2html(getpublicusername($accessusers[$i]))." ";
                }
                $this->stringvars['restricteduserlist'].='</em>';
            }

            $values=array();
            $descriptions=array();
            $allpublicusers=getallpublicusers();

            for($i=0;$i<count($allpublicusers);$i++)
            {
                $values[]=$allpublicusers[$i];
                $descriptions[]=title2html(getpublicusername($allpublicusers[$i]));
            }
            $this->vars['selectusers']= new OptionForm(0, $values, $descriptions, "selectusers[]", "Select Users: ", 5, "multiple");
        }
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
class PermissionsForm extends Template
{

    function __construct($permissions)
    {
        parent::__construct();

        $this->stringvars['actionvars']= makelinkparameters(array("page" => $this->stringvars['page'], "action" => "setpermissions"));

        $this->stringvars['copyright']=input2html($permissions['copyright']);
        $this->stringvars['image_copyright']=input2html($permissions['image_copyright']);

        $this->vars['permission_granted']= new RadioButtonForm("", "permission", PERMISSION_GRANTED, "Permission granted", $permissions['permission']==PERMISSION_GRANTED, "right");
        $this->vars['no_permission']= new RadioButtonForm("", "permission", NO_PERMISSION, "No permission", $permissions['permission']==NO_PERMISSION, "right");

        $this->vars['submitrow']= new SubmitRow("setpermissions", "Change Copyright and Permissions", true);
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
class RenamePageForm extends Template
{

    function __construct()
    {
        parent::__construct();

        $this->stringvars['actionvars']= makelinkparameters(array("page" => $this->stringvars['page'], "action" => "rename"));
        $this->stringvars['navtitle']=input2html(getnavtitle($this->stringvars['page']));
        $this->stringvars['pagetitle']=input2html(getpagetitle($this->stringvars['page']));
        $this->vars['submitrow']= new SubmitRow("submit", "Rename", true);
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
class SetPublishableForm extends Template
{

    function __construct()
    {
        parent::__construct();

        $this->stringvars['actionvars']= makelinkparameters(array("page" => $this->stringvars['page'], "action" => "setpublishable"));

        $ispublishable=ispublishable($this->stringvars['page']);
        $this->vars['publishable_yes']= new RadioButtonForm("", "ispublishable", "public", "Public page", $ispublishable);
        $this->vars['publishable_no']= new RadioButtonForm("", "ispublishable", "internal", "Internal page", !$ispublishable);

        $this->vars['submitrow']= new SubmitRow("submit", "Change Setting", true);
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
class ExternalForm extends Template
{

    function __construct()
    {
        parent::__construct();

        $this->stringvars['actionvars']= makelinkparameters(array("page" => $this->stringvars['page'], "action" => "edit"));
        $this->stringvars['link']=getexternallink($this->stringvars['page']);
        $this->vars['submitrow'] = new SubmitRow("changelink", "Change Link", true);
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
class EditPage extends Template
{
    function __construct($page) {
        parent::__construct($page, array(), array(0 => "admin/includes/javascript/editpage.js"));

        $pagecontents = getpagecontents($page);

        if ($pagecontents['pagetype'] === "external") {
            $this->vars['contentsform']= new ExternalForm();
            $this->vars['navigationbuttons']= new PageEditNavigationButtons("", "");
        } else {
            $this->vars['navigationbuttons']= new PageEditNavigationButtons(new EditPageIntroSettingsButton(), new EditPageContentsButton());
            $this->vars['permissionsform']= new PermissionsForm($pagecontents);
            $this->vars['restrictaccessform']=  new RestrictAccessForm();
        }

        $this->vars['renamepageform']= new RenamePageForm();

        $this->vars['setpublishableform']= new SetPublishableForm();

        $this->vars['movepageform']= new MovePageForm($page, $page);

        $this->vars['findnewparentform']= new FindNewParentForm();
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/editpage.tpl");
    }
}
?>
