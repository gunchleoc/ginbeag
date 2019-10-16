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

$lang=array();

/* screen reader titles ***************************************/

$lang["title_navigator"] = "Clàr-taice";
$lang["title_content"] = "Sùsbaint";

/* page header ************************************************/

$lang["header_mobilestyle"]="Inneal-làimhe";
$lang["header_desktopstyle"]="Dèasg";
$lang["header_showmenu"]="Clàr-taice ▼";
$lang["header_hidemenu"]="Clàr-taice ▲";

/* page footer ************************************************/

//s1 = date
$lang["footer_lastedited"]="An dèasachadh mu dheireadh: %s";

//s1 = date, s2 = author
$lang["footer_lasteditedauthor"]="An dèasachadh mu dheireadh: %1s le %2s";

// s1 = page_textcopyright, s2 = page_imagecopyright, s3 = page_bypermission
$lang["footer_copyright"]="%s %s %s";

// text copyright s = copyright holder
$lang["footer_textcopyright"]="&copy; %s.";

// image copyright s = copyright holder
$lang["footer_imagecopyright"]="Na dèalbhan &copy; %s.";

// copyright permission
$lang["footer_bypermission"]=" Gan clèachdadh le cead.";

$lang["footer_imageuploaded"]="Dèalbh air a luchdadh suas: %s";
$lang["footer_imageuploadedauthor"]="Dèalbh air a luchdadh suas: %1s le %2s";



/* menu pages *************************************************/


$lang["menu_filter_displayoptions"]="Ròghainnean an t-seallaidh:";
$lang["menu_filter_categories"]="Roinnean-seòrsa: ";
$lang["menu_filter_timespan"]="Àm: ";
$lang["menu_filter_from"]="Bhò: ";
$lang["menu_filter_to"]="Gù: ";
$lang["menu_filter_go"]="Siùthad";
$lang["menu_filter_orderby"]="Òrdugh a rèir: ";
$lang["menu_filter_property"]="Bùadh: ";
$lang["menu_filter_nomatch"]="Cha do dh'fhrèagair gin de na h-aistean";
$lang["menu_filter_clearsearch"]="Fàg an lorg";
$lang["menu_filter_badyearselection"]="Cha dèan an dà bhliadhna a thagh thu ciall còmhla.";
$lang["menu_filter_result"]="Tòraidhean an luirg";



/* articlemenu pages ******************************************/

$lang["article_filter_title"]="Tìotal";
$lang["article_filter_author"]="Ùghdar";
$lang["article_filter_date"]="Cèann-latha";
$lang["article_filter_source"]="Tùs";
$lang["article_filter_changes"]="Dèasachadh mu dheireadh";
$lang["article_filter_allyears"]="A h-uile blìadhna";
$lang["article_filter_from"]="Bhò: ";
$lang["article_filter_to"]="Gù: ";
$lang["article_filter_showall"]="Seall na h-uile àiste";


/* news pages *************************************************/

$lang["news_filter_displayoptions"]="Ròghainnean an t-seallaidh:";
$lang["news_filter_categories"]="Roinnean-seòrsa: ";
$lang["news_filter_from"]="Bhò: ";
$lang["news_filter_to"]="Gù: ";
$lang["news_filter_go"]="Siùthad";
$lang["news_filter_orderby"]="Òrdugh a rèir: ";
$lang["news_filter_property"]="Bùadh: ";
$lang["news_filter_nomatch"]="Cha do dh'fhrèagair gin de na naidheachdan";
$lang["news_filter_clearsearch"]="Fàg an lorg";
$lang["news_filter_badyearselection"]="Cha dèan an dà bhliadhna a thagh thu ciall còmhla.";
$lang["news_filter_result"]="Tòraidhean an luirg";
$lang["news_filter_showall"]="Seall na h-uile nàidheachd air: %s";

$lang["news_filter_title"]="Tìotal";
$lang["news_filter_date"]="Cèann-latha";
$lang["news_filter_source"]="Tùs";

$lang["news_filter_fromday"]="Bhon là";
$lang["news_filter_frommonth"]="Bohn mhìos";
$lang["news_filter_fromyear"]="Bhon bhlìadhna";
$lang["news_filter_today"]="Gus an là";
$lang["news_filter_tomonth"]="Gus a' mhìos";
$lang["news_filter_toyear"]="Gus a' bhlìadhna";

