<?xml version="1.1" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">
	<meta name="keywords" content="{KEYWORDS}">
	<meta http-equiv="Content-Type"	content="text/html;	charset=utf-8">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel="stylesheet" href="{STYLESHEET}" type="text/css">
	<title>{SITENAME} - {BROWSERTITLE}</title>
	<script type="text/javascript" src="includes/javascript/jquery.js"></script>
	<script language="JavaScript">

// special treatment for IE
if(navigator.appName =="Microsoft Internet Explorer")
{
	document.write('<link rel="stylesheet" type="text/css" href="templates/ie.css">');
}

$(document).ready(function() {
	// height for pages where content is shorter than navigator
	var navheight = $("#navigator").outerHeight(true);
	var bannerheight = $("#banners").outerHeight(true);
	var highestheader = $("#headerleft");

	if ($("#headerright").outerHeight(true) > highestheader.outerHeight(true))
	{
		highestheader = $("#headerright");
	}
	if ($("#headercenter").outerHeight(true) > highestheader.outerHeight(true))
	{
		highestheader = $("#headercenter");
	}
	var headerheight = highestheader.outerHeight(true);
	var titleheight = $("#headerpagetitle").outerHeight(true);
	var wrapperheight = Math.ceil(navheight+bannerheight+headerheight+titleheight);
	if ($("#wrapper").height() < wrapperheight)
	{
		$("#wrapper").height(wrapperheight);
		var difference = $("#wrapper").outerHeight() - $("#wrapper").height();
		//var difference = $("#wrapper").css("margin-top")+ $("#wrapper").css("margin-bottom")+ $("#wrapper").css("padding-top")+$("#wrapper").css("padding-bottom");
		var margin = $("#contentarea").css("margin-bottom").replace("px","") + $("#contentarea").css("margin-top").replace("px","");
		$("#contentarea").height(Math.ceil(navheight+bannerheight-difference-margin));
	}

	/** mobile menu **/

	var menuon = false;
	var navigatorcontent = $("#navigator").html();

	hidemenu();
	if(navigator.appName == "Microsoft Internet Explorer")
	{
		$("#contentarea").css("width","96%");
	}
	else
	{
		$("#contentarea").css("width","initial");
	}
	$("#contentarea").css("margin-left","0px");
	$("#wrapper").css("padding","0px");
	$("#contentarea").css("padding-bottom","2em");

	function hidemenu()
	{
		$("#navigator").css("width","0px");
		$("#navigator").css("visibility","hidden");
		$("#navigator").text("");
		$("#contentarea").css("visibility","visible");
		menuon = false;
		$('#menubutton').attr("value", "{L_SHOWMENU}");
	}

	function showmenu()
	{
		$("#navigator").css("width","96%");
		$("#navigator").css("visibility","visible");
		$("#navigator").html(navigatorcontent);
		$("#contentarea").css("visibility","hidden");
		menuon = true;
		$('#menubutton').attr("value", "{L_HIDEMENU}");
	}


	/* menubutton */
	$("#menubutton").click(function() {
		if(menuon)
		{
			hidemenu()
		}
		else
		{
			showmenu()
		}

	}); // menubutton

});
	</script>
</head>
<body>
	<div id="wrapper">
		<div id="header">
			<div id="headerleft">
				<!-- BEGIN switch LEFT_IMAGE -->
					<!-- BEGIN switch LEFT_LINK -->
				  <a href="{LEFT_LINK}">
					  <!-- END switch LEFT_LINK -->
					  <img src="{LEFT_IMAGE}" border="0" alt="{SITENAME}" vspace="1" width="{LEFT_WIDTH}" height="{LEFT_HEIGHT}" />
					  <!-- BEGIN switch LEFT_LINK -->
				  </a>
				<!-- END switch LEFT_LINK -->
				<!-- END switch LEFT_IMAGE -->
					<!-- BEGIN switch LOGGED_IN -->
				  <div class="logoutlink"><a href="{LOGOUTLINK}">Log out</a></div>
					<!-- END switch LOGGED_IN -->
			</div>
			<div id="headercenter">
				 <h1 class="maintitle">{SITENAME}</h1>
				 <!-- BEGIN switch SITE_DESCRIPTION -->
				 <div id="sitedescription">{SITE_DESCRIPTION}</div>
				 <!-- END switch SITE_DESCRIPTION -->
			</div>
				<div id="headerright">
					&nbsp;
				</div>
			</div>
		<h1 id="headerpagetitle" class="headerpagetitle newline" style="width: initial;">
			<div class="leftalign" style="font-size:80%; margin-left:0.2em; margin-right:1em;">
				<input id="menubutton" type="button" name="menubutton" value="Menu" class="buttonlink" />
			</div>
			<div class="leftalign">{TITLE}</div>
			<div class="rightalign" style="font-size:80%; margin-top:0.25em; margin-left:1em; margin-right:0.2em;">
				<a href="{DISPLAYTYPELINK}" class="buttonlink">{L_DISPLAYTYPELINK}</a>
			</div>
			<div class="newline"></div>
		</h1>

