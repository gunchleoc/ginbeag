<h1 class="headerpagetitle">Manage public user</h1>
<div class="contentheader">Manage User for Restriced Areas: {USERNAME}</div>
<div class="contentsection">
	<form name="profile" action="{PROFILEACTIONVARS}" method="post">
		<fieldset>
			<legend class="highlight">{USERNAME}'s Password</legend>	
			<div class="leftalign">
				<label for="pass">New Password:</label>
				<input id="pass" type="password" name="pass" size="20" maxlength="32" />
				<div class="formexplain">Type a new password if you wish to change it.</div>
			</div>
			<div style="margin-left: 50%;">
				<label for="passconfirm">Confirm New Password:</label>
				<input id="passconfirm" type="password" name="passconfirm" maxlength="32" size="20" />
				<div class="formexplain">Type the same password again.</div>
			</div>
		</fieldset>
		<input type="hidden" name="userid" value="{USERID}" />
		{SUBMITROW}
	</form>

	<form name="activatepublic" action="{ACTIVATEACTIONVARS}" method="post">
		<input type="hidden" name="userid" value="{USERID}" />
		<!-- BEGIN switch ISACTIVE -->
		<input type="submit" name="deactivate" value="Deactivate '{USERNAME}'" class="mainoption">
		<!-- END switch ISACTIVE -->
		<!-- BEGIN switch NOTACTIVE -->
		<input type="submit" name="activate" value="Activate '{USERNAME}'" class="mainoption">
		<!-- END switch NOTACTIVE -->
	</form>
</div>
<div class="submitrow">
	<fieldset>
		<input type="submit" name="done" value="Edit This User's Permissions" onClick="self.location.href='{PERMISSIONSLINK}'" />
		<input type="submit" name="done" value="User Management" onClick="self.location.href='{RETURNLINK}'" />
		<input type="submit" name="done" value="List Users" onClick="self.location.href='{USERLISTLINK}'" />
	</fieldset>
</div>