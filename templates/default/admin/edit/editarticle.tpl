<h1 class="headerpagetitle">Edit Article</h1>
{JAVASCRIPT}
<input type="hidden" id="{JSID}sid" name="sid" value="{SID}">
<input type="hidden" id="{JSID}page" name="page" value="{PAGE}">

{BACKBUTTONS}
<div class="contentheader">Edit pages</div>
<div class="contentsection">
	<div class="leftalign">
	<form name="articlepages" method="post">{ARTICLEPAGEBUTTON}</form>
	</div>
	<div class="rightalign">
		<form name="addarticlepageform" action="{ACTIONVARS}" method="post">
			<input type="submit" name="addarticlepage" value="Add Page" class="mainoption" />
	    </form>
	</div>
	<div class="newline"></div>
</div>

<div class="contentheader">Synopsis</div>
<div class="contentsection">{SYNOPSISEDITOR}{IMAGEFORM}</div>

<div class="contentheader">Source, Date & Categorization</div>
<div class="contentsection">
	<form>
		<fieldset>
			<legend class="highlight">Source</legend>
			<label for="{JSID}author">Author:</label>
			<input id="{JSID}author" type="text" name="author" size="80" maxlength="255" value="{AUTHOR}" />
			<br /><label for="{JSID}location">Location:</label>
			<input id="{JSID}location" type="text" name="location" size="58" maxlength="255" value="{LOCATION}" />
			<br /><label for="{JSID}source">Source name:</label>
			<input id="{JSID}source" type="text" name="source" size="58" maxlength="255" value="{SOURCE}" />
			<br /><label for="{JSID}sourcelink">Source link:</label>
			<input id="{JSID}sourcelink" type="text" name="sourcelink" size="58" maxlength="255" value="{SOURCELINK}" />
			<br />{DAYFORM} &nbsp;&nbsp; {MONTHFORM} &nbsp;&nbsp;
			<label for="{JSID}year">Year (4-digit):</label><!-- todo: yearform for consistency -->
			<input id="{JSID}year" type="text" name="year" size="5" maxlength="4" value="{YEAR}" />
		
		</fieldset>
		
		<fieldset>
			<legend class="highlight">Table of Contents</legend>
			<label for="{JSID}author">Display Table of Contents:</label>
			<input id="{JSID}toc_yes" type="radio" name="toc" value="yes" {TOC_YES_CHECKED} />Yes
			<input id="{JSID}toc_no" type="radio" name="toc" value="no" {TOC_NO_CHECKED} />No
		</fieldset>
		<input type="button" id="{JSID}savesourcebutton" name="savesourcebutton" value="Save Changes" class="mainoption" />
		&nbsp;&nbsp;
		<input id="{JSID}savesourcereset" type="reset" value="Reset" />
	</form>
</div>

<div class="contentheader">Categories</div>
<div class="contentsection">
	<span id="{JSID}categorylist">{CATEGORYLIST}</span>
	
	<p>
		{CATEGORYSELECTION}
		<br>&nbsp;<br><input type="button" id="{JSID}addcatbutton" name="addcatbutton" value="Add Categories" class="mainoption" />
		<input type="button" id="{JSID}removecatbutton" name="removecatbutton" value="Remove Categories" />
	</p>
</div>
{BACKBUTTONS}
<div id="{JSID}messagebox" class="messagebox highlight" style="height:0px; width=0px; position:absolute;"></div>
<div id="{JSID}progressbox" class="messagebox" style="height:0px; width=0px; position:absolute;"></div>