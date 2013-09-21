{JAVASCRIPT}
<input type="hidden" id="{JSID}page" name="page" value="{PAGE}">
<input type="hidden" id="{JSID}sid" name="sid" value="{SID}">

<form id="{JSID}editor">
	<div class="contentoutline">
		<div class="contentheader">{TITLE}</div>
		<div class="contentsection">
			<div id="{JSID}previewarea" class="editorpreviewarea">{PREVIEWTEXT}</div>
			<div id="{JSID}status" name="status" class="highlight"></div>
			<div id="{JSID}editorcontents">{EDITORCONTENTS}</div>	
		</div>
	</div>
</form>
<div id="{JSID}messagebox" class="messagebox highlight" style="height:0px; width=0px; position:absolute;"></div>
<div id="{JSID}progressbox" class="messagebox" style="height:0px; width=0px; position:absolute;"></div>