<form name="setpublishableform" action="?sid={SID}&page={PAGE}&action=setpublishable" method="post">

  <tr>
    <th class="thHead" colspan="2">Will this page be published?</th>
  </tr>
  <!-- BEGIN switch PERMISSIONREFUSED -->
  <tr>
    <td class="table" align="center" colspan="2">
      <p class="gen">Copyright: This item may not be published.</p>
    </td>
  </tr>
  <!-- END switch PERMISSIONREFUSED -->
  <!-- BEGIN switch NOT_PERMISSIONREFUSED -->
  <tr>
    <td class="table" align="center" colspan="2">
      {PUBLISHABLE_YES}
      {PUBLISHABLE_NO}
    </td>
  </tr>
  <tr>
    <td align="center" colspan="2" class="table">
      <input type="submit" value="Change Setting" class="mainoption">
      &nbsp;&nbsp;<input type="reset" value="Reset" class="liteoption">
    </td>
  </tr>
  <!-- END switch NOT_PERMISSIONREFUSED -->
  <tr><td class="spacer" colspan="2"></td></tr>
</form>
