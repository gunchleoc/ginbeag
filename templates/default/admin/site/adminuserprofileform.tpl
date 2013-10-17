<h1 class="headerpagetitle">Manage user</h1>
<div class="contentheader">Account Settings for: {USERNAME}</div>
<div class="contentsection">

	<form name="profile" action="{PROFILEACTIONVARS}" method="post">
		{HIDDENVARS}
		<fieldset>
			<legend class="highlight">{USERNAME}'s E-mail Adress</legend>
			<label for="email">E-mail Address:</label>
			<input id="email" type="text" name="email" size="20" maxlength="255" value="{EMAIL}" />
			<div class="formexplain">Type a new e-mail address if you wish to change it.</div>
		</fieldset>
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
			<div class="newline">
				<br />
				
				<input type="submit" name="profile" value="Change Password / E-mail" class="mainoption" />
				<input type="reset" value="Reset Forms" />
			</div>
	</form>

	<form name="generatepasswordform" action="{PASSGENACTIONVARS}" method="post">
		{HIDDENVARS}
		<p><input type="submit" name="generate" value="Auto-generate new password" class="mainoption"></p>
	</form>

	<form name="activate" action="{ACTIVATEACTIONVARS}" method="post">
		{HIDDENVARS}
		<!-- BEGIN switch ISACTIVE -->
		<input type="submit" name="deactivate" value="Deactivate {USERNAME}'s account" class="mainoption">
		<!-- END switch ISACTIVE -->
		<!-- BEGIN switch NOTACTIVE -->
		<input type="submit" name="activate" value="Activate {USERNAME}'s account" class="mainoption">
		<!-- END switch NOTACTIVE -->
	</form>
	
</div>
<div class="contentheader">Profile for: {USERNAME}</div>
<div class="contentsection">
	<form name="contactsettingsform" action="{CONTACTACTIONVARS}" method="post">
		{HIDDENVARS}
		<fieldset>
			<legend class="highlight">Contact page options</legend>
			<div class="leftalign">
				<label for="iscontact"><em>{USERNAME}</em> can be contacted through the contact page:</label>
				<input id="iscontact" type="checkbox" name="iscontact" value="Is Contact"<!-- BEGIN switch ISCONTACT --> checked<!-- END switch ISCONTACT -->/>
			</div>
			<div style="margin-left: 50%;">
				<label for="contactfunction">People should contact <em>{USERNAME}</em> for questions about:</label>
				<input id="contactfunction" type="text" name="contactfunction" size="20" maxlength="50" value="{CONTACTFUNCTION}" />
			</div>

		</fieldset>
		<div class="newline">
			<input type="submit" name="contact" value="Submit Changes" class="mainoption" />
			<input type="reset" value="Reset" />
		</div>
	</form>
</div>
<div class="submitrow">
	<fieldset>
		<input type="submit" name="done" value="Edit This User's Permissions" onClick="self.location.href='{PERMISSIONSLINK}'" />
		<input type="submit" name="done" value="User Management" onClick="self.location.href='{RETURNLINK}'" />
		<input type="submit" name="done" value="List users" onClick="self.location.href='{USERLISTLINK}'" />
	</fieldset>
</div>