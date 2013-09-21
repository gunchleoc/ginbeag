<div class="contentheadersmall">{REFERRER}</div>
<form name="referrerunblockform" action="{ACTIONVARS}" method="post">
	<fieldset>
		<legend class="highlight">Blocked site</legend>	
    	{REFERRER}
		<input type="hidden" name="referrer" value="{REFERRER}" />
		<input type="submit" name="unblock" value="Allow this site" />
	</fieldset>
</form>
