<div class="contentoutline">
	<div class="contentheader">Edit Banner #{JSID} - {HEADER}</div>
	<div class="contentsection">
		<form name="bannerproperties" enctype="multipart/form-data" action="{EDITACTIONVARS}" method="post">
			{BANNER}
			<!-- BEGIN switch INCOMPLETE -->
			<p class="highlight">This banner is not complete and will not be displayed! Please fill out all required fields.</p>
			<!-- END switch INCOMPLETE -->
	
			<fieldset>
				<legend class="highlight">Header (optional)</legend>	
				<input id="{JSID}header" type="text" name="header" size="50" maxlength="255" value="{HEADER}" />
				<div class="formexplain">Optional title for the banner.</div>
			</fieldset>
			<span class="highlight">- For the content, specify either image, description and link, or enter the banner code manually. -</span>
			<fieldset>
				<legend class="highlight">Banner Content</legend>	
				<!-- BEGIN switch IMAGE -->
				<input type="hidden" name="oldimage" value="{IMAGE}" />
				{IMAGE}
				<br />
				<!-- END switch IMAGE -->
				<label for="{JSID}image">Image:</label>
				<input id="{JSID}image" type="file" name="image" size="40" maxlength="255" />
				<br /><label for="{JSID}description">Description:</label>
				<input id="{JSID}description" type="text" name="description" size="50" maxlength="255" value="{DESCRIPTION}" />
				<br /><label for="{JSID}link">Link:</label>
				<input id="{JSID}text" type="text" name="link" size="50" maxlength="255" value="{LINK}" />
				<div class="formexplain">Specify image, description and link. If you use this, leave the Banner Code blank.</div>
			</fieldset>
			<span class="highlight">- or -</span>
			<fieldset>
				<legend class="highlight">Banner Code</legend>	
				<label for="{JSID}code">Code (HTML):</label>
				<textarea id="{JSID}code" name="code" cols="50" rows="5">{CODE}</textarea>
				<div class="formexplain">Enter the banner code manually. If you use this, leave Image, Description and Link blank.</div>
			</fieldset>
			<input type="hidden" name="bannerid" value="{BANNERID}" />
			{SUBMITROW}
		</form>
	
		<hr>
		<form name="movebanner" action="{MOVEACTIONVARS}" method="post">
			<p>
				<input type="submit" name="movebannerup" value="move banner up" />
				<input type="hidden" name="bannerid" value="{BANNERID}" />
				&nbsp;&nbsp;&nbsp;<input type="text" name="positions" size="2" maxlength="3" value="1" />
				&nbsp;&nbsp;&nbsp;<input type="submit" name="movebannerdown" value="move banner down" />
			</p>
		</form>
		<hr>
		<form name="deletebanner" action="{DELETEACTIONVARS}" method="post">
			<p>
		  		<input type="submit" name="deletebanner" value="Delete this banner" />
		  		<input type="checkbox" name="deletebannerconfirm" value="Confirm delete" />
		  		Confirm delete
		  	</p>
		  	<input type="hidden" name="bannerid" value="{BANNERID}" />
		</form>
	</div>
</div>