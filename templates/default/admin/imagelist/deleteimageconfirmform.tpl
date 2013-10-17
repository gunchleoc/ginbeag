<div class="imagecontentarea">
	<div class="contentoutline">
		<div class="contentheader">Delete Image</div>
		<div class="contentsection">
			<h1 class="pagetitle">Deleting image {FILENAME}</h1>
			<p class="highlight">Are you sure you want to delete this image?<br>This cannot be undone!</p>
			{IMAGE}
			<p>
			<form name="deleteimageform" action="{ACTIONVARS}" method="post">
				{HIDDENVARS}
				<input type="submit" name="delete" value="Yes, please delete" />
				&nbsp;&nbsp;&nbsp;
				<input type="submit" name="nodelete" value="Oops, no!" class="mainoption" />
			</form>
		</div>
	</div>
</div>