<div class="contentheader">Add new link</div>
<div class="contentsection">
	<form name="addlink" action="{ACTIONVARS}" method="post">
		<fieldset>
			<legend class="highlight">Enter properties for the new link</legend>
			<label for="addlinktitle">Title:</label>
			<input id="addlinktitle" type="text" name="title" size="50" maxlength="255" value="" />
			<br /><label for="addlinklink">Link:</label>
			<input id="addlinklink" type="text" name="link" size="50" maxlength="255" value="" />
			<br /><label for="addlinkimage">Image:</label>
			<input id="addlinkimage" type="text" name="imagefilename" size="50" maxlength="255" value="" />
			<br /><label for="addlinkdescription">Description:</label><br />
			<textarea id="addlinkdescription" name="description" rows="10" cols ="50"></textarea>
		</fieldset>
		{SUBMITROW}
	</form>
</div>
