				<h1 class="headerpagetitle">Login</h1>
					<form name="login" action="login.php{PARAMS}" method="post">
						<fieldset>
							<legend class="highlight">Enter your login data</legend>
							<label for="user">Username:</label>
							<input id="user" type="text" name="user" size="20" maxlength="25" value="{USERNAME}" />
							<br /><label for="pass">Password:</label>
							<input id="pass" type="password" name="pass" size="20" />
						</fieldset>
						<input type="submit" value="Login" class="mainoption">
						&nbsp;&nbsp;
						<input type="button" name="cancel" value="Cancel" onClick="self.location.href='admin.php'" >
					</form>
				<p><a href="{FORGETFULLINK}">I forgot my password</a></p>
			</div>
		</div>
	</div>
</div>
