<form name="login" action="login.php{PARAMS}" method="post">
  <table>
    <tr>
      <td class="bodyline">
        <table>
          <tr>
            <th class="thHead" colspan="2">Login</th>
          </tr>
          <tr>
            <td class="gen">Username:</td>
            <td>
              <input type="text" name="user" size="20" maxlength="25" value="{USERNAME}" />
            </td>
          </tr>
          <tr>
            <td class="gen">Password:</td>
            <td>
              <input type="password" name="pass" size="20" />
            </td>
          </tr>
          <tr><td colspan="2" class="spacer"></td></tr>
          <tr>
            <td colspan="2" align="center">
              <input type="submit" value="Login" class="mainoption">
              &nbsp;&nbsp;
              <input type="button" name="cancel" value="Cancel" onClick="self.location.href='admin.php'" class="liteoption">
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</form>
<p class="gen"><a href="?forgetful=on&user={USERNAME}">I forgot my password</a></p>
