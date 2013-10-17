<p class="highlight">Are you sure you want to delete this entry?</p>

{ENTRY}

<form name="deleteconfirmform" action="{DELETEACTIONVARS}" method="post">
	{HIDDENVARS}
	<input type="submit" name="deleteconfirm" value="Yes, please delete this entry!" class="mainoption" />
	<input type="submit" name="deleteabort" value="Oops, no!" />
</form>