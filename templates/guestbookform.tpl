<p>
<form name="guestbook" action="" method="post">
  <table>
    <tr>
      <td align="right"><span class="gen">{L_NAME}</span></td>
      <td><input type="text" name="postername" size="60" value="{NAME}" /></td>
      </td>
    </tr>
    <tr>
      <td align="right"><span class="gen">{L_EMAIL}</span></td>
      <td><input type="text" name="addy" size="60" value="{EMAIL}" /></td>
    </tr>
    <tr>
      <td align="right"><span class="gen">{L_SUBJECT}</span></td>
      <td><input type="text" name="subject" size="60" value="{SUBJECT}" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="right" valign="top"><span class="gen">{L_MESSAGE}</span></td>
      <td>
        <textarea name="messagetext" cols="60" rows="20">{MESSAGE}</textarea>
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;<br><input type="submit" name="submitpost" value="{L_SUBMIT}"  class="mainoption">
      &nbsp;&nbsp;<input type="submit" name="cancel" value="{L_CANCEL}"  class="liteoption">
      </td>
    </tr>
  </table>
</form>