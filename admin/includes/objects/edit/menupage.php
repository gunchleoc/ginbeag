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

require_once $projectroot."functions/pagecontent/menupages.php";
require_once $projectroot."includes/objects/template.php";
require_once $projectroot."includes/objects/images.php";
require_once $projectroot."admin/includes/objects/forms.php";
require_once $projectroot."admin/includes/objects/editor.php";
require_once $projectroot."admin/includes/objects/imageeditor.php";
require_once $projectroot."admin/functions/pagesmod.php";

//
//
//
class EditMenuLevelsForm extends Template
{
    function __construct($page,$sistersinnavigator,$pagelevel,$navigatorlevel)
    {
        parent::__construct($page, array(), array(0 => "admin/includes/javascript/editmenu.js"));
        $this->stringvars['javascript']=$this->getScripts();
        $this->stringvars['hiddenvars'] = $this->makehiddenvars();

        $this->vars['pagelevelsform']= new NumberOptionForm($pagelevel, 1, 10, false, $this->stringvars['jsid'], "pagelevels", "pagelevels");
        $this->vars['navigatorlevelsform']=new NumberOptionForm($navigatorlevel, 1, 10, false, $this->stringvars['jsid'], "navlevels", "navlevels");
        $this->vars["sistersinnavigator"]= new CheckboxForm("sisters", "1", "List items in same level", $sistersinnavigator, "right");
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/edit/menulevelsform.tpl");
    }
}


//
//
//
class MenuMovePageForm extends Template
{
    function __construct($page,$position,$noofelements,$title,$jsid,$movepageform)
    {
        parent::__construct($jsid, array(), array(0 => "admin/includes/javascript/editmenumovepage.js"));
        $this->stringvars['javascript']=$this->getScripts();

        $this->stringvars['page']=$page;
        $hiddenvars["position"] = $position;
        $hiddenvars["noofelements"] = $noofelements;
        $this->stringvars['hiddenvars'] = $this->makehiddenvars($hiddenvars);

        $this->stringvars['title']=title2html($title);
        $this->vars['movepageform']= $movepageform;
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/edit/menumovepageform.tpl");
    }
}

//
//
//
class MenuMovePageFormContainer extends Template
{
    function __construct($page,$subpageids)
    {
        parent::__construct();

        $titles_navigator=getallsubpagenavtitles($page);

        for($i=0;$i<count($subpageids);$i++)
        {
            $this->listvars['movepageform'][] = new MenuMovePageForm($page, $i, count($subpageids), $titles_navigator[$i], $subpageids[$i], new MovePageForm($page, $subpageids[$i]));
        }
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/edit/menumovepageformcontainer.tpl");
    }
}




//
//
//
class EditMenuSubpages extends Template
{
    function __construct($page)
    {
        parent::__construct($page, array(0 => "includes/javascript/jcaret.js"));
        $this->stringvars['javascript']=$this->getScripts();

        $linkparams["page"] = $page;
        $linkparams["action"] = "editcontents";
        $this->stringvars['actionvars']= makelinkparameters($linkparams);

        $subpageids = getchildren($page);
        if (!empty($subpageids)) {
            $this->vars['movepageform'] = new MenuMovePageFormContainer($page, $subpageids);
        } else {
            $this->vars['movepageform'] = "This menu has no subpages.";
        }

        $this->vars['navigationbuttons']= new PageEditNavigationButtons(new GeneralSettingsButton(), new EditPageIntroSettingsButton());
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/edit/editmenusubpages.tpl");
    }
}



//
//
//
class EditMenu extends Template
{
    function __construct($page)
    {
        parent::__construct($page, array(0 => "includes/javascript/jcaret.js"));
        $this->stringvars['javascript']=$this->getScripts();

        $this->vars['intro']= new Editor($page, 0, "pageintro", "Synopsis");
        $this->vars['imageeditor'] = new ImageEditor($page, 0, "pageintro", getpageintroimage($page));

        $contents=getmenucontents($page);

        $this->vars['menulevelsform'] = new EditMenuLevelsForm($page, $contents['sistersinnavigator'], $contents['displaydepth'], $contents['navigatordepth']);

        $this->vars['navigationbuttons']= new PageEditNavigationButtons(new GeneralSettingsButton(), new EditPageContentsButton());
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/edit/editmenu.tpl");
    }
}

?>
