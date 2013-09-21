{HEADER}
<table>
  <tr>
    <td class="bodyline">
      <table>
        <form name="profile" action="profile.php?sid={SID}&page={PAGE}" method="post">
          <tr>
            <th class="thHead" colspan="2">Username: {USERNAME}</th>
          </tr>
          <tr>
            <td class="gen">Old Password:</td>
            <td class="table">
              <input type="password" name="oldpass" size="20" maxlength="32" />
            </td>
          </tr>
          <tr>
            <td class="gen">New Password:</td>
            <td class="table">
              <input type="password" name="pass" size="20" maxlength="32" />
            </td>
          </tr>
          <tr>
            <td class="gen">Confirm New Password:</td>
            <td class="table">
              <input type="password" name="passconfirm" maxlength="32" size="20" />
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
              <input type="submit" value="Change Profile" class="mainoption" />
            </td>
          </tr>
          <tr><td class="spacer" colspan="2"></td></tr>
        </form>
        <form name="contactsettingsform" action="profile.php?sid={SID}&page={PAGE}" method="post">
          <tr>
            <th class="thHead" colspan="2">Contact page options</th>
          </tr>
          <tr>
            <td class="gen">Display me on the contact page:</td>
            <td class="table">{IS_CONTACT}</td>
          </tr>
          <tr>
            <td class="gen">Responsible for:</td>
            <td class="table">
              <input type="text" name="contactfunction" size="20" maxlength="50" value="{CONTACTFUNCTION}" />
            </td>
          </tr>
          <tr>
            <td class="table" colspan="2" align="center">
              <input type="submit" name="contact" value="Submit" class="mainoption" />
            </td>
          </tr>
          <tr><td class="spacer" colspan="2"></td></tr>
        </form>
      </table>
    </td>
  </tr>
</table>
<p class="gen">
<a href="pagedisplay.php?sid={SID}&page={PAGE}" target="_self">Return to page editing</a>
</p>
{FOOTER}
