<h1 class="headerpagetitle">Copyright Permissions</h1>
<p class="sectiontitle">Editing Blanket Copyright: ID {ID}</p>
<p>
	Last Person Responsible: <i>{EDITOR}</i>
	&nbsp;&nbsp;
	Added: <i>{DATEADDED}</i>
	&nbsp;&nbsp;
	Last Update: <i>{EDITDATE}</i>
</p>
<form name="editform" action="{ACTIONVARS}" method="post">
	<table cellpadding="5">
		{FORMELEMENTS}
		<tr>
			<td colspan="2" class="table" align="center">
				{HIDDENVARS}
				<input type="submit" name="changecopyright" value="Submit Changes" class="mainoption" />
				&nbsp;&nbsp;
				<input type="reset" name="reset" value="Reset Forms" />
				&nbsp;&nbsp;
				<input type="submit" name="cancel" value="Cancel Editing" />
			</td>
		</tr>
	</table>
</form>