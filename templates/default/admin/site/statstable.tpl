<h1 class="headerpagetitle">Site Statistics</h1>
<!-- BEGIN switch STATS -->
<div class="contentheader">Site Statistics</div>
<div class="contentsection">
	<table>
		<caption>Stats for {MONTH} {YEAR} - Top {COUNT} pages</caption>
		<tr>
			<th>Rank</th>
			<th>Views</th>
			<th>Pagetype</th>
			<th>Page</th>
		</tr>
		{STATS}
	</table>

	<!-- END switch STATS -->
	<!-- BEGIN switch NOSTATS -->
	<p class="contentheader">Stats for {MONTH} {YEAR} not available.
	<!-- END switch NOSTATS -->
	<form name="selectmonth"  method="post">
	<fieldset>
			<legend class="highlight">Select monthly statistics</legend>
			<label for="countmonth">Show top</label>
			<input id="countmonth" type="text" name="countmonth" size="5" maxlength="5" value="{COUNT_SELECTION}" />
			for {MONTH_SELECTION} {MONTH_YEAR_SELECTION}
			<input type="submit" name="selectmonth" value="Show month" class="mainoption" />
	</fieldset>
	<span class="highlight">- or -</span>
	<fieldset>
			<legend class="highlight">Select yearly statistics</legend>

			<label for="countyear">Show top</label>
			<input id="countyear" type="text" name="countyear" size="5" maxlength="5" value="{COUNT_SELECTION}" />
			for {YEAR_YEAR_SELECTION}
			<input type="submit" name="selectyear" value="Show year" class="mainoption" />

	</fieldset>
	</form>
</div>
