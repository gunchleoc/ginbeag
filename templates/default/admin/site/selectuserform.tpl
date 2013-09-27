<h1 class="headerpagetitle">User Management</h1>
<form name="profile" action="{SELECTACTIONVARS}" method="post">
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

<form name="createpublicform" action="{CREATEACTIONVARS}" method="post">
	<div class="contentheader">Create Public User</div>
	<div class="contentsection">
		<input type="submit" name="createuser" value="Create User for Restricted Areas" class="mainoption">
		<div class="formexplain">You can only create public users here. Webpage Editors need to <a href="register.php">register themselves</a>. Once they have done so, you will need to activate their accounts.</div>
	</div>
</form>