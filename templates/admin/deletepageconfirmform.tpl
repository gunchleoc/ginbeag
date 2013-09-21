{HEADER}
<!-- BEGIN switch SUBPAGES -->
<div class="highlight">Subpages that will be included in delete:</div>
{SUBPAGES}
<!-- END switch SUBPAGES -->
<p class="highlight">{DELETEMESSAGE}<br>This cannot be undone!</p>
<p>
<form action="?sid={SID}&page={PAGE}" method="post">
  <input type="submit" name="executedelete" value="Yes, please delete" class="liteoption" />
  &nbsp;&nbsp;&nbsp;
  <input type="submit" name="nodelete" value="Oops, no!" class="mainoption" />
</form>
{FOOTER}
