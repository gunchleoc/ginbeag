				<h1 class="headerpagetitle">Login</h1>
					<form name="login" action="{PARAMS}" method="post">
						<fieldset>
							<legend class="highlight">Enter your login data</legend>
							<label for="user">Username:</label>
							<input id="user" type="text" name="user" size="20" maxlength="25" value="{USERNAME}" />
							<br /><label for="pass">Password:</label>
							<input id="pass" type="password" name="pass" size="20" />
						</fieldset>
						{SUBMITROW}
					</form>
				<p><a href="{FORGETFULLINK}">I forgot my password</a></p>
			</div>
		</div>
	</div>
</div>
