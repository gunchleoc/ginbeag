<!-- BEGIN switch NOTQUOTE -->
{JAVASCRIPT}
{HIDDENVARS}
<a name="section{NEWSITEMSECTION}"></a>
<div class="contentheader">Section: <span id="{JSID}sectionheader">{SECTIONHEADER}</span></div>
<div class="contentsection">
	<span class="medtext">Section titles and images are optional.</span>
	<form>
		<input id="{JSID}sectiontitle" type="text" name="sectiontitle" value="{SECTIONTITLE}" />
		<input type="button" id="{JSID}savesectiontitlebutton" name="savesectiontitlebutton" value="Save Section Title" class="mainoption" />
		<input id="{JSID}savesectiontitlereset" type="reset" value="Reset" />
	</form>
	{SECTIONEDITOR}
	{IMAGEEDITOR}
</div>

<p>
<form action="{ACTIONVARS}" method="post">
  <input type="submit" name="deletesection" value="Delete This Section" />
</form>
{INSERTNEWSITEMSECTIONFORM}
<div id="{JSID}messagebox" class="messagebox highlight" style="height:0px; width=0px; position:absolute;"></div>
<div id="{JSID}progressbox" class="messagebox" style="height:0px; width=0px; position:absolute;"></div>
<!-- END switch NOTQUOTE -->
<!-- BEGIN switch QUOTESTART -->
<div class="newsquote"><p class="highlight">begin quote</p>
<!-- END switch QUOTESTART -->
<!-- BEGIN switch QUOTEEND -->
<p class="highlight">end quote</p></div>
<!-- END switch QUOTEEND -->
