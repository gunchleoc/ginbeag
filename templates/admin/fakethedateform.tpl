<form name="fakethedateform"
	action="?sid={SID}&page={PAGE}&offset={OFFSET}&newsitem={NEWSITEM}&action={EDITCONTENTS}"
	method="post">

<table>
	<tr>
		<td class="bodyline">
		<table>
			<tr>
				<th class="thHead" colspan="2">Date: {DATE}</th>
			</tr>

			<tr>
				<td class="table">&nbsp; <br>
				<span class="gen">Day:</span> {DAYFORM} <span class="gen">&nbsp;&nbsp;Month:</span>
				{MONTHFORM} <span class="gen">&nbsp;&nbsp;Year (4-digit):</span> <!-- todo: yearform for consistency -->
				<input type="text" name="year" size="5" maxlength="4" value="{YEAR}" />

				&nbsp;&nbsp;<span class="gen">Hours:</span> {HOURSFORM} <span class="gen">&nbsp;&nbsp;Minutes:</span>
				{MINUTESFORM} <span class="gen">&nbsp;&nbsp;Seconds:</span>
				{SECONDSFORM} <br>
				<input type="submit" name="fakethedate" value="Fake the Date!"
					class="liteoption" /></td>
			</tr>
		</table>
		</td>
	</tr>
</table>
</form>
