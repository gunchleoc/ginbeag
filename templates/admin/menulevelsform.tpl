<form name="editmenu" action="?sid={SID}&page={PAGE}&action=editcontents" method="post">
<table>
  <tr>
    <td class="bodyline">
      <table cellpadding="5"
<tr>
<th class="thHead" colspan="2">Edit Menu Page Options</th>
</tr>
<tr>
<td class="gen">List items in same level</td>
<td class="gen"><input type="checkbox" name="sisters" {SISTERSINNAVIGATOR}></td>
</tr>

<tr>
<td class="gen">Numbers of levels to display on page</td>
<td class="table">
{PAGELEVELSFORM}
</td>
</tr>

<tr>
<td class="gen">Numbers of levels to display in navigator</td>
<td class="table">
{NAVIGATORLEVELSFORM}
</td>
</tr>
        <tr>
		      <td colspan="2" align="center">
            <input type="submit" name="editmenulevels" value="Edit levels" class="mainoption" />
            &nbsp;&nbsp;
            <input type="reset" value="Reset" class="liteoption" />
		      </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</form>
