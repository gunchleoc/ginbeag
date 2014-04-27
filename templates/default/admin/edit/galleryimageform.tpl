{JAVASCRIPT}
<div class="contentheader">Gallery Image</div>
<div class="contentsection">
	<form name="galleryimageform" action="{ACTIONVARS}" method="post">
		{HIDDENVARS}
		<div class="leftalign" style="width:70%">
			<fieldset>
				<legend class="highlight">Image File</legend>
				<label for="{JSID}imagefilename">Filename:</label>
				<input id="{JSID}imagefilename" type="text" name="imagefilename" size="50" maxlength="255" value="{IMAGEFILENAME}" />
				<div class="formexplain">Change this to a different image by putting in a filename without the path. (<a href="{IMAGELISTPATH}" target="_blank">View files</a>)</div>
				<p>
					<input type="button" id="{JSID}saveimagebutton" name="saveimagebutton" value="Change Image" class="mainoption" />
					&nbsp;&nbsp;
					<input type="reset" id="{JSID}saveimagereset" name="saveimagereset" value="Reset" />
				</p>
				<!-- BEGIN switch NO_THUMBNAIL -->
				<p class="highlight">{NO_THUMBNAIL}</p>
				<!-- END switch NO_THUMBNAIL -->
			</fieldset>
			<p>
				<input type="submit" name="moveimageup" value="move up" />
				&nbsp;&nbsp;&nbsp;<input type="text" name="positions" size="2" maxlength="3" value="1" />
				&nbsp;&nbsp;&nbsp;<input type="submit" name="moveimagedown" value="move down" />
			</p>
			<p>
				<input type="submit" name="removegalleryimage" value="Remove image from this gallery" />
				{REMOVECONFIRMFORM}
			</p>
	    </div>
	    <div class="rightalign"><span id="{JSID}image">{IMAGE}</span></div>
		<div class="newline"></div>
	</form>
</div>
<div id="{JSID}messagebox" class="messagebox highlight" style="height:0px; width=0px; position:absolute;"></div>
<div id="{JSID}progressbox" class="messagebox" style="height:0px; width=0px; position:absolute;"></div>