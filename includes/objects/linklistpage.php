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

require_once $projectroot."functions/pagecontent/linklistpages.php";
require_once $projectroot."includes/objects/template.php";
require_once $projectroot."includes/objects/images.php";
require_once $projectroot."includes/includes.php";

//
// a link in a linklist
//
class LinklistLink extends Template
{

    function __construct($linkid, $contents) {
        parent::__construct();
        $this->stringvars['title'] = title2html($contents['title']);
        $this->stringvars['link'] = $contents['link'];
        $this->stringvars['linkid'] = $linkid;

        // image permissions checked in LinkList
        if (!empty($contents['image_filename'])) {
            $contents['imageautoshrink'] = true;
            $contents['usethumbnail'] = true;
            $contents['imagealign'] = 'left';
            $contents['imageautoshrink'] = true;
            $contents['title'] = $this->stringvars['title'];
            $contents = Image::make_imagedata($contents);
            $this->vars['image'] = new Image($contents['image_filename'], $contents);
        }

        $this->stringvars['text'] = text2html($contents['description']);
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("pages/linklist/linklistlink.tpl");
    }
}

//
// main class for linklistpages
//
class LinklistPage extends Template
{

    function __construct($introcontents, $showhidden) {
        parent::__construct();
        $linkparams["printview"]="on";
        $linkparams["page"]=$this->stringvars['page'];

        $this->vars['printviewbutton']= new LinkButton(makelinkparameters($linkparams), getlang("pagemenu_printview"), "img/printview.png");

        $this->vars['pageintro'] = new PageIntro($introcontents['title_page'], $introcontents['introtext'], "introtext", $introcontents, $showhidden);

        // links
        $links = getlinklistitems($this->stringvars['page']);
        foreach ($links as $linkid => $contents) {
            $this->listvars['link'][]= new LinkListLink($linkid, $contents);
        }

        $this->vars['editdata']= new Editdata($introcontents, $showhidden);
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("pages/linklist/linklistpage.tpl");
    }
}




//
// main class for linklistpages
//
class LinklistPagePrintview extends Template
{

    function __construct($introcontents, $showhidden)
    {
        parent::__construct();

        $this->vars['pageintro'] = new PageIntro("", $introcontents['introtext'], "introtext", $introcontents, $showhidden);

        $this->stringvars['pagetitle']=title2html($introcontents['title_page']);

        $links = getlinklistitems($this->stringvars['page']);
        foreach ($links as $linkid => $contents) {
            $this->listvars['link'][]= new LinkListLink($linkid, $contents);
        }

        $this->vars['editdata']= new Editdata($introcontents);
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("pages/linklist/linklistpage.tpl");
    }
}

?>
