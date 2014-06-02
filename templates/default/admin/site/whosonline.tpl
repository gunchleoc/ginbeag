<h1 class="headerpagetitle">Online public users</h1>
<div class="contentheader">Public users for pages with Restricted Access that are currently online</div>
<div class="contentsection">
	<!-- BEGIN switch ONLINEUSERS -->
	<table>
		<caption>Public Users Online</caption>
		<tr>
			<th>Username</th>
			<th>Last Click</th>
			<th>IP/Host</th>
			<th>Login Successful?</th>
			<th>Retries</th>
		</tr>
		{ONLINEUSERS}
	</table>
	<!-- END switch ONLINEUSERS -->
	<!-- BEGIN switch NOONLINEUSERS -->
	<span class="highlight">{NOONLINEUSERS}</span>
	<!-- END switch NOONLINEUSERS -->
</div>
