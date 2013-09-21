<p class="pagetitle">E-mail {NAME}</p>
<p>
<form name="guestbookemail" action="?sendemail" method="post">
<input type="hidden" name="recipient" value="{RECIPIENT}" />
  <table>
    <tr>
      <td align="right">{L_EMAIL}</td>
      <td><input type="text" name="addy" size="60" value="{EMAIL}" /></td>
    </tr>
    <tr>
      <td align="right">{L_SUBJECT}</td>
      <td><input type="text" name="subject" size="60" value="{SUBJECT}" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="right" valign="top">{L_MESSAGE}</td>
      <td>
        <textarea name="messagetext" cols="60" rows="20">{MESSAGE}</textarea>
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>
        <input type="checkbox" name="sendcopy" {SENDCOPY}>
        {L_SENDCOPY}
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;<br><input type="submit" name="sendemail" value="{L_SEND}"  class="mainoption" />
      &nbsp;&nbsp;<input type="submit" name="cancel" value="{L_CANCEL}" />
      </td>
    </tr>
  </table>
</form>
<?php
}