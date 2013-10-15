{JAVASCRIPT}
<input type="hidden" id="{JSID}linkid" name="linkid" value="{LINKID}">
<input type="hidden" id="{JSID}page" name="page" value="{PAGE}">
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
	{IMAGEEDITOR}
	
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