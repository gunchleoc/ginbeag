<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."functions/db.php";
require_once $projectroot."admin/functions/sessions.php";
require_once $projectroot."functions/users.php";
require_once $projectroot."functions/images.php";

//
// returns false if image already exists
//
function addimage($filename, $subpath, $caption, $source, $sourcelink, $copyright, $permission) 
{
    if(imageexists($filename)) {
        return false;
    }

    $columns = array('image_filename', 'uploaddate', 'editor_id', 'permission');
    $values = array($filename, date(DATETIMEFORMAT, strtotime('now')), getsiduser(), $permission);
    $datatypes = 'ssii';

    if (!empty($subpath)) {
        array_push($columns, 'path');
        array_push($values, $subpath);
        $datatypes .= 's';
    }
    if (!empty($caption)) {
        array_push($columns, 'caption');
        array_push($values, $caption);
        $datatypes .= 's';
    }
    if (!empty($source)) {
        array_push($columns, 'source');
        array_push($values, $source);
        $datatypes .= 's';
    }
    if (!empty($sourcelink)) {
        array_push($columns, 'sourcelink');
        array_push($values, $sourcelink);
        $datatypes .= 's';
    }
    if (!empty($copyright)) {
        array_push($columns, 'copyright');
        array_push($values, $copyright);
        $datatypes .= 's';
    }

    $sql = new SQLInsertStatement(IMAGES_TABLE, $columns, $values, $datatypes);
    return $sql->insert();
}


//
//
//
function addthumbnail($image,$thumbnail) 
{
    $sql = new SQLInsertStatement(
        THUMBNAILS_TABLE,
        array('image_filename', 'thumbnail_filename'),
        array($image, $thumbnail),
        'ss'
    );
    return $sql->insert();
}

//
// delete thumbnail file from file system first!!!
// this function only deletes the database entry.
//
function deletethumbnail($imagefilename) 
{
    $sql = new SQLDeleteStatement(THUMBNAILS_TABLE, array('image_filename'), array($imagefilename), 's');
    return $sql->run();
}

//
// delete image and thumbnail files from file system first!!!
// this function only deletes the database entries.
//
function deleteimage($filename) 
{
    $result = true;
    if(!imageisused($filename)) {
        $sql = new SQLDeleteStatement(IMAGES_TABLE, array('image_filename'), array($filename), 's');
        $result = $result & $sql->run();
        $sql = new SQLDeleteStatement(THUMBNAILS_TABLE, array('image_filename'), array($filename), 's');
        $result = $result & $sql->run();
        $sql = new SQLDeleteStatement(IMAGECATS_TABLE, array('image_filename'), array($filename), 's');
        $result = $result & $sql->run();
    }
    else { $result = false;
    }
    return $result;
}

//
//
//
function savedescription($filename, $caption, $source, $sourcelink, $copyright, $permission) 
{
    $sql = new SQLUpdateStatement(
        IMAGES_TABLE,
        array('caption', 'source', 'sourcelink', 'copyright', 'permission'), array('image_filename'),
        array($caption, $source, $sourcelink, $copyright, $permission, $filename), 'ssssis'
    );
    return $sql->run();
}


// *************************** filtering functions ************************** //

//
// $files: Images to be filtered
//
function getmissingimages($order, $ascdesc, $files)
{
    global $projectroot;
    $result=array();

    $keys = array_keys($files);
    while($key = next($keys))
    {
        if(!file_exists($projectroot.getproperty("Image Upload Path").getimagesubpath($files[$key])."/".$files[$key])) {
            array_push($result, $files[$key]);
        }
    }
    return $result;
}


//
//
//
function getunknownimages()
{
    global $projectroot;
    $result = getunknownimageshelper("");

    $dir = new DirectoryIterator($projectroot.getproperty("Image Upload Path"));
    foreach ($dir as $fileinfo)
    {
        if ($fileinfo->isDir() && !$fileinfo->isDot()) {
            $result = array_merge($result, getunknownimageshelper("/".$fileinfo->getFilename()));
        }
    }
    return $result;
}


//
//
//
function getunknownimageshelper($subpath)
{
    global $projectroot;
    $result=array();

    $dirtolist = $projectroot.getproperty("Image Upload Path").$subpath;

    //using the opendir function
    $dir_handle = @opendir($dirtolist)
    or die("Unable to open path");

    while($file = readdir($dir_handle)) {
        if(!is_dir($dirtolist."/".$file) && !strpos(strtolower($file), ".php") && !strpos(strtolower($file), ".htm")) {
            if (!(imageexists($file) || thumbnailexists($file))) {
                array_push($result, array("filename" => $file, "subpath" => $subpath));
            }
        }
    }
    return $result;
}


//
// $files: Images to be filtered
//
function getunusedimages($order,$ascdesc,$files)
{
    global $projectroot;
    $result=array();

    $keys = array_keys($files);
    while($key = next($keys))
    {
        if(!imageisused($files[$key])) {
            array_push($result, $files[$key]);
        }
    }
    return $result;
}


