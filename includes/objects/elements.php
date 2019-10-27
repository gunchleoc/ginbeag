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

require_once $projectroot."includes/objects/template.php";

//
// a general header
//
class HTMLHeader extends Template
{

    function __construct($title,$headertitle,$message="", $redirecturl="",$urltext="If the page does not load, use this link", $isredirect=false,$stylesheet="main.css",$scriptpaths=array())
    {
        parent::__construct();
        $this->stringvars['stylesheet']=getCSSPath($stylesheet);
        $this->stringvars['stylesheetcolors']= getCSSPath("colors.css");
        $this->stringvars['site_name']=title2html(getproperty("Site Name"));
        $this->stringvars['header_title']=$headertitle;
        if(strlen($title)>0) { $this->stringvars['title']=$title;
        }
        if(strlen($message)>0) { $this->stringvars['message']=$message;
        }
        if(strlen($isredirect)>0) { $this->stringvars['is_redirect']="redirect";
        }
        if(strlen($redirecturl)>0) { $this->stringvars['url']=$redirecturl;
        }
        $this->stringvars['url_text']=$urltext;

        if (count($scriptpaths)) {
            $this->stringvars['script']="";
            for($i=0;$i<count($scriptpaths);$i++)
            {
                $this->stringvars['script'].='<script type="text/javascript" src="'.getprojectrootlinkpath().$scriptpaths[$i].'"></script>';
            }
        }
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("htmlheader.tpl");
    }
}

//
// a general footer
//
class HTMLFooter extends Template
{

    function __construct()
    {
        parent::__construct();
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("htmlfooter.tpl");
    }
}


//
// container for a highlighted message
//
class Message extends Template
{

    function __construct($message)
    {
        parent::__construct();
        $this->stringvars['message']=$message;
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("message.tpl");
    }
}

?>
