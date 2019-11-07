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

require_once $projectroot."includes/objects/template.php";
require_once $projectroot."admin/functions/usersmod.php";
require_once $projectroot."admin/functions/publicusersmod.php";
require_once $projectroot."includes/objects/elements.php";
require_once $projectroot."admin/includes/objects/forms.php";

//
//
//
class SiteCreatePublicUser extends Template
{

    function __construct($username, $message="", $newuserid=-1)
    {
        global $projectroot;
        parent::__construct();

        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["action"] = "siteusercreate";
        $this->stringvars['actionvars'] = makelinkparameters($linkparams);

        $linkparams["action"] = "siteuserman";
        $this->vars['submitrow']= new SubmitRow("createuser", "Create User", false, true, "admin.php".makelinkparameters($linkparams));

        $this->stringvars['username'] = title2html($username);

        if($newuserid >= 0) {
            $linkparams["userid"] = $newuserid;
            $linkparams["type"] = "public";
            $linkparams["action"] = "siteuserman";
            $this->stringvars['newuserlinks']='<p>The new user has been created. You can <a href="admin.php'.makelinkparameters($linkparams).'">Manage this user</a> now.</p>';
        }
        else
        {
            $this->stringvars['newuserlinks']="";
        }
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/site/createpublicuser.tpl");
    }
}



//
//
//
class SiteUserlist extends Template
{

    function __construct($ref)
    {
        parent::__construct();

        $users = getallusers();
        foreach ($users as $id => $data) {
            $this->listvars['adminusers'][]=new SiteUserlistAdminUser($id, $data, $ref);
        }

        $users=getallpublicusers();

        for($i=0; $i<count($users);$i++)
        {
            $this->listvars['publicusers'][]=new SiteUserlistPublicUser($users[$i], $ref);
        }
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/site/userlist.tpl");
    }
}



//
//
//
class SiteUserlistAdminUser extends Template
{

    function __construct($userid, $userdata, $ref)
    {
        parent::__construct();

        $lastlogin = $userdata['last_login'];
        $retries = $userdata['retries'];

        $this->stringvars['username'] = title2html($userdata['displayname']);

        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["userid"] = $userid;

        if (!empty($ref)) {
            $linkparams["action"] = $ref;
            $this->stringvars['reflink'] = makelinkparameters($linkparams);
        } else {
            $linkparams["action"] = "siteuserman";
            $this->stringvars['managelink'] = 'admin.php'.makelinkparameters($linkparams);
            $linkparams["action"] = "siteuserperm";
            $this->stringvars['permissionslink'] = 'admin.php'.makelinkparameters($linkparams);
        }

        $this->stringvars['email'] = $userdata['email'];

        if ($userdata['iscontact']) {
            $this->stringvars['iscontact']="Yes";
        } else {
            $this->stringvars['iscontact']="&mdash;";
        }

        $this->stringvars['contactfunction'] = title2html($userdata['contactfunction']);

        if ($userdata['user_active']) {
            $this->stringvars['isactive']="Yes";
        } else {
            $this->stringvars['isactive']="&mdash;";
        }

        if ($userdata['userlevel'] == USERLEVEL_USER) {
            $this->stringvars['userlevel']="User";
        } elseif ($userdata['userlevel'] == USERLEVEL_ADMIN) {
            $this->stringvars['userlevel']="Administrator";
        }

        $this->stringvars['lastlogin'] = $userdata['last_login'];
        $this->stringvars['retries'] = $userdata['retries'];
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/site/userlistadminuser.tpl");
    }
}

//
//
//
class SiteUserlistPublicUser extends Template
{

    function __construct($userid,$ref)
    {
        parent::__construct();

        $this->stringvars['username'] = title2html(getpublicusername($userid));

        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["userid"] = $userid;
        $linkparams["type"] = "public";

        if(strlen($ref) > 0) {
            $linkparams["action"] = $ref;
            $this->stringvars['reflink'] = makelinkparameters($linkparams);
        }
        else
        {
            $linkparams["action"] = "siteuserman";
            $this->stringvars['managelink'] = 'admin.php'.makelinkparameters($linkparams);
            $linkparams["action"] = "siteuserperm";
            $this->stringvars['permissionslink'] = 'admin.php'.makelinkparameters($linkparams);
        }

        if(ispublicuseractive($userid)) { $this->stringvars['isactive']="Yes";
        } else { $this->stringvars['isactive']="&mdash;";
        }

        $userpages=getpageaccessforpublicuser($userid);

        $noofpages=count($userpages);
        if(!$noofpages>0) {

            $this->stringvars['userpages']='<div align="center"> &mdash; </div>';
        }
        else
        {
            $this->stringvars['userpages']='';

            for($i=0;$i<$noofpages;$i++)
            {
                if($i>0) {
                    $this->stringvars['userpages'].=' &ndash; ';
                }
                $this->stringvars['userpages'].='<a href="'.getprojectrootlinkpath().'admin/pagedisplay.php'.makelinkparameters(array("page" => $userpages[$i])).'" target="_blank">'.$userpages[$i].": ".title2html(getnavtitle($userpages[$i])).'</a>';
            }
        }
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/site/userlistpublicuser.tpl");
    }
}

