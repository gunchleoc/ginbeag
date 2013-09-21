<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<meta http-equiv="Content-Type"	content="text/html;	charset=utf-8">
		<meta http-equiv="Content-Style-Type" content="text/css">
	  	<link rel="stylesheet" href="{STYLESHEET}" type="text/css">
	  	<link rel="stylesheet" href="{ADMINSTYLESHEET}" type="text/css">
		<title>{HEADERTITLE}</title>
	</head>
	<body>
		<div id="wrapper">
			<div class="maintitle">Webpage Building - Register an account</div>
			<div id="main">
				<div id="contentarealogin">
					<!-- BEGIN switch MESSAGE --><div class="inlinemessage">{MESSAGE}</div><!-- END switch MESSAGE -->
					<!-- BEGIN switch SHOWFORM -->
					<form name="register" action="register.php" method="post">
						<fieldset>
							<legend class="highlight">Enter account data for registering</legend>
							<label for="user">Username:</label>
							<input id="user" type="text" name="user" size="20" maxlength="25" value="{USER}" />
							<br /><label for="pass">Password:</label>
							<input id="pass" type="password" name="pass" size="20" maxlength="32" />
							<br /><label for="pass">Confirm Password:</label>
							<input id="passcomfirm" type="password" name="passconfirm" size="20" maxlength="32" />
							<br /><label for="email">E-mail address:</label>
							<input id="email" type="text" name="email" size="20" maxlength="255" value="{EMAIL}" />
						</fieldset>
						<input type="submit" value="Register" class="mainoption" />
	              		&nbsp;&nbsp;<input type="reset" value="Reset" />
					</form>
					<!-- END switch SHOWFORM -->
				</div>
			</div>
		</div>
	</body>
</html>