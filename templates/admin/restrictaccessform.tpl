<form name="accessform" action="?sid={SID}&page={PAGE}&action=restrictaccess" method="post">
  <tr>
    <th class="thHead" colspan="2">Access Restriction</th>
  </tr>
  <tr>
    <td class="gen">Restrict access:</td>
    <td class="gen">
      {RESTRICT_YES}
      {RESTRICT_NO}
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center" class="table">
      <p>
        <input type="submit" value="Change Access Restriction" class="mainoption" />
        &nbsp;&nbsp;<input type="reset" value="Reset" class="liteoption" />
      </p>
    </td>
  </tr>
  <tr><td class="spacer" colspan="2"></td></tr>
</form>
<!-- BEGIN switch ACCESSRESTRICTED -->
<form name="accessusersform" action="?sid={SID}&page={PAGE}&action=restrictaccessusers" method="post">
  <tr><td class="spacer" colspan="2"></td></tr>
  <tr>
    <th class="thHead" colspan="2">Select Users</th>
  </tr>
  <tr>
    <td class="table" align="center" colspan="2">
      <p class="gen">{RESTRICTEDUSERLIST}</p>
        {SELECTUSERS}
        <br />&nbsp;
    </td>
  </tr>
  <tr>
    <td align="center" colspan="2" class="table">
      <p>
        <input type="submit" name="addpublicusers" value="Add Users" class="mainoption" />
        &nbsp;&nbsp;&nbsp;<input type="submit" name="removepublicusers" value="Remove" class="mainoption" />
      </p>
    </td>
  </tr>
  <tr><td class="spacer" colspan="2"></td></tr>
</form>
<!-- END switch ACCESSRESTRICTED -->
