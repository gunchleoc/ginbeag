<?php
/*
 * An Gineadair Beag is a content management system to run websites with.
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
 */

$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."includes/objects/template.php";
require_once $projectroot."includes/objects/elements.php";


//
//
//
class SiteDBUtilsBackupForm extends Template
{

    function SiteDBUtilsBackupForm()
    {
        parent::__construct();
        $linkparams["page"] = $this->stringvars['page'];
        $linkparams["postaction"] = "backup";
        $linkparams["action"] = "sitedb";
        $this->stringvars['backupactionvars'] = makelinkparameters($linkparams);

        $linkparams["postaction"] = "cache";
        $this->stringvars['cacheactionvars'] = makelinkparameters($linkparams);
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/site/dbutilsbackupform.tpl");
    }
}


//
//
//
class SiteDBUtilsDBDump extends Template
{

    function SiteDBUtilsDBDump($dump)
    {
        parent::__construct();
        $this->stringvars['dump']=$dump;
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/site/dbutilsdbdump.tpl");
    }
}


//
//
//
class SiteDBUtilsTableHeader extends Template
{

    function SiteDBUtilsTableHeader($header)
    {
        parent::__construct();
        $this->stringvars['header']=$header;
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/site/dbutilsshowtableheader.tpl");
    }
}
?>
