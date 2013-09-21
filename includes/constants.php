<?php

// Debug Level
define('DEBUG', 1); // Debugging on
//define('DEBUG', 0); // Debugging off

define('DATETIMEFORMAT','Y-m-d H:i:s');
define('SHORTDATETIMEFORMAT','d M y H:i:s');
//todo move to config http://de.php.net/manual/en/timezones.php
date_default_timezone_set ('Europe/London');

// Table names
define('ANTISPAM_TABLE', $table_prefix.'antispam');
define('ARTICLEOFTHEDAY_TABLE', $table_prefix.'articleoftheday');
define('ARTICLES_TABLE', $table_prefix.'articles');
define('ARTICLESECTIONS_TABLE', $table_prefix.'articlesections');
define('BANNERS_TABLE', $table_prefix.'banners');
define('BLOCKEDREFERRERS_TABLE', $table_prefix.'blockedreferrers');
define('CATEGORIES_TABLE', $table_prefix.'categories');
define('COPYRIGHT_TABLE', $table_prefix.'blanketcopyright');
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
define('PAGECATS_TABLE', $table_prefix.'pages_categories');
define('PAGETYPES_TABLE', $table_prefix.'pagetypes');
define('PUBLICSESSIONS_TABLE', $table_prefix.'publicsessions');
define('PUBLICUSERS_TABLE', $table_prefix.'publicusers');
define('PICTUREOFTHEDAY_TABLE', $table_prefix.'pictureoftheday');
define('RESTRICTEDPAGES_TABLE', $table_prefix.'pages_restricted');
define('RESTRICTEDPAGESACCESS_TABLE', $table_prefix.'pages_restricted_access');
define('RESTRICTEDPAGESBANNEDIPS_TABLE', $table_prefix.'pages_restricted_bannedips');
define('RSS_TABLE', $table_prefix.'rss');
define('SESSIONS_TABLE', $table_prefix.'sessions');
define('SITEPOLICY_TABLE', $table_prefix.'sitepolicy');
define('SITEPROPERTIES_TABLE', $table_prefix.'siteproperties');
define('MONTHLYPAGESTATS_TABLE', $table_prefix.'stats_pages_monthly');
define('THUMBNAILS_TABLE', $table_prefix.'thumbnails');
define('USERS_TABLE', $table_prefix.'users');

define('PERMISSION_PENDING',10);
define('PERMISSION_NOREPLY',9);
define('PERMISSION_IMAGESONLY',5);
define('PERMISSION_LINKIMAGESONLY',4);
define('PERMISSION_LINKONLY',3);
define('PERMISSION_GRANTED',2);
define('NO_PERMISSION',1);
define('PERMISSION_REFUSED',0);

define('USERLEVEL_USER',0);
define('USERLEVEL_ADMIN',1);

define('MAXIMAGEDIMENSION',200); // todo replace with thumbnail width parameter?

?>