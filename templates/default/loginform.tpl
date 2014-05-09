<div id="contentarea">
	<h2 class="pagetitle">{PAGETITLE}</h2>
	<!-- BEGIN switch ERROR -->
	<p class="highlight">{ERROR}</p><p>{TRYAGAIN}</p>
	<br />
	<!-- END switch ERROR -->
	<form name="login" action="{PARAMS}" method="post">
	<fieldset>
		<legend class="highlight">{L_LEGEND_LOGIN}</legend>
		<fieldset>
			<legend>{L_LEGEND_LOGIN_DATA}</legend>
			<p>
				<label for="user">{L_USERNAME}</label>
				<br />
				<input id="user" type="text" name="user" size="20" maxlength="25" value="{USERNAME}" />
			</p>
			<p>
				<label for="pass">{L_PASSWORD}</label>
				<br />
				<input id="pass" type="password" name="pass" size="20" />
			</p>
		</fieldset>
		<input type="submit" value="{L_SUBMIT}" class="mainoption">
	      &nbsp;&nbsp;<input type="button" name="cancel" value="{L_CANCEL}" onClick="self.location.href='{HOMELINK}'" />
	</fieldset>
	<a href="{HOMELINK}">{L_HOME}</a>
</div>
