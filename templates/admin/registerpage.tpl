{HEADER}
<!-- BEGIN switch SHOWFORM -->
<form name="register" action="register.php" method="post">
  <table>
    <tr>
      <td class="bodyline">
        <table>
          <tr>
            <th class="thHead" colspan="2">Register</th>
          </tr>
          <tr>
            <td class="gen">Username:</td>
            <td class="table">
              <input type="text" name="user" size="20" maxlength="25" value="{USER}" />
            </td>
          </tr>
          <tr>
            <td class="gen">Password:</td>
            <td class="table">
              <input type="password" name="pass" size="20" maxlength="32" />
            </td>
          </tr>
          <tr>
            <td class="gen">Confirm Password:</td>
            <td class="table">
              <input type="password" name="passconfirm" size="20" maxlength="32" />
            </td>
          </tr>
          <tr>
            <td class="gen">E-mail address:</td>
            <td class="table">
              <input type="text" name="email" size="20" maxlength="255" value="{EMAIL}" />
            </td>
          </tr>
          <tr>
            <td class="table" colspan="2" align="center">
              <input type="submit" value="Register" class="mainoption" />
              &nbsp;&nbsp;<input type="reset" value="Reset" class="liteoption" />
            </td>
          </tr>
          <tr><td class="spacer" colspan="2"></td></tr>
        </table>
      </td>
    </tr>
  </table>
</form>
<!-- END switch SHOWFORM -->
{FOOTER}
