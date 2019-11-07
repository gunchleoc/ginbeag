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


// Datetime format for database entries
define('DATETIMEFORMAT', 'Y-m-d H:i:s');

// Table names
define('ANTISPAM_TABLE', $table_prefix.'antispam');
define('ANTISPAM_TOKENS_TABLE', $table_prefix.'antispamtokens');
define('ARTICLECATS_TABLE', $table_prefix.'pages_categories');
define('ARTICLEOFTHEDAY_TABLE', $table_prefix.'articleoftheday');
define('ARTICLES_TABLE', $table_prefix.'articles');
define('ARTICLESECTIONS_TABLE', $table_prefix.'articlesections');
define('BANNERS_TABLE', $table_prefix.'banners');
define('BLOCKEDREFERRERS_TABLE', $table_prefix.'blockedreferrers');
define('CATEGORIES_ARTICLES_TABLE', $table_prefix.'categories_articles');
define('CATEGORIES_IMAGES_TABLE', $table_prefix.'categories_images');
define('CATEGORIES_NEWS_TABLE', $table_prefix.'categories_news');
define('EXTERNALS_TABLE', $table_prefix.'externals');
define('GALLERYITEMS_TABLE', $table_prefix.'galleryitems');
define('GUESTBOOK_TABLE', $table_prefix.'guestbook');
define('IMAGECATS_TABLE', $table_prefix.'image_categories');
define('IMAGES_TABLE', $table_prefix.'images');
define('LINKS_TABLE', $table_prefix.'links');
define('LOCKS_TABLE', $table_prefix.'locks');
define('MENUS_TABLE', $table_prefix.'menus');
define('NEWS_TABLE', $table_prefix.'news');
define('NEWSITEMCATS_TABLE', $table_prefix.'newsitem_categories');
define('NEWSITEMS_TABLE', $table_prefix.'newsitems');
define('NEWSITEMSYNIMG_TABLE', $table_prefix.'newsitems_synopsisimages');
define('NEWSITEMSECTIONS_TABLE', $table_prefix.'newsitemsections');
define('PAGECACHE_TABLE', $table_prefix.'pagecache');
define('PAGES_TABLE', $table_prefix.'pages');
define('PAGETYPES_TABLE', $table_prefix.'pagetypes');
define('PUBLICSESSIONS_TABLE', $table_prefix.'publicsessions');
define('PUBLICUSERS_TABLE', $table_prefix.'publicusers');
define('PICTUREOFTHEDAY_TABLE', $table_prefix.'pictureoftheday');
define('RESTRICTEDPAGES_TABLE', $table_prefix.'pages_restricted');
define('RESTRICTEDPAGESACCESS_TABLE', $table_prefix.'pages_restricted_access');
define('RESTRICTEDPAGESBANNEDIPS_TABLE', $table_prefix.'pages_restricted_bannedips');
define('RSS_TABLE', $table_prefix.'rss');
define('SESSIONS_TABLE', $table_prefix.'sessions');
define('SITEPROPERTIES_TABLE', $table_prefix.'siteproperties');
define('SPECIALTEXTS_TABLE', $table_prefix.'specialtexts');
define('MONTHLYPAGESTATS_TABLE', $table_prefix.'stats_pages_monthly');
define('THUMBNAILS_TABLE', $table_prefix.'thumbnails');
define('USERS_TABLE', $table_prefix.'users');

// Database tables allowed in queries
$legal_tables = array(
    ANTISPAM_TABLE => 1,
    ANTISPAM_TOKENS_TABLE => 1,
    ARTICLECATS_TABLE => 1,
    ARTICLEOFTHEDAY_TABLE => 1,
    ARTICLES_TABLE => 1,
    ARTICLESECTIONS_TABLE => 1,
    BANNERS_TABLE => 1,
    BLOCKEDREFERRERS_TABLE => 1,
    CATEGORIES_ARTICLES_TABLE => 1,
    CATEGORIES_IMAGES_TABLE => 1,
    CATEGORIES_NEWS_TABLE => 1,
    EXTERNALS_TABLE => 1,
    GALLERYITEMS_TABLE => 1,
    GUESTBOOK_TABLE => 1,
    IMAGECATS_TABLE => 1,
    IMAGES_TABLE => 1,
    LINKS_TABLE => 1,
    LOCKS_TABLE => 1,
    MENUS_TABLE => 1,
    NEWS_TABLE => 1,
    NEWSITEMCATS_TABLE => 1,
    NEWSITEMS_TABLE => 1,
    NEWSITEMSYNIMG_TABLE => 1,
    NEWSITEMSECTIONS_TABLE => 1,
    PAGECACHE_TABLE => 1,
    PAGES_TABLE => 1,
    PAGETYPES_TABLE => 1,
    PUBLICSESSIONS_TABLE => 1,
    PUBLICUSERS_TABLE => 1,
    PICTUREOFTHEDAY_TABLE => 1,
    RESTRICTEDPAGES_TABLE => 1,
    RESTRICTEDPAGESACCESS_TABLE => 1,
    RESTRICTEDPAGESBANNEDIPS_TABLE => 1,
    RSS_TABLE => 1,
    SESSIONS_TABLE => 1,
    SITEPROPERTIES_TABLE => 1,
    SPECIALTEXTS_TABLE => 1,
    MONTHLYPAGESTATS_TABLE => 1,
    THUMBNAILS_TABLE => 1,
    USERS_TABLE => 1
);

