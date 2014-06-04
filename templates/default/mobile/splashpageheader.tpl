<?xml version="1.1" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta name="keywords" content="{KEYWORDS}">
	<meta http-equiv="Content-Type"	content="text/html;	charset=utf-8">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<link rel="stylesheet" href="{STYLESHEET}" type="text/css">
	<title>{SITENAME}</title>
	<style>
html body {
	font-size: 200%;
}
	</style>
	<script language="JavaScript">

// special treatment for IE
if(navigator.appName =="Microsoft Internet Explorer")
{
	document.write('<link rel="stylesheet" type="text/css" href="templates/ie.css">');
}
	</script>
</head>
<body>
	<div id="wrapper">
		<div id="headerleft">
			<!-- BEGIN switch LOGGED_IN -->
			 <a href="{LOGOUTLINK}">Log out</a>
			<!-- END switch LOGGED_IN -->
		</div>
		<div id="headercenter" class="splashpageheaderspacer">
			<span class="maintitle">{SITENAME}</span>
			<!-- BEGIN switch SITE_DESCRIPTION -->
			<div id="sitedescription">{SITE_DESCRIPTION}</div>
			<!-- END switch SITE_DESCRIPTION -->
		</div>
		<div id="headerright">&nbsp;
		</div>
		<div class="newline"></div>
	<div><a href="{DISPLAYTYPELINK}" class="buttonlink">{L_DISPLAYTYPELINK}</a></div>
