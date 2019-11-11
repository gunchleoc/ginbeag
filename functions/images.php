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
$projectroot=substr($projectroot, 0, strrpos($projectroot, "functions"));

require_once $projectroot."functions/db.php";

function extract_image_from_text($text) {
    $image_pattern = "/\[img\](.*?)\[\/img\]/i";
    $image_matches=array();
    $found = preg_match($image_pattern, $text, $image_matches);
    if ($found) {
        return $image_matches[1];
    }
    return "";
}

//
//
//
function getimagedimensions($filepath) {
    $width = 0;
    $height  =0;

    if (file_exists($filepath) && filetype($filepath) == "file") {
        $imageproperties = @getimagesize($filepath);
        $width = $imageproperties[0];
        $height = $imageproperties[1];
    }
    return array("width" => $width, "height" => $height);
}

//
//
//
function calculateimagedimensions($filepath, $autoshrink = false)
{
    $result = getimagedimensions($filepath);
    $result["resized"] = false;

    if ($autoshrink) {
        // todo Mobile Thumbnail Size
        $thumbnailsize = getproperty("Thumbnail Size");
        if (ismobile()) {
            $thumbnailsize *= 2;
        }
        if ($result["width"] > $thumbnailsize) {
            $result["resized"] = true;
            $factor = ceil($result["width"] / $thumbnailsize); // add a little more because captioned images are framed
            $result["width"] = floor($result["width"] / $factor);
            $result["height"] = floor($result["height"] / $factor);
        }
        if ($result["height"] > $thumbnailsize) {
            $result["resized"] = true;
            $factor = ceil($result["height"] / $thumbnailsize);
            $result["width"] = floor($result["width"] / $factor);
            $result["height"] = floor($result["height"] / $factor);
        }
    }
    return $result;
}

//
//
//
function getimagelinkpath($filename, $subpath)
{
    $localpath = getproperty("Local Path");
    $domain = getproperty("Domain Name");
    $imagepath = getproperty("Image Upload Path");
    $result = getproperty('Server Protocol').$domain.'/';
    if (!empty($localpath)) {
        $result .= $localpath.'/';
    }
    $result .= $imagepath . $subpath . '/' . rawurlencode(basename($filename));
    return $result;
}

//
//
//
function getimagedir($path) {
    global $projectroot;
    return $projectroot.getproperty("Image Upload Path").$path;
}

//
//
//
function getimagepath($filename, $path) {
    return getimagedir($path) . '/'.$filename;
}

//
//
//
function getthumbnail($imagefilename)
{
    if (empty($imagefilename)) {
        return "";
    }
    $sql = new SQLSelectStatement(IMAGES_TABLE, 'thumbnail_filename', array('image_filename'), array($imagefilename), 's');
    return $sql->fetch_value();
}

//
//
//
function getimage($filename)
{
    $sql = new SQLSelectStatement(IMAGES_TABLE, '*', array('image_filename'), array($filename), 's');
    return $sql->fetch_row();
}

//
//
//
function imageisused($filename)
{
    return count(pagesforimage($filename)) > 0 || count(newsitemsforimage($filename)) > 0;
}


//
// todo: modify for each new pagetype
//
function pagesforimage($filename)
{
    $sql = new SQLSelectStatement(PAGES_TABLE, 'page_id', array('image_filename'), array($filename), 's');
    $sql->set_order(array('page_id' => 'ASC'));
    $pageintros = $sql->fetch_column();

    $sql = new SQLSelectStatement(ARTICLESECTIONS_TABLE, 'article_id', array('image_filename'), array($filename), 's');
    $sql->set_order(array('article_id' => 'ASC'));
    $articlesections = $sql->fetch_column();

    $sql = new SQLSelectStatement(GALLERYITEMS_TABLE, 'page_id', array('image_filename'), array($filename), 's');
    $sql->set_order(array('page_id' => 'ASC'));
    $galleryitems = $sql->fetch_column();

    $sql = new SQLSelectStatement(LINKS_TABLE, 'page_id', array('image_filename'), array($filename), 's');
    $sql->set_order(array('page_id' => 'ASC'));
    $linkimages = $sql->fetch_column();

    return array_merge($pageintros, $articlesections, $galleryitems, $linkimages);
}

//
//
//
function newsitemsforimage($filename)
{
    $sql = new SQLSelectStatement(NEWSITEMSYNIMG_TABLE, 'newsitem_id', array('image_filename'), array($filename), 's');
    $sql->set_order(array('newsitem_id' => 'ASC'));
    $synopsisimages = $sql->fetch_column();

    $sql = new SQLSelectStatement(NEWSITEMSECTIONS_TABLE, 'newsitem_id', array('image_filename'), array($filename), 's');
    $sql->set_order(array('newsitem_id' => 'ASC'));
    $sectionimages = $sql->fetch_column();

    return array_merge($synopsisimages, $sectionimages);
}

//
//
//
function getpictureoftheday()
{
    $date=date("Y-m-d", strtotime('now'));

    $sql = new SQLSelectStatement(PICTUREOFTHEDAY_TABLE, 'potd_filename', array('potd_date'), array($date), 's');
    $potd = $sql->fetch_value();

    if (empty(getthumbnail($potd)) || !imageisused($potd)) {
        $sql = new SQLDeleteStatement(PICTUREOFTHEDAY_TABLE, array('potd_date'), array($date), 's');
        $sql->run();
        $potd="";
    }

    if (empty($potd)) {
        $cats=explode(",", getproperty('Picture of the Day Categories'));

        // get all category children
        $categories=array();
        for($i=0;$i<count($cats);$i++)
        {
            $pendingcategories=array(0 => $cats[$i]);
            while(count($pendingcategories))
            {
                $selectedcat=array_pop($pendingcategories);
                array_push($categories, $selectedcat);
                $pendingcategories=array_merge($pendingcategories, getcategorychildren($selectedcat, CATEGORY_ARTICLE));
            }
        }
        if (count($categories)==0) {
            $categories=array(0);
        }

        $query="SELECT DISTINCTROW images.image_filename, images.thumbnail_filename from ";
        $query.=IMAGES_TABLE." as images, ";
        $query.=IMAGECATS_TABLE." as cats WHERE ";
        $query.="images.thumbnail_filename IS NOT NULL AND images.thumbnail_filename <> ''";
        $query.=" AND images.image_filename = cats.image_filename";
        $query.=" AND cats.category in(" . implode(',', array_fill(0, count($categories), '?')) . ")";

        $sql = new RawSQLStatement($query, $categories, str_pad("", count($categories), 'i'));
        $images = $sql->fetch_column();

        // Keep selecting from the prefiltered images at random until
        // a viable candidate is found or no more images are available
        while (!empty($images)) {
            list($usec, $sec) = explode(' ', microtime());
            $random = ((float) $sec + ((float) $usec * 100000)) % count($images);

            if (isset($images[$random])) {
                $potd=$images[$random];
                if (!imageisused($potd)) {
                    unset($images[$random]);
                    continue;
                }
                $sql = new SQLInsertStatement(
                    PICTUREOFTHEDAY_TABLE,
                    array('potd_date', 'potd_filename'),
                    array($date, $potd),
                    'ss'
                );
                $sql->insert();
                return $potd;
            }
        }
    }
    return $potd;
}

?>
