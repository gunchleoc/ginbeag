<fieldset>
	<legend class="highlight">Image Size</legend>
	<fieldset class="leftalign" style="width:45%;">
		<legend>Shrink Image</legend>
		{SHRINK_ON_BUTTON} {SHRINK_OFF_BUTTON}
		<div class="formexplain">Choose if the image will be resized automatically when no thumbnail is used.</div>
	</fieldset>
	<fieldset class="leftalign" style="width:45%;">
		<legend>Use Thumbnail</legend>
		{THUMBNAIL_ON_BUTTON} {THUMBNAIL_OFF_BUTTON}
		<div class="formexplain">Choose if the image thumbnail will be used, if available.</div>
	</fieldset>
	<div class="newline">
	<input type="button" id="{JSID}submitsize" name="{SUBMITNAME}" value="Save Image Size Options" class="mainoption" />
	&nbsp;&nbsp;<input id="{JSID}resetsize" type="reset" value="Reset" />
	</div>
</fieldset>
