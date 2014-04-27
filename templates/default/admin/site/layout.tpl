<h1 class="headerpagetitle">Site Layout</h1>
<form name="site" action="{ACTIONVARS}" method="post">

	<div class="contentheader">Template</div>
	<div class="contentsection">
		<fieldset>
			<legend class="highlight">Default Template</legend>	
			{DEFAULT_TEMPLATE}
			<div class="formexplain">Select which general style is used with the site.</div>
		</fieldset>
	</div>
	
	<div class="contentheader">Site Header Elements</div>
	<div class="contentsection">
		<fieldset>
			<legend class="highlight">Site Name</legend>	
			<label for="sitename">Site name:</label>
			<input id="sitename" type="text" name="sitename" size="50" maxlength="255" value="{SITENAME}" />
			<div class="formexplain">The Site Name will be displayed on all pages.</div>
		</fieldset>
		<fieldset>
			<legend class="highlight">Site Description</legend>	
			<label for="sitedescription">Site description:</label>
			<input id="sitedescription" type="text" name="sitedescription" size="50" maxlength="255" value="{SITEDESCRIPTION}" />
			<div class="formexplain">Smaller text that goes underneath the Site Name.</div>
		</fieldset>
		<fieldset>
			<legend class="highlight">Left Header</legend>	
			<div class="leftalign">
				<label for="leftimage">Left header image:</label>
				<input id="leftimage" type="text" name="leftimage" size="50" maxlength="255" value="{LEFTIMAGE}" />
				<div class="formexplain">Please use your FTP program to upload the image to <i>{UPLOADPATH}</i>.</div>
			</div>
			<div style="float:right;">
				<label for="leftlink">Left header link:</label>
				<input id="leftlink" type="text" name="leftlink" size="50" maxlength="255" value="{LEFTLINK}" />
				<div class="formexplain">e.g. <i>index.php</i> for the splash page.</div>
			</div>
			<div class="newline"></div>
		</fieldset>
		<fieldset>
			<legend class="highlight">Right Header</legend>	
			<div class="leftalign">
				<label for="rightimage">Right header image:</label>
				<input id="rightimage" type="text" name="rightimage" size="50" maxlength="255" value="{RIGHTIMAGE}" />
				<div class="formexplain">Please use your FTP program to upload the image to <i>{UPLOADPATH}</i>.</div>
			</div>
			<div style="float:right;">
				<label for="rightlink">Right header link:</label>
				<input id="rightlink" type="text" name="rightlink" size="50" maxlength="255" value="{RIGHTLINK}" />
				<div class="formexplain">e.g. <i>index.php</i> for the splash page.</div>
			</div>
			<div class="newline"></div>
		</fieldset>
	</div>
	
	<div class="contentheader">Site Footer Elements</div>
	<div class="contentsection">
		<fieldset>
			<legend class="highlight">Footer Message</legend>	
			<label for="footermessage">Footer message:</label>
			<input id="footermessage" type="text" name="footermessage" size="100" maxlength="255" value="{FOOTERMESSAGE}" />
			<div class="formexplain">This Footer Message will be displayed on all pages: {FOOTERMESSAGEDISPLAY}</div>
		</fieldset>
	</div>
	
	<div class="contentheader">Items Per Page Setup</div>
	<div class="contentsection">
		<fieldset>
			<legend class="highlight">Newsitems per Page</legend>	
			<label for="newsperpage">Newsitems per page:</label>
			<input id="newsperpage" type="text" name="newsperpage" size="3" maxlength="5" value="{NEWSPERPAGE}" />
			<div class="formexplain">The number of newsitems that will be displayed on news pages. Please enter a whole, positive number</div>
		</fieldset>
		<fieldset>
			<legend class="highlight">Gallery Images per Page</legend>	
			<label for="galleryimagesperpage">Gallery images per page:</label>
			<input id="galleryimagesperpage" type="text" name="galleryimagesperpage" size="3" maxlength="5" value="{GALLERYIMAGESPERPAGE}" />
			<div class="formexplain">The number of images that will be displayed on gallery pages. Please enter a whole, positive number</div>
		</fieldset>
	</div>
	
	
	<div class="contentheader">Splash Page</div>
	<div class="contentsection">
		<fieldset>
			<legend class="highlight">Splash Page Links</legend>
			{LINKSONSPLASHPAGE}	
			<div class="formexplain">Current selection of links to main pages to be displayed on the splash page: {LINKSONSPLASHPAGEDISPLAY}</div>
	<br />
			{ALLLINKSONSPLASHPAGE_YES} {ALLLINKSONSPLASHPAGE_NO}
			<div class="formexplain">Toggles if all main pages are automatically displayed on the Splash Page, or if the above selection is used.</div>
		</fieldset>
		<fieldset>
			<legend class="highlight">Display Site Description on Splash Page</legend>
			{SHOWSITEDESCRIPTION_YES} {SHOWSITEDESCRIPTION_NO}
			<div class="formexplain">Toggles if the Site Description will be displayed on the Splash Page</div>
		</fieldset>
		<fieldset>
			<legend class="highlight">Splash Page Font</legend>	
			{SPFONT_NORMAL} {SPFONT_ITALIC} {SPFONT_BOLD}
			<div class="formexplain">Font style for the text entered below</div>
		</fieldset>
		<fieldset>
			<legend class="highlight">Splash Page content</legend>	
			<label for="sptext1">Text 1:</label>
			<br /><textarea id="sptext1" name="sptext1" rows="10" cols="50">{SPLASHTEXT1}</textarea>
			<div class="formexplain">This text will appear above the image</div>
			<br />
			<label for="spimage">Image:</label>
			<input id="spimage" type="text" name="spimage" size="50" maxlength="255" value="{SPLASHIMAGE}" />
			<div class="formexplain">Optional image to be centered below the navigator. Please use your FTP program to upload the image to <i>{UPLOADPATH}</i>.</div>
			<br />
			<label for="sptext2">Text 2:</label>
			<br /><textarea name="sptext2" rows="10" cols="50">{SPLASHTEXT2}</textarea>
			<div class="formexplain">This text will appear below the image</div>
			<br />
			<a href=".." target="_blank">View Splash Page</a>
		</fieldset>
		{SUBMITROW}
	</div>
</form>