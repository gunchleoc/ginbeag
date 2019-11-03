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
    ANTISPAM_TABLE,
    ANTISPAM_TOKENS_TABLE,
    ARTICLECATS_TABLE,
    ARTICLEOFTHEDAY_TABLE,
    ARTICLES_TABLE,
    ARTICLESECTIONS_TABLE,
    BANNERS_TABLE,
    BLOCKEDREFERRERS_TABLE,
    CATEGORIES_ARTICLES_TABLE,
    CATEGORIES_IMAGES_TABLE,
    CATEGORIES_NEWS_TABLE,
    EXTERNALS_TABLE,
    GALLERYITEMS_TABLE,
    GUESTBOOK_TABLE,
    IMAGECATS_TABLE,
    IMAGES_TABLE,
    LINKS_TABLE,
    LOCKS_TABLE,
    MENUS_TABLE,
    NEWS_TABLE,
    NEWSITEMCATS_TABLE,
    NEWSITEMS_TABLE,
    NEWSITEMSYNIMG_TABLE,
    NEWSITEMSECTIONS_TABLE,
    PAGECACHE_TABLE,
    PAGES_TABLE,
    PAGETYPES_TABLE,
    PUBLICSESSIONS_TABLE,
    PUBLICUSERS_TABLE,
    PICTUREOFTHEDAY_TABLE,
    RESTRICTEDPAGES_TABLE,
    RESTRICTEDPAGESACCESS_TABLE,
    RESTRICTEDPAGESBANNEDIPS_TABLE,
    RSS_TABLE,
    SESSIONS_TABLE,
    SITEPROPERTIES_TABLE,
    SPECIALTEXTS_TABLE,
    MONTHLYPAGESTATS_TABLE,
    THUMBNAILS_TABLE,
    USERS_TABLE
);

// Database columns allowed in queries
$legal_columns = array(
    '*',
    'activationkey',
    'allow_root',
    'allow_self',
    'allow_simplemenu',
    'aotd_date',
    'aotd_id',
    'article_author',
    'article_id',
    'articlesection_id',
    'banner_id',
    'browseragent',
    'cache_key',
    'caption',
    'category_id',
    'category',
    'code',
    'contactfunction',
    'content_html',
    'contributor',
    'copyright',
    'date',
    'day',
    'description',
    'displaydepth',
    'editdate',
    'editor_id',
    'email',
    'galleryitem_id',
    'header',
    'id',
    'image_copyright',
    'image_filename',
    'image',
    'imagealign',
    'imageautoshrink',
    'imagehalign',
    'imgcat_id',
    'introimage',
    'introtext',
    'ip',
    'iscontact',
    'ispublishable',
    'ispublished',
    'last_login',
    'lastmodified',
    'link_id',
    'link',
    'location',
    'locktime',
    'masterpage',
    'message_id',
    'message',
    'month',
    'name',
    'navigatordepth',
    'newsitem_id',
    'newsitemcat_id',
    'newsitemimage_id',
    'newsitemsection_id',
    'numberofpages',
    'page_id',
    'pagecat_id',
    'pagenumber',
    'pagetype',
    'parent_id',
    'password',
    'path',
    'permission',
    'position_navigator',
    'position',
    'potd_date',
    'potd_filename',
    'property_name',
    'property_value',
    'publicuser_id',
    'referrerurl',
    'restrictedaccess_id',
    'retries',
    'sectionimage',
    'sectionnumber',
    'sectiontitle',
    'sent',
    'session_id',
    'session_ip',
    'session_time',
    'session_user_id',
    'session_valid',
    'shownewestfirst',
    'showpermissionrefusedimages',
    'sistersinnavigator',
    'source',
    'sourcelink',
    'stats_id',
    'subject',
    'synopsis',
    'text',
    'thumbnail_filename',
    'title_navigator',
    'title_page',
    'title',
    'token_id',
    'type_description',
    'type_key',
    'uploaddate',
    'use_toc',
    'user_active',
    'user_id',
    'userlevel',
    'username',
    'usethumbnail',
    'viewcount',
    'year',
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
