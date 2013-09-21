<form name="newsitempermissionsform"
	action="?sid={SID}&page={PAGE}&newsitem={NEWSITEM}&offset={OFFSET}&action=editcontents"
	method="post">

<table>
	<tr>
		<td class="bodyline">
		<table>
			<tr>
				<th class="thHead" colspan="2">Copyright</th>
			</tr>
			<tr>
				<td class="gen">Copyright Holder:</td>
				<td class="table"><input type="text" name="copyright" size="70"
					maxlength="255" value="{COPYRIGHT}" /></td>
			</tr>
			<tr>
				<td class="gen">Permissions:</td>
				<td class="table">{PERMISSION_GRANTED} {NO_PERMISSION}
				{PERMISSION_REFUSED}</td>
			</tr>
			<tr>
				<td class="spacer" colspan="2"></td>
			</tr>
			<tr>
				<td class="gen">Image Copyright Holder:</td>
				<td class="table"><input type="text" name="imagecopyright"
					size="70" maxlength="255" value="{IMAGE_COPYRIGHT}" /></td>
			</tr>
			<tr>
				<td colspan="2" align="center" class="table"><input
					type="submit" name="setpermissions" value="Change Permissions"
					class="mainoption"> &nbsp;&nbsp;<input type="reset"
					value="Reset" class="liteoption"></td>
			</tr>
			<tr>
				<td class="spacer" colspan="2"></td>
			</tr>
		</table>
		</td>
	</tr>
</table>
</form>