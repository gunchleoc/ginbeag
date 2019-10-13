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
// zweimal, weil nur auf "a" geprüft wird
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."includes/objects/template.php";
require_once $projectroot."admin/functions/publicusersmod.php";
require_once $projectroot."functions/publicsessions.php";
require_once $projectroot."includes/objects/elements.php";

//
//
//
class SiteWhosOnline extends Template
{

    function SiteWhosOnline()
    {
        parent::__construct();
        $sessions=getallpublicsessions();

        $noofsessions=count($sessions);
        if($noofsessions>0) {
            for($i=0; $i<$noofsessions;$i++)
            {
                // get all user values from DB
                $userid=getpublicsiduser($sessions[$i]);
                $username=getpublicusername($userid);
                $ip = getpublicip($sessions[$i]);
                $lastlogin = getlastpubliclogin($userid, $ip);
                $isvalid = ispublicsessionvalid($sessions[$i]);
                $retries = getpublicretries($userid, $ip);

                $this->listvars["onlineusers"][]= new SiteWhosOnlineUser($userid, $username, $ip, $lastlogin, $isvalid, $retries);
            }
        }
        else { $this->stringvars["noonlineusers"]= "No public users online";
        }
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/site/whosonline.tpl");
    }
}

//
//
//
class SiteWhosOnlineUser extends Template
{

    function SiteWhosOnlineUser($userid, $username, $ip, $lastlogin, $isvalid, $retries)
    {
        parent::__construct();

        $this->stringvars["userid"]=$userid;
        $this->stringvars["username"]=$username;
        $this->stringvars["ip"]=long2ip($ip);
        $this->stringvars["host"]=gethostbyaddr(long2ip($ip));
        $this->stringvars["lastlogin"]=$lastlogin;
        if($isvalid) { $this->stringvars["isvalid"]="true";
        } else { $this->stringvars["notvalid"]="true";
        }
        $this->stringvars["retries"]=$retries;
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/site/whosonlineuser.tpl");
    }
}
?>
