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

require_once $projectroot."includes/objects/template.php";

class AdminMessage extends Template
{

    function __construct($message, $iserror)
    {
        parent::__construct("adminmessage");
        if($iserror) { $this->stringvars["error"] = "true";
        } else { $this->stringvars["error"] = "false";
        }
        $this->stringvars["alertmessage"] = addslashes(strip_tags($message));

        if(strlen($message) > 0) {
            $this->stringvars['javascript']=$this->getScripts();
            $this->stringvars["message"] = addslashes($message);
        }
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/adminmessage.tpl");
    }
}
?>
