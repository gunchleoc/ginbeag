<h1 class="headerpagetitle">Deleting page: {PAGETITLE}</h1>

<!-- BEGIN switch SUBPAGES -->
<div class="highlight">Subpages that will be included in delete:</div>
<div style="font-size:150%">
{SUBPAGES}
</div>
<!-- END switch SUBPAGES -->
<p class="highlight">{DELETEMESSAGE}<br>This cannot be undone!</p>
<p>
<form action="{ACTIONVARS}" method="post">
	<input type="submit" name="executedelete" value="Yes, please delete" />
	&nbsp;&nbsp;&nbsp;
	<input type="submit" name="nodelete" value="Oops, no!" class="mainoption" />
</form>