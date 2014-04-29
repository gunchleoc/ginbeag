<?php

//Datetime format for database entries
define('DATETIMEFORMAT','Y-m-d H:i:s');

// Table names
define('ANTISPAM_TABLE', $table_prefix.'antispam');
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

// Content permissions
define('PERMISSION_GRANTED',2);
define('NO_PERMISSION',1);

// Admin/Webediting panel permissions
define('USERLEVEL_USER',0);
define('USERLEVEL_ADMIN',1);

// for different types of categories
define('CATEGORY_ARTICLE', 0);
define('CATEGORY_IMAGE', 1);
define('CATEGORY_NEWS', 2);

// todo replace site property? Hack for gallery pages CSS
define('IMAGECAPTIONLINEHEIGHT',8);

?>