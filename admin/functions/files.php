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

// security check: restrict which calling scripts can write files
$allowedscripts = array(
    'admin/admin.php' =>1,
    'admin/editimagelist.php' => 1
);

$server_script = preg_replace('/\/\/+/', '/', $_SERVER["SCRIPT_FILENAME"]);
$install_with_root =  preg_replace('/\/\/+/', '/', ($_SERVER["DOCUMENT_ROOT"] . "/" . $installdir . "/"));

if (!array_key_exists(substr($server_script, strlen($install_with_root)), $allowedscripts)) {
    header("HTTP/1.0 404 Not Found");
    print("HTTP 404: Sorry, but this page does not exist.");
    if (DEBUG) {
        print("<br />'" . $server_script . "' not registered with file scripts.");
    }
    exit;
}

require_once $projectroot."functions/imagefiles.php";

//
//
//
function uploadfile($subdir, $paramname, $newname="")
{
    global $projectroot, $_FILES;
    $success = false;
    //print_r($_FILES);

    if (isset($_FILES[$paramname])
        && isset($_FILES[$paramname]['error']) && $_FILES[$paramname]['error'] == UPLOAD_ERR_OK
        && isset($_FILES[$paramname]['size']) && $_FILES[$paramname]['size'] > 0
    ) {
        if(strlen($newname)>4) {
            $filename=$projectroot.$subdir.'/'.$newname;
        }
        else
        {
            $filename=$projectroot.$subdir.'/'.$_FILES[$paramname]['name'];
        }
        if(exif_imagetype($_FILES[$paramname]['tmp_name']) > 0) {
            $success = move_uploaded_file($_FILES[$paramname]['tmp_name'], $filename);
        }
        else
        {
            $success = false;
            return WRONG_MIME_TYPE_NO_IMAGE;
        }
        if($success) { chmod($filename, 0644);
        }
    }
    return $_FILES[$paramname]['error'];
}


//
//
//
function replacefile($subdir, $paramname, $filename)
{
    global $projectroot, $_FILES;
    $success = false;

    if(file_exists($filename)) {
        $success = deletefile($subdir, $filename);
    }
    else { $success = true;
    }
    if($success) {
        $errorcode = uploadfile($subdir, $paramname, $filename);
    }
    return $errorcode;
}

//
//
//
function deletefile($subdir,$filename)
{
    global $projectroot;

    //http://www.morrowland.com/apron/tutorials/web/php/writetextfile/index.php
    $filename = $projectroot.$subdir.'/'.basename($filename);

    $delete = @unlink($filename);
    if (@file_exists($filename)) {
        $filesys = str_replace("/", chr(92), $filename);
        $delete = @system("del $filesys");
        if (@file_exists($filename)) {
            $delete = @chmod($filename, 0775);
            $delete = @unlink($filename);
            $delete = @system("del $filesys");
        }
    }
    return $delete;
}

//
//
//
function fileerrors($errorno)
{
    global $_FILES;
    $errorcodes[UPLOAD_ERR_OK] = "";
    $errorcodes[UPLOAD_ERR_INI_SIZE] = "The uploaded file exceeds the upload_max_filesize directive in php.ini.";
    $errorcodes[UPLOAD_ERR_FORM_SIZE] = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.";
    $errorcodes[UPLOAD_ERR_PARTIAL] = "The uploaded file was only partially uploaded.";
    $errorcodes[UPLOAD_ERR_NO_FILE] = "No file was uploaded.";
    $errorcodes[UPLOAD_ERR_NO_TMP_DIR] = "Missing a temporary folder.";
    $errorcodes[UPLOAD_ERR_CANT_WRITE] = "Failed to write file to disk.";
    $errorcodes[UPLOAD_ERR_EXTENSION] = "A PHP extension stopped the file upload.";
    $errorcodes[WRONG_MIME_TYPE_NO_IMAGE] = "The file is not an image file.";
    return $errorcodes[$errorno];
}

?>
