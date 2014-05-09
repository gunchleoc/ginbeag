				<h1 class="headerpagetitle">Request a new password</h1>
					<form name="forgotpasswordform" method="post">
						<fieldset>
							<legend class="highlight">Enter your login data</legend>
							<label for="user">Username:</label>
							<input id="user" type="text" name="user" size="20" maxlength="25" value="{USERNAME}" />
							<br /><label for="email">E-mail:</label>
							<input id="email" type="text" name="email" size="20" maxlength="255" />
						</fieldset>
						<input type="submit" name="requestpassword" value="Send password" class="mainoption" />
						&nbsp;&nbsp;
						<input type="button"  value="Cancel" onClick="self.location.href='admin.php'" />
					</form>
				<p><a href="{FORGETFULLINK}">Help! I forgot my e-mail as well!</a></p>
			</div>
		</div>
	</div>
</div>
