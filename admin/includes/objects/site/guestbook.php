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
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."includes/objects/template.php";
require_once $projectroot."includes/objects/page.php";
require_once $projectroot."functions/guestbook.php";
require_once $projectroot."includes/objects/elements.php";
require_once $projectroot."admin/includes/objects/editor.php";
require_once $projectroot."admin/includes/objects/forms.php";


//
// List entries in Guestbook
//
class AdminGuestbookEntryList extends Template
{

    function __construct($number, $offset)
    {
        parent::__construct();
        $entries=getguestbookentries($number, $offset);

        $this->vars['enableform'] = new AdminGuestbookEnableForm();

        $this->vars['guestbookintro']= new Editor($this->stringvars['page'], 0, "guestbook", "Guestbook Intro");
        $this->vars['contactintro']= new Editor($this->stringvars['page'], 0, "contact", "Contact Form Intro");

        $this->vars['pagemenu']=new PageMenu($offset, $number, countguestbookentries(), array("action" => "siteguest"));


        if(count($entries)==0) {
            $this->stringvars['no_entries']= getlang("guestbook_nomessages");
        }
        else
        {
            for($i=0;$i<count($entries);$i++)
            {
                $this->listvars['entries'][] = new AdminGuestbookEntry($entries[$i]);
            }
        }
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/site/guestbookentrylist.tpl");
    }

}



//
// Entry displayed in Guestbook
//
class AdminGuestbookEntry extends Template
{

    function __construct($entryid, $showdeleteform=true)
    {
        parent::__construct();

        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["action"] = "siteguest";
        $this->stringvars['deleteactionvars'] = makelinkparameters($linkparams);
        $this->stringvars['hiddenvars'] = $this->makehiddenvars(array("messageid" => $entryid));

        $contents=getguestbookentrycontents($entryid);
        $this->stringvars['name']=title2html($contents["name"]);
        $this->stringvars['email']=title2html($contents["email"]);
        $this->stringvars['date']=formatdatetime($contents["date"]);
        $this->stringvars['subject']=title2html($contents["subject"]);
        $this->stringvars['message']=text2html($contents["message"]);

        if($showdeleteform) { $this->stringvars['deleteform']="deleteform";
        }

        $this->stringvars['l_toppage']=getlang("pagemenu_topofthispage");
        $this->stringvars['l_name']=getlang("guestbook_name");
        $this->stringvars['l_email']=getlang("guestbook_email");
        $this->stringvars['l_date']=getlang("guestbook_date");
        $this->stringvars['l_subject']=getlang("guestbook_subject");
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/site/guestbookentry.tpl");
    }

}





//
// To confirm deleting of entry
//
class AdminGuestbookDeleteConfirmForm extends Template
{

    function __construct($entryid)
    {
        parent::__construct();
        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["action"] = "siteguest";
        $this->stringvars['actionvars'] = makelinkparameters($linkparams);
        $this->stringvars['hiddenvars'] = $this->makehiddenvars(array("messageid" => $entryid));
        $this->vars['entry']=new AdminGuestbookEntry($entryid, false);
        $this->vars['confirmbuttons'] = new CancelConfirmButtons($this->stringvars['actionvars'], "deleteconfirm", "deleteabort", $this->stringvars['hiddenvars']);
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/site/guestbookdeleteconfirmform.tpl");
    }
}




//
// To switch guestbook on and off
//
class AdminGuestbookEnableForm extends Template
{

    function __construct()
    {
        parent::__construct();

        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["postaction"] = "saveproperties";
        $linkparams["action"] = "siteguest";
        $this->stringvars['enableactionvars'] = makelinkparameters($linkparams);

        $properties=getproperties();

        $this->vars['enableguestbook_yes'] = new RadioButtonForm($this->stringvars['jsid'], "enableguestbook", 1, "Yes", $properties["Enable Guestbook"], "right");
        $this->vars['enableguestbook_no'] = new RadioButtonForm($this->stringvars['jsid'], "enableguestbook", 0, "No", !$properties["Enable Guestbook"], "right");

        $this->stringvars['entriesperpage']=$properties["Guestbook Entries Per Page"];

        $this->vars['submitrow']= new SubmitRow("saveproperties", "Submit", true);
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/site/guestbookenableform.tpl");
    }

}

?>
