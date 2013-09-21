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
						<input id="filterfilename" type="text" maxlength="255" size="30" name="filename" value="{FILENAME}" />
						<div class="formexplain">Search for text contained in a filename.</div>
						
						<br/><label for="filtercaption">Caption: </label>
						<input id="filtercaption" type="text" maxlength="255" size="30" name="caption" value="{CAPTION}" />
						<div class="formexplain">Search for text contained in a caption.</div>
				
						<br/><label for="filtersource">Source: </label>
						<input id="filtersource" type="text" maxlength="255" size="30" name="source" value="{SOURCE}" />
						<br /><input id="filtersourceblank" type="checkbox" name="sourceblank" {SOURCEBLANK} />
						<label for="filtersourceblank">Search for images with blank source</label>
						<div class="formexplain">Search for text contained in a source.</div>
						
						<br/><label for="filtercopyright">Copyright Holder: </label>
						<input id="filtercopyright" type="text" maxlength="255" size="30" name="copyright" value="{COPYRIGHT}" />
						<br /><input id="filtercopyrightblank" type="checkbox" name="copyrightblank" {COPYRIGHTBLANK} />
						<label for="filtercopyrightblank">Search for images with blank copyight holder</label>
						<div class="formexplain">Search for copyright holder.</div>
					</fieldset>
					<fieldset>
						<legend class="highlight">Only images uploaded by:</legend>
				        {USERSSELECTIONFORM}
					</fieldset>
				</div>
				<div class="leftalign" style="width:31% !important; padding-right:2%;">
					<fieldset>
						<legend class="highlight">Image category</legend>
				        {CATEGORYSELECTION}
				        <br /><input id="filtercategoriesblank" type="checkbox" name="categoriesblank" {CATEGORIESBLANK} />
						<label for="filtercategoriesblank">Search for images without categories</label>
					</fieldset>
				</div>
				<div class="newline"></div>
				
				<input type="submit" name="filter" value="Display Selection" class="mainoption" />
				<input type="submit" name="clear" value="Show all images" />
				
			    <!-- todo reinstate functions
			      <p class="highlight">Image options:</p>
			
			          <p class="highlight">Webpage Status:</p>
			          <input type="submit" name="unused" value="Show Unused Images" class="mainoption" />
			          
			          <p class="highlight">File System:</p>
			          <input type="submit" name="missing" value="Missing Image Files" class="mainoption" />
			          &nbsp;&nbsp;&nbsp;<input type="submit" name="unknown" value="Unknown Image Files" class="mainoption" />
			          
			      <p class="highlight">Thumbnail options:</p>
			                <input type="submit" name="nothumb" value="Images without Thumbnails" class="mainoption" />
			          <input type="submit" name="missingthumb" value="Missing Thumbnail Files" class="mainoption" />
			  //-->
			</form>
			<!-- BEGIN switch MESSAGE -->
			<span class="highlight">{MESSAGE}</span>
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