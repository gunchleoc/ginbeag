<h1 class="headerpagetitle">User Permissions</h1>
<div class="contentheader">Set the restricted pages that <em>{USERNAME}</em> may access</div>
<div class="contentsection">
			<!-- BEGIN switch PAGESWITHACCESS -->
			<table>
				<caption>The pages <em>{USERNAME}</em> can access</caption>
				<tr>
					<th>Page</th>
					<th>Action</th>
				</tr>
				{PAGESWITHACCESS}
			</table>
			<!-- END switch PAGESWITHACCESS -->
			<!-- BEGIN switch PAGESNOACCESS -->
			<table>
				<caption>The pages <em>{USERNAME}</em> is locked out of</caption>
				<tr>
					<th>Page</th>
					<th>Action</th>
				</tr>
				{PAGESNOACCESS}
			</table>
			<!-- END switch PAGESNOACCESS -->
</div>
<div class="submitrow">
	<fieldset>
		<input type="submit" name="done" value="Manage This User" onClick="self.location.href='{MANAGELINK}'" />
		<input type="submit" name="done" value="User Permissions" onClick="self.location.href='{RETURNLINK}'" />
		<input type="submit" name="done" value="List Users" onClick="self.location.href='{USERLISTLINK}'" />
	</fieldset>
</div>