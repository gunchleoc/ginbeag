<div class="contentheader">Image</div>
<div class="contentsection">
	<form name="newsitemimagepropertiesform" action="{ACTIONVARS}" method="post">
		<div class="leftalign">
			<fieldset>
				<legend class="highlight">Image Properties</legend>
				<label for="{JSID}imagefilename{IMAGEFILENAME}">Filename:</label>
				<input id="{JSID}imagefilename{IMAGEFILENAME}" type="text" name="imagefilename" size="50" maxlength="255" value="{IMAGEFILENAME}" />
				<div class="formexplain">Change to a different image by putting in a filename without the path (<a href="{IMAGELISTPATH}" target="_blank">View files</a>)</div>
				<input type="submit" name="editnewsitemsynopsisimage" value="Change Image" class="mainoption" />
				&nbsp;&nbsp;<input type="reset" value="Reset" />
			</fieldset>
              <p>
                <input type="submit" name="removenewsitemsynopsisimage" value="Remove image" />
                {REMOVECONFIRMFORM}
              </p>
		</div>
		<div class="rightalign">{IMAGE}</div>
		<div class="newline"></div>
	</form>
</div>