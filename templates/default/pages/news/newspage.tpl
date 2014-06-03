{PAGEINTRO}

<!-- BEGIN switch JUMPFORM -->
{JUMPFORM}
<!-- END switch JUMPFORM -->

<!-- BEGIN switch RSS -->
<div class="rss">{RSS}</div>
<!-- END switch RSS -->

<!-- BEGIN switch PAGEMENU -->
<div class="pagemenu">{PAGEMENU}</div>
<!-- END switch PAGEMENU -->

<!-- BEGIN switch SEARCH_RESULT -->
<p class="highlight">{MESSAGE}</p>
<p><a href="{ACTIONVARS}">{L_SHOWALL}</a><br>&nbsp;</p>
<!-- END switch SEARCH_RESULT -->


<!-- BEGIN switch NEWSITEM -->
{NEWSITEM}
<!-- END switch NEWSITEM -->

<!-- BEGIN switch PAGEMENU -->
<div class="pagemenu">{PAGEMENU}</div>
<!-- END switch PAGEMENU -->

<!-- start filterform -->
<div class="newline"></div>
<br />
<form name="newsfilterform" method="get">
	{HIDDENVARS}
	<fieldset>
		<legend class="highlight">{L_DISPLAYOPTIONS}</legend>
		<fieldset>
			<legend>{L_FROM}</legend>
			{FROM_DAY}  &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; {FROM_MONTH}  &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; {FROM_YEAR}
		</fieldset>
		<fieldset>
			<legend>{L_TO}</legend>
			{TO_DAY}  &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; {TO_MONTH}  &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; {TO_YEAR}
		</fieldset>
		<fieldset>
			<legend>{L_CATEGORIES}</legend>
			{CATEGORYSELECTION}
		</fieldset>
		<fieldset>
			<legend>{L_ORDERBY}</legend>
			{ORDER}  &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; {ASCDESC}
		</fieldset>
		<div align="left"><input title="Filter newsitems" type="submit" name="filter" value="{L_GO}" class="mainoption" /></div>
	</fieldset>
</form>
<!-- end filterform -->

<!-- BEGIN switch JUMPFORM -->
{JUMPFORM}
<!-- END switch JUMPFORM -->

<!-- BEGIN switch RSS -->
<div class="rss">{RSS}</div>
<!-- END switch RSS -->

<!-- BEGIN switch PAGEMENU -->
<div class="pagemenu">{PAGEMENU}</div>
<!-- END switch PAGEMENU -->

{EDITDATA}
