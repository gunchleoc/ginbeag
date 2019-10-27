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
// zweimal, weil nur auf "a" geprüft wird
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."includes/objects/template.php";
require_once $projectroot."includes/objects/elements.php";
require_once $projectroot."admin/includes/objects/forms.php";

//
//
//
class SiteTechnical extends Template
{

    function __construct()
    {
        parent::__construct();

        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["postaction"] = "savesite";
        $linkparams["action"] = "sitetech";
        $this->stringvars['actionvars'] = makelinkparameters($linkparams);

        $properties=getproperties();

        $this->stringvars["googlekeywords"]=input2html($properties["Google Keywords"]);
        $this->vars['serverprotocolform']= new OptionForm($properties["Server Protocol"], array(0 => "http://", 1 => "https://"), array(0 => "Unencrypted (http://)", 1 => "Encrypted (https://)"), "serverprotocol");
        $this->stringvars["domainname"]=$properties["Domain Name"];
        $this->stringvars["localpath"]=$properties["Local Path"];
        $this->stringvars["cookieprefix"]=$properties["Cookie Prefix"];
        $this->stringvars["imagepath"]=$properties["Image Upload Path"];
        $this->stringvars["adminemail"]=$properties["Admin Email Address"];
        $this->stringvars["emailsig"]=input2html($properties["Email Signature"]);
        $this->stringvars["datetimeformat"]=$properties["Date Time Format"];
        $this->stringvars["dateformat"]=$properties["Date Format"];
        $this->stringvars["imagewidth"]=$properties["Image Width"];
        $this->stringvars["thumbnailsize"]=$properties["Thumbnail Size"];
        $this->stringvars["mobilethumbnailsize"]=$properties["Mobile Thumbnail Size"];
        $this->stringvars["imagesperpage"]=$properties['Imagelist Images Per Page'];

        $this->vars['submitrow']= new SubmitRow("submit", "Submit", true);
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/site/technical.tpl");
    }
}
?>
