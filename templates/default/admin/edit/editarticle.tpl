<h1 class="headerpagetitle">Edit Article</h1>
{JAVASCRIPT}
{HIDDENVARS}

{NAVIGATIONBUTTONS}

<div class="contentheader">Synopsis</div>
<div class="contentsection">{SYNOPSISEDITOR}{IMAGEEDITOR}</div>

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
		</fieldset>
		<fieldset>
			<legend class="highlight">Date</legend>
			{DAYFORM} &nbsp;&nbsp; {MONTHFORM} &nbsp;&nbsp;
			<label for="{JSID}year">Year (4-digit):</label><!-- todo: yearform for consistency -->
			<input id="{JSID}year" type="text" name="year" size="5" maxlength="4" value="{YEAR}" />
			<div class="formexplain">You can leave the Day empty, or the Day and the Month. Set the Year to 0000 for an empty date.</div>
		</fieldset>

		<fieldset>
			<legend class="highlight">Table of Contents</legend>
			<label for="{JSID}author">Display Table of Contents:</label>
			{TOC_YES} {TOC_NO}
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
{NAVIGATIONBUTTONS}
<div id="{JSID}messagebox" class="messagebox highlight" style="height:0px; width=0px; position:absolute;"></div>
<div id="{JSID}progressbox" class="messagebox" style="height:0px; width=0px; position:absolute;"></div>
