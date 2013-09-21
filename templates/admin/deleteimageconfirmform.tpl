<h1 class="pagetitle">Deleting image {FILENAME}</h1>
<p class="highlight">Are you sure you want to delete this image?<br>This cannot be undone!</p>
{IMAGE}
<p>
<form name="deleteimageform" action="{ACTIONVARS}" method="post">
  <input type="hidden" name="filename" value="{FILENAME}" />
  <input type="submit" name="delete" value="Yes, please delete" class="liteoption">
  &nbsp;&nbsp;&nbsp;
  <input type="submit" name="nodelete" value="Oops, no!" class="mainoption">
</form>
