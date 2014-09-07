<h1 class="headerpagetitle">Deleting page: {PAGETITLE}</h1>
<p class="highlight">{DELETEMESSAGE}<br>This cannot be undone!</p>
<!-- BEGIN switch SUBPAGES -->
<p>You can review the subpages that will be included in the deletion below.</p>
<!-- END switch SUBPAGES -->
<form action="{ACTIONVARS}" method="post">
	<input type="submit" name="executedelete" value="Yes, please delete" />
	&nbsp;&nbsp;&nbsp;
	<input type="submit" name="nodelete" value="Oops, no!" class="mainoption" />
</form>
<!-- BEGIN switch SUBPAGES -->
<div class="highlight">Subpages that will be deleted as well:</div>
<div style="font-size:150%">
{SUBPAGES}
</div>
<!-- END switch SUBPAGES -->