$lang["news_single_showing"]="A' sealltainn naidheachd a-mhàin";
$lang["news_single_link"]="Dèan ceangal dhan naidheachd seo";
$lang["news_single_link_short"]="Ceàngal";


$lang["news_source_source"]="Tùs:";
$lang["news_source_foundby"]="Air a lòrg le:";

$lang["news_rss_feed"]="Ìnbhir RSS airson na duilleige seo";

$lang["newsitem_returnbutton"]="Seall na h-uile nàidheachd";

$lang["news_title_default"]="Nàidheachd – %s";

/* categories *************************************************/

$lang["categorylist_categories"]="Roinnean-seòrsa: ";
$lang["categorylist_none"]="gìn";

/* article pages **********************************************/

$lang["article_page_source"]="Tùs: ";
$lang["article_page_author"]="Lè";
$lang["article_page_toc"]="Clàr-innse";



/* forms ******************************************************/

$lang["form_ascdesc_ascending"]="A' dìreadh";
$lang["form_ascdesc_descending"]="A' teàrnadh";
$lang["form_ascdesc_label"]="Còmhair: ";

$lang["form_cat_allcats"]="A h-uile roinn-seòrsa";



/* sections ***************************************************/

$lang["section_quote"]="Às-earrann:";

/* special pages **********************************************/

$lang["navigator_contact"]="Fìos thugainn";
$lang["navigator_sitemap"]="Mapa an làraich";
$lang["navigator_home"]="Dhàchaigh";
$lang["navigator_guestbook"]="Leàbhar nan aoighean";
$lang["navigator_aotd"]="Alt an làtha";
$lang["navigator_potd"]="Air&nbsp;thuaiream:&nbsp;Dealbh&nbsp;an&nbsp;làtha";
$lang["navigator_notfound"]="Cha deach an cèangal a lorg";

$lang["pagetitle_contact"]="Fìos thugainn";
$lang["pagetitle_sitemap"]="Mapa an làraich";
//$lang["pagetitle_home"]="Dàchaigh";
$lang["pagetitle_guestbook"]="Leàbhar nan aoighean";

$lang["pageintro_contact"]="Cuir pòst-d thugainn";
$lang["pageintro_sitemap"]="Mapa an làraich";
//$lang["pageintro_home"]="Dàchaigh";
$lang["pageintro_guestbook"]="Tèachdaireachdan nan aoighean";



/* guestbook **************************************************/

$lang["guestbook_leavemessageguestbook"]="Cuir tèachdaireachd ri leabhar nan aoighean";
$lang["guestbook_leavemessage"]="Cuir tèachdaireachd ris";
$lang["guestbook_name"]="Àinm: ";
$lang["guestbook_email"]="Pòst-d: ";
$lang["guestbook_date"]="Ceànn-latha: ";
$lang["guestbook_subject"]="Cùspair: ";
$lang["guestbook_message"]="Tèachdaireachd: ";
$lang["guestbook_yourentry"]="Do thèachdaireachd gu leabhar nan aoighean:";
$lang["guestbook_nomessages"]="Gun tèachdaireachd";
$lang["guestbook_latestentries"]="Na teachdaireachdan as ùire:";

$lang["guestbook_legend_yourmessage"]="An tèachdaireachd agad";
$lang["guestbook_legend_yourmessagetous"]="An tèachdaireachd agad thugainn";
$lang["guestbook_yourname"]="D' àinm: ";
$lang["guestbook_youremail"]="An seòladh puist-d agad: ";
$lang["guestbook_yoursubject"]="Cùspair na teachdaireachd: ";
$lang["guestbook_yourmessage"]="An tèachdaireachd agad: ";

$lang["guestbook_submit"]="Cuir a-steach ì";
$lang["guestbook_cancel"]="Sguir dhèth";

$lang["guestbook_disabled"]="Chaidh leabhar nan aoighean a chur à comas.";
$lang["guestbook_needname"]="Fèuch ri d' ainm a chur a-steach";
$lang["guestbook_invalidtoken"]="MEARACHD: Tòcan mì-dhligheach. Cha deach an teachdaireachd agad a chur ris. Faodaidh tu feuchainn ris a cur a-null a-rithist.";

