{PAGEINTRO}

<!-- BEGIN switch PAGEMENU -->
<div class="pagemenu">{PAGEMENU}</div>
<!-- END switch PAGEMENU -->

<!-- start filterform -->
<br />
<form name="articlefilterform" method="get">
	{HIDDENVARS}
	<fieldset>
		<legend class="highlight">{L_DISPLAYOPTIONS}</legend>
		<fieldset>
			<legend>{L_TIMESPAN}</legend>
			{FROM_YEAR}<br />{TO_YEAR}
		</fieldset>
		<fieldset>
			<legend>{L_CATEGORIES}</legend>
			{CATEGORYSELECTION}
		</fieldset>
		<fieldset>
			<legend>{L_ORDERBY}</legend>
			{ORDER}<br />{ASCDESC}
		</fieldset>
		<div align="left"><input title="Filter articles" type="submit" name="filter" value="{L_GO}" class="buttonlink" /></div>
	</fieldset>
</form>
<!-- end filterform -->

<!-- BEGIN switch SEARCH_RESULT -->
<h3 class="sectiontitle">{MESSAGE}</h3>
<!-- END switch SEARCH_RESULT -->

<!-- BEGIN switch SUBPAGES -->
<div class="subpages">{SUBPAGES}</div>
<!-- END switch SUBPAGES -->

<!-- BEGIN switch PAGEMENU -->
<div class="pagemenu">{PAGEMENU}</div>
<!-- END switch PAGEMENU -->

<!-- BEGIN switch SEARCH_RESULT -->
<br />
<form name="showallform" action="{ACTIONVARS}" method="post">
	<input type="submit" name="showall" value="{L_SHOWALL}">
</form>
<!-- END switch SEARCH_RESULT -->
{EDITDATA}
