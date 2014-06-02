<div class="contentheader">Add Image to end of gallery</div>
<div class="contentsection">
	<form name="addgalleryimageform" action="{ACTIONVARS}" method="post">
		{HIDDENVARS}
		<fieldset>
			<legend class="highlight">Image File</legend>
			<label for="addimagefilename">Filename:</label>
			<input id="{JSID}addimagefilename" type="text" name="imagefilename" size="50" maxlength="255" value="" />
			<div class="formexplain">Add an image to the end of the gallery by putting in a filename without the path. (<a href="{IMAGELISTPATH}" target="_blank">View files</a>)</div>
			<br /><input type="submit" name="addgalleryimage" value="Add Image" class="mainoption" />
		</fieldset>
	</form>
</div>
