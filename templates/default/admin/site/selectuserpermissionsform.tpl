<h1 class="headerpagetitle">User Permissions</h1>
<form name="profile" action="{ACTIONVARS}" method="post">
	<div class="contentheader">Search for User</div>
	<div class="contentsection">
		<fieldset>
			<legend class="highlight">Search for Webpage Editor or Public User</legend>
			<label for="username">Username:</label>
			<input id="username" type="text" name="username" size="20" maxlength="25" value="{USERNAME}" />
			<div class="formexplain">Please enter the full username.</div>
			<p><input type="submit" name="searchuser" value="Webpage Editor" class="mainoption">
			&nbsp;&nbsp;&nbsp;<input type="submit" name="searchpublicuser" value="User for Restriced Areas" class="mainoption">

		</fieldset>
		<input type="button" name="userlist" value="Select user from list" onClick="self.location.href='{USERLISTLINK}'" class="mainoption">
	</div>
</form>
