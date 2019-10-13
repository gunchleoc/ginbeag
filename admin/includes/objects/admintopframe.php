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

require_once $projectroot."functions/pages.php";
require_once $projectroot."admin/functions/sessions.php";
require_once $projectroot."includes/objects/template.php";
require_once $projectroot."includes/objects/forms.php";
require_once $projectroot."admin/includes/objects/forms.php";
require_once $projectroot."includes/objects/elements.php";
require_once $projectroot."admin/includes/actions.php";

//
// Templating for Admin Navigator
//
class AdminTopFrameLink extends Template
{

    function AdminTopFrameLink($link,$linktitle,$params=array(),$target="")
    {
        parent::__construct();
        $params["page"] = $this->stringvars['page'];
        $this->stringvars['link']=getprojectrootlinkpath()."admin/".$link.makelinkparameters($params);
        $this->stringvars['linktitle']=$linktitle;
        $this->stringvars['target']=$target;
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/admintopframelink.tpl");
    }
}


//
// Templating for Admin Navigator in left frame
//
class AdminTopFrame extends Template
{

    function AdminTopFrame($page,$action)
    {
        parent::__construct();

        $this->stringvars['sitename']=title2html(getproperty("Site Name"));

        if(isloggedin()) {
            if($page) {
                $this->stringvars['pagetitle']=title2html(getnavtitle($page));
                $this->stringvars['publishformactionlink']=getprojectrootlinkpath()."admin/pageedit.php";
                if(ispublished($page)) {
                    $this->vars['publishlink']=new AdminTopFrameLink("pageedit.php", "Hide Page", array("action" => "unpublish"));
                    $this->stringvars['published']="(published)";
                }
                elseif(ispublishable($page)) {
                    $this->vars['publishlink']=new AdminTopFrameLink("pageedit.php", "Publish Page", array("action" => "publish"));
                }
            }
            else
            {
                $this->stringvars['pagetitle']="No page selected";
            }
            if($action == "pagenew") { $this->stringvars['newpagelink']="New Page";
            } else { $this->vars['newpagelink']=new AdminTopFrameLink("pagenew.php", "New Page");
            }


            if($action == "edit" || $action == "editcontents" || $action == "editpageintro") {
                $this->vars['donelink']=new AdminTopFrameLink("admin.php", "Done", array("action" => "show", "unlock" => "on"));
                $this->stringvars['editpagelink']="Edit Page";
            }
            elseif($this->stringvars['page']) {
                $pagetype=getpagetype($page);
                if($pagetype==="article") {
                    $this->vars['editpagelink']=new AdminTopFrameLink("edit/articleedit.php", "Edit Page");
                }
                elseif($pagetype==="gallery") {
                    $this->vars['editpagelink']=new AdminTopFrameLink("edit/galleryedit.php", "Edit Page");
                }
                elseif($pagetype==="linklist") {
                    $this->vars['editpagelink']=new AdminTopFrameLink("edit/linklistedit.php", "Edit Page");
                }
                elseif($pagetype==="menu" || $pagetype==="articlemenu" || $pagetype==="linklistmenu") {
                    $this->vars['editpagelink']=new AdminTopFrameLink("edit/menuedit.php", "Edit Page");
                }
                elseif($pagetype==="news") {
                    $this->vars['editpagelink']=new AdminTopFrameLink("edit/newsedit.php", "Edit Page");
                }
                else
                {
                    $this->vars['editpagelink']=new AdminTopFrameLink("pageedit.php", "Edit Page", array("action" => "edit"));
                }
            }
            $this->vars['previewpagelink']=new AdminTopFrameLink("pagedisplay.php", "Preview Page", array(), "_blank");

            if($action == "pagedelete") { $this->stringvars['deletepagelink']="Delete Page";
            } elseif($this->stringvars['page']) { $this->vars['deletepagelink']=new AdminTopFrameLink("pagedelete.php", "Delete Page", array("action" => "delete"));
            }
            $this->vars['imageslink']=new AdminTopFrameLink("editimagelist.php", "Images", array(), "_blank");

            if($action == "editcategories") { $this->stringvars['categorieslink']="Categories";
            } else { $this->vars['categorieslink']=new AdminTopFrameLink("editcategories.php", "Categories");
            }

            if(issiteaction($action)) {
                $this->stringvars['siteadminlink']="Site";
                $this->vars['returnpageeditinglink']=new AdminTopFrameLink("admin.php", "Return to Page Editing");
                $this->stringvars['showsitelinks']="on";
            }
            else
            {
                $this->vars['siteadminlink']=new AdminTopFrameLink("admin.php", "Site", array("action" => "site"));
                $this->stringvars['showeditlinks']="on";
            }
            $profilelinktitle="Profile [".title2html(getusername(getsiduser()))."]";
            if($action == "profile") { $this->stringvars['profilelink']=$profilelinktitle;
            } else { $this->vars['profilelink']=new AdminTopFrameLink("profile.php", $profilelinktitle);
            }

            $this->vars['logoutlink']=new AdminTopFrameLink("admin.php", "Logout", array("logout" => "on"), "_top");
            $this->stringvars['onlineusers']=implode(", ", getloggedinusers());
        }
        else
        {
            $this->vars['registerlink']=new AdminTopFrameLink("register.php", "Register");
            $this->vars['loginlink']=new AdminTopFrameLink("login.php", "Login", array(), "_top");
        }
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/admintopframe.tpl");
    }
}

?>
