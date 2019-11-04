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

$lang["title_navigator"] = "Navigator";
$lang["title_content"] = "Content";

/* page header ************************************************/

$lang["header_mobilestyle"] = "Mobile Style";
$lang["header_desktopstyle"] = "Desktop Style";
$lang["header_showmenu"] = "Menu ▼";
$lang["header_hidemenu"] = "Menu ▲";

/* page footer ************************************************/

//s1 = date
$lang["footer_lastedited"] = "Last edited: %s";

//s1 = date, s2 = author
$lang["footer_lasteditedauthor"] = "Last edited: %1s by %2s";

// s1 = page_textcopyright, s2 = page_imagecopyright, s3 = page_bypermission
$lang["footer_copyright"] = "%s %s %s";

// text copyright s = copyright holder
$lang["footer_textcopyright"] = "&copy; %s.";

// image copyright s = copyright holder
$lang["footer_imagecopyright"] = " Images &copy; %s.";

// copyright permission
$lang["footer_bypermission"] = " Used by permission.";

$lang["footer_imageuploaded"] = "Image uploaded: %s";
$lang["footer_imageuploadedauthor"] = "Image uploaded: %1s by %2s";




/* menu pages *************************************************/


$lang["menu_filter_displayoptions"] = "Display options:";
$lang["menu_filter_categories"] = "Categories: ";
$lang["menu_filter_timespan"] = "Time span: ";
$lang["menu_filter_from"] = "From: ";
$lang["menu_filter_to"] = "To: ";
$lang["menu_filter_go"] = "Go";
$lang["menu_filter_orderby"] = "Order by: ";
$lang["menu_filter_property"] = "Property: ";
$lang["menu_filter_nomatch"] = "No matching pages found";
$lang["menu_filter_clearsearch"] = "Clear Search";
$lang["menu_filter_badyearselection"] = "Your year selection doesn't make sense";
$lang["menu_filter_result"] = "Search Result";



/* articlemenu pages ******************************************/

$lang["article_filter_title"] = "Title";
$lang["article_filter_author"] = "Author";
$lang["article_filter_date"] = "Article Date";
$lang["article_filter_source"] = "Source";
$lang["article_filter_changes"] = "Latest Changes";
$lang["article_filter_allyears"] = "All Years";
$lang["article_filter_from"] = "From: ";
$lang["article_filter_to"] = "To: ";
$lang["article_filter_showall"] = "Show all articles";


/* news pages *************************************************/

$lang["news_filter_displayoptions"] = "Display options:";
$lang["news_filter_categories"] = "Categories: ";
$lang["news_filter_from"] = "From: ";
$lang["news_filter_to"] = "To: ";
$lang["news_filter_go"] = "Go";
$lang["news_filter_orderby"] = "Order by: ";
$lang["news_filter_property"] = "Property: ";
$lang["news_filter_nomatch"] = "No matching news items found";
$lang["news_filter_clearsearch"] = "Clear Search";
$lang["news_filter_badyearselection"] = "Your year selection doesn't make sense";
$lang["news_filter_result"] = "Search Result";
$lang["news_filter_showall"] = "Show all news items in: %s";

$lang["news_filter_title"] = "Title";
$lang["news_filter_date"] = "Date";
$lang["news_filter_source"] = "Source";

$lang["news_filter_fromday"] = "From Day";
$lang["news_filter_frommonth"] = "From Month";
$lang["news_filter_fromyear"] = "From Year";
$lang["news_filter_today"] = "To Day";
$lang["news_filter_tomonth"] = "To Month";
$lang["news_filter_toyear"] = "To Year";

$lang["news_single_showing"] = "Showing Single Newsitem";
$lang["news_single_link"] = "Link to this newsitem";
$lang["news_single_link_short"] = "Link";

$lang["news_source_source"] = "Source:";
$lang["news_source_foundby"] = "Found By:";

$lang["news_rss_feed"] = "RSS feed for this page";

$lang["newsitem_returnbutton"] = "Show all newsitems";

$lang["news_title_default"] = "News – %s";

/* categories *************************************************/

$lang["categorylist_categories"] = "Categories: ";
$lang["categorylist_none"] = "none";

/* article pages **********************************************/

$lang["article_page_source"] = "Source: ";
$lang["article_page_author"] = "By";
$lang["article_page_toc"] = "Table of Contents";



/* forms ******************************************************/

$lang["form_ascdesc_ascending"] = "Ascending";
$lang["form_ascdesc_descending"] = "Descending";
$lang["form_ascdesc_label"] = "Sort order by: ";

