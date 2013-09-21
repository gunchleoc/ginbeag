<?php

// Debug Level
define('DEBUG', 1); // Debugging on
//define('DEBUG', 0); // Debugging off

define('DATETIMEFORMAT','Y-m-d H:i:s');
define('SHORTDATETIMEFORMAT','d M y H:i:s');

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
define('GALLERIES_TABLE', $table_prefix.'galleries');
define('GALLERYITEMS_TABLE', $table_prefix.'galleryitems');
define('GUESTBOOK_TABLE', $table_prefix.'guestbook');
define('IMAGECATS_TABLE', $table_prefix.'image_categories');
define('IMAGES_TABLE', $table_prefix.'images');
define('LINKLISTS_TABLE', $table_prefix.'linklists');
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

define('MAXIMAGEDIMENSION',500);


// makelinkparameters($_GET,true);
// todo site
// todo separate list for admin?

$LEGALVARS = array();
$LEGALVARS["action"]=1;
$LEGALVARS["all"]=1;
$LEGALVARS["articlepage"]=1;
$LEGALVARS["articlesection"]=1;
$LEGALVARS["ascdesc"]=1;
$LEGALVARS["caption"]=1;
$LEGALVARS["categoriesblank"]=1;
$LEGALVARS["clear"]=1;
$LEGALVARS["contents"]=1;
$LEGALVARS["copyright"]=1;
$LEGALVARS["copyrightblank"]=1;
$LEGALVARS["elementtype"]=1;
$LEGALVARS["filename"]=1;
$LEGALVARS["filter"]=1;
$LEGALVARS["filterpage"]=1;
$LEGALVARS["forgetful"]=1;
$LEGALVARS["from"]=1;
$LEGALVARS["fromday"]=1;
$LEGALVARS["frommonth"]=1;
$LEGALVARS["fromyear"]=1;
$LEGALVARS["image"]=1;
$LEGALVARS["imageid"]=1;
$LEGALVARS["item"]=1;
$LEGALVARS["jumppage"]=1;
$LEGALVARS["key"]=1;
$LEGALVARS["link"]=1;
$LEGALVARS["logout"]=1;
$LEGALVARS["missing"]=1;
$LEGALVARS["missingthumb"]=1;
$LEGALVARS["mode"]=1;
$LEGALVARS["newsitem"]=1;
$LEGALVARS["newsitemsection"]=1;
$LEGALVARS["noofimages"]=1;
$LEGALVARS["nothumb"]=1;
$LEGALVARS["number"]=1;
$LEGALVARS["offset"]=1;
$LEGALVARS["order"]=1;
$LEGALVARS["override"]=1;
$LEGALVARS["page"]=1;
$LEGALVARS["pageposition"]=1;
$LEGALVARS["params"]=1;
$LEGALVARS["permission"]=1;
$LEGALVARS["printview"]=1;
$LEGALVARS["search"]=1;
$LEGALVARS["searchpage"]=1;
$LEGALVARS["selectedcat"]=1;
$LEGALVARS["showall"]=1;
$LEGALVARS["sid"]=1;
$LEGALVARS["sitemap"]=1;
$LEGALVARS["sitepolicy"]=1;
$LEGALVARS["source"]=1;
$LEGALVARS["sourceblank"]=1;
$LEGALVARS["sourcelink"]=1;
$LEGALVARS["subpages"]=1;
$LEGALVARS["superforgetful"]=1;
$LEGALVARS["text"]=1;
$LEGALVARS["to"]=1;
$LEGALVARS["today"]=1;
$LEGALVARS["tomonth"]=1;
$LEGALVARS["toyear"]=1;
$LEGALVARS["unknown"]=1;
$LEGALVARS["unlock"]=1;
$LEGALVARS["unused"]=1;
$LEGALVARS["uploader"]=1;
$LEGALVARS["user"]=1;


$LEGALVARSSITE = array();
$LEGALVARSSITE["action"]=1;
$LEGALVARSSITE["ascdesc"]=1;
$LEGALVARSSITE["backup"]=1;
$LEGALVARSSITE["bannerid"]=1;
$LEGALVARSSITE["changeaccess"]=1;
$LEGALVARSSITE["changelevel"]=1;
$LEGALVARSSITE["clearpagecache"]=1;
$LEGALVARSSITE["contact"]=1;
$LEGALVARSSITE["display"]=1;
$LEGALVARSSITE["filterpermission"]=1;
$LEGALVARSSITE["generate"]=1;
$LEGALVARSSITE["holder"]=1;
$LEGALVARSSITE["offset"]=1;
$LEGALVARSSITE["order"]=1;
$LEGALVARSSITE["profile"]=1;
$LEGALVARSSITE["ref"]=1;
$LEGALVARSSITE["search"]=1;
$LEGALVARSSITE["sid"]=1;
$LEGALVARSSITE["structure"]=1;
$LEGALVARSSITE["type"]=1;
$LEGALVARSSITE["userid"]=1;

?>
