<h1 class="headerpagetitle">Copyright Permissions</h1>
<p class="sectiontitle">Are you sure you want to delete this item?</p>
<table cellpadding="5">
	<caption>Copyright Holder</caption>
	<tr>
		<th>ID</th>
		<th>Copyright Holder</th>
		<th>Contact Information</th>
		<th>Comments/ Restrictions</th>
		<th>Perm.</th>
		<th>Preferred Credit</th>
		<th><font size="-2">Responsible/Added/ Last&nbsp;Update</font></th>
	</tr>
	<tr>
		<td align="right" valign="top">{ID}</td>
		<td align="left" valign="top"><b>{HOLDER}</b></td>
		<td align="left" valign="top">{CONTACT}</td>
		<td align="left" valign="top">{COMMENTS}</td>
		<td align="left" valign="top">{PERMISSION}</td>
		<td align="left" valign="top">{CREDIT}</td>
		<td align="left" valign="top">
			<p>{EDITOR}</p>
			<p class="footer">{DATEADDED}</p>
			<p class="footer">{EDITDATE}</p>
		</td>
	</tr>
</table>

<form name="confirmdeleteform" action="{ACTIONVARS}" method="post">
  	{HIDDENVARS}
  	<input type="submit" name="confirmdelete" value="Yes, please delete" />
  	&nbsp;&nbsp;
  	<input type="submit" name="cancel" value="No, please cancel" />
</form>
{PERMISSIONEXPLANATION}