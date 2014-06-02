<div class="contentheader">Create a new Page under: {PARENTNAME}</div>
<div class="contentsection">
	<form name="newpageform" action="{ACTIONVARS}" method="post">
		<fieldset>
			<legend class="highlight">Publishing</legend>
			{IS_PUBLISHABLE_YES} {IS_PUBLISHABLE_NO}
			<div class="formexplain">This setting can be change later at any time. Internal pages can't be published unless you change their status to "Public page" first.</div>
		</fieldset>
		<fieldset>
			<legend class="highlight">Placing</legend>
			{ROOTCHECKEDFORM}
			<div class="formexplain">If this is checked, the new page will be a main page. If this is empty, the page will be created under the last page you selected.</div>
		</fieldset>
		<fieldset>
			<legend class="highlight">Page Type</legend>
			{TYPESELECTION}
			<div class="formexplain">Different page types have different properties as to which content you can add.</div>
		</fieldset>
		<fieldset>
			<legend class="highlight">Page Title</legend>
			<label for="navtitle">Page title (short):</label>
			<input id="navtitle" type="text" name="navtitle" size="60" maxlength="60" value="{NAVTITLE}" />
			<div class="formexplain">This title will be displayed in navigators.</div>
			<label for="title">Page title:</label>
			<input id="title" type="text" name="title" size="60" maxlength="200" value="{PAGETITLE}" />
			<div class="formexplain">This title will be displayed on the page itself.</div>
		</fieldset>
		<input type="submit" name="create" value="Create" class="mainoption" />
		&nbsp;&nbsp;<input type="reset" value="Cancel" />
	</form>
</div>
