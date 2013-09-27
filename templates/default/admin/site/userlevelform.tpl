<h1 class="headerpagetitle">User Permissions</h1>
<div class="contentheader">Permissions for: {USERNAME}</div>
<div class="contentsection">
	<form name="profile" action="{ACTIONVARS}" method="post">
		<fieldset>
			<legend class="highlight">{USERNAME}'s Permissions</legend>
			<label for="userlevel">Set <em>{USERNAME}</em>'s access level to:</label>
			<select id="userlevel" name="userlevel" size="1">
				<option value="{USERLEVEL_USER}"<!-- BEGIN switch LEVELISUSER --> selected<!-- END switch LEVELISUSER -->>User</option>
				<option value="{USERLEVEL_ADMIN}"<!-- BEGIN switch LEVELISADMIN --> selected<!-- END switch LEVELISADMIN -->>Administrator</option>
			</select>
		</fieldset>
		<input type="hidden" name="userid" value="{USERID}" />
		{SUBMITROW}
	</form>
</div>
<div class="submitrow">
	<fieldset>
		<input type="submit" name="done" value="Manage This User" onClick="self.location.href='{MANAGELINK}'" />
		<input type="submit" name="done" value="User Permissions" onClick="self.location.href='{RETURNLINK}'" />
		<input type="submit" name="done" value="List Users" onClick="self.location.href='{USERLISTLINK}'" />
	</fieldset>
</div>