$lang["form_cat_allcats"] = "All Categories";



/* sections ***************************************************/

$lang["section_quote"] = "Quote:";


/* special pages **********************************************/

$lang["navigator_contact"] = "Contact";
$lang["navigator_sitemap"] = "Sitemap";
$lang["navigator_home"] = "Home";
$lang["navigator_guestbook"] = "Guestbook";
$lang["navigator_aotd"] = "Article of the day";
$lang["navigator_potd"] = "On&nbsp;Random:&nbsp;Picture&nbsp;of&nbsp;the&nbsp;Day";
$lang["navigator_notfound"] = "Link not found";

$lang["pagetitle_contact"] = "Contact";
$lang["pagetitle_sitemap"] = "Sitemap";
//$lang["pagetitle_home"] = "Home";
$lang["pagetitle_guestbook"] = "Guestbook";


$lang["pageintro_contact"] = "Send us an e-mail";
$lang["pageintro_sitemap"] = "Sitemap";
//$lang["pageintro_home"] = "Home";
$lang["pageintro_guestbook"] = "Guestbook Messages";



/* guestbook **************************************************/

$lang["guestbook_leavemessageguestbook"] = "Leave a message in the Guestbook";
$lang["guestbook_leavemessage"] = "Leave a message";
$lang["guestbook_name"] = "Name: ";
$lang["guestbook_email"] = "E-mail: ";
$lang["guestbook_date"] = "Date: ";
$lang["guestbook_subject"] = "Subject: ";
$lang["guestbook_message"] = "Message: ";
$lang["guestbook_yourentry"] = "Your entry to our guest book:";
$lang["guestbook_nomessages"] = "No messages";
$lang["guestbook_latestentries"] = "The latest entries:";

$lang["guestbook_legend_yourmessage"] = "Your message";
$lang["guestbook_legend_yourmessagetous"] = "Your message to us";
$lang["guestbook_yourname"] = "Your name: ";
$lang["guestbook_youremail"] = "Your e-mail address: ";
$lang["guestbook_yoursubject"] = "Subject for the message: ";
$lang["guestbook_yourmessage"] = "Your Message: ";

$lang["guestbook_submit"] = "Submit Message";
$lang["guestbook_cancel"] = "Cancel";

$lang["guestbook_disabled"] = "The Guestbook has been disabled.";
$lang["guestbook_needname"] = "Please fill out a name";
$lang["guestbook_invalidtoken"]
    = "ERROR: Invalid token. Your message has not been added. "
    . "You can try submitting it again.";

$lang["guestbook_messageadded"] = "Your message has been added to the Guestbook.";

$lang["guestbook_return"] = "Return to the Guestbook";
$lang["guestbook_leavemessage"] = "Leave a message";

/* email functions ********************************************/

// labels
$lang["email_address"] = "Your e-mail address: ";
$lang["email_subject"] = "Subject: ";
$lang["email_message"] = "Message: ";
$lang["email_sendemail"] = "Send e-mail";
$lang["email_from"] = "From: ";
$lang["email_to"]=  "To:   ";
$lang["email_webmaster"] = "Webmaster";
$lang["email_legend_youremail"] = "Your e-mail";
$lang["email_legend_options"] = "Options";
$lang["email_legend_youremailtous"] = "Your e-mail to us";

// email text
$lang["email_email"] = "E-mail:";
// %1s = site name, $2s = subject
$lang["email_guestbooksubject"] = "[%1s Guestbook] %2s";
$lang["email_guestbookintro"] = "A new message was posted to your guestbook:";
// %1s = site name, $2s = subject
$lang["email_contactsubject"] = "[%1s Site] %2s";
$lang["email_contactintro"] = "This message was sent via your contact form:";



// error messages
$lang["email_enteremail"] = "Please specify an e-mail address";
$lang["email_reenteremail"] = "Please correct your-mail address";
$lang["email_specifysubject"] = "Please specify a subject";
$lang["email_emptymessage"] = "Your message is empty";
$lang["email_wrongmathcaptcha"]
    = "You did not answer the security math question correctly!";
$lang["email_generic_error"]
    = "Sorry, but something went wrong with submitting your message a-null.";
$lang["email_errorsending"] = "Error sending e-mail";
$lang["email_tryagain"] = "The data you entered is not correct. Please try again!";
// %s = open and close link to contact page for webmaster
$lang["email_contactwebmaster"] = "! Please contact the %swebmaster%s";
$lang["email_invalidtoken"]
    = "ERROR: Invalid token. Your email has not been sent. "
    . "You can try submitting it again.";
