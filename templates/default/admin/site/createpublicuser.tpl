<h1 class="headerpagetitle">Create public user</h1>
{NEWUSERLINKS}
<form name="createpublicuser" action="{ACTIONVARS}" method="post">
	<div class="contentheader">Create a user for access to restricted pages</div>
		<div class="contentsection">
			<fieldset>
				<legend class="highlight">Username</legend>
				<label for="username">Username:</label>
				<input id="username" type="text" name="username" size="20" maxlength="25" value="" />
				<div class="formexplain">This is the shared username for login.</div>
			</fieldset>
			<fieldset>
				<legend class="highlight">Password</legend>
			<div class="leftalign">
				<label for="pass">Password:</label>
				<input id="pass" type="password" name="pass" size="20" maxlength="32" />
				<div class="formexplain">Type a password used for logging in.</div>
			</div>
			<div style="margin-left: 50%;">
				<label for="passconfirm">Confirm Password:</label>
				<input id="passconfirm" type="password" name="passconfirm" maxlength="32" size="20" />
				<div class="formexplain">Type the same password again.</div>
			</div>


			</fieldset>
		{SUBMITROW}

		</div>
	</div>


</form>
