<form name="renamepageform" action="{ACTIONVARS}" method="post" enctype="charset=UTF-8">
	<div class="contentoutline">
		<div class="contentheader">Rename Page</div>
		<div class="contentsection">
			<fieldset>
				<legend class="highlight">Edit page names</legend>
				<p>
					<label for="navtitle" class="leftalign labelleft">Navigator page title (short):</label>
					<input id="navtitle" type="text" name="navtitle" size="50" maxlength="60" value="{NAVTITLE}" />
				</p>
				<p>
					<label for="title" class="leftalign labelleft">Page title:</label>
					<input id="title" type="text" name="title" size="70" maxlength="200" value="{PAGETITLE}" />
				</p>
				{SUBMITROW}
			</fieldset>
		</div>
	</div>
</form>
<div class="newline"></div>