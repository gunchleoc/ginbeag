<h1 class="headerpagetitle">My Profile: {USERNAME}</h1>
<div class="contentheader">Password and e-mail address</div>
<div class="contentsection">
	<form name="profile" action="{ACTIONVARS}" method="post">
		<fieldset>
			<legend class="highlight">Password</legend>
			<label for="oldpass">Old Password:</label>
			<input id="oldpass" type="password" name="oldpass" size="20" maxlength="32" />
			<div class="formexplain">If you wish to change your password, please type in your old password first.</div>
			<br /><label for="pass">New Password:</label>
			<input id="pass" type="password" name="pass" size="20" maxlength="32" />
			<div class="formexplain">Type in the new password.</div>
			<br /><label for="passconfirm">Confirm New Password:</label>
			<input id="passconfirm" type="password" name="passconfirm" maxlength="32" size="20" />
			<div class="formexplain">Type in the new password again.</div>
		</fieldset>
		<fieldset>
			<legend class="highlight">E-mail</legend>
			<label for="email">E-mail address:</label>
			<input id="email" type="text" name="email" size="40" maxlength="255" value="{EMAIL}" />
			<div class="formexplain">You can change your e-mail address here.</div>
		</fieldset>
		<input type="submit" value="Change Account Settings" class="mainoption" />
	</form>
</div>
<div class="contentheader">Contact page options</div>
<div class="contentsection">
	<form name="contactsettingsform" action="{ACTIONVARS}" method="post">

		<fieldset>
			<legend class="highlight">Contact page options</legend>
			<div class="leftalign">
				<label for="email">Display me on the contact page:</label>
				{IS_CONTACT}
				<div class="formexplain">If the box is ticked, you will appear on the contact page.</div>
			</div>
			<div class="rightalign">
				<label for="contactfunction">Responsible for:</label>
				<input id="contactfunction" type="text" name="contactfunction" size="30" maxlength="50" value="{CONTACTFUNCTION}" />
				<div class="formexplain">The areas people should select you for when they contact the site.</div>
			</div>
			<div class="newline"></div>
		</fieldset>
		<input type="submit" name="contact" value="Submit" class="mainoption" />
	</form>
</div>
<p><a href="{RETURNVARS}" target="_self">Return to page editing</a></p>
