{JAVASCRIPT}
{HIDDENVARS}
<div class="contentoutline">
	<div class="contentheader">Image: <em>{FILENAME}</em></div>
	<div class="contentsection">
		<div class="leftalign" style="width:31% !important; padding-right:2%;">
			<fieldset>
				<legend class="highlight">Image information</legend>
				<span class="smalltext highlight">Image path: {FILEPATH}</span>
				<span id="{JSID}image">{IMAGE}</span>
			</fieldset>
			<form>
				<fieldset>
					<legend class="highlight">Edit Image description</legend>
					<fieldset>
						<legend>Caption elements</legend>
						<label for="{JSID}caption">Caption: </label>
						<input id="{JSID}caption" type="text" name="caption" value="{CAPTION}" size="30" />
						<div class="formexplain">Describe what's on the image.</div>

						<br /><label for="{JSID}source">Source Name: </label>
						<input id="{JSID}source" type="text" name="source" value="{SOURCE}" size="30" maxlength="255" />
						<div class="formexplain">Name of the website or other source you got the image from.</div>

						<br /><label for="{JSID}sourcelink">Source URL: </label>
						<input id="{JSID}sourcelink" type="text" name="sourcelink" value="{SOURCELINK}" size="30" maxlength="255" />
						<div class="formexplain">Link to the website you got the image from, starting with the protocol, e.g. <em>http://</em>.</div>

						<br /><label for="{JSID}copyright">Copyright Holder: </label>
						<input id="{JSID}copyright" type="text" name="copyright" value="{COPYRIGHT}" size="30" maxlength="255" />
						<div class="formexplain">Name of the person or organization who owns this image.</div>
					</fieldset>

					<fieldset>
						<legend>Permissions</legend>
						<span id="{JSID}permission">{PERMISSION_GRANTED} {NO_PERMISSION}</span>
						<div class="formexplain">Did the copyright owner give us permission to use this image?</div>
					</fieldset>

					<input type="button" id="{JSID}savedescriptionbutton" name="savebutton" value="Save Image Description" class="mainoption" />
					&nbsp;&nbsp;
					<input type="reset" id="{JSID}resetdescriptionbutton" name="reset" value="Reset" />
				</fieldset>
			</form>
		</div>



		<div class="leftalign" style="width:31% !important; padding-right:2%;">
			<form>
				<fieldset>
					<legend class="highlight">Categories</legend>
					<span id="{JSID}categorylist">{CATEGORYLIST}</span>
	      			<p>
	          			{CATEGORYSELECTION}
	          			<br>&nbsp;<br><input type="button" id="{JSID}addcatbutton" name="addcatbutton" value="Add Categories" class="mainoption" />
	          			<input type="button" id="{JSID}removecatbutton" name="removecatbutton" value="Remove Categories" />
	      			</p>
				</fieldset>
				<fieldset>
					<legend class="highlight">Usage</legend>
					<div id="{JSID}usage"></div><br>
					<input type="button" id="{JSID}showusagebutton" name="showusagebutton" value="Show where this image is used" class="mainoption" />
				</fieldset>
			</form>
		</div>

		<div class="leftalign" style="width:31% !important;">
			<fieldset>
				<legend class="highlight">File Operations</legend>
				<fieldset>
					<legend>Image file</legend>
					<form name="replaceimageform" action="{ACTIONVARSREPLACE}" enctype="multipart/form-data" method="post">
	      			{HIDDENVARS}
						<label for="{JSID}replaceimagefile">Select a new image:</label></br>
						<input id="{JSID}replaceimagefile" type="file" name="newfilename" size="30" maxlength="255" />
      				<input type="submit" name="replaceimage" value="Replace Image File" class="mainoption">
						<div class="formexplain">Replace this image with a new file.</div>
	    			</form>
	    			<!-- BEGIN switch ACTIONVARSRESIZEIMAGE -->
						<form name="resizeimageform" action="{ACTIONVARSRESIZEIMAGE}" enctype="multipart/form-data" method="post">
							{HIDDENVARS}
							<input type="submit" name="resizeimage" value="Resize Image Width" class="mainoption" />
							<div class="formexplain">
								Scale the image down to fit the default width of {DEFAULTIMAGEWIDTH} pixels.
								<br />Supported filetypes: <em>gif</em>, <em>jpeg</em>,<em>png</em>, <em>wbmp</em>, <em>xbm</em>.
							</div>
					<!-- END switch ACTIONVARSRESIZEIMAGE -->
	      		</fieldset>
	      		<fieldset>
					<legend>Thumbnail file</legend>
	      			<!-- BEGIN switch NO_THUMBNAIL -->
  					<form name="addthumbform" action="{ACTIONVARSADDTHUMB}" enctype="multipart/form-data" method="post">
	      				{HIDDENVARS}
						<label for="{JSID}addthumbnailfile">Select a new thumbnail:</label></br>
           				<input id="{JSID}addthumbnailfile" type="file" name="thumbnail" size="30" maxlength="255" />
	      				<input type="submit" name="addthumb" value="Add Thumbnail" class="mainoption" />
							<div class="formexplain">Add a thumbnail to this image.</div>
	     			</form>
  					<!-- END switch NO_THUMBNAIL -->
  					<!-- BEGIN switch THUMBNAIL -->
  					<form name="replacethumbform" action="{ACTIONVARSREPLACETHUMB}" enctype="multipart/form-data" method="post">
	      				{HIDDENVARS}
						<label for="{JSID}replacethumbnailfile">Select a new thumbnail:</label></br>
           				<input id="{JSID}replacethumbnailfile" type="file" name="thumbnail" size="30" maxlength="255" />
	      				<input type="submit" name="replacethumb" value="Replace Thumbnail File" class="mainoption" />
							<div class="formexplain">Replace the thumbnail for this image with a new file.</div>
  					</form>
      				<!-- END switch THUMBNAIL -->
						<!-- BEGIN switch ACTIONVARSCREATETHUMBNAIL -->
						<form name="createthumbnailform" action="{ACTIONVARSCREATETHUMBNAIL}" enctype="multipart/form-data" method="post">
							{HIDDENVARS}
							<input type="submit" name="createthumbnail" value="Generate Thumbnail" class="mainoption" />
							<div class="formexplain">
								Autogenerate a new thumbnail.
								<br />Supported filetypes: <em>gif</em>, <em>jpeg</em>,<em>png</em>, <em>wbmp</em>, <em>xbm</em>.
							</div>
						<!-- END switch ACTIONVARSCREATETHUMBNAIL -->
						</form>
      			</fieldset>
      			<fieldset>
					<legend>Deleting</legend>
					<form name="deleteform" action="{ACTIONVARSDELETE}" method="post">
	        			{HIDDENVARS}
	        			<input type="submit" name="delete" value="Delete this image from database and file system" class="mainoption" />
	      			</form>
	      			<!-- BEGIN switch THUMBNAIL -->
            		<form name="deletethumbform" action="{ACTIONVARSDELETETHUMBNAIL}" method="post">
              			{HIDDENVARS}
              			<input type="submit" name="deletethumb" value="Delete Thumbnail File" class="mainoption" />
            		</form>
	        		<!-- END switch THUMBNAIL -->
				</fieldset>
      			</fieldset>
		</div>
		<div class="newline"></div>
	</div>
</div>
<div id="{JSID}messagebox" class="messagebox highlight" style="height:0px; width=0px; position:absolute;"></div>
<div id="{JSID}progressbox" class="messagebox" style="height:0px; width=0px; position:absolute;"></div>
