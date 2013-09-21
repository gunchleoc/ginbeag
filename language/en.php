<?php

$lang=array();


/**************************************************************/
/* page footer ************************************************/
/**************************************************************/

//s1 = date
$lang["footer_lastedited"]="Last edited: %s1"; 

//s1 = date, s2 = author
$lang["footer_lasteditedauthor"]="Last edited: %s1 by %s2"; 

// s1 = page_textcopyright, s2 = page_imagecopyright, s3 = page_bypermission
$lang["footer_copyright"]="%s %s %s";

// text copyright s = copyright holder
$lang["footer_textcopyright"]="&copy; %s.";

// image copyright s = copyright holder
$lang["footer_imagecopyright"]=" Images &copy; %s.";

// copyright permission
$lang["footer_bypermission"]=" Used by permission.";

$lang["footer_imageuploaded"]="Image uploaded: %s1";
$lang["footer_imageuploadedauthor"]="Image uploaded: %s1 by %s2";




/**************************************************************/
/* menu pages *************************************************/
/**************************************************************/


$lang["menu_filter_displayoptions"]="Display options:";
$lang["menu_filter_categories"]="Categories: ";
$lang["menu_filter_from"]="From: ";
$lang["menu_filter_to"]="To: ";
$lang["menu_filter_go"]="Go";
$lang["menu_filter_orderby"]="Order by: ";
$lang["menu_filter_subpages"]="Include subpages";
$lang["menu_filter_nomatch"]="No matching pages found";
$lang["menu_filter_clearsearch"]="Clear Search";
$lang["menu_filter_badyearselection"]="Your year selection doesn't make sense";
$lang["menu_filter_result"]="Search Result";



/**************************************************************/
/* articlemenu pages ******************************************/
/**************************************************************/

$lang["article_filter_title"]="Title";
$lang["article_filter_author"]="Author";
$lang["article_filter_date"]="Article Date";
$lang["article_filter_source"]="Source";
$lang["article_filter_changes"]="Latest Changes";
$lang["article_filter_allyears"]="All Years";
$lang["article_filter_from"]="From: ";
$lang["article_filter_to"]="To: ";



/**************************************************************/
/* news pages *************************************************/
/**************************************************************/

$lang["news_filter_displayoptions"]="Display options:";
$lang["news_filter_categories"]="Categories: ";
$lang["news_filter_from"]="From: ";
$lang["news_filter_to"]="To: ";
$lang["news_filter_go"]="Go";
$lang["news_filter_orderby"]="Order by: ";
$lang["news_filter_nomatch"]="No matching news items found";
$lang["news_filter_clearsearch"]="Clear Search";
$lang["news_filter_badyearselection"]="Your year selection doesn't make sense";
$lang["news_filter_result"]="Search Result";
$lang["news_filter_showall"]="Show all news items in: %s";

$lang["news_filter_title"]="Title";
$lang["news_filter_date"]="Date";
$lang["news_filter_source"]="Source";

$lang["news_filter_fromday"]="From Day";
$lang["news_filter_frommonth"]="From Month";
$lang["news_filter_fromyear"]="From Year";
$lang["news_filter_today"]="To Day";
$lang["news_filter_tomonth"]="To Month";
$lang["news_filter_toyear"]="To Year";


/**************************************************************/
/* article pages **********************************************/
/**************************************************************/

$lang["article_page_source"]="Source: ";



/**************************************************************/
/* forms ******************************************************/
/**************************************************************/

$lang["form_ascdesc_ascending"]="Ascending";
$lang["form_ascdesc_descending"]="Descending";

$lang["form_cat_allcats"]="All Categories";



/**************************************************************/
/* sections ***************************************************/
/**************************************************************/

$lang["section_quote"]="Quote:";


/**************************************************************/
/* special pages **********************************************/
/**************************************************************/

$lang["navigator_contact"]="Contact";
$lang["navigator_sitemap"]="Sitemap";
$lang["navigator_home"]="Home";
$lang["navigator_guestbook"]="Guestbook";

$lang["pagetitle_contact"]="Contact";
$lang["pagetitle_sitemap"]="Sitemap";
//$lang["pagetitle_home"]="Home";
$lang["pagetitle_guestbook"]="Guestbook";


