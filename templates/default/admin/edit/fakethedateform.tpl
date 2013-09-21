<form name="fakethedateform">
	<div class="contentheader">Date: <span id="{JSID}dateheader">{DATE}</span></div>
	<div class="contentsection">
		<fieldset>
			<legend class="highlight">Fake the date!</legend>
			{DAYFORM} &nbsp;&nbsp; {MONTHFORM} &nbsp;&nbsp;
			<label for="{JSID}year">Year (4-digit):</label><!-- todo: yearform for consistency -->
			<input id="{JSID}year" type="text" name="year" size="5" maxlength="4" value="{YEAR}" />
			&nbsp;&nbsp;{HOURSFORM}
			&nbsp;&nbsp;{MINUTESFORM}
			&nbsp;&nbsp;{SECONDSFORM} <br>			
		</fieldset>
		<input type="button" id="{JSID}savedatebutton" name="savedatebutton" value="Fake the Date!" class="mainoption" />
		<input id="{JSID}savedatereset" type="reset" value="Reset" />
	</div>
</form>