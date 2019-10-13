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
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

// check legal vars
require $projectroot."admin/includes/legaladminvars.php";

require_once $projectroot."admin/functions/pagescreate.php";
require_once $projectroot."admin/functions/sessions.php";
require_once $projectroot."admin/includes/objects/pagenew.php";
require_once $projectroot."admin/includes/objects/forms.php";
require_once $projectroot."includes/functions.php";
require_once $projectroot."admin/includes/objects/adminmain.php";

checksession();

if(isset($_GET['page'])) { $page=$_GET['page'];
} else { $page=0;
}

$message = "";
$error = false;

//print_r($_POST);
//print_r($_GET);

if(isset($_POST['create'])) {
    $title=fixquotes($_POST['title']);
    $navtitle=fixquotes($_POST['navtitle']);
    $type=$_POST['type'];
    $root=isset($_POST['root']);
    $ispublishable=$_POST['ispublishable'];

    if($title && $navtitle) {
        if($ispublishable==="public") {
            $ispublishable=1;
        }
        else
        {
            $ispublishable=0;
        }
        if($root || !$page) : $parent=0; else: $parent=$page;
        endif;
        $parentpagetype=getpagetype($parent);

        $createpage=islegalparentpage($type, $parent);

        if($createpage) {
            $userid=getsiduser();
            $page=createpage($parent, $title, $navtitle, $type, $userid, $ispublishable);
            if($parent) {
                $title=getpagetitle($parent);
                $message="Created a new page under page: <em>".title2html($title)."</em> (".getpagetype($parent).")";
            }
            else { $message="Created a new page as a main page";
            }

            $redirect = editedRedirect($page, "Created a new page");
            $content = new AdminMain($parent, "show", new AdminMessage($message, $error), $redirect);
            print($content->toHTML());
        }
        else
        {
            $message.='<i>'.ucfirst($type).'</i> pages can only be created inside the following types of pages:';
            $keys=array_keys(getlegalparentpagetypes($type));
            for($i=0;$i<count($keys);$i++)
            {
                if($keys[$i]!="root") {
                    $message.=" <i>".$keys[$i]."</i>";
                }
            }
            if (array_search("root", $keys) || $keys[0] === "root") {
                $message.=", or as a main page";
            }
            if($parentpagetype) {
                $message.='.<br />You tried to add a <i>'.$type.'</i> page to a <i>'.$parentpagetype.'</i> page.';
            }
            else
            {
                $message.='</i>.<br />You tried to add a <i>'.$type.'</i> page as a main page.';
            }
            $error = true;
        }
    } // title && navtitle
    else
    {
        $message.="Please specify the new page's title (long and short)";
        $error = true;
    }
    $content = new AdminMain($page, "pagenew", new AdminMessage($message, $error), new NewPageForm($page, $title, $navtitle, $ispublishable, isset($_POST['root'])));
    print($content->toHTML());
}
else
{
    $content = new AdminMain($page, "pagenew", new AdminMessage($message, $error), new NewPageForm($page, "", "", true, false));
    print($content->toHTML());
}
?>
