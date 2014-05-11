<h1 class="headerpagetitle">Archiving Items from News Page</h1>
<div class="contentheader">Select the date range for archiving newsitems</div>
<div class="contentsection">
	<form name="doarchivenewsitemsform" action="{ACTIONVARS}" method="post">
		{HIDDENVARS}
		Archive News Items from
		<span class="highlight">{OLDDATE}</span>
		to {DAYFORM} &nbsp; {MONTHFORM} &nbsp; {YEARFORM} &nbsp;
		<input type="submit" name="doarchivenewsitems" value="Do it!" class="mainoption">
	</form>
</div>
{NAVIGATIONBUTTONS}
