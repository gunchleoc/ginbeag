{JAVASCRIPT}
<input type="hidden" id="{JSID}linkid" name="linkid" value="{LINKID}">
<input type="hidden" id="{JSID}page" name="page" value="{PAGE}">
<input type="hidden" id="{JSID}sid" name="sid" value="{SID}">
<div class="contentheader"><span id="{JSID}sectionheader">{LINKTITLE}</span></div>
<div class="contentsection">
	<form name="changelinkproperties">
		<fieldset>
			<legend class="highlight">Link Properties</legend>
			<label for="{JSID}title">Title:</label>
			<input id="{JSID}title" type="text" name="title" size="50" maxlength="255" value="{LINKINPUTTITLE}" />
			<br /><label for="{JSID}link">Link:</label>
			<input id="{JSID}link" type="text" name="link" size="50" maxlength="255" value="{LINK}" />
		</fieldset>
		<input type="button" id="{JSID}savepropertiesbutton" name="savepropertiesbutton" value="Save Link Properties" class="mainoption" />
		&nbsp;&nbsp;
		<input type="reset" id="{JSID}savepropertiesreset" name="savepropertiesreset" value="Reset" />
	</form>

	{EDITDESCRIPTION}
	
	<div class="contentheader">Image</div>
	<div class="contentsection">
		<form>
			<div class="leftalign">
				<fieldset>
					<legend class="highlight">Image File</legend>
					<label for="{JSID}imagefilename">Filename:</label>
					<input id="{JSID}imagefilename" type="text" name="imagefilename" size="50" maxlength="255" value="{IMAGEFILENAME}" />
					<div class="formexplain">Change to a different image by putting in a filename without the path. (<a href="{IMAGELISTPATH}" target="_blank">View files</a>)</div>
					<br /><input type="button" id="{JSID}saveimagebutton" name="saveimagebutton" value="Add/Change Image" class="mainoption" />
					&nbsp;&nbsp;
					<input type="reset" id="{JSID}saveimagereset" name="saveimagereset" value="Reset" />
				</fieldset>
				
				<!-- BEGIN switch IMAGE -->
					<input type="button" id="{JSID}removeimagebutton" name="removeimagebutton" value="Remove Image" />
					<input id="{JSID}removeconfirm" type="checkbox" name="removeconfirm" value="Confirm remove" />
					Confirm remove
				<!-- END switch IMAGE -->
			</div>
			<div class="rightalign"><span id="{JSID}image">{IMAGE}</span></div>
			<div class="newline"></div>
		</form>
	</div>
	
	<form name="movelink" action="{ACTIONVARS}" method="post">
		<p>
			<input type="submit" name="movelinkup" value="move link up" />
	 		&nbsp;&nbsp;&nbsp;
	 		<input type="text" name="positions" size="2" maxlength="3" value="1" />
			&nbsp;&nbsp;&nbsp;
	  		<input type="submit" name="movelinkdown" value="move link down" />
	  	</p>
		<p>
	  		<input type="submit" name="deletelink" value="Delete this link" />
	  		<input type="checkbox" name="deletelinkconfirm" value="Confirm delete" />
	  		Confirm delete
	  	</p>
	</form>
</div>
<div id="{JSID}messagebox" class="messagebox highlight" style="height:0px; width=0px; position:absolute;"></div>
<div id="{JSID}progressbox" class="messagebox" style="height:0px; width=0px; position:absolute;"></div>