$lang["guestbook_messageadded"]="Chàidh an teachdaireachd agad a chur ri leabhar nan aoighean.";

$lang["guestbook_return"]="Tìll gu leabhar nan aoighean";
$lang["guestbook_leavemessage"]="Fàg teachdaireachd";


/* email functions ********************************************/

// labels
$lang["email_address"]="An seòladh puist-d agad: ";
$lang["email_subject"]="Cùspair: ";
$lang["email_message"]="Tèachdaireachd: ";
$lang["email_sendcopy"]="Cùir lethbhreac thugam";
$lang["email_copyrequested"]="Dh'iarr thu lethbhrèac";
$lang["email_nocopyrequested"]="Cha do dh'iarr thu lethbhrèac";
$lang["email_sendemail"]="Cuir pòst-d";
$lang["email_from"]="Bhò: ";
$lang["email_to"]=  "Gù:  ";
$lang["email_webmaster"]="Maighstir-lìn";
$lang["email_legend_youremail"]="Am pòst-d";
$lang["email_legend_options"]="Ròghainnean";
$lang["email_legend_youremailtous"]="Am post-d agad thùgainn";

// email text
$lang["email_email"]="Pòst-d:";
$lang["email_guestbooksubject"]="Teachdaireachd aoigh ùr @ ";
// %s = site name
$lang["email_contactsubject"]="Teachdaireachd tro làrach %s - ";


// error messages
$lang["email_enteremail"]="Feuch an sònraich thu seòladh puist-d";
$lang["email_reenteremail"]="Feuch an ceartaich thu an seòladh puist-d agad";
$lang["email_specifysubject"]="Feuch an sònraich thu cuspair";
$lang["email_emptymessage"]="Tha an teachdaireachd agad bàn";
$lang["email_wrongmathcaptcha"]="Chan eil an fhreagairt cheart don cheist tèarainteachd matamataigeach agad!";
$lang["email_generic_error"]="Tha sinn duilich ach cha deach leinn an tèachdaireachd agad a chur a-null.";
$lang["email_errorsending"]="Thàchair mearachd teignigeach a' cur a' phost-d agad";
$lang["email_tryagain"]="Chan eil am fìosrachadh a chur thu a-steach ceart. Am feuch thu ris a-rithist?";
// %s = open and close link to contact page for webmaster
$lang["email_contactwebmaster"]="! Feuch ri fìos a chur gun %swebmaster%s";
$lang["email_invalidtoken"]="MEARACHD: Tòcan mì-dhligheach. Cha deach am post-d agad a chur. Faodaidh tu feuchainn ris a chur a-null a-rithist.";

// messages
$lang["email_enteredmessage"]="Chuir thu a-steach an tèachdaireachd a leanas";
$lang["email_thisemailwassent"]="Chàidh am post-d agad a chur gu soirbheachail";
// %s = site name
$lang["email_yourmessage"]="An tèachdaireachd agad gu %s - ";
$lang["email_yourguestbookentry"]="An tèachdaireachd agad gu leabhar nan aoighean ";
$lang["email_emailsent"]="Tha am pòst-d air a chur";


/* Anti-spam **************************************************/

$lang["antispam_legend_captcha"]="An dèarbhaich thu nach e inneal-rannsachaidh a th' annad?";


/* show image *************************************************/

$lang["image_viewing"]="A' sealltainn air dèalbh";
$lang["image_viewthumbnails"]="Till dhan duilleag shlàn";


/* Login and restricted pages *********************************/

$lang["login_pagetitle"]="Lògadh a-steach";
$lang["login_legend_login"]="Lòg a-steach dha dhuilleagan cuingichte";
$lang["login_legend_logindata"]="An cuir thu a-steach an dàta clàraidh agad?";
$lang["login_username"]="Ainm a' chlèachdaiche: ";
$lang["login_password"]="Fàcal-faire: ";
$lang["login_submit"]="Lòg a-steach mi";
$lang["login_cancel"]="Sgùir dheth";

$lang["login_success"]="Chàidh do logadh a-steach gu soirbheachail";
$lang["login_enter"]="Ràch a-steach";

