<h1 class="headerpagetitle">Edit Synopsis & news page settings</h1>
{NAVIGATIONBUTTONS}
{INTRO}
{IMAGEEDITOR}

<div class="contentheader">Global Functions for this News Page</div>
<div class="contentsection">
	<div class="leftalign">{NEWSITEMDISPLAYORDERFORM}</div>
	<div class="rightalign">
		<form name="rssform" action="{ACTIONVARS}" method="post">
	  		<!-- BEGIN switch RSSBUTTON -->{RSSBUTTON} &nbsp;&nbsp;&nbsp; <!-- END switch RSSBUTTON -->
	  		<input type="submit" name="rssfeed" value="{BUTTONTEXT}" class="mainoption">
	  		<input type="hidden" name="{FIELDNAME}">
		</form>
		<br />{NEWSITEMARCHIVEFORM}
	</div>
	<div class="newline"></div>
</div>

<div><a href="#top" class="smalltext">Top of this page</a></div>
<br />
{NAVIGATIONBUTTONS}
