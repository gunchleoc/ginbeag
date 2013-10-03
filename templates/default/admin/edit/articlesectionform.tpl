{JAVASCRIPT}
<input type="hidden" id="{JSID}sid" name="sid" value="{SID}">
<input type="hidden" id="{JSID}articlesection" name="articlesection" value="{ARTICLESECTION}">
<input type="hidden" id="{JSID}page" name="page" value="{PAGE}">
<a name="section{ARTICLESECTION}"></a>
<div class="contentheader"><span id="{JSID}sectionheader">{SECTIONHEADER}</span></div>
<div class="contentsection">
	<form>
		<input id="{JSID}sectiontitle" type="text" name="sectiontitle" value="{SECTIONTITLE}" />
		<input type="button" id="{JSID}savesectiontitlebutton" name="savesectiontitlebutton" value="Save Section Title" class="mainoption" />
		<input id="{JSID}savesectiontitlereset" type="reset" value="Reset" />
		<div class="formexplain">Section titles and images are optional.</div>
	</form>
	{SECTIONEDITOR}
	{IMAGEEDITOR}

	<form name="movearticlesection" action="{ACTIONVARS}" method="post">
		<p><input type="submit" name="movesectionup" value="{MOVEUP}" />
			&nbsp;&nbsp;&nbsp;
			<input type="submit" name="movesectiondown" value="{MOVEDOWN}" />
		</p>
	</form>
	<form name="deletesection" action="{ACTIONVARS}" method="post">
		<input type="submit" name="deletesection" value="Delete This Section" class="mainoption">
	</form>
</div>
<div id="{JSID}messagebox" class="messagebox highlight" style="height:0px; width=0px; position:absolute;"></div>
<div id="{JSID}progressbox" class="messagebox" style="height:0px; width=0px; position:absolute;"></div>
