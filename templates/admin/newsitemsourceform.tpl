<p class="pagetitle">Source, Date & Categorization</p>
<form name="newsitemsource"
	action="?sid={SID}&page={PAGE}&offset={OFFSET}&newsitem={NEWSITEM}&action=editcontents"
	method="post">
<table>
	<tr>
		<td class="bodyline">
		<table>
			<tr>
				<th class="thHead" colspan="2">Source</th>
			</tr>
			<tr>
				<td class="gen">Found by:</td>
				<td class="table"><input type="text" name="contributor"
					size="58" maxlength="255" value="{CONTRIBUTOR}" /></td>
			</tr>
			<tr>
				<td class="spacer" colspan=2"></td>
			</tr>
			<tr>
				<td class="gen">Location:</td>
				<td class="table"><input type="text" name="location" size="58"
					maxlength="255" value="{LOCATION}" /></td>
			</tr>
			<tr>
				<td class="spacer" colspan=2"></td>
			</tr>
			<tr>
				<td class="gen">Source name:</td>
				<td class="table"><input type="text" name="source" size="58"
					maxlength="255" value="{SOURCE}" /></td>
			</tr>
			<tr>
				<td class="gen">Source link:</td>
				<td class="table"><input type="text" name="sourcelink"
					size="58" maxlength="255" value="{SOURCELINK}" /></td>
			</tr>
			<tr>
				<td class="spacer" colspan=2"></td>
			</tr>
			<tr>
				<td class="gen"></td>
				<td class="table"><input type="submit" name="newsitemsource"
					value="Save Changes" class="mainoption"> &nbsp;&nbsp;<input
					type="reset" value="Reset" class="liteoption"></td>
			</tr>
		</table>
		</td>
	</tr>
</table>
</form>