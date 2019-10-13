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
require_once $projectroot."functions/pagecontent/linklistpages.php";
require_once $projectroot."includes/objects/template.php";
require_once $projectroot."admin/includes/objects/editor.php";
require_once $projectroot."admin/includes/objects/imageeditor.php";

//
//
//
class AddLinklistLinkForm extends Template
{
    function AddLinklistLinkForm()
    {
        parent::__construct();
        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["action"] = "editcontents";
        $this->stringvars['actionvars']= makelinkparameters($linkparams);
        $this->vars['submitrow'] = new SubmitRow("addlink", "Add Link", true);
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/edit/addlinklistlinkform.tpl");
    }
}


//
//
//
class EditLinkListLinkForm extends Template
{
    function EditLinkListLinkForm($linkid)
    {
        parent::__construct($linkid, array(), array(0 => "admin/includes/javascript/editlinklist.js"));
        $this->stringvars['javascript']=$this->getScripts();

        $this->stringvars['hiddenvars'] = $this->makehiddenvars(array("linkid" => $linkid));

        $linkparams["page"] = $this->stringvars['page'];
        $this->stringvars['imagelistpath']=getprojectrootlinkpath()."admin/editimagelist.php".makelinkparameters($linkparams);

        $linkparams["link"] = $linkid;
        $linkparams["action"] = "editcontents";
        $this->stringvars['actionvars']= makelinkparameters($linkparams);

        $this->stringvars['linkid']=$linkid;

        $contents=getlinkcontents($linkid);

        $this->stringvars['linktitle']=title2html($contents['title']);
        if(!$contents['title']) {
            $this->stringvars['linktitle'] = "New Link";
        }

        $this->stringvars['linkinputtitle']=input2html($contents['title']);
        $this->stringvars['link']=$contents['link'];
        $this->stringvars['description']=text2html($contents['description']);

        $this->vars['imageeditor'] = new ImageEditor($this->stringvars['page'], $linkid, "link", $contents);

        $this->vars['editdescription']= new Editor($this->stringvars['page'], $linkid, "link", "Link Description");
        $this->vars['deleteconfirmform']= new CheckboxForm("deletelinkconfirm", "deletelinkconfirm", "Confirm delete", false, "right");
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/edit/editlinklistlinkform.tpl");
    }
}


//
//
//
class EditLinklist extends Template
{
    function EditLinklist($page)
    {
        parent::__construct($page, array(0 => "includes/javascript/jcaret.js"));
        $this->stringvars['javascript']=$this->getScripts();

        $linkparams["page"] = $this->stringvars['page'];
        $this->stringvars['imagelistpath']=getprojectrootlinkpath()."admin/editimagelist.php".makelinkparameters($linkparams);

        $linkparams["action"] = "editcontents";
        $this->stringvars['actionvars']= makelinkparameters($linkparams);

        $linkids=getlinklistitems($page);
        if(count($linkids) < 1) { $this->stringvars['linkform']="";
        }

        for($i=0;$i<count($linkids);$i++)
        {
            $this->listvars['linkform'][] = new EditLinkListLinkForm($linkids[$i]);
        }

        $this->vars['addform'] = new AddLinklistLinkForm();
        $this->vars['navigationbuttons']= new PageEditNavigationButtons(new GeneralSettingsButton(), new EditPageIntroSettingsButton());
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/edit/editlinklist.tpl");
    }
}

?>
