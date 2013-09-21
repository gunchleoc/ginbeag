<h1 class="headerpagetitle">Edit News Page and Item {NEWSITEM}: <span id="{JSID}headernewsitemtitle">{NEWSITEMTITLE}</span></h1>
{JAVASCRIPT}
<input type="hidden" id="{JSID}sid" name="sid" value="{SID}">
<input type="hidden" id="{JSID}newsitem" name="newsitem" value="{NEWSITEM}">
<input type="hidden" id="{JSID}page" name="page" value="{PAGE}">

{BACKBUTTONS}

<div class="contentheader">Global Functions for this News Page</div>
<div class="contentsection">
	<div class="leftalign">{NEWSITEMADDFORM}<br />{NEWSITEMDISPLAYORDERFORM}</div>
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

<!-- BEGIN switch HASNEWSITEMS -->
<div class="leftalign">{NEWSITEMSEARCHFORM}</div>
<div class="rightalign">{PAGEMENU}{JUMPTOPAGEFORM}</div>
<div class="newline"></div>

<div class="contentheader">News Item <em><span id="{JSID}newsitemheader">{NEWSITEMTITLE}</span></em>: Title, Publishing & Preview</div>
<div class="contentsection">
	<div class="leftalign">
		<span id="{JSID}newsitemtitleheader" class="sectiontitle">{NEWSITEMTITLE}</span>
		<form>
			<input id="{JSID}title" type="text" name="title" size="25" maxlength="255" value="{TITLE}" /> 
			<input type="button" id="{JSID}savetitlebutton" name="savetitlebutton" value="Save Title" class="mainoption" />
			&nbsp;&nbsp;
			<input id="{JSID}savetitlereset" type="reset" value="Reset" />
		</form>
		Copyright, Source information, Categorization and Date can be edited on the bottom.
	</div>
	<div class="rightalign">{NEWSITEMPUBLISHFORM}Added by {AUTHORNAME}{NEWSITEMDELETEFORM}</div>
	<div class="newline"></div>
</div>

{NEWSITEMSYNOPSISFORM}
<div class="contentheader">Sections</div>
<div class="contentsection">{NEWSITEMSECTIONFORM}</div>
{NEWSITEMPERMISSIONSFORM}
{NEWSITEMSOURCEFORM}
{FAKETHEDATEFORM}
<span id="{JSID}categorylist">{CATEGORYLIST}</span>
<p>
	{CATEGORYSELECTION}
	<br>&nbsp;<br><input type="button" id="{JSID}addcatbutton" name="addcatbutton" value="Add Categories" class="mainoption" />
	<input type="button" id="{JSID}removecatbutton" name="removecatbutton" value="Remove Categories" />
</p>
<hr>
<div align="right">{PAGEMENU}</div>
<div align="right">{JUMPTOPAGEFORM}</div>
<!-- END switch HASNEWSITEMS -->
{BACKBUTTONS}
<div id="{JSID}messagebox" class="messagebox highlight" style="height:0px; width=0px; position:absolute;"></div>
<div id="{JSID}progressbox" class="messagebox" style="height:0px; width=0px; position:absolute;"></div>