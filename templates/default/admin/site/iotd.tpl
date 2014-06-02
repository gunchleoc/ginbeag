<h1 class="headerpagetitle">Random Items of the Day</h1>
<form name="site" action="{ACTIONVARS}" method="post">
	{HIDDENVARS}
	<div class="contentheader">On Random: Picture of the Day</div>
	<div class="contentsection">
		<fieldset>
			<legend class="highlight">Display Picture of the Day</legend>
			{DISPLAYPOTD_YES} {DISPLAYPOTD_NO}
			<div class="formexplain">Will a Picture of the Day be shown in the navigator?</div>
		</fieldset>
		<fieldset>
			<legend class="highlight">Categories for Picture of the Day</legend>
			{POTDCATFORM}
			<div class="formexplain">The random Picture of the Day will be generated from the selected categories and their subcategories.<br /><br />Current categories: {POTDLIST}</div>
		</fieldset>
	</div>





	<div class="contentheader">On Random: Article of the Day</div>
	<div class="contentsection">
		<fieldset>
			<legend class="highlight">Display Article of the Day</legend>
			{DISPLAYAOTD_YES} {DISPLAYAOTD_NO}
			<div class="formexplain">Will an Article of the Day be shown in the navigator?</div>
		</fieldset>
		<fieldset>
			<legend class="highlight">Article of the Day Start Pages</legend>
			<label for="aotdpages">Articlemenu pages:</label>
			<input id="aotdpages" type="text" name="aotdpages" size="25" maxlength="255" value="{AOTDPAGES}" /></td>
			<div class="formexplain">Get the Article of the Day from these articlemenu pages and their subpages (separate with commas, e.g. '5,260,6').</div>

		</fieldset>
	</div>

	{SUBMITROW}
</form>
