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

//
//
//
function imageexists($filename)
{
    $sql = new SQLSelectStatement(IMAGES_TABLE, 'image_filename', array('image_filename'), array($filename), 's');
    return strlen($filename) > 0 && strcasecmp($sql->fetch_value(), $filename) == 0;
}

//
//
//
function thumbnailexists($thumbnailfilename)
{
    if (empty($thumbnailfilename)) { return false;
    }
    $sql = new SQLSelectStatement(THUMBNAILS_TABLE, 'thumbnail_filename', array('thumbnail_filename'), array($thumbnailfilename), 's');
    return $sql->fetch_value() === $thumbnailfilename;
}

//
//
//
function hasthumbnail($imagefilename)
{
    if (empty($imagefilename)) { return false;
    }
    $sql = new SQLSelectStatement(THUMBNAILS_TABLE, 'thumbnail_filename', array('image_filename'), array($imagefilename), 's');
    return strlen($sql->fetch_value()) > 0;
}

//
//
//
function getthumbnail($imagefilename)
{
    $sql = new SQLSelectStatement(THUMBNAILS_TABLE, 'thumbnail_filename', array('image_filename'), array($imagefilename), 's');
    return $sql->fetch_value();
}

//
//
//
function getallfilenames($order="",$ascdesc="ASC")
{
    if($order) {
        if($order=="uploader") { $order="editor_id";
        } elseif($order=="filename") { $order="image_filename";
        }
    }
    else {
        $order = 'image_filename';
    }

    $sql = new SQLSelectStatement(IMAGES_TABLE, 'image_filename');
    $sql->set_order(array($order => $ascdesc));
    return $sql->fetch_column();
}


//
//
//
function getallcaptions()
{
    $sql = new SQLSelectStatement(IMAGES_TABLE, 'caption');
    $sql->set_order(array('image_filename' => 'ASC'));
    return $sql->fetch_column();
}

//
//
//
function getsomefilenames($offset,$number, $order="filename", $ascdesc="ASC")
{
    if(strtolower($ascdesc)=="desc") { $ascdesc="DESC";
    } else { $ascdesc="ASC";
    }

    if($order=="uploader") { $order="editor_id";
    } elseif($order=="caption") { $order="caption";
    } elseif($order=="source") { $order="source";
    } elseif($order=="uploaddate") { $order="uploaddate";
    } elseif($order=="copyright") { $order="copyright";
    } else { $order="image_filename";
    }

    $sql = new SQLSelectStatement(IMAGES_TABLE, 'image_filename');
    $sql->set_order(array($order => $ascdesc));
    $sql->set_limit($number, $offset);
    return $sql->fetch_column();
}

//
//
//
function countimages()
{
    $sql = new SQLSelectStatement(IMAGES_TABLE, 'image_filename');
    $sql->set_operator('count');
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
function getimagesubpath($filename)
{
    if (empty($filename)) { return "";
    }
    $sql = new SQLSelectStatement(IMAGES_TABLE, 'path', array('image_filename'), array($filename), 's');
    return $sql->fetch_value();
}

//
//
//
function getcaption($filename)
{
    $sql = new SQLSelectStatement(IMAGES_TABLE, 'caption', array('image_filename'), array($filename), 's');
    return $sql->fetch_value();
}


//
//
//
function getsource($filename)
{
    $sql = new SQLSelectStatement(IMAGES_TABLE, 'source', array('image_filename'), array($filename), 's');
    return $sql->fetch_value();
}

//
//
//
function getsourcelink($filename)
{
    $sql = new SQLSelectStatement(IMAGES_TABLE, 'sourcelink', array('image_filename'), array($filename), 's');
    return $sql->fetch_value();
}


//
//
//
function getuploaddate($filename)
{
    $sql = new SQLSelectStatement(IMAGES_TABLE, 'uploaddate', array('image_filename'), array($filename), 's');
    return $sql->fetch_value();
}


//
//
//
function getuploader($filename)
{
    $sql = new SQLSelectStatement(IMAGES_TABLE, 'editor_id', array('image_filename'), array($filename), 's');
    return $sql->fetch_value();
}

//
//
//
function getimagecopyright($filename)
{
    $sql = new SQLSelectStatement(IMAGES_TABLE, 'copyright', array('image_filename'), array($filename), 's');
    return $sql->fetch_value();
}

//
//
//
function getimagepermission($filename)
{
    $sql = new SQLSelectStatement(IMAGES_TABLE, 'permission', array('image_filename'), array($filename), 's');
    return $sql->fetch_value();
}


//
//
//
function getallsources()
{
    $sql = new SQLSelectStatement(IMAGES_TABLE, 'source');
    $sql->set_order(array('source' => 'ASC'));
    $sql->set_distinct();
    return $sql->fetch_column();
}


//
//
//
function imageisused($filename)
{
    return count(pagesforimage($filename))>0 || count(newsitemsforimage($filename))>0;
}

//
// todo: modify for each new pagetype
//
function pagesforimage($filename)
{
    $sql = new SQLSelectStatement(PAGES_TABLE, 'page_id', array('introimage'), array($filename), 's');
    $sql->set_order(array('page_id' => 'ASC'));
    $pageintros = $sql->fetch_column();

    $sql = new SQLSelectStatement(ARTICLESECTIONS_TABLE, 'article_id', array('sectionimage'), array($filename), 's');
    $sql->set_order(array('article_id' => 'ASC'));
    $articlesections = $sql->fetch_column();

    $sql = new SQLSelectStatement(GALLERYITEMS_TABLE, 'page_id', array('image_filename'), array($filename), 's');
    $sql->set_order(array('page_id' => 'ASC'));
    $galleryitems = $sql->fetch_column();

    $sql = new SQLSelectStatement(LINKS_TABLE, 'page_id', array('image'), array($filename), 's');
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

    $sql = new SQLSelectStatement(NEWSITEMSECTIONS_TABLE, 'newsitem_id', array('sectionimage'), array($filename), 's');
    $sql->set_order(array('newsitem_id' => 'ASC'));
    $sectionimages = $sql->fetch_column();

    return array_merge($synopsisimages, $sectionimages);
}


//
//
//
function getpictureoftheday()
{
    $date = date(DATEFORMAT, strtotime('now'));

    $sql = new SQLSelectStatement(PICTUREOFTHEDAY_TABLE, 'potd_filename', array('potd_date'), array($date), 's');
    $potd = $sql->fetch_value();

    if (!hasthumbnail($potd) || !imageisused($potd)) {
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

        $query="SELECT DISTINCTROW thumbs.image_filename from ";
        $query.=THUMBNAILS_TABLE." as thumbs, ";
        $query.=IMAGES_TABLE." as images, ";
        $query.=IMAGECATS_TABLE." as cats WHERE ";
        $query.=" thumbs.image_filename = cats.image_filename";
        $query.=" AND thumbs.image_filename = images.image_filename";
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
