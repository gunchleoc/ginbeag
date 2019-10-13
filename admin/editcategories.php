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
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

// check legal vars
require $projectroot."admin/includes/legaladminvars.php";

require_once $projectroot."includes/includes.php";
require_once $projectroot."admin/functions/sessions.php";
require_once $projectroot."admin/functions/categoriesmod.php";
require_once $projectroot."admin/includes/objects/categories.php";
require_once $projectroot."admin/includes/objects/adminmain.php";
require_once $projectroot."includes/functions.php";

checksession();

//print_r($_GET);
//print_r($_POST);

if(isset($_GET['page'])) {
    $page=$_GET['page'];
}
else { $page="0";
}

$message = "";
$error = false;
$title = "";

if(isset($_POST['addsubtext'])) { $addsubtext=fixquotes(trim($_POST['addsubtext']));
} else { $addsubtext="";
}

if(isset($_POST['editcattext'])) { $editcattext=fixquotes(trim($_POST['editcattext']));
} else { $editcattext="";
}

if(isset($_POST['selectedcat'])) { $selectedcat=$_POST['selectedcat'];
} else { $selectedcat=-1;
}

if(isset($_POST['cattype'])) { $cattype=$_POST['cattype'];
} elseif(isset($_GET['cattype'])) { $cattype=$_GET['cattype'];
} else { $cattype=CATEGORY_ARTICLE;
}


if(isset($_POST['addsub'])) {
    $title="Added category";

    if($selectedcat<0) {
        $message = "Please select a parent category";
        $error = true;
    }
    elseif(strlen($addsubtext)<1) {
        $message = "You cannot create a category that has no name";
        $error = true;
    }
    else
    {
        addcategory($selectedcat, $addsubtext, $cattype);
        $message='Added "'.$addsubtext.'" to "'.title2html(getcategoryname($selectedcat, $cattype)).'"';
    }
}
elseif(isset($_POST['editcat'])) {
    $title="Modified category";

    if($selectedcat<0) {
        $message = "Please select a category for renaming";
        $error = true;
    }
    elseif(strlen($editcattext)<1) {
        $message = "You cannot enter an empty name";
        $error = true;
    }
    elseif(isroot($selectedcat, $cattype)) {
        $message="You cannot rename the root category";
        $error = true;
    }
    else
    {
        $name=title2html(getcategoryname($selectedcat, $cattype));
        renamecategory($selectedcat, $editcattext, $cattype);
        $message='"'.$name.'" renamed to "'.$editcattext.'"';
    }
}
elseif(isset($_POST['delcat'])) {
    $title="Deleting category";
    if($selectedcat<0) {
        $message = "Please select a category for deleting";
        $error = true;
    }
    if(!isset($_POST['delcatconfirm'])) {
        $message = "Please select 'Confirm delete' when deleting a category";
        $error = true;
    }
    elseif(isroot($selectedcat, $cattype)) {
        $message = "You cannot delete the root category";
        $error = true;
    }
    elseif(getcategorychildren($selectedcat, $cattype)) {
        $message = "You cannot delete a category that still has subcategories";
        $error = true;
    }
    else
    {
        $name=title2html(getcategoryname($selectedcat, $cattype));
        deletecategory($selectedcat, $cattype);
        $message='Deleted "'.$name.'"';
    }
}
elseif(isset($_POST['movecat'])) {
    $title="Moving Category";
    if(!isset($_POST['movefrom'])) {
        $message = 'Please select a category to move';
        $error = true;
    }
    elseif(!isset($_POST['moveto'])) {
        $message = 'Please select a destination category to move to';
        $error = true;
    }
    else
    {
        $movefrom=$_POST['movefrom'];
        $moveto=$_POST['moveto'];

        if(isdescendant($movefrom, $moveto, $cattype)) {
            $message = 'You are not allowed to move "'.title2html(getcategoryname($movefrom, $cattype)).'" to "'.title2html(getcategoryname($moveto, $cattype)).'".';
            $error = true;
        }
        else if($movefrom == $moveto) {
            $message = "You can't move a category into itself";
            $error = true;
        }
        else
        {
            $success=movecategory($movefrom, $moveto, $cattype);
            if($success) {
                $message='Moved "'.title2html(getcategoryname($movefrom, $cattype)).'" to "'.title2html(getcategoryname($moveto, $cattype)).'".';
            }
            else
            {
                $message = 'Failed to move "'.title2html(getcategoryname($movefrom, $cattype)).'" to "'.title2html(getcategoryname($moveto, $cattype)).'".';
                $error = true;
            }
        }
    }
}
else
{
    $title="Edit Categories";
}

$content = new AdminMain($page, "editcategories", new AdminMessage($message, $error), new AdminCategories($title, $addsubtext, $editcattext, $cattype));
print($content->toHTML());
?>
