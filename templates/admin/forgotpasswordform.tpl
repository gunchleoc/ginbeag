<form name="forgotpasswordform" action="login.php" method="post">
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
          <tr>
            <td class="gen">E-mail:</td>
            <td class="table">
              <input type="text" name="email" size="20" maxlength="255" />
            </td>
          </tr>
          <tr><td colspan="2" class="spacer"></td></tr>
          <tr>
            <td colspan="2" align="center">
              <input type="submit" name="requestpassword" value="Send password" class="mainoption" />
              &nbsp;&nbsp;
              <input type="submit"  value="Cancel" class="liteoption" />
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</form>

<p class="gen"><a href="?superforgetful=on&user={USERNAME}">Help! I forgot my e-mail as well!</a></p>
