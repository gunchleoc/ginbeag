<form name="newpageform" action="pagenew.php?sid={SID}&page={PAGE}" method="post">
  <table>
    <tr>
      <td class="bodyline">
        <table>
          <tr>
            <th class="thHead" colspan="2">Create new page</th>
          </tr>
          <tr>
            <td class="gen">
              Will this page be published?
            </td>
            <td class="table">
              {IS_PUBLISHABLE_YES}
              {IS_PUBLISHABLE_NO}
            </td>
          </tr>
          <tr>
            <td class="gen">Create main page instead</td>
            <td class="gen"><input type="checkbox" name="root" {ROOTCHECKED} /></td>
          </tr>
          <tr>
            <td class="gen">Page title (short):</td>
            <td class="table"><input type="text" name="navtitle" size="60" maxlength="60" value="{NAVTITLE}" /></td>
          </tr>
          <tr>
            <td class="gen">Page title:</td>
            <td class="table"><input type="text" name="title" size="60" maxlength="200" value="{PAGETITLE}" /></td>
          </tr>
          <tr>
            <td class="gen">Page type:</td>
            <td class="table">
              {TYPESELECTION}
            </td>
          </tr>
          <tr><td class="spacer" colspan="2"></td></tr>
          <tr>
            <td class="table" align="center" colspan="2">
              <input type="submit" name="create" value="Create" class="mainoption">
              &nbsp;&nbsp;<input type="reset" value="Cancel" class="liteoption">
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</form>
