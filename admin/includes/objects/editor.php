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
$projectroot=substr($projectroot, 0, strrpos($projectroot, "objects"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."includes/functions.php";
require_once $projectroot."includes/objects/template.php";
require_once $projectroot."includes/objects/forms.php";
require_once $projectroot."functions/pages.php";
require_once $projectroot."functions/pagecontent/articlepages.php";
require_once $projectroot."functions/pagecontent/linklistpages.php";
require_once $projectroot."functions/pagecontent/newspages.php";
require_once $projectroot."includes/objects/elements.php";
require_once $projectroot."includes/objects/images.php";


//
// Templating for Editor
//
class Editor extends Template
{

    function Editor($page,$item, $elementtype, $title="Text", $iscollapsed=true)
    {
        parent::__construct($page.'-'.$item, array(), array(0 => "admin/includes/javascript/editor.js"));
        $this->stringvars['javascript']=$this->getScripts();

        $this->stringvars['item']=$item;
        $this->stringvars['elementtype']=$elementtype;
        $this->stringvars['title']=$title;

        if($iscollapsed) {
            $this->vars['editorcontents']= new EditorContentsCollapsed($page, $item, $elementtype, "Edit ".$title);
        } else {
            $this->vars['editorcontents']= new EditorContentsExpanded($page, $item, $elementtype, "Edit ".$title);
        }

        $this->stringvars['previewtext']=text2html(geteditortext($page, $item, $elementtype));
        $this->stringvars['hiddenvars'] = $this->makehiddenvars();
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/editor.tpl");
    }
}

//
// expanded editor contents
//
class EditorContentsExpanded extends Template
{

    function EditorContentsExpanded($page,$item, $elementtype,$title="Edit text", $edittext=false)
    {
        parent::__construct($page.'-'.$item);

        $hiddenvars["page"] = $page;
        $hiddenvars["item"] = $item;
        $hiddenvars["title"] = $title;
        $hiddenvars["elementtype"] = $elementtype;
        $this->stringvars['hiddenvars'] = $this->makehiddenvars($hiddenvars);

        $this->stringvars['title']=$title;

        if($edittext!=false) {
            $edittext = stripslashes(stripslashes($edittext));
            $this->stringvars['text']=$edittext;
            $this->stringvars['previewtext']=text2html($edittext);
        }
        else
        {
            $text = geteditortext($page, $item, $elementtype);
            $this->stringvars['text']=input2html($text);
              $this->stringvars['previewtext']=text2html($text);
        }
        $this->vars['styleform']=new OptionForm("0", array(0=>"0", 1=>"en"), array(0=>"-- Style --", 1=>"English"), $this->stringvars['jsid']."styleform", "", 1);
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/editorcontentsexpanded.tpl");
    }
}


//
// expanded editor contents
//
class EditorContentsCollapsed extends Template
{

    function EditorContentsCollapsed($page,$item, $elementtype, $title="Edit text")
    {
        parent::__construct($page.'-'.$item);

        $hiddenvars["page"] = $page;
        $hiddenvars["item"] = $item;
        $hiddenvars["title"] = $title;
        $hiddenvars["elementtype"] = $elementtype;
        $this->stringvars['hiddenvars'] = $this->makehiddenvars($hiddenvars);

        $this->stringvars['title']=$title;
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/editorcontentscollapsed.tpl");
    }
}

//
// expanded editor contents
//
class EditorContentsSaveDialog extends Template
{

    function EditorContentsSaveDialog($page,$item, $elementtype, $edittext,$title="Edit text")
    {
        parent::__construct($page.'-'.$item);

        $hiddenvars["page"] = $page;
        $hiddenvars["item"] = $item;
        $hiddenvars["title"] = $title;
        $hiddenvars["elementtype"] = $elementtype;
        $hiddenvars["edittext"] = htmlspecialchars($edittext);
        $this->stringvars['hiddenvars'] = $this->makehiddenvars($hiddenvars);
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/editorcontentssavedialog.tpl");
    }
}




//
// helper function to get text for editor from database
//
function geteditortext($page,$item, $elementtype)
{
    $text="Text could not be loaded for ".$elementtype.", page ".$page.", item ".$item.".";

    if($elementtype==="pageintro") {
        $text=getpageintrotext($page);
    } elseif($elementtype==="articlesection") {
        $text=getarticlesectiontext($item);
    } elseif($elementtype==="link") {
        $text=getlinkdescription($item);
    } elseif($elementtype==="newsitemsynopsis") {
        $text=getnewsitemsynopsistext($item);
    } elseif($elementtype==="newsitemsection") {
        $text=getnewsitemsectiontext($item);
    } elseif($elementtype==="sitepolicy" || $elementtype==="guestbook" || $elementtype==="contact") {
        $sql = new SQLSelectStatement(SPECIALTEXTS_TABLE, 'text', array('id'), array($elementtype), 's');
        $text= $sql->fetch_value();
    }
    return stripslashes($text);
}


?>