//
//
//
class SiteSelectUserForm extends Template
{

    function __construct($username="")
    {
        parent::__construct();

        $this->stringvars['username'] = title2html($username);

        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["action"] = "siteusercreate";
        $this->stringvars['createactionvars'] = makelinkparameters($linkparams);

        $linkparams["action"] = "siteuserman";
        $this->stringvars['selectactionvars'] = makelinkparameters($linkparams);

        $linkparams["ref"] = "siteuserman";
        $linkparams["action"] = "siteuserlist";
        $this->stringvars['userlistlink'] = makelinkparameters($linkparams);
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/site/selectuserform.tpl");
    }
}

//
//
//
class SiteAdminUserProfileForm extends Template
{

    function __construct($userid)
    {
        parent::__construct();

        $linkparams=array();
        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["userid"] = $userid;
        $linkparams["profile"] = "change";
        $linkparams["action"] = "siteuserman";
        $this->stringvars['profileactionvars'] = makelinkparameters($linkparams);

        $linkparams=array();
        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["userid"] = $userid;
        $linkparams["action"] = "siteuserman";
        $this->stringvars['activateactionvars'] = makelinkparameters($linkparams);

        $linkparams=array();
        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["userid"] = $userid;
        $linkparams["generate"] = "generate";
        $linkparams["action"] = "siteuserman";
        $this->stringvars['passgenactionvars'] = makelinkparameters($linkparams);

        $linkparams=array();
        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["userid"] = $userid;
        $linkparams["contact"] = "contact";
        $linkparams["action"] = "siteuserman";
        $this->stringvars['contactactionvars'] = makelinkparameters($linkparams);

        $this->stringvars['hiddenvars'] = $this->makehiddenvars(array("userid" => $userid));

        $userdata = getuser($userid, array('user_id', 'displayname', 'email', 'contactfunction', 'iscontact', 'user_active'));

        $this->stringvars['username'] = title2html($userdata['displayname']);
        $this->stringvars['email'] = $userdata['email'];
        $this->stringvars['contactfunction'] = input2html($userdata['contactfunction']);

        if ($userdata['user_active']) {
            $this->stringvars['isactive']="true";
        } else {
            $this->stringvars['notactive']="true";
        }

        if ($userdata['iscontact']) {
            $this->stringvars['iscontact']="true";
        }
        $this->vars['iscontactform']= new CheckboxForm("iscontact", "iscontact", "<em>".$this->stringvars['username']."</em> can be contacted through the contact page:", $userdata['iscontact']);

        $linkparams=array();
        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["action"] = "siteuserman";
        $this->stringvars['returnlink'] = makelinkparameters($linkparams);

        $linkparams=array();
        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["userid"] = $userid;
        $linkparams["action"] = "siteuserperm";
        $this->stringvars['permissionslink'] = makelinkparameters($linkparams);

        $linkparams=array();
        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["action"] = "siteuserlist";
        $this->stringvars['userlistlink'] = makelinkparameters($linkparams);
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/site/adminuserprofileform.tpl");
    }
}

//
//
//
class SitePublicUserProfileForm extends Template
{

    function __construct($userid)
    {
        parent::__construct();

        $this->stringvars['username'] = title2html(getpublicusername($userid));
        $this->stringvars['userid']=$userid;

        $linkparams=array();
        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["userid"] = $userid;
        $linkparams["profile"] = "change";
        $linkparams["type"] = "public";
        $linkparams["action"] = "siteuserman";
        $this->stringvars['profileactionvars'] = makelinkparameters($linkparams);

        $linkparams=array();
        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["userid"] = $userid;
        $linkparams["type"] = "public";
        $linkparams["action"] = "siteuserman";
        $this->stringvars['activateactionvars'] = makelinkparameters($linkparams);

        $this->stringvars['hiddenvars'] = $this->makehiddenvars(array("userid" => $userid));

        if(ispublicuseractive($userid)) { $this->stringvars['isactive']="true";
        } else { $this->stringvars['notactive']="true";
        }


        $linkparams=array();
        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["action"] = "siteuserman";
        $this->stringvars['returnlink'] = makelinkparameters($linkparams);

        $this->vars['submitrow']= new SubmitRow("profile", "Change Password", false, true, $this->stringvars['returnlink']);

        $linkparams=array();
        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["userid"] = $userid;
        $linkparams["type"] = "public";
        $linkparams["action"] = "siteuserperm";
        $this->stringvars['permissionslink'] = makelinkparameters($linkparams);

        $linkparams=array();
        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["action"] = "siteuserlist";
        $this->stringvars['userlistlink'] = makelinkparameters($linkparams)."#public";
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/site/publicuserprofileform.tpl");
    }
}
?>
