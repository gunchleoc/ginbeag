<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<meta http-equiv="Content-Type"	content="text/html;	charset=utf-8">
		<meta http-equiv="Content-Style-Type" content="text/css">
	  	<link rel="stylesheet" href="{STYLESHEET}" type="text/css">
	  	<link rel="stylesheet" href="{ADMINSTYLESHEET}" type="text/css">
	  	<!-- BEGIN switch SCRIPTLINKS -->
	  	{SCRIPTLINKS}
	  	<!-- END switch SCRIPTLINKS -->
	  	<!-- BEGIN switch JAVASCRIPT -->
	  	{JAVASCRIPT}
	  	<!-- END switch JAVASCRIPT -->
		<title>{HEADERTITLE}</title>
	</head>
	<body>
		<div id="imagelistwrapper">
			<div class="maintitle">Webpage Building - Image Database</div>
			<div id="main">
				<div>
					{MESSAGE}
					<!-- BEGIN switch EDITIMAGEFORM -->
					{EDITIMAGEFORM}
					<!-- END switch EDITIMAGEFORM -->
					<!-- BEGIN switch ADDIMAGEFORM -->
					{ADDIMAGEFORM}
					<!-- END switch ADDIMAGEFORM -->
					{FORM}
					<form><input type="button" name="close" value="Close this window/tab" onClick="window.close()" class="mainoption"></form>
					<p>
					<a href="{PAGEEDITINGLINK}" target="_top">Return to page editing</a>
					</p>
				</div>
			</div>
		</div>
	</body>
</html>
