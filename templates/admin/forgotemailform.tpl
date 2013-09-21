<form name="forgotemailform" action="login.php" method="post">
  <table>
    <tr>
      <td class="bodyline">
        <table>
          <tr>
            <th class="thHead" colspan="2">Request a new password</th>
          </tr>
          <tr>
            <td class="gen">Username:</td>
            <td class="table">
              <input type="text" name="user" size="20" maxlength="25" value="{USERNAME}" />
            </td>
          </tr>
          <tr><td colspan="2" class="spacer"></td></tr>
          <tr>
            <td colspan="2" align="center">
              <input type="submit" name="requestemail" value="Request password" class="mainoption" />
              &nbsp;&nbsp;
              <input type="submit"  value="Cancel" class="liteoption" />
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</form>
