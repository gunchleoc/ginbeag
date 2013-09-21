<form>
	<div class="contentheader">Copyright</div>
	<div class="contentsection">
		<fieldset>
			<legend class="highlight">Text Content</legend>
			<label for="{JSID}copyright">Copyright Holder:</label>
			<input id="{JSID}copyright" type="text" name="copyright" size="70" maxlength="255" value="{COPYRIGHT}" />
			<br /><label for="{JSID}permission">Permissions:</label>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {PERMISSION_GRANTED} &nbsp;&nbsp;&nbsp; {NO_PERMISSION} &nbsp;&nbsp;&nbsp; {PERMISSION_REFUSED}
		</fieldset>
		<fieldset>
			<legend class="highlight">Image Content</legend>
			<label for="{JSID}imagecopyright">Image Copyright Holder:</label>
			<input id="{JSID}imagecopyright" type="text" name="imagecopyright" size="70" maxlength="255" value="{IMAGE_COPYRIGHT}" />
		</fieldset>
		<input type="button" id="{JSID}savepermissionsbutton" name="savepermissionsbutton" value="Save Copyright Info" class="mainoption" />
		<input id="{JSID}savepermissionsreset" type="reset" value="Reset" />
	</div>
</form>