//
// $files: Images to be filtered
//
function getmissingthumbnails($order, $ascdesc, $files)
{
    global $projectroot;
    $result=array();

    $keys = array_keys($files);
    while($key = next($keys))
    {
        if(hasthumbnail($files[$key]) && !file_exists($projectroot.getproperty("Image Upload Path").getimagesubpath($files[$key])."/".getthumbnail($files[$key]))) {
            array_push($result, $files[$key]);
        }
    }
    return $result;
}


//
// $files: Images to be filtered
//
function getimageswithoutthumbnails($order, $ascdesc, $files)
{
    global $projectroot;
    $result=array();

    $keys = array_keys($files);
    while($key = next($keys))
    {
        if(!hasthumbnail($files[$key])) {
            array_push($result, $files[$key]);
        }
    }
    return $result;
}

//
//
//
function getfilteredimages(
    $filename, $caption, $source, $sourceblank, $uploader,
    $copyright, $copyrightblank, $selectedcats, $categoriesblank,
    $order, $ascdesc
) {

    $filename = trim($filename);
    $caption = trim($caption);
    $source = trim($source);
    $copyright = trim($copyright);
    $result=array();

    // get all category children
    if(count($selectedcats)>0 && !$categoriesblank) {
        $result=getfilteredimageshelper($filename, $caption, $source, $sourceblank, $uploader, $copyright, $copyrightblank, array_pop($selectedcats), $categoriesblank, $order, $ascdesc);
        while(count($selectedcats))
        {
            $filenames= getfilteredimageshelper($filename, $caption, $source, $sourceblank, $uploader, $copyright, $copyrightblank, array_pop($selectedcats), $categoriesblank, $order, $ascdesc);
            $result=array_intersect($result, $filenames);
        }
    }
    else
    {
        $result=getfilteredimageshelper($filename, $caption, $source, $sourceblank, $uploader, $copyright, $copyrightblank, -1, $categoriesblank, $order, $ascdesc);
    }
    return $result;
}

//
//
//
function getfilteredimageshelper($filename,$caption,$source,$sourceblank,$uploader,$copyright,$copyrightblank,$selectedcat,$categoriesblank,$order,$ascdesc) 
{
    $result=array();
    $categories=array();
    if($selectedcat>=0) {
        $pendingcategories=array(0 => $selectedcat);
        while(count($pendingcategories))
        {
            $selectedcat=array_pop($pendingcategories);
            array_push($categories, $selectedcat);
            $pendingcategories=array_merge($pendingcategories, getcategorychildren($selectedcat, CATEGORY_IMAGE));
        }
    }

    $values = array();
    $datatypes = "";
    $query="SELECT DISTINCTROW images.image_filename FROM ";
    $query.=IMAGES_TABLE." as images";

    if(count($categories)>0) {
        $query.=", ".IMAGECATS_TABLE." AS cat";
        $query.=" WHERE cat.image_filename = images.image_filename";
    }
    else
    {
        $query.=" WHERE ?";
        array_push($values, 1);
        $datatypes .= 'i';
    }

    if($filename) {
        $query.=" AND images.image_filename LIKE ?";
        array_push($values, '%' . $filename . '%');
        $datatypes .= 's';
    }
    if(strlen($caption) > 0) {
        $query.=" AND caption LIKE ?";
        array_push($values, '%' . $caption . '%');
        $datatypes .= 's';
    }
    if($sourceblank) {
        $query.=" AND source = ''";
    }
    elseif(strlen($source) > 0) {
        $query.=" AND source LIKE ?";
        array_push($values, '%' . $source . '%');
        $datatypes .= 's';
    }
    if($copyrightblank) {
        $query.=" AND copyright = ''";
    }
    elseif(strlen($copyright) >0) {
        $query.=" AND copyright LIKE ?";
        array_push($values, '%' . $copyright . '%');
        $datatypes .= 's';
    }
    if($uploader > 0) {
        $query.=" AND editor_id = ?";
        array_push($values, $uploader);
        $datatypes .= 's';
    }
    if(count($categories)>0) {
        $placeholders = array_fill(0, count($categories), '?');
        $query.=" AND cat.category IN (".implode(",", $placeholders).")";
        foreach ($categories as $cat) {
            $values[] = $cat;
        }
        $datatypes .= str_pad("", count($categories), 's');
    }
    if($order) {
        if($order=="uploader") { $order="editor_id";
        } elseif($order=="filename") { $order="image_filename";
        }
        $query .= " ORDER BY ? " . (strtolower($ascdesc) == "desc" ? "DESC" : "ASC");
        array_push($values, $order);
        $datatypes .= 's';
    }

    $sql = new RawSQLStatement($query, $values, $datatypes);
    $result = $sql->fetch_column();


    //  print('Some debugging info: '.$query.'<p>');

    if($categoriesblank) {
        $temp=$result;
        $result=array();
        for($i=0;$i<count($temp);$i++)
        {
            $sql = new SQLSelectStatement(IMAGECATS_TABLE, 'image_filename', array('image_filename'), array($temp[$i]), 's');
            $sql->set_distinct();
            if (!$sql->fetch_value()) {
                array_push($result, $temp[$i]);
            }
        }
    }
    return $result;
}

?>
