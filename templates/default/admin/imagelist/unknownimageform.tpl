<div class="contentoutline">
	<div class="contentheader">Image: <em>{FILENAME}</em></div>
	<div class="contentsection">
		<form name="addunknownform" action="{ACTIONVARSADDUNKNOWNFILE}" method="post">
			{HIDDENVARS}
			<div class="leftalign" style="width:31% !important; padding-right:2%;">
				<fieldset>
					<legend class="highlight">Image information</legend>
					<div><img src="{IMAGE}" width="{WIDTH}" height ="{HEIGHT}" /></div>
					<span class="highlight">{IMAGEPATH}</span>
					<br />{IMAGEPROPERTIES}

				</fieldset>
				<input type="submit" name="addunknownfile" value="Add this file to the database" class="mainoption" />
			</div>
			<div class="leftalign" style="width:31% !important; padding-right:2%;">
				<fieldset>
					<legend class="highlight">Image description</legend>
					<fieldset>
						<legend>Caption elements</legend>
						<label for="addimagecaption">Caption: </label>
						<input id="addimagecaption" type="text" name="caption" value="" size="30" maxlength="200" />
						<div class="formexplain">Optional. Describe what's on the image.</div>
				
						<br /><label for="addimagesource">Source Name: </label>
						<input id="addimagesource" type="text" name="source" value="" size="30" maxlength="255" />
						<div class="formexplain">Optional. Name of the website or other source you got the image from.</div>
				
						<br /><label for="addimagesourcelink">Source URL: </label>
						<input id="addimagesourcelink" type="text" name="sourcelink" value="" size="30" maxlength="255" />
						<div class="formexplain">Optional. Link to the website you got the image from, starting with the protocol, e.g. <em>http://</em>.</div>
				
						<br /><label for="addimagecopyright">Copyright Holder: </label>
						<input id="addimagecopyright" type="text" name="copyright" value="" size="30" maxlength="255" />
						<div class="formexplain">Name of the person or organization who owns this image.</div>
					</fieldset>
					<fieldset>
						<legend>Permissions</legend>
						{PERMISSION_GRANTED} {NO_PERMISSION}
						<div class="formexplain">Did the copyright owner give us permission to use this image?</div>
					</fieldset>
				</fieldset>
			</div>
			<div class="leftalign" style="width:31% !important; padding-right:2%;">
				<fieldset>
						<legend class="highlight">Categories</legend>
				        {CATEGORYSELECTION}
				        <div class="formexplain">Optional, but strongly recommended, because this will help you find the image again later. Hold down the CTRL key to select more than one caption.</div>
					</fieldset>
			</div>
		</form>
		<form name="deleteform" action="{ACTIONVARSDELETEFILE}" method="post">
			{HIDDENVARS}
			<div class="newline">
					<input type="submit" name="deletefile" value="Delete this file" class="mainoption" /> {DELETEFILECONFIRMFORM}
			</div>
		</form>
	</div>
</div>
