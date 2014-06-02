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
			{SUBMITROW}
		</fieldset>
	</div>
</form>
