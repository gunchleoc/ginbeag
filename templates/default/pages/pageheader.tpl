<?xml version="1.1" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<meta http-equiv="Content-Type"	content="text/html; charset=utf-8">
	<meta http-equiv="Content-Style-Type" content="text/css">
	{META_CONTENT}
	<link rel="stylesheet" href="{STYLESHEETCOLORS}" type="text/css">
	<link rel="stylesheet" href="{STYLESHEET}" type="text/css">
	<title>{SITENAME} - {BROWSERTITLE}</title>
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
				<div><a href="{DISPLAYTYPELINK}" class="logoutlink">{L_DISPLAYTYPELINK}</a></div>
			</div>
			<div id="headercenter">
			    <h1 class="maintitle">{SITENAME}</h1>
			    <!-- BEGIN switch SITE_DESCRIPTION -->
			    <div id="sitedescription">{SITE_DESCRIPTION}</div>
			    <!-- END switch SITE_DESCRIPTION -->
			</div>
			<div id="headerright">
				<!-- BEGIN switch RIGHT_IMAGE -->
				<!-- BEGIN switch RIGHT_LINK -->
				<a href="{RIGHT_LINK}">
					<!-- END switch RIGHT_LINK -->
					<img src="{RIGHT_IMAGE}" border="0" alt="{SITENAME}" vspace="1" width="{RIGHT_WIDTH}" height="{RIGHT_HEIGHT}" />
					<!-- BEGIN switch RIGHT_LINK -->
				</a>
				<!-- END switch RIGHT_LINK -->
				<!-- END switch RIGHT_IMAGE -->
		      	<!-- BEGIN switch LOGGED_IN -->
		        <div class="logoutlink"><a href="{LOGOUTLINK}">Log out</a></div>
		      	<!-- END switch LOGGED_IN -->
			</div>
			<div class="newline"></div>
		</div>
		<h1 id="headerpagetitle" class="headerpagetitle newline">{TITLE}</h1>