$lang["pageintro_contact"]="Send us an e-mail";
$lang["pageintro_sitemap"]="Sitemap";
//$lang["pageintro_home"]="Home";
$lang["pageintro_guestbook"]="Guestbook Messages";



/**************************************************************/
/* guestbook **************************************************/
/**************************************************************/

$lang["guestbook_leavemessageguestbook"]="Leave a message in the Guestbook";
$lang["guestbook_leavemessage"]="Leave a message";
$lang["guestbook_name"]="Name: ";
$lang["guestbook_email"]="E-mail: ";
$lang["guestbook_date"]="Date: ";
$lang["guestbook_subject"]="Subject: ";
$lang["guestbook_message"]="Message: ";
$lang["guestbook_yourentry"]="Your entry to our guest book:";
$lang["guestbook_nomessages"]="No messages";
$lang["guestbook_latestentries"]="The latest entries:";

$lang["guestbook_yourname"]="Your name: ";
$lang["guestbook_youremail"]="Your e-mail address: ";
$lang["guestbook_yoursubject"]="Subject for the message: ";
$lang["guestbook_yourmessage"]="Your Message: ";

$lang["guestbook_submit"]="Submit Message";
$lang["guestbook_cancel"]="Cancel";

$lang["guestbook_disabled"]="The Guestbook has been disabled.";
$lang["guestbook_needname"]="Please fill out a name";

$lang["guestbook_messageadded"]="Your message has been added to the Guestbook.";

$lang["guestbook_return"]="Return to the Guestbook";
$lang["guestbook_leavemessage"]="Leave a message";

/**************************************************************/
/* email functions ********************************************/
/**************************************************************/

// labels
$lang["email_address"]="Your e-mail address";
$lang["email_subject"]="Subject";
$lang["email_message"]="Message";
$lang["email_sendcopy"]="Please send me a copy";
$lang["email_copyrequested"]="Copy requested";
$lang["email_nocopyrequested"]="No copy requested";
$lang["email_sendemail"]="Send e-mail";
$lang["email_from"]="From";
$lang["email_to"]="To";

// email text
$lang["email_email"]="E-mail:";
$lang["email_guestbooksubject"]="New Guestbook entry @ ";
// %s = site name
$lang["email_contactsubject"]="Message sent via the %s contact form - ";




// error messages
$lang["email_enteremail"]="Please specify an e-mail address";
$lang["email_illegalchar"]="Your e-mail address contains illegal characters: ";
$lang["email_reenteremail"]="Please correct your-mail address";
$lang["email_notvalidemail"]="This is not a valid e-mail address";
$lang["email_specifysubject"]="Please specify a subject";
$lang["email_emptymessage"]="Your message is empty";
$lang["email_wrongmathcaptcha"]="You did not answer the security math question correctly!";
$lang["email_errorsending"]="Error sending e-mail";
$lang["email_tryagain"]="The data you entered is not correct. Please try again!";
// %s = open and close link to contact page for webmaster
$lang["email_contactwebmaster"]="! Please contact the %swebmaster%s";

// messages
$lang["email_enteredmessage"]="You entered the following message";
$lang["email_emailsentto"]="Your e-mail was sent to ";
$lang["email_youremailsent"]="Your e-mail was sent";
$lang["email_thisemailwassent"]="Your e-mail was sent successfully";
$lang["email_yourmessage"]="Your message to ";
$lang["email_yourguestbookentry"]="Your guestbook entry ";
$lang["email_emailsent"]="Sent e-mail";


/**************************************************************/
/* general ****************************************************/
/**************************************************************/


// top link text
$lang["page_topofthispage"]="Top of this page";

$lang["pagemenu_goto"]="Goto page: ";
$lang["pagemenu_previous"]="Previous";
$lang["pagemenu_next"]="Next";


$lang["image_image"]="Image ";
$lang["image_bypermission"]=" Used by permission.";



$lang["date_month"][1]="January";
$lang["date_month"][2]="February";
$lang["date_month"][3]="March";
$lang["date_month"][4]="April";
$lang["date_month"][5]="May";
$lang["date_month"][6]="June";
$lang["date_month"][7]="July";
$lang["date_month"][8]="August";
$lang["date_month"][9]="September";
$lang["date_month"][10]="October";
$lang["date_month"][11]="November";
$lang["date_month"][12]="December";
?>