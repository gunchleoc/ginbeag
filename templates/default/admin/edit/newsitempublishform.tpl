<p>
	<!-- BEGIN switch NOTPERMISSIONREFUSED -->
	<!-- BEGIN switch ISPUBLISHED -->
	<input type="hidden" id="{JSID}ispublished" name="ispublished" value="true">
	<input type="button" id="{JSID}publishbutton" name="publishbutton" value="Hide newsitem" class="mainoption" />
	<!-- END switch ISPUBLISHED --> 
	<!-- BEGIN switch ISNOTPUBLISHED -->
	<input type="hidden" id="{JSID}ispublished" name="ispublished" value="false">
	<input type="button" id="{JSID}publishbutton" name="publishbutton" value="Publish newsitem" class="mainoption" />
	<!-- END switch ISNOTPUBLISHED -->
	<!-- END switch NOTPERMISSIONREFUSED -->
	
	<!-- BEGIN switch PERMISSIONREFUSED --> Copyright: This item may not be	published. <!-- END switch PERMISSIONREFUSED -->
	&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="button" value="Preview Newsitem" onClick="window.open('{PREVIEWLINK}')">


</p>