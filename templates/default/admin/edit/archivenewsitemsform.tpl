<h1 class="headerpagetitle">Archiving Items from News Page</h1>
<div class="contentheader">Select the date range for archiving newsitems</div>
<div class="contentsection">
	<form name="doarchivenewsitemsform" action="{ACTIONVARS}" method="post">
	  Archive News Items from
	  <span class="highlight">{DAY} {MONTH} {YEAR}</span>
	  to Day: {DAYFORM}
	  &nbsp;&nbsp;Month: {MONTHFORM}
	  &nbsp;&nbsp;Year: {YEARFORM}
	  &nbsp;&nbsp;
	  <input type="hidden" name="oldestday" value="{DAY}">
	  <input type="hidden" name="oldestmonth" value="{MONTH}">
	  <input type="hidden" name="oldestyear" value="{YEAR}">
	  <input type="submit" name="doarchivenewsitems" value="Do it!" class="mainoption">
	</form>
</div>
{BACKBUTTONS}