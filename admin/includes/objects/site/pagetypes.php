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
require_once $projectroot."admin/functions/pagesmod.php";
require_once $projectroot."includes/objects/elements.php";

//
//
//
class SitePageTypes extends Template
{

    function SitePageTypes()
    {
        parent::__construct();

        $pagetypes=getpagetypes();
        $keys=array_keys($pagetypes);

        for($i=0;$i<count($keys);$i++)
        {
            $pagetype=$keys[$i];
            $restrictions=getrestrictions($pagetype);

            $this->listvars['pagetype'][]=new SitePageType($pagetype, $pagetypes[$pagetype], $restrictions);
        }
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/site/pagetypes.tpl");
    }
}


//
//
//
class SitePageType extends Template
{

    function SitePageType($pagetype, $description, $restrictions)
    {
        parent::__construct();

        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["action"] = "sitepagetype";
        $this->stringvars['actionvars'] = makelinkparameters($linkparams);
        $this->stringvars['hiddenvars'] = $this->makehiddenvars(array("pagetype" => $pagetype));

        $this->stringvars['pagetype']=$pagetype;
        $this->stringvars['description']=$description;
        $this->vars['allowrootform']= new CheckboxForm("allowroot", "allowroot", "", $restrictions["allow_root"]);
        $this->vars['allowsimplemenuform']= new CheckboxForm("allowsimplemenu", "allowsimplemenu", "", $restrictions["allow_simplemenu"]);
        if($restrictions["allow_self"]) { $this->stringvars['allowself']="true";
        }
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/site/pagetype.tpl");
    }
}

?>
