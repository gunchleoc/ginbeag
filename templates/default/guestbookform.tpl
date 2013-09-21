<form name="guestbook" action="" method="post">
<fieldset>
	<legend class="highlight">{L_LEGEND_YOURMESSAGETOUS}</legend>
	<fieldset>
		<legend>{L_LEGEND_YOURMESSAGE}</legend>
		<p>
			<label for="postername">{L_NAME}</label>
			<br />
			<input id="postername" type="text" name="postername" size="70" value="{NAME}" />
		</p>
		<p>
			<label for="emailaddress">{L_EMAIL}</label>
			<br />
			<input id="emailaddress" type="text" name="{EMAILVARIABLE}" size="70" value="{EMAIL}" />
		</p>
		<p>
			<label for="subject">{L_SUBJECT}
			<br />
			<input id="subject" type="text" name="{SUBJECTVARIABLE}" size="70" value="{SUBJECT}" />
		</p>
		<p>
			<label for="message">{L_MESSAGE}</label>
			<br />
			<textarea id="message" name="{MESSAGEVARIABLE}" cols="52" rows="20">{MESSAGE}</textarea>
		</p>
	</fieldset>
	<!-- BEGIN switch CAPTCHA -->
	<fieldset>
			<legend>{L_LEGEND_CAPTCHA}</legend>
		{CAPTCHA}
	</fieldset>
	<!-- END switch CAPTCHA -->
	<input type="submit" name="submitpost" value="{L_SUBMIT}"  class="mainoption" />
      &nbsp;&nbsp;<input type="submit" name="cancel" value="{L_CANCEL}" />
</fieldset>
</form>