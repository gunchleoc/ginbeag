<form name="displayorderform" action="?sid={SID}&page={PAGE}&action=editcontents" method="post">
  <select name="displayorder" size="1">
    <option value="1"<!-- BEGIN switch NEWESTFIRST --> selected<!-- END switch NEWESTFIRST -->>Newest First</option>
    <option value="0"<!-- BEGIN switch OLDESTFIRST --> selected<!-- END switch OLDESTFIRST -->>Oldest First</option>
  </select>
  <input type="submit" name="setdisplayorder" value="Set Display Order" class="mainoption" />
</form>
