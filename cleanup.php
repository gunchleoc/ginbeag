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

$projectroot=dirname(__FILE__)."/";

require_once $projectroot."functions/db.php";

// anti bot nonsense links
// ********************************* achtung - bot secure ist server-spezifisch!
$testpath = "/".getproperty("Local Path");
if (getproperty("Local Path") == "") {
    $testpath = "";
}

if (!DEBUG
    && !((isset($_SERVER["ORIG_PATH_TRANSLATED"])
    && $_SERVER["ORIG_PATH_TRANSLATED"] == $projectroot."index.php")
    || $_SERVER["PHP_SELF"] == $testpath."/index.php")
) {
    header("HTTP/1.0 404 Not Found");
    print("HTTP 404: Sorry, but this page does not exist.");
    exit;
}

// check legal vars
require_once $projectroot."includes/legalvars.php";

// Do some cleanup

// Old antispam sessions
require_once $projectroot . "functions/antispam.php";
cleartokens();

// clearoldpagecacheentries()
$sql = new SQLDeleteStatement(
    PAGECACHE_TABLE, array(),
    array(date(DATETIMEFORMAT, strtotime('-1 day'))), 's', 'lastmodified < ?'
);
$sql->run();

// Optimize tables
$tables_to_optimize = array (
    ANTISPAM_TOKENS_TABLE,
    ARTICLEOFTHEDAY_TABLE,
    ARTICLES_TABLE,
    ARTICLESECTIONS_TABLE,
    GALLERYITEMS_TABLE,
    GUESTBOOK_TABLE,
    IMAGECATS_TABLE,
    IMAGES_TABLE,
    LINKS_TABLE,
    MONTHLYPAGESTATS_TABLE,
    NEWSITEMS_TABLE,
    NEWSITEMSECTIONS_TABLE,
    NEWSITEMSYNIMG_TABLE,
    PAGECACHE_TABLE,
    PAGES_TABLE,
    PICTUREOFTHEDAY_TABLE,
    PUBLICSESSIONS_TABLE,
    PUBLICUSERS_TABLE,
    THUMBNAILS_TABLE,
);

foreach ($tables_to_optimize as $table) {
    $sql = new RawSQLStatement("OPTIMIZE TABLE $table");
    $sql->fetch_Value();
}

print("Cleanup done.");

?>
