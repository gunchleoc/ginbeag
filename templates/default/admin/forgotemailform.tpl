				<h1 class="headerpagetitle">Request a new password</h1>
				<form name="forgotemailform" method="post">
					<fieldset>
						<legend class="highlight">Enter your login data</legend>
						<label for="user">Username:</label>
						<input id="user" type="text" name="user" size="20" maxlength="25" value="{USERNAME}" />
					</fieldset>
					<input type="submit" name="requestemail" value="Request password" class="mainoption" />
					&nbsp;&nbsp;
					<input type="button"  value="Cancel" onClick="self.location.href='admin.php'" />
				</form>
			</div>
		</div>
	</div>
</div>