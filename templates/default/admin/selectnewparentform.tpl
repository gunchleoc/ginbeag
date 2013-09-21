<h1 class="headerpagetitle">Select destination for moving this page: {PAGETITLE}</h1>
<form name="selectnewparentform" action="{ACTIONVARS}" method="post">
  {TARGETFORM}
  <p>
    <input type="submit" name="newparent" value="Select Destination" class="mainoption" />
    &nbsp;&nbsp;&nbsp;
    <input type="button" name="location" value="Cancel" onClick="self.location.href='{CANCELLOCATION}'" />
  </p>
</form>
<div class="highlight">All subpages will be moved with this page.</div>