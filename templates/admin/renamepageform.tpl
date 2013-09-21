<form name="renamepageform" action="?sid={SID}&page={PAGE}&action=rename" method="post" enctype="charset=UTF-8">
  <tr>
    <th class="thHead" colspan="2">Rename page</th>
  </tr>
  <tr>
    <td class="gen">Navigator page title (short):</td>
    <td class="table">
      <input type="text" name="navtitle" size="50" maxlength="60" value="{NAVTITLE}" class="gen" />
    </td>
  </tr>
  <tr>
    <td class="gen">Page title:</td>
    <td class="table">
      <input type="text" name="title" size="70" maxlength="200" value="{PAGETITLE}" class="gen" />
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center" class="table">
      <input type="submit" value="Rename" class="mainoption">
      &nbsp;&nbsp;<input type="reset" value="Reset" class="liteoption">
    </td>
  </tr>
  <tr><td class="spacer" colspan="2"></td></tr>

</form>
