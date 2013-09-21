<form name="setpublishableform" action="{ACTIONVARS}" method="post">
	<div class="contentoutline">
		<div class="contentheader">Publishing options</div>
		<div class="contentsection">

			<fieldset>
				<legend class="highlight">Will this page be published?</legend>
		  		<!-- BEGIN switch PERMISSIONREFUSED -->
		      	<p>Copyright: This item may not be published.</p>
		  		<!-- END switch PERMISSIONREFUSED -->
				<!-- BEGIN switch NOT_PERMISSIONREFUSED -->
		      		{PUBLISHABLE_YES}
		      		{PUBLISHABLE_NO}
		      		{SUBMITROW}
				<!-- END switch NOT_PERMISSIONREFUSED -->
			</fieldset>
		</div>
	</div>
</form>
