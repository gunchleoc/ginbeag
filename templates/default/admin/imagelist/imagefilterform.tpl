<div class="imagecontentarea">
	<div class="contentoutline">
		<div class="contentheader">Search Images</div>
		<div class="contentsection">
			<form name="imagefilterform" method="get">
				{HIDDENFIELDS}
				<div class="leftalign" style="width:31% !important; padding-right:2%;">
					<fieldset>
						<legend class="highlight">Image description and filename</legend>
						<label for="filterfilename">Filename: </label>
						<input id="filterfilename" type="text" maxlength="255" size="30" name="s_filename" value="{FILENAME}" />
						<div class="formexplain">Search for text contained in a filename.</div>
						
						<br/><label for="filtercaption">Caption: </label>
						<input id="filtercaption" type="text" maxlength="255" size="30" name="s_caption" value="{CAPTION}" />
						<div class="formexplain">Search for text contained in a caption.</div>
				
						<br/><label for="filtersource">Source: </label>
						<input id="filtersource" type="text" maxlength="255" size="30" name="s_source" value="{SOURCE}" />
						<br />{SOURCEBLANKFORM}
						<div class="formexplain">Search for text contained in a source.</div>
						
						<br/><label for="filtercopyright">Copyright Holder: </label>
						<input id="filtercopyright" type="text" maxlength="255" size="30" name="s_copyright" value="{COPYRIGHT}" />
						<br />{COPYRIGHTBLANKFORM}
						<div class="formexplain">Search for copyright holder.</div>
					</fieldset>
					<fieldset>
						<legend class="highlight">Only images uploaded by:</legend>
				        {USERSSELECTIONFORM}
					</fieldset>
					<input type="submit" name="filter" value="Display Selection" class="mainoption" />
					<input type="submit" name="clear" value="Show all images" />
				</div>
				<div class="leftalign" style="width:31% !important; padding-right:2%;">
					<fieldset>
						<legend class="highlight">Image category</legend>
				        {CATEGORYSELECTION}
				        <br />{CATEGORIESBLANKFORM}
					</fieldset>
				</div>
				<div class="leftalign" style="width:31% !important; padding-right:2%;">
					<fieldset>
						<legend class="highlight">Special Searches</legend>
						<fieldset>
							<legend>File System</legend>
							<input type="submit" name="s_missing" value="Missing Image Files" class="mainoption" />
							&nbsp;&nbsp;&nbsp;<input type="submit" name="s_missingthumb" value="Missing Thumbnail Files" class="mainoption" />
							<br /><input type="submit" name="s_unknown" value="Unknown Image Files" class="mainoption" />
						</fieldset>
						<fieldset>
							<legend>Database</legend>
							<input type="submit" name="s_unused" value="Show Unused Images" class="mainoption" />
						</fieldset>
						<input type="submit" name="clear" value="Clear special searches" />
					</fieldset>
				</div>
			</form>
			    <!-- todo reinstate functions
			      <p class="highlight">Thumbnail options:</p>
			                <input type="submit" name="nothumb" value="Images without Thumbnails" class="mainoption" />
			  //-->
				<div class="newline"></div>
			<!-- BEGIN switch MESSAGE -->
			<div class="spacer">&nbsp;</div>
			<div class="highlight">{MESSAGE}</div>
			<!-- END switch MESSAGE -->
		</div>
	</div>
</div>
<form method="get">
	<div class="leftalign">
		{ORDERSELECTIONHIDDENFIELDS}
		Images per page:
		<input type="text" name="number" size="2" maxlength="2" value="{NUMBER}" />
		{IMAGEORDERSELECTION}
		&nbsp;&nbsp;&nbsp;
		{ASCDESCSELECTION}
		&nbsp;&nbsp;&nbsp;
		<input type="submit" name="doorder" value="Go" class="mainoption" />
	</div>
	<!-- BEGIN switch PAGEMENU -->
	<div class="rightalign">{PAGEMENU}</div>
	<!-- END switch PAGEMENU -->
	<div class="newline"></div>
</form>