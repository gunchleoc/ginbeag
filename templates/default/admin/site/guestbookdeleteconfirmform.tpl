<p class="highlight">Are you sure you want to delete this entry?<br>This cannot be undone!</p>
<p>You can review the entry below.</p>
<form name="deleteconfirmform" action="{DELETEACTIONVARS}" method="post">
	{HIDDENVARS}
	<input type="submit" name="deleteconfirm" value="Yes, please delete this entry!" />
	<input type="submit" name="deleteabort" value="Oops, no!" class="mainoption" />
</form>
{ENTRY}
