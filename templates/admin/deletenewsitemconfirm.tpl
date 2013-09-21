{HEADER}
{ITEM}
<hr>
<h2 class="pagetitle">Are you sure you want to delete this newsitem?<br>This cannot be undone!</h2>
<p>
<form action="?sid={SID}&page={PAGE}&newsitem={NEWSITEM}&offset={OFFSET}&action=editcontents" method="post">
  <input type="submit" name="confirmdeleteitem" value="Yes, please delete" class="liteoption">
  &nbsp;&nbsp;&nbsp;
  <input type="submit" name="nodeleteitem" value="Oops, no!" class="mainoption">
</form>
{FOOTER}
