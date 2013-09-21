{HEADER}
<form name="doarchivenewsitemsform" action="?sid={SID}&page={PAGE}&action=editcontents" method="post">
  <span class="gen">Archive News Items from </span>
  <span class="highlight">{DAY} {MONTH} {YEAR}</span>
  <span class="gen">to Day:</span>
  {DAYFORM}
  <span class="gen">&nbsp;&nbsp;Month:</span>
  {MONTHFORM}
  <span class="gen">&nbsp;&nbsp;Year:</span>
  {YEARFORM}
  &nbsp;&nbsp;
  <input type="hidden" name="oldestday" value="{DAY}">
  <input type="hidden" name="oldestmonth" value="{MONTH}">
  <input type="hidden" name="oldestyear" value="{YEAR}">
  <input type="submit" name="doarchivenewsitems" value="Do it!" class="mainoption">
</form>
{BACKBUTTONS}
{FOOTER}