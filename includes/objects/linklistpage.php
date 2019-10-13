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

require_once $projectroot."functions/pagecontent/linklistpages.php";
require_once $projectroot."includes/objects/template.php";
require_once $projectroot."includes/objects/images.php";
require_once $projectroot."includes/includes.php";

//
// a link in a linklist
//
class LinklistLink extends Template
{

    function LinklistLink($linkid,$title,$image,$description,$link)
    {
        parent::__construct();
        // image permissions checked in LinkList
        $hasimage=strlen($image)>0;
        $this->stringvars['title'] = title2html($title);
        $this->stringvars['link'] = $link;
        $this->stringvars['linkid'] = $linkid;

        if($hasimage) {
            $this->vars['image'] = new LinkedImage($image, $link, $this->stringvars['title']);
        }

        $this->stringvars['text'] = text2html($description);
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("pages/linklist/linklistlink.tpl");
    }
}


//
// click on thumbnail goes to link instead of showimage.php
//
class LinkedImage extends Template
{

    function LinkedImage($filename,$linkurl, $linkname)
    {
        global $projectroot;

        parent::__construct();

        $image="";
        $this->stringvars['halign']="float: left;";
        $alttext=title2html($linkname);


        $filepath = getimagepath($filename);
        $thumbnail = getthumbnail($filename);
        $thumbnailpath = getthumbnailpath($filename, $thumbnail);
        if(ismobile()) {
            $usethumbnail = true;
            $extension = substr($filename, strrpos($filename, "."), strlen($filename));
            $thumbname = substr($filename, 0, strrpos($filename, ".")).'_thn'.$extension;
            $path = $projectroot.getproperty("Image Upload Path").getimagesubpath(basename($filename));

            // make sure a mobile thumbnail exists
            if (extension_loaded('gd') && function_exists('gd_info')) {
                if(!file_exists($path."/mobile/".$thumbname)) {
                    include_once $projectroot."functions/imagefiles.php";
                    createthumbnail($path, $filename, getproperty("Mobile Thumbnail Size"), true);
                }
            }

            $path = $path."/mobile/".$thumbname;
            if(file_exists($path)) {
                $thumbnailpath = $path;
                $thumbnail = $thumbname;
            }
            $width=getproperty("Mobile Thumbnail Size");
        }
        else
        {
            $width=getproperty("Thumbnail Size");
        }

        if(thumbnailexists($thumbnail) && file_exists($thumbnailpath) && !is_dir($thumbnailpath)) {
            $dimensions=getimagedimensions($thumbnailpath);
            $image='<a href="'.$linkurl.'"><img src="'.getimagelinkpath($thumbnail, getimagesubpath($filename)).'" width="'.$dimensions["width"].'" height="'.$dimensions["height"].'" alt="'.$alttext.'" title="'.$alttext.'" class="linkedimage"></a>';
        }
        else if(imageexists($filename) && file_exists($filepath) && !is_dir($filepath)) {
            $dimensions=calculateimagedimensions($filepath, true);
            $image='<a href="'.$linkurl.'"><img src="'.getimagelinkpath($filename, getimagesubpath($filename)).'" width="'.$dimensions["width"].'" height="'.$dimensions["height"].'" alt="'.$alttext.'" title="'.$alttext.'" class="linkedimage"></a>';
        }
        else
        {
            $image='<a href="'.$linkurl.'">'.$alttext.'</a>';
        }

        $this->stringvars['image']=$image;
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("images/image.tpl");
    }
}





//
// main class for linklistpages
//
class LinklistPage extends Template
{

    function LinklistPage($offset=0,$showhidden)
    {
        parent::__construct();
        $linkids=getlinklistitems($this->stringvars['page']);
        $noofids=count($linkids);
        if(!$offset) { $offset=0;
        }

        $linkparams["printview"]="on";
        $linkparams["page"]=$this->stringvars['page'];

        $this->vars['printviewbutton']= new LinkButton(makelinkparameters($linkparams), getlang("pagemenu_printview"), "img/printview.png");

        $pageintro = getpageintro($this->stringvars['page']);
        $this->vars['pageintro'] = new PageIntro(getpagetitle($this->stringvars['page']), $pageintro['introtext'], $pageintro['introimage'], $pageintro['imageautoshrink'], $pageintro['usethumbnail'], $pageintro['imagehalign'], $showhidden);

        // links
        for($i=$offset;$i<($noofids)&&$i<$noofids;$i++)
        {
            $contents=getlinkcontents($linkids[$i]);
            $this->listvars['link'][]= new LinkListLink($linkids[$i], $contents['title'], $contents['image'], $contents['description'], $contents['link']);
        }

        $this->vars['editdata']= new Editdata($showhidden);
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("pages/linklist/linklistpage.tpl");
    }
}




//
// main class for linklistpages
//
class LinklistPagePrintview extends Template
{

    function LinklistPagePrintview($showhidden)
    {
        parent::__construct();
        $linkids=getlinklistitems($this->stringvars['page']);
        $noofids=count($linkids);

        $pageintro = getpageintro($this->stringvars['page']);
        $this->vars['pageintro'] = new PageIntro("", $pageintro['introtext'], $pageintro['introimage'], $pageintro['imageautoshrink'], $pageintro['usethumbnail'], $pageintro['imagehalign'], $showhidden);

        $this->stringvars['pagetitle']=title2html(getpagetitle($this->stringvars['page']));

        for($i=0;$i<$noofids;$i++)
        {
            $contents=getlinkcontents($linkids[$i]);
            $this->listvars['link'][]= new LinkListLink($linkids[$i], $contents['title'], $contents['image'], $contents['description'], $contents['link'], false, false);
        }

        $this->vars['editdata']= new Editdata($showhidden);
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("pages/linklist/linklistpage.tpl");
    }
}

?>
