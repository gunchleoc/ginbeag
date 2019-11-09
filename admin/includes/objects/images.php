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
require_once $projectroot."includes/objects/images.php";
require_once $projectroot."includes/functions.php";


//
//
//
class CaptionedImageAdmin extends Template
{

    function __construct($imagedata, $page)
    {
        parent::__construct();

        if (!isset($imagedata['path'])) {
            $imagedata['path'] = getimagesubpath(basename($imagedata['image_filename']));
        }

        $this->vars['image']= new CaptionedImage($imagedata, array("page" => $page), true);
        $this->stringvars['imagelinkpath']=getimagelinkpath($imagedata['image_filename'], $imagedata['path']);

        $linkparams["page"] = $page;
        $linkparams["s_filename"] = $imagedata['image_filename'];
        $linkparams["filter"] = 1;
        $this->stringvars['editimagelink']='<a href="'.getprojectrootlinkpath().'admin/editimagelist.php'.makelinkparameters($linkparams).'" target="_blank">Edit this image</a>';
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/captionedimageadmin.tpl");
    }
}

?>
