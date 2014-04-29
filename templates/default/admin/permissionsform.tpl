<form name="permissionsform" action="{ACTIONVARS}" method="post">
	<div class="contentoutline">
		<div class="contentheader">Copyright information</div>
		<div class="contentsection">
			<fieldset>
				<legend class="highlight">Copyright holders and permission</legend>
				<p>
					<label for="copyright" class="leftalign labelleft">Text Copyright Holder:</label>
					<input id="copyright" type="text" name="copyright" size="70" maxlength="255" value="{COPYRIGHT}" />
				</p>
				<p>
					<label for="imagecopyright" class="leftalign labelleft">Image Copyright Holder:</label>
					<input id="imagecopyright" type="text" name="imagecopyright" size="70" maxlength="255" value="{IMAGE_COPYRIGHT}" />
				</p>
			</fieldset>
			<fieldset>
				<legend class="highlight">Permissions</legend>
				<p>
					<span class="leftalign labelleft">Permissions for text content:</span>
					{PERMISSION_GRANTED} {NO_PERMISSION}
				</p>
			</fieldset>
			{SUBMITROW}
		</div>
	</div>
</form>
<div class="newline"></div>