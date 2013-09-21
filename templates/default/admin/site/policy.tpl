{JAVASCRIPT}
<h1 class="headerpagetitle">Site Policy</h1>
<form name="site" action="{ACTIONVARS}" method="post">

	<div class="contentheader">Site Policy Settings</div>
	<div class="contentsection">
		<fieldset>
			<legend class="highlight">Display Site Policy</legend>	
			<input type="radio" name="displaypolicy" value="1"<!-- BEGIN switch POLICYON --> checked<!-- END switch POLICYON -->>Yes
           	<input type="radio" name="displaypolicy" value="0"<!-- BEGIN switch POLICYOFF --> checked<!-- END switch POLICYOFF -->>No
			<div class="formexplain">Will a Site Policy be shown in all pages?</div>
		</fieldset>
		<fieldset>
			<legend class="highlight">Title for the Site Policy</legend>
			<label for="policytitle">Title:</label>	
			<input id="policytitle" type="text" name="policytitle" size="50" maxlength="255" value="{POLICYTITLE}" />
			<div class="formexplain">Used to display a link to the Site Policy, and as the title of the Site Policy page.</div>
		</fieldset>
		{SUBMITROW}
	</div>
</form>
{POLICYTEXT}