<?xml version="1.1" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">
	<meta name="keywords" content="{KEYWORDS}">
	<meta http-equiv="Content-Type"	content="text/html;	charset=utf-8">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel="stylesheet" href="{STYLESHEETCOLORS}" type="text/css">
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
	/** mobile menu **/

	var menuon = false;

	$('#header').css("height", Math.max($('#headerleft').outerHeight(true), $('#sitedescription').outerHeight(true)));

	hidemenu();

	function hidemenu()
	{
		setpageheight($('#contentarea'));
		$('#navigator').css("width","0px");
		$('#navigator').css("height", "0px");
		$('#contentarea').css("visibility","visible");
		$('#navigator').css("visibility","hidden");
		menuon = false;
		$('#menubutton').attr("value", "{L_SHOWMENU}");
		setpageheight($('#contentarea'));
		setpageheight($('#wrapper'));
	}

	function showmenu()
	{
		$('#navigator').css("width","96%");
		$('#contentarea').css("height", "0px");
		$('#navigator').css("visibility","visible");
		$('#contentarea').css("visibility","hidden");
		menuon = true;
		$('#menubutton').attr("value", "{L_HIDEMENU}");
		setpageheight($('#navigator'));
		setpageheight($('#wrapper'));
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

	function setpageheight(element)
	{
		var height = 0;

		 element.children().each(function() {
			 height = height + $(this).outerHeight(true);
		});
		element.css("height", (Math.ceil(height)+"px"));
	}

});
	</script>
</head>
<body>
	<div id="wrapper">
		<div id="header">
			<div id="headerleft" class="leftalign maintitle">
				<!-- BEGIN switch LEFT_IMAGE -->
					<!-- BEGIN switch LEFT_LINK -->
			        <a href="{LEFT_LINK}">
				        <!-- END switch LEFT_LINK -->
				        <img src="{LEFT_IMAGE}" border="0" alt="{SITENAME}" vspace="1" width="{LEFT_WIDTH}" height="{LEFT_HEIGHT}" />
				        <!-- BEGIN switch LEFT_LINK -->
			        </a>
			      <!-- END switch LEFT_LINK -->
			      <!-- END switch LEFT_IMAGE -->
			</div>
			<div id="sitedescription" class="leftalign">
				<div class="maintitle">{SITENAME}</div>
				<!-- BEGIN switch SITE_DESCRIPTION -->{SITE_DESCRIPTION}<!-- END switch SITE_DESCRIPTION -->
			</div>
		</div>
		<h1 id="headerpagetitle" class="headerpagetitle newline">
			<div class="leftalign" style="font-size:80%;">
				<input id="menubutton" type="button" name="menubutton" value="Menu" class="buttonlink" />
			</div>
			<div class="rightalign" style="font-size:80%; margin-top:0.20em;">
				<!-- BEGIN switch LOGGED_IN --><a href="{LOGOUTLINK}" class="buttonlink">Log out</a><!-- END switch LOGGED_IN -->
				<a href="{DISPLAYTYPELINK}" class="buttonlink">{L_DISPLAYTYPELINK}</a>
			</div>
			<div class="newline"></div>
		</h1>

