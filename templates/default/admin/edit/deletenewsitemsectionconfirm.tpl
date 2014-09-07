<h1 class="headerpagetitle">Deleting Section of News Item: {NEWSITEMTITLE}</h1>
<p class="highlight">Are you sure you want to delete this section?<br>This cannot be undone!</p>
<p>You can review the section below.</p>
<form action="{ACTIONVARS}" method="post">
  <input type="submit" name="confirmdeletesection" value="Yes, please delete" />
  &nbsp;&nbsp;&nbsp;
  <input type="submit" name="nodeletesection" value="Oops, no!" class="mainoption" />
</form>
<hr>
{SECTION}
