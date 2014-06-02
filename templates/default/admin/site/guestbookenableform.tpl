<form name="guestbookproperties" action="{ENABLEACTIONVARS}" method="post">
	<div class="contentheader">Guestbook Settings</div>
	<div class="contentsection">
		<fieldset>
			<legend class="highlight">Enable Guestbook</legend>
			{ENABLEGUESTBOOK_YES} {ENABLEGUESTBOOK_NO}
			<div class="formexplain">Adds a guestbook to the site.</div>
		</fieldset>
		<fieldset>
			<legend class="highlight">Guestbook Entries Per Page</legend>
			<label for="guestbookperpage">Guestbook Entries Per Page:</label>
			<input id="guestbookperpage" type="text" name="guestbookperpage" size="3" maxlength="5" value="{ENTRIESPERPAGE}" />
			<div class="formexplain">The number of entries per page in the guestbook. Please enter a whole, positive number.</div>
		</fieldset>
        {SUBMITROW}
	</div>
</form>
