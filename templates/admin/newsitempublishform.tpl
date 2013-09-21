<form name="newsitempublishform"
	action="?sid={SID}&page={PAGE}&offset={OFFSET}&newsitem={NEWSITEM}&action=editcontents"
	method="post">

		<p><!-- BEGIN switch NOTPERMISSIONREFUSED -->
		 <!-- BEGIN switch ISPUBLISHED -->
		<input type="submit" name="unpublish" value="Hide newsitem"
			class="mainoption" />
			 <!-- END switch ISPUBLISHED --> 
			 <!-- BEGIN switch ISNOTPUBLISHED -->
		<input type="submit" name="publish" value="Publish newsitem"
			class="mainoption" />
	 <!-- END switch ISNOTPUBLISHED -->
	  <!-- END switch NOTPERMISSIONREFUSED -->

		<!-- BEGIN switch PERMISSIONREFUSED --> Copyright: This item may not be
		published. <!-- END switch PERMISSIONREFUSED -->
		&nbsp;&nbsp;&nbsp;&nbsp; <a
			href="{PREVIEWPATH}?sid={SID}&page={PAGE}&offset={OFFSET}&newsitem={NEWSITEM}"
			target="_blank" class="gen">Preview Newsitem</a></p>
</form>