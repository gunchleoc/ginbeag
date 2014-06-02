<div class="submitrow">
	<input id="{JSID}{SUBMIT}" type="submit" name="{SUBMIT}" value="{SUBMITLABEL}"  class="mainoption" />
	<!-- BEGIN switch SHOW_RESET -->
	&nbsp;&nbsp;<input type="reset" value="Reset" />
	<!-- END switch SHOW_RESET -->
	<!-- BEGIN switch SHOW_CANCEL -->
	<!-- BEGIN switch NO_CANCELLOCATION -->
	&nbsp;&nbsp;<input id="{JSID}cancel" type="submit" name="cancel" value="Cancel" />
	<!-- END switch NO_CANCELLOCATION -->
	<!-- BEGIN switch CANCELLOCATION -->
	&nbsp;&nbsp;<input id="{JSID}cancel" type="button" name="cancel" value="Cancel" onClick="self.location.href='{CANCELLOCATION}'" />
	<!-- END switch CANCELLOCATION -->
	<!-- END switch SHOW_CANCEL -->
</div>