$lang["email_toosoon"]
    = "ERROR: You may not sent a second message so soon. Please wait a bit.";
$lang["email_duplicate"] = "ERROR: Duplicate message.";
$lang["email_toomany"]
    = "ERROR: Too many messages are being sent. Please wait a bit.";

// messages
$lang["email_enteredmessage"] = "You entered the following message";
$lang["email_thisemailwassent"] = "Your e-mail was sent successfully";
$lang["email_emailsent"] = "Sent e-mail";


/* Anti-spam **************************************************/

$lang["antispam_legend_captcha"] = "Please prove you're not a spambot";


/* show image *************************************************/

$lang["image_viewing"] = "Viewing Image";
$lang["image_viewthumbnails"] = "Return to the full page";


/* Login and restricted pages *********************************/

$lang["login_pagetitle"] = "Login";
$lang["login_legend_login"] = "Login to restricted pages";
$lang["login_legend_logindata"] = "Please enter your login data";
$lang["login_username"] = "Username: ";
$lang["login_password"] = "Password: ";
$lang["login_submit"] = "Login";
$lang["login_cancel"] = "Cancel";

$lang["login_success"] = "Your login was successful";
$lang["login_enter"] = "Enter";

$lang["login_error"] = "Error";
$lang["login_error_tryagain"] = "Please try again.";
$lang["login_error_username"] = "Wrong username or password";
$lang["login_error_inactive"] = "Your account has been deactivated";
$lang["login_passwordcount"]
    = "You have entered the wrong password too often, so we have to lock you out "
    . "for now. Please try again later.";
$lang["login_error_sessionfail"] = "Failed to create session";
$lang["login_error_ipban"] = "Your IP address has been banned";

$lang["restricted_nopermission"] = "You do not have permission do view this page.";
$lang["restricted_expired"] = "Your session has expired.";
$lang["restricted_pagetitle"] = "Access restricted";
$lang["restricted_pleaselogin"] = "Please log in";


/* general ****************************************************/


// top link text
$lang["pagemenu_topofthispage"] = "Top of this page";

$lang["pagemenu_goto"] = "Goto page: ";
$lang["pagemenu_previous"] = "Previous";
$lang["pagemenu_next"] = "Next";
$lang["pagemenu_printview"] = "Print View";
$lang["pagemenu_printview_short"] = "Print";
$lang["pagemenu_jumptopage"] = "Jump to page:";
$lang["pagemenu_go"] = "Go";


$lang["image_image"] = "Image ";
$lang["image_bypermission"] = " Used by permission.";

$lang["error_pagenotfound"] = "Page not found";
$lang["error_pagenonotfound"] = "Could not find page %d.";


$lang["date_month"][1] = "January";
$lang["date_month"][2] = "February";
$lang["date_month"][3] = "March";
$lang["date_month"][4] = "April";
$lang["date_month"][5] = "May";
$lang["date_month"][6] = "June";
$lang["date_month"][7] = "July";
$lang["date_month"][8] = "August";
$lang["date_month"][9] = "September";
$lang["date_month"][10] = "October";
$lang["date_month"][11] = "November";
$lang["date_month"][12] = "December";

$lang["date_month_format"][1] = "January";
$lang["date_month_format"][2] = "February";
$lang["date_month_format"][3] = "March";
$lang["date_month_format"][4] = "April";
$lang["date_month_format"][5] = "May";
$lang["date_month_format"][6] = "June";
$lang["date_month_format"][7] = "July";
$lang["date_month_format"][8] = "August";
$lang["date_month_format"][9] = "September";
$lang["date_month_format"][10] = "October";
$lang["date_month_format"][11] = "November";
$lang["date_month_format"][12] = "December";

$lang["date_month_short"][1] = "Jan";
$lang["date_month_short"][2] = "Feb";
$lang["date_month_short"][3] = "Mar";
$lang["date_month_short"][4] = "Apr";
$lang["date_month_short"][5] = "May";
$lang["date_month_short"][6] = "Jun";
$lang["date_month_short"][7] = "Jul";
$lang["date_month_short"][8] = "Aug";
$lang["date_month_short"][9] = "Sep";
$lang["date_month_short"][10] = "Oct";
$lang["date_month_short"][11] = "Nov";
$lang["date_month_short"][12] = "Dec";

function lang_date_day_format($day)
{
    return $day;
}
?>
