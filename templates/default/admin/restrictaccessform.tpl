<div class="contentoutline">
	<div class="contentheader">Access Restriction</div>
	<div class="contentsection">
		<form name="accessform" action="{ACTIONVARS}" method="post">
			<fieldset>
				<legend class="highlight">Restrict access for this page and all of its subpages</legend>
				<span class="leftalign labelleft">Restrict access:</span>
				{RESTRICT_YES} {RESTRICT_NO}
				{SUBMITROW}
			</fieldset>
		</form>
		<!-- BEGIN switch ACCESSRESTRICTED -->
		<form name="accessusersform" action="{USERSACTIONVARS}" method="post">
			<fieldset>
				<legend class="highlight">Public users with access to this page</legend>
				<p>

					
					{SELECTUSERS}
				</p>
				<p>
				{RESTRICTEDUSERLIST}
				</p>
				<input type="submit" name="addpublicusers" value="Add Users" class="mainoption" />
				&nbsp;&nbsp;&nbsp;<input type="submit" name="removepublicusers" value="Remove" class="mainoption" />
			</fieldset>	  
		</form>
		<!-- END switch ACCESSRESTRICTED -->
	</div>
</div>
<div class="newline"></div>