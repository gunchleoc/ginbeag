<div class="contentheader">{HEADER} Image</div>
<div class="contentsection">
	<form name="imagepropertiesform" action="{ACTIONVARS}<!-- BEGIN switch ANCHOR -->#{ANCHOR}<!-- END switch ANCHOR -->" method="post">
		<div class="leftalign">
			<fieldset>
				<legend class="highlight">Image File</legend>
				<label for="{JSID}imagefilename{IMAGEFILENAME}">Filename:</label>
				<input id="{JSID}imagefilename{IMAGEFILENAME}" type="text" name="imagefilename" size="50" maxlength="255" value="{IMAGEFILENAME}" />
				<div class="formexplain">Change to a different image by putting in a filename without the path (<a href="{IMAGELISTPATH}" target="_blank">View files</a>)<br />Remove the image by entering a blank file name.</div>
				<input type="submit" name="changeimage" value="Change Image" class="mainoption" />
				&nbsp;&nbsp;<input type="reset" value="Reset" />
			</fieldset>
			<fieldset>
				<legend class="highlight">Horizontal Alignment</legend>
				{LEFT_ALIGN_BUTTON} {CENTER_ALIGN_BUTTON} {RIGHT_ALIGN_BUTTON}
			</fieldset>
		      <input type="submit" name="{SUBMITNAME}" value="Save Image Properties" class="mainoption" />
      		&nbsp;&nbsp;<input type="reset" value="Reset" />
		</div>
		<div class="rightalign">{IMAGE}</div>
		<div class="newline"></div>
	</form>
</div>