<h1 class="headerpagetitle">Anti-Spam for Contact Form and Guestbook</h1>
<form name="site" action="{ACTIONVARS}" method="post">
	{HIDDENVARS}
	<div class="contentheader">Random Variable Names</div>
	<div class="contentsection">
		<fieldset>
			<legend class="highlight">Generate new variable names</legend>
			<label for="renamevariables">Rename Variables:</label>
			<input id="renamevariables" type="submit" name="renamevariables" value="Rename Variables" />
			<div class="formexplain">Creates new random variable names to confuse spambots.</div>
		</fieldset>
	</div>

	<div class="contentheader">CAPTCHA</div>
	<div class="contentsection">
		<fieldset>
			<legend class="highlight">Math CAPTCHA</legend>
			<label for="usemathcaptcha">Use Math CAPTCHA:</label>
			{USEMATHCAPTCHA_YES} {USEMATHCAPTCHA_NO}
			<div class="formexplain">Spam protection - people have to solve a simple addition problem on random before sending an e-mail or making an entry in the Guestbook.</div>
			{MATHCAPTCHA_SUBMITROW}
		</fieldset>
	</div>
	<div class="contentheader">Spam Words</div>
	<div class="contentsection">
		<fieldset>
			<legend class="highlight">Spam Words</legend>
			<label for="spam_wordssubject">Subject Line</label>
			<br /><textarea id="spamwords_subject" name="spamwords_subject" rows="6" cols="40">{SPAMWORDS_SUBJECT}</textarea>
			<div class="formexplain">Contact form and guestbook messages containing this text in the subject line are considered spam. Enter 1 item per line or leave blank.</div>
			<br /><label for="spamwords_content">Message Content</label>
			<br /><textarea id="spamwords_content" name="spamwords_content" rows="6" cols="40">{SPAMWORDS_CONTENT}</textarea>
			<div class="formexplain">Contact form and guestbook messages containing this text in the message content are considered spam. Enter 1 item per line or leave blank.</div>
			{SPAMWORDS_SUBMITROW}
		</fieldset>
	</div>
</form>
