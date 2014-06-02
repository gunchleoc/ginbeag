<h1 class="headerpagetitle">IP Ban</h1>
<div class="contentheader">Users accessing the site from these IP adresses are blocked from accessing any page with Restricted Access</div>
<div class="contentsection">

	<form name="banipallrestricted" action="{ACTIONVARS}" method="post">
		<fieldset>
			<legend class="highlight">Ban IP address from all pages with Restricted Access</legend>
			<label for="ip">IP address:</label>
			<input id="ip" type="text" name="ip" value="" width="15" maxlength="15" class="post" />
			<input type="submit" name="banipallrestricted" value="Ban this IP address" class="mainoption" />
			<div class="formexplain">Enter the IP address as 4 numbers between 0 and 255, separated by full stops. The pattern looks like this: <em>111.111.111.111 </em></div>
		</fieldset>

	</form>

	<!-- BEGIN switch IPS -->
	<table cellpadding="5">
			<caption>Banned IP adresses</caption>

		<tr>
			<th>IP Address</th>
			<th>Action</th>
		</tr>
		{IPS}
	</table>
	<!-- END switch IPS -->
	<!-- BEGIN switch NOIPS -->
	<p class="pagetitle">No IPs have been banned.</p>
	<!-- END switch NOIPS -->
</div>
