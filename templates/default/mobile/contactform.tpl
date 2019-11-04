<form name="email" action="" method="post">
<fieldset>
	<legend class="highlight">{L_LEGEND_YOUREMAILTOUS}</legend>
	<fieldset>
		<legend>{L_LEGEND_YOUREMAIL}</legend>
		{CONTACTS}
		<p>
			<label for="emailaddress">{L_EMAILADRESS}</label>
			<br />
			<input id="emailaddress" type="text" name="{EMAILVARIABLE}" value="{ADDRESS}" />
		</p>
		<p>
			<label for="subject">{L_EMAILSUBJECT}
			<br />
			<input id="subject" type="text" name="{SUBJECTVARIABLE}" value="{SUBJECT}" />
		</p>
		<p>
			<label for="message">{L_EMAILMESSAGE}</label>
			<br />
			<textarea id="message" name="{MESSAGEVARIABLE}" rows="20">{MESSAGE}</textarea>
		</p>
	</fieldset>
	<!-- BEGIN switch CAPTCHA -->
	<fieldset>
			<legend>{L_LEGEND_CAPTCHA}</legend>
		{CAPTCHA}
	</fieldset>
	<!-- END switch CAPTCHA -->
	<input type="submit" value="{L_SENDEMAIL}"  class="mainoption" />
	<input type="hidden" name ="token" value="{TOKEN}"  class="mainoption" />
</fieldset>
</form>
