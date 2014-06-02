<h1 class="headerpagetitle">Deleting News Item on Page: {PAGETITLE}</h1>
{ITEM}
<hr>
<h2 class="pagetitle">Are you sure you want to delete this newsitem?<br>This cannot be undone!</h2>
<p>
<form action="{ACTIONVARS}" method="post">
  <input type="submit" name="confirmdeleteitem" value="Yes, please delete" />
  &nbsp;&nbsp;&nbsp;
  <input type="submit" name="nodeleteitem" value="Oops, no!" class="mainoption">
</form>
