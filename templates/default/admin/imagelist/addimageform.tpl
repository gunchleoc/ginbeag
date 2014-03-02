<form name="addimageform" action="editimagelist.php{ACTIONVARS}" enctype="multipart/form-data" method="post">
	<div class="imagecontentarea">
		<div class="contentoutline">
			<div class="contentheader">Add Image</div>
			<div class="contentsection">
				<div class="leftalign" style="width:31% !important; padding-right:2%;">
			
					<fieldset>
						<legend class="highlight">Upload an image file</legend>
						<label for="addimagefile">Image file: </label>
						<input is="addimagefile" type="file" name="filename" size="30" maxlength="255" />
						<div class="formexplain">Required. Select an image file from your computer for uploading.<br />It will be uploaded to <i>{IMAGELINKPATH}</i></div>
		
						<br /><label for="addimagenewname">Rename Image: </label>
						<input id="addimagenewname" type="text" name="newname" size="30" maxlength="255" />
						<div class="formexplain">Optional. You can give the image a new filename if you want.</div>
		
						<br /><label for="addimagethumbnail">Thumbnail file: </label>
						<input id="addimagethumbnail" type="file" name="thumbnail" size="30" maxlength="255" />
						<div class="formexplain">Optional. A small preview version of the image.<br />Thumbnails should be {THUMBNAILSIZE} pixels in width.</div>
			        </fieldset>
				</div>
				<div class="leftalign" style="width:31% !important; padding-right:2%;">
					<fieldset>
						<legend class="highlight">Image description</legend>
						<fieldset>
							<legend>Caption elements</legend>
							<label for="addimagecaption">Caption: </label>
							<input id="addimagecaption" type="text" name="caption" value="{CAPTION}" size="30" maxlength="200" />
							<div class="formexplain">Optional. Describe what's on the image.</div>
					
							<br /><label for="addimagesource">Source Name: </label>
							<input id="addimagesource" type="text" name="source" value="{SOURCE}" size="30" maxlength="255" />
							<div class="formexplain">Optional. Name of the website or other source you got the image from.</div>
					
							<br /><label for="addimagesourcelink">Source URL: </label>
							<input id="addimagesourcelink" type="text" name="sourcelink" value="{SOURCELINK}" size="30" maxlength="255" />
							<div class="formexplain">Optional. Link to the website you got the image from, starting with the protocol, e.g. <em>http://</em>.</div>
					
							<br /><label for="addimagecopyright">Copyright Holder: </label>
							<input id="addimagecopyright" type="text" name="copyright" value="{COPYRIGHT}" size="30" maxlength="255" />
							<div class="formexplain">Name of the person or organization who owns this image.</div>
						</fieldset>
						<fieldset>
							<legend>Permissions</legend>
							{PERMISSION_GRANTED} {NO_PERMISSION} {PERMISSION_REFUSED}
							<div class="formexplain">Did the copyright owner give us permission to use this image?</div>
						</fieldset
					</fieldset>	
				</div>
				<div class="leftalign" style="width:31% !important; padding-right:2%;">
					<fieldset>
						<legend class="highlight">Categories</legend>
				        {CATEGORYSELECTION}
				        <div class="formexplain">Optional, but strongly recommended, because this will help you find the image again later. Hold down the CTRL key to select more than one caption.</div>
					</fieldset>
				</div>
		        <div class="newline">
		        	<input type="submit" name="addimage" value="Add New Image" class="mainoption" />
		        </div>
			</div>
		</div>
	</div>
</form>