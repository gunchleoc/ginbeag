<form name="addimageform" action="{ACTIONVARS}" enctype="multipart/form-data" method="post">
	<div class="imagecontentarea">
		<div class="contentoutline">
			<div class="contentheader">Add Image</div>
			<div class="contentsection">
				<div class="leftalign" style="width:31% !important; padding-right:2%;">
					<fieldset width="100%">
						<legend class="highlight">Image File</legend>
						<label for="addimagefile">Image file: </label>
						<input is="addimagefile" type="file" name="filename" size="30" maxlength="255" />
						<div class="formexplain"><strong>Required</strong>. Select an image file from your computer for uploading.<br />It will be uploaded to <i>{IMAGELINKPATH}</i></div>

						<br /><label for="addimagenewname">Rename Image: </label>
						<input id="addimagenewname" type="text" name="newname" size="30" maxlength="255" />
						<div class="formexplain"><strong>Optional</strong>. You can give the image a new filename if you want.</div>

						<!-- BEGIN switch RESIZEIMAGEFORM -->
							{RESIZEIMAGEFORM}
							<div class="formexplain">
								<strong>Optional</strong>. If this is checked, the image will be scaled down to fit the default width of {DEFAULTIMAGEWIDTH} pixels.
								<br />Supported filetypes: <em>gif</em>, <em>jpeg</em>,<em>png</em>, <em>wbmp</em>, <em>xbm</em>.
							</div>
						<!-- END switch RESIZEIMAGEFORM -->

					</fieldset>
					<fieldset width="100%">
						<legend class="highlight">Thumbnail</legend>
						<!-- BEGIN switch CREATETHUMBNAILFORM -->
							{CREATETHUMBNAILFORM}
							<div class="formexplain">
								<strong>Optional</strong>. If this is checked, a thumbnail for the newly uploaded image will be created automatically.
								<br />Supported filetypes: <em>gif</em>, <em>jpeg</em>,<em>png</em>, <em>wbmp</em>, <em>xbm</em>.
							</div>
							<br /><div class="highlight">- or-</div><br />
						<!-- END switch CREATETHUMBNAILFORM -->
						<label for="addimagethumbnail">Thumbnail file: </label>
						<input id="addimagethumbnail" type="file" name="thumbnail" size="30" maxlength="255" />
						<div class="formexplain"><strong>Optional</strong>. A small preview version of the image.<br />Thumbnails should be {THUMBNAILSIZE} pixels in width.</div>

				</div>
				<div class="leftalign" style="width:31% !important; padding-right:2%;">
					<fieldset>
						<legend class="highlight">Image description</legend>
						<fieldset>
							<legend>Caption Elements</legend>
							<label for="addimagecaption">Caption: </label>
							<input id="addimagecaption" type="text" name="caption" value="{CAPTION}" size="30" maxlength="200" />
							<div class="formexplain">Describe what's on the image.</div>

							<br /><label for="addimagesource">Source Name: </label>
							<input id="addimagesource" type="text" name="source" value="{SOURCE}" size="30" maxlength="255" />
							<div class="formexplain">Name of the website or other source you got the image from.</div>

							<br /><label for="addimagesourcelink">Source URL: </label>
							<input id="addimagesourcelink" type="text" name="sourcelink" value="{SOURCELINK}" size="30" maxlength="255" />
							<div class="formexplain"> Link to the website you got the image from, starting with the protocol, e.g. <em>http://</em>.</div>

							<br /><label for="addimagecopyright">Copyright Holder: </label>
							<input id="addimagecopyright" type="text" name="copyright" value="{COPYRIGHT}" size="30" maxlength="255" />
							<div class="formexplain">Name of the person or organization who owns this image.</div>
						</fieldset>
						<fieldset>
							<legend>Permissions</legend>
							{PERMISSION_GRANTED} {NO_PERMISSION}
							<div class="formexplain">Did the copyright owner give us permission to use this image?</div>
						</fieldset>
						<div class="formexplain">All caption elements are optional.</div>
					</fieldset>
				</div>
				<div class="leftalign" style="width:31% !important; padding-right:2%;">
					<fieldset>
						<legend class="highlight">Categories</legend>
				        {CATEGORYSELECTION}
				        <div class="formexplain">Optional, but strongly recommended, because this will help you find the image again later. Hold down the CTRL key to select more than one category.</div>
					</fieldset>
				</div>
		        <div class="newline">
		        	<input type="submit" name="addimage" value="Add New Image" class="mainoption" />
		        </div>
			</div>
		</div>
	</div>
</form>
