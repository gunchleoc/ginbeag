<hr>
<p class="pagetitle">Add new link</p>

<form name="addlink" action="?sid={SID}&page={PAGE}&action=editcontents" method="post">
<table><tr><td class="bodyline">
<table><tr>
<th class="thHead" colspan="2">
Link Properties
</th>
</tr>
<tr>
  <td class="gen" valign="top">Title:
  </td>
  <td class="table" valign="top">
    <input type="text" name="title" size="50" maxlength="255" value="" />
  </td>
</tr>
<tr>
  <td class="gen" valign="top">Link:
  </td>
  <td class="table" valign="top">
    <input type="text" name="link" size="50" maxlength="255" value="" />
  </td>
</tr>
<tr>
  <td class="gen" valign="top">Image:
  </td>
  <td class="table" valign="top">
    <input type="text" name="imagefilename" size="50" maxlength="255" value="" />
  </td>
</tr>
<tr>
  <td class="gen" valign="top">Description:
  </td>
  <td class="table" valign="top">
    <textarea name="description" rows="10" cols ="50"></textarea>
  </td>
</tr>
<tr>
  <td class="gen" valign="top">&nbsp;
  </td>
  <td class="table" valign="top">
<p><input type="submit" name="addlink" value="Submit" class="mainoption">
&nbsp;&nbsp;<input type="reset" value="Reset" class="liteoption">
  </td>
</tr>
</table>

</td></tr>
</table>

</form>