// Database columns allowed in queries
$legal_columns = array(
    '*' => 1,
    'activationkey' => 1,
    'allow_root' => 1,
    'allow_self' => 1,
    'allow_simplemenu' => 1,
    'aotd_date' => 1,
    'aotd_id' => 1,
    'article_author' => 1,
    'article_id' => 1,
    'articlesection_id' => 1,
    'banner_id' => 1,
    'browseragent' => 1,
    'cache_key' => 1,
    'caption' => 1,
    'category_id' => 1,
    'category' => 1,
    'code' => 1,
    'contactfunction' => 1,
    'content_html' => 1,
    'contributor' => 1,
    'copyright' => 1,
    'date' => 1,
    'day' => 1,
    'description' => 1,
    'displaydepth' => 1,
    'displayname' => 1,
    'editdate' => 1,
    'editor_id' => 1,
    'email' => 1,
    'galleryitem_id' => 1,
    'header' => 1,
    'id' => 1,
    'image_copyright' => 1,
    'image_filename' => 1,
    'image' => 1,
    'imagealign' => 1,
    'imageautoshrink' => 1,
    'imagehalign' => 1,
    'imgcat_id' => 1,
    'introimage' => 1,
    'introtext' => 1,
    'ip' => 1,
    'iscontact' => 1,
    'ispublishable' => 1,
    'ispublished' => 1,
    'last_login' => 1,
    'lastmodified' => 1,
    'link_id' => 1,
    'link' => 1,
    'location' => 1,
    'locktime' => 1,
    'masterpage' => 1,
    'message_id' => 1,
    'message' => 1,
    'month' => 1,
    'name' => 1,
    'navigatordepth' => 1,
    'newsitem_id' => 1,
    'newsitemcat_id' => 1,
    'newsitemimage_id' => 1,
    'newsitemsection_id' => 1,
    'numberofpages' => 1,
    'page_id' => 1,
    'pagecat_id' => 1,
    'pagenumber' => 1,
    'pagetype' => 1,
    'parent_id' => 1,
    'password' => 1,
    'path' => 1,
    'permission' => 1,
    'position_navigator' => 1,
    'position' => 1,
    'potd_date' => 1,
    'potd_filename' => 1,
    'property_name' => 1,
    'property_value' => 1,
    'publicuser_id' => 1,
    'referrerurl' => 1,
    'restrictedaccess_id' => 1,
    'retries' => 1,
    'sectionimage' => 1,
    'sectionnumber' => 1,
    'sectiontitle' => 1,
    'sent' => 1,
    'session_id' => 1,
    'session_ip' => 1,
    'session_time' => 1,
    'session_user_id' => 1,
    'session_valid' => 1,
    'shownewestfirst' => 1,
    'showpermissionrefusedimages' => 1,
    'sistersinnavigator' => 1,
    'source' => 1,
    'sourcelink' => 1,
    'stats_id' => 1,
    'subject' => 1,
    'synopsis' => 1,
    'text' => 1,
    'thumbnail_filename' => 1,
    'title_navigator' => 1,
    'title_page' => 1,
    'title' => 1,
    'token_id' => 1,
    'type_description' => 1,
    'type_key' => 1,
    'uploaddate' => 1,
    'use_toc' => 1,
    'user_active' => 1,
    'user_id' => 1,
    'userlevel' => 1,
    'username' => 1,
    'usethumbnail' => 1,
    'viewcount' => 1,
    'year' => 1,
);

// Content permissions
define('PERMISSION_GRANTED', 2);
define('NO_PERMISSION', 1);

// Admin/Webediting panel permissions
define('USERLEVEL_USER', 0);
define('USERLEVEL_ADMIN', 1);

// for different types of categories
define('CATEGORY_ARTICLE', 0);
define('CATEGORY_IMAGE', 1);
define('CATEGORY_NEWS', 2);

// error codes for image upload
define('WRONG_MIME_TYPE_NO_IMAGE', -1);

// todo replace site property? Hack for gallery pages CSS
define('IMAGECAPTIONLINEHEIGHT', 8);

?>
