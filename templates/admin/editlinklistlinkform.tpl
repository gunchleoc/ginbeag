<hr>
<p class="pagetitle">{LINKTITLE}</p>
{EDITDESCRIPTION}
<form name="changelinkproperties" action="?sid={SID}&page={PAGE}&link={LINKID}&action=editcontents" method="post">
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
    <input type="text" name="title" size="50" maxlength="255" value="{LINKINPUTTITLE}" />
  </td>
</tr>
<tr>
  <td class="gen" valign="top">Link:
  </td>
  <td class="table" valign="top">
    <input type="text" name="link" size="50" maxlength="255" value="{LINK}" />
  </td>
</tr>
<tr>
  <td class="gen" valign="top">&nbsp;
  </td>
  <td class="table" valign="top">
<p><input type="submit" name="linkproperties" value="Submit" class="mainoption">
&nbsp;&nbsp;<input type="reset" value="Reset" class="liteoption">
  </td>
</tr>
</table>

</td></tr>
</table>

</form>
<form name="changelinkimage" action="?sid={SID}&page={PAGE}&link={LINKID}&action=editcontents" method="post">
<table><tr><td class="bodyline">
<table><tr>
<th class="thHead" colspan="3">
Image
</th>
</tr>
<tr>
<td class="table" valign="top"><span class="gen">Filename:</span>
<br><span class="gensmall"><a href="editimagelist.php?sid={SID}" target="_blank">
View files</a></span>
</td>
<td class="table" valign="top"><input type="text" name="imagefilename" size="50" maxlength="255" value="{IMAGEFILENAME}" />
<p><input type="submit" name="changelinkimage" value="Add/Change Image" class="mainoption">
&nbsp;&nbsp;<input type="reset" value="Reset" class="liteoption">
<!-- BEGIN switch IMAGEFILENAME -->
<p>&nbsp;</p>
  <input type="submit" name="removelinkimage" value="Remove image" class="liteoption">
  <input type="checkbox" name="removeconfirm" value="Confirm remove" class="gen" />
  <span class="gen">Confirm remove</span>
<!-- END switch IMAGEFILENAME -->

<td rowspan="3" class="table" valign="top">
{IMAGE}
<br>
<a href="{IMAGELINKPATH}" target="_blank" class="gensmall">View full size</a>
</td>
</tr>
</table>
</td></tr>
</table>

</form>

<form name="movelink" action="?sid={SID}&page={PAGE}&link={LINKID}&action=editcontents" method="post">
<p><input type="submit" name="movelinkup" value="move link up" class="liteoption">
 &nbsp;&nbsp;&nbsp;
 <input type="text" name="positions" size="2" maxlength="3" value="1" />
&nbsp;&nbsp;&nbsp;
  <input type="submit" name="movelinkdown" value="move link down" class="liteoption">
<p>
  <input type="submit" name="deletelink" value="Delete this link" class="liteoption">
  <input type="checkbox" name="deletelinkconfirm" value="Confirm delete" class="gen" />
  <span class="gen">Confirm delete</span>
</form>
