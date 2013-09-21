<form name="email" action="" method="post">
<fieldset>
	<legend class="highlight">{L_LEGEND_YOUREMAILTOUS}</legend>
	<fieldset>
		<legend>{L_LEGEND_YOUREMAIL}</legend>
		{CONTACTS}
		<p>
			<label for="emailaddress">{L_EMAILADRESS}</label>
			<br />
			<input id="emailaddress" type="text" name="{EMAILVARIABLE}" size="70" value="{ADDRESS}" />
		</p>
		<p>
			<label for="subject">{L_EMAILSUBJECT}
			<br />
			<input id="subject" type="text" name="{SUBJECTVARIABLE}" size="70" value="{SUBJECT}" />
		</p>
		<p>
			<label for="message">{L_EMAILMESSAGE}</label>
			<br />
			<textarea id="message" name="{MESSAGEVARIABLE}" cols="52" rows="20">{MESSAGE}</textarea>
		</p>
	</fieldset>
	<fieldset>
		<legend>{L_LEGEND_OPTIONS}</legend>
		<input id="sendcopy" type="checkbox" name="sendcopy" {SENDCOPY_CHECKED} />
		<label for="sendcopy">{L_EMAILSENDCOPY}</label>
	</fieldset>
	<!-- BEGIN switch CAPTCHA -->
	<fieldset>
			<legend>{L_LEGEND_CAPTCHA}</legend>
		{CAPTCHA}
	</fieldset>
	<!-- END switch CAPTCHA -->
	<input type="submit" value="{L_SENDEMAIL}"  class="mainoption" />
</fieldset>
</form>
