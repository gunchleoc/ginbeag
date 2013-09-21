<h1 class="headerpagetitle">Blocked Referrers</h1>
<div class="contentoutline">
	<div class="contentheader">Block Referrer</div>
	<div class="contentsection">
		<form name="blockform" action="{ACTIONVARS}" method="post">
		<fieldset>
			<legend class="highlight">Add a site to be blocked</legend>	
			<label for="referrer">The site to be blocked: </label>
        	<input id="referrer" type="text" name="referrer" value="" />
        	<input type="submit" name="block" value="Block this site" class="mainoption" />
   			<div class="formexplain">Keeps a site from linking to us. Do not add the protocol, e.g. <em>http://</em></div>
      	</form>
	</div>
</div>

<!-- BEGIN switch BLOCKEDREFERRER -->
<div class="contentoutline">
	<div class="contentheader">Unblock Referrer</div>
	<div class="contentsection">{BLOCKEDREFERRER}</div>
</div>
<!-- END switch BLOCKEDREFERRER -->