$lang["login_error"]="Mèarachd";
$lang["login_error_tryagain"]="Am fèuch thu ris a-rithist?";
$lang["login_error_username"]="Chan eil d' ainm is facal-faire mar bu chòir.";
$lang["login_error_inactive"]="Chaidh an cunntas seo a chur à gnìomh.";
$lang["login_passwordcount"]="Tha thu air facal-faire ceàrr a chur a-steach ro thric, mar sin feumaidh sinn do ghlasadh a-mach an-dràsta. Am feuch thu ris a-rithist an ceann greis?";
$lang["login_error_sessionfail"]="Cha dèach leinn seisean a chruthachadh.";
$lang["login_error_ipban"]="Chaidh an seòladh IP agad a bhacadh.";

$lang["restricted_nopermission"]="Chan eil cead agad gus sèalltainn air an duilleag seo.";
$lang["restricted_expired"]="Dh'fhalbh an ùine air an seisean agad.";
$lang["restricted_pagetitle"]="Ìnntrigeadh cuingichte";
$lang["restricted_pleaselogin"]="An lòg thu a-steach?";


/* general ****************************************************/


// top link text
$lang["pagemenu_topofthispage"]="Cèann na duilleige";

$lang["pagemenu_goto"]="Ràch gu duilleag: ";
$lang["pagemenu_previous"]="Air àis";
$lang["pagemenu_next"]="Air àdhart";
$lang["pagemenu_printview"]="Sealladh clò-bhualaidh";
$lang["pagemenu_printview_short"]="Clò-bhuail";
$lang["pagemenu_jumptopage"]="Leum gu dùilleag:";
$lang["pagemenu_go"]="Siùthad";


$lang["image_image"]="Dèalbh ";
$lang["image_bypermission"]=" Ga clèachdadh le cead.";

$lang["error_pagenotfound"]="Cha deach an dùilleag a lorg";
$lang["error_pagenonotfound"]="Cha deach dùilleag %d a lorg.";

$lang["date_month"][1]="Am Fàoilleach";
$lang["date_month"][2]="An Gèarran";
$lang["date_month"][3]="Am Màrt";
$lang["date_month"][4]="An Gìblean";
$lang["date_month"][5]="An Cèitean";
$lang["date_month"][6]="An t-Ògmhios";
$lang["date_month"][7]="An t-Iùchar";
$lang["date_month"][8]="An Lùnastal";
$lang["date_month"][9]="An Sùltain";
$lang["date_month"][10]="An Dàmhair";
$lang["date_month"][11]="An Sàmhainn";
$lang["date_month"][12]="An Dùbhlachd";

$lang["date_month_format"][1]="dhen Fhàoilleach";
$lang["date_month_format"][2]="dhen Ghèarran";
$lang["date_month_format"][3]="dhen Mhàrt";
$lang["date_month_format"][4]="dhen Ghìblean";
$lang["date_month_format"][5]="dhen Chèitean";
$lang["date_month_format"][6]="dhen Ògmhios";
$lang["date_month_format"][7]="dhen Iùchar";
$lang["date_month_format"][8]="dhen Lùnastal";
$lang["date_month_format"][9]="dhen t-Sùltain";
$lang["date_month_format"][10]="dhen Dàmhair";
$lang["date_month_format"][11]="dhen t-Sàmhainn";
$lang["date_month_format"][12]="dhen Dùbhlachd";

$lang["date_month_short"][1]="Fàoi";
$lang["date_month_short"][2]="Geàrr";
$lang["date_month_short"][3]="Màrt";
$lang["date_month_short"][4]="Gìbl";
$lang["date_month_short"][5]="Cèit";
$lang["date_month_short"][6]="Ògmh";
$lang["date_month_short"][7]="Iùch";
$lang["date_month_short"][8]="Lùn";
$lang["date_month_short"][9]="Sùlt";
$lang["date_month_short"][10]="Dàmh";
$lang["date_month_short"][11]="Sàmh";
$lang["date_month_short"][12]="Dùbh";

function lang_date_day_format($day)
{
    if($day == "1" || $day == 11) {
        return $day."d";
    } elseif ($day == "2" || $day == 12) {
        return $day."a";
    } elseif ($day == "3" || $day == 13) {
        return $day."s";
    } else {
        return $day."mh";
    }
}
?>
