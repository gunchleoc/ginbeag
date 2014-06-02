<h1 class="headerpagetitle">Technical Setup</h1>
<div class="contentheader">Global Technical Setup</div>
<div class="contentsection">
	<form name="site" action="{ACTIONVARS}" method="post">

		<fieldset>
			<legend class="highlight">Search Engines</legend>
			<label for="keywords">Google Keywords:</label>
			<input id="keywords" type="text" name="keywords" size="100" maxlength="255" value="{GOOGLEKEYWORDS}" />
			<div class="formexplain">You can specify extra keywords you want search engines to pick up for each page. Separate with commas.</div>
		</fieldset>
		<fieldset>
			<legend class="highlight">Site setup</legend>
			<label for="domainname">Domain Name:</label>
			<input id="domainname" type="text" name="domainname" size="50" maxlength="255" value="{DOMAINNAME}" />
			<div class="formexplain">The internet name of your webserver.</div>
			<br />
			<label for="localpath">Local Path:</label>
			<input id="localpath" type="text" name="localpath" size="50" maxlength="255" value="{LOCALPATH}" />
			<div class="formexplain">Leave this empty if you are installing to your site's root.</div>
			<br />
			<label for="cookieprefix">Cookie Prefix:</label>
			<input id="cookieprefix" type="text" name="cookieprefix" size="50" maxlength="255" value="{COOKIEPREFIX}" />
			<div class="formexplain">Prefix for the cookies used in webpage editors' sessions<br><span class="highlight">CAUTION: If you change this setting, everybody will have to login again.</span><br>So, save any page edits before you proceed, and make sure there are no other webpage editors online.</div>
		</fieldset>
		<fieldset>
			<legend class="highlight">Images</legend>
			<label for="imagepath">Image Upload Path:</label>
			<input id="imagepath" type="text" name="imagepath" size="50" maxlength="255" value="{IMAGEPATH}" />
			<div class="formexplain">The folder where images are stored. Relative to Local Path.</div>
			<br />
			<label for="imagesperpage">Images per page:</label>
			<input id="imagesperpage" type="text" name="imagesperpage" size="2" maxlength="2" value="{IMAGESPERPAGE}" />
			<div class="formexplain">The number of images displayed to editors on each page in the image database.</div>
			<br />
			<label for="thumbnailsize">Thumbnail Size:</label>
			<input id="thumbnailsize" type="text" name="thumbnailsize" size="2" maxlength="255" value="{THUMBNAILSIZE}" />
			<div class="formexplain">The size thumbnails should be at. Shown to webpage editors when they upload images.</div>
			<br />
			<label for="mobilethumbnailsize">Mobile Thumbnail Size:</label>
			<input id="mobilethumbnailsize" type="text" name="mobilethumbnailsize" size="2" maxlength="255" value="{MOBILETHUMBNAILSIZE}" />
			<div class="formexplain">The size for mobile thumbnails. For future implementation; ignore this for now.</div>
		</fieldset>
		<fieldset>
			<legend class="highlight">E-Mail</legend>
			<label for="email">Admin Email Address:</label>
			<input id="email" type="text" name="email" size="50" maxlength="255" value="{ADMINEMAIL}" />
			<div class="formexplain">E-Mail address for the webmaster. This address is also used by the software to send out e-mails.</div>
			<br />
			<label for="signature">Email Signature:</label>
			<br /><textarea id="signature" name="signature" rows="10" cols="53">{EMAILSIG}</textarea>
			<div class="formexplain">This text will appear on the bottom of e-mails.</div>
		</fieldset>
		<fieldset>
			<legend class="highlight">Date & Time</legend>
			<label for="datetime">Date Time Format:</label>
			<input id="datetime" type="text" name="datetime" size="25" maxlength="255" value="{DATETIMEFORMAT}" />
			<div class="formexplain">For "Last edited" on page bottom etc. (<a href="http://www.php.net/manual/en/datetime.formats.php">PHP function reference)</a></div>
			<br />
			<label for="date">Date Format:</label>
			<input id="date" type="text" name="date" size="25" maxlength="255" value="{DATEFORMAT}" />
			<div class="formexplain">Formatting dates of articles & newsitems. (<a href="http://www.php.net/manual/en/datetime.formats.php">PHP function reference</a>)</div>
		</fieldset>
		{SUBMITROW}
	</form>
</div>
