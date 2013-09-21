<tr>
  <td>
    <table border="0" cellpadding="5" cellspacing="1" width="100%" class="bodyline">
      <tr>
        <th class="thHead" colspan="2">{FILENAME}</th>
      </tr>
      <tr>
        <td class="table" valign="top">{IMAGE}</td>
        <td class="table" colspan"2">
          <form name="deleteform" action="{ACTIONVARSDELETEFILE}" method="post">
            {HIDDENVARS}
            <input type="submit" name="deletefile" value="Delete this file" class="mainoption" />
            <br /><input type="checkbox" name="deletefileconfirm" /><span class="gen">Confirm delete</span>
          </form>
        </td>
      </tr>
      <tr>
        <td class="table" colspan="2">
          <form name="addunknownform" action="{ACTIONVARSADDUNKNOWNFILE}" method="post">
            {HIDDENVARS}
            <table border="0" cellpadding="4" cellspacing="1" width="100%" class="bodyline">
              <tr>
                <td class="gen" valign="top">
                  Caption:
                  <br><span class="gensmall">optional</span>
                </td>
                <td class="table" valign="top">
                  <input type="text" name="caption" value="" size="30" maxlength="200" />
                </td>
                <td rowspan="7" class="table" valign="top">
                  <span class="gen">Select categories:<br />&nbsp;<br /></span>
                  {CATEGORYSELECTION}
                </td>
              </tr>
              <tr>
                <td class="gen" valign="top">
                  Source:
                  <br><span class="gensmall">optional</span>
                </td>
                <td class="gen" valign="top">
                  <input type="text" name="source" value="" size="30" maxlength="255" />
                </td>
              </tr>
              <tr>
                <td class="gen" valign="top">
                  Sourcelink:
                  <br><span class="gensmall">optional</span>
                </td>
                <td class="gen" valign="top">
                  <input type="text" name="sourcelink" value="" size="30" maxlength="255" />
                </td>
              </tr>
              <tr>
                <td class="gen" valign="top">
                  Copyright Holder:
                  <br><span class="gensmall">optional</span>
                </td>
                <td class="gen" valign="top">
                  <input type="text" name="copyright" value="" size="30" maxlength="255" />
                </td>
              </tr>
              <tr>
                <td class="gen">Permissions:</td>
                <td class="gen">
                  <p>
                    <input type="radio" name="permission" value="{PERMISSION_GRANTED}" class="gen" />
                    Permission granted
                    <input type="radio" name="permission" value="{NO_PERMISSION}" class="gen" checked />
                    No Permission
                    <input type="radio" name="permission" value="{PERMISSION_REFUSED}" class="gen" />
                    Permission Refused
                  </p>
                </td>
              </tr>
              <tr><td class="spacer"></td></tr>
              <tr>
                <td colspan="3" align="left" class="gen">
                  <input type="submit" name="addunknownfile" value="Add this file to the database" class="mainoption" />
                </td>
              </tr>
            </table>
          </form>
        </td>
      </tr>
    </table>
  </td>
</tr>
<tr><td class="bodyline" colspan="2"></td></tr>
