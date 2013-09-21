<div class="contentoutline">
	<div class="contentheader">Image: <em>{FILENAME}</em></div>
	<div class="contentsection">
		<div class="leftalign" style="width:31% !important; padding-right:2%;">
			<form name="deleteform" action="{ACTIONVARSDELETEFILE}" method="post">
				{HIDDENVARS}
				<input type="submit" name="deletefile" value="Delete this file" class="mainoption" />
				<br /><input type="checkbox" name="deletefileconfirm" />Confirm delete
			</form>
		</div>
		<form name="addunknownform" action="{ACTIONVARSADDUNKNOWNFILE}" method="post">
			{HIDDENVARS}
			<div class="leftalign" style="width:31% !important; padding-right:2%;">
	
				<fieldset>
					<legend class="highlight">Image information</legend>
					{IMAGE}
				</fieldset>
	            
				<fieldset>
					<legend class="highlight">Edit Image description</legend>
					<fieldset>
						<legend>Caption elements</legend>
						<label for="{JSID}caption">Caption: </label>
						<input id="{JSID}caption" type="text" name="caption" value="{CAPTION}" size="30" />
						<div class="formexplain">Describe what's on the image.</div>
				
						<br /><label for="{JSID}source">Source: </label>
						<input id="{JSID}source" type="text" name="source" value="{SOURCE}" size="30" maxlength="255" />
						<div class="formexplain">Name of the website or other source you got the image from.</div>
				
						<br /><label for="{JSID}sourcelink">Source Link: </label>
						<input id="{JSID}sourcelink" type="text" name="sourcelink" value="{SOURCELINK}" size="30" maxlength="255" />
						<div class="formexplain">Link to the website you got the image from.</div>
				
						<br /><label for="{JSID}copyright">Copyright Holder: </label>
						<input id="{JSID}copyright" type="text" name="copyright" value="{COPYRIGHT}" size="30" maxlength="255" />
						<div class="formexplain">Name of the person or organization who owns this image.</div>
					</fieldset>
					
					<fieldset>
						<legend>Permissions</legend>
						<span id="{JSID}permission">{PERMISSION_GRANTED} {NO_PERMISSION} {PERMISSION_REFUSED}</span>
						<div class="formexplain">Did the copyright owner give us permission to use this image?</div>
					</fieldset>
		
					<input type="button" id="{JSID}savedescriptionbutton" name="savebutton" value="Save Image Description" class="mainoption" />
					&nbsp;&nbsp;
					<input type="reset" id="{JSID}resetdescriptionbutton" name="reset" value="Reset" />
				</fieldset>
			</div>
			<div class="leftalign" style="width:31% !important; padding-right:2%;">
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
			</div>
			<div class="newline">
	       		<input type="submit" name="addunknownfile" value="Add this file to the database" class="mainoption" />
	       	</div>
		</form>
	</div>
</div>