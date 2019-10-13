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
require_once $projectroot."admin/functions/publicusersmod.php";
require_once $projectroot."includes/objects/elements.php";


//
//
//
class SiteRestrictedPages extends Template
{

    function SiteRestrictedPages()
    {
        parent::__construct();

        $pages=getrestrictedpages();
        if(count($pages)) {
            $this->stringvars["hasrestrictedpages"]="true";
            for($i=0;$i<count($pages);$i++)
            {
                $this->listvars['restrictedpages'][]= new SiteRestrictedPage($pages[$i]);
            }

        }
        else
        {
            $this->stringvars["norestrictedpages"]="true";
        }
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/site/restrictedpages.tpl");
    }
}


//
//
//
class SiteRestrictedPage extends Template
{

    function SiteRestrictedPage($page)
    {
        parent::__construct();

        $this->stringvars["linktopage"]=getprojectrootlinkpath()."admin/admin.php".makelinkparameters(array("page" => $page));

          $this->stringvars["page"]=$page;
          $this->stringvars["pagetype"]=getpagetype($page);
          $this->stringvars["pagetitle"]=text2html(getpagetitle($page));

          $accessusers=getallpublicuserswithaccessforpage($page);
        if(count($accessusers)==0) {
              $this->stringvars["accessuserlist"]='<p align="center">&mdash;</p>';
        }
        else
        {
            $this->stringvars["accessuserlist"]="";
            for($j=0;$j<count($accessusers);$j++)
            {
                if($j>0) { $this->stringvars["accessuserlist"].=' - ';
                }

                $linkparams["page"] = $this->stringvars['page'];
                $linkparams["userid"] = $accessusers[$j];
                $linkparams["type"] = "public";
                $linkparams["action"] = "siteuserperm";
                $this->stringvars["accessuserlist"].='<a href="'.makelinkparameters($linkparams).'">'.getpublicusername($accessusers[$j]).'</a>';
            }
        }
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/site/restrictedpage.tpl");
    }
}

?>
