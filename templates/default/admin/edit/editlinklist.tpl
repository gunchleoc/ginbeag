<h1 class="headerpagetitle">Edit Linklist</h1>
{BACKBUTTONS}
{INTRO}

<div class="contentheader">Synopsis Image</div>
<div class="contentsection">
	<form name="changelinklistimage" action="{ACTIONVARS}" method="post">
		<div class="leftalign">
			<fieldset>
				<legend class="highlight">Image File</legend>
				<label for="synopsisimagefilename">Filename:</label>
				<input id="synopsisimagefilename" type="text" name="imagefilename" size="50" maxlength="255" value="{IMAGEFILENAME}" />
				<div class="formexplain">Change to a different image by putting in a filename without the path. (<a href="{IMAGELISTPATH}" target="_blank">View files</a>)</div>
				<br /><input type="submit" name="changelinklistimage" value="Add/Change Image" class="mainoption">
				&nbsp;&nbsp;
				<input type="reset" value="Reset" />
			</fieldset>
			
			<!-- BEGIN switch IMAGE -->
				<input type="submit" name="removelinklistimage" value="Remove image" />
                <input type="checkbox" name="removeconfirm" value="Confirm remove" />
                Confirm remove
			<!-- END switch IMAGE -->
		</div>
		<!-- BEGIN switch IMAGE -->
		<div class="rightalign"><span id="{JSID}image">{IMAGE}</span></div>
		<!-- END switch IMAGE -->
		<div class="newline"></div>
	</form>
</div>

{LINKFORM}
{ADDFORM}
<div><a href="#top" class="smalltext">Top of this page</a></div>
<br />
{BACKBUTTONS}