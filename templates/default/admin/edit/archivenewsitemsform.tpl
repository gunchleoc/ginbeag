<h1 class="headerpagetitle">Archiving Items from News Page</h1>
<div class="contentheader">Select the date range for archiving newsitems</div>
<div class="contentsection">
	<form name="doarchivenewsitemsform" action="{ACTIONVARS}" method="post">
		Archive News Items from
		<span class="highlight"><input class="highlight" type="text" name="oldestday" value="{DAY}" style="width:2em; border:0px;" disabled />
		<input class="highlight" type="text" name="oldestmonth" value="{MONTH}" style="width:7em; border:0px;" disabled />
		<input class="highlight" type="text" name="oldestyear" value="{YEAR}" style="width:3em; border:0px;" disabled /></span>
		to {DAYFORM} &nbsp; {MONTHFORM} &nbsp; {YEARFORM} &nbsp;
		<input type="submit" name="doarchivenewsitems" value="Do it!" class="mainoption">
	</form>
</div>
{NAVIGATIONBUTTONS}