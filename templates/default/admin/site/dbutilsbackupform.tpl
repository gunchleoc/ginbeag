<h1 class="headerpagetitle">Database Backup</h1>
<div class="contentheader">Backup Database</div>
<div class="contentsection">
	<form name="backupform" action="{BACKUPACTIONVARS}" method="post">
		<fieldset>
			<legend class="highlight">What to backup</legend>
			<input id="structure" type="radio" name="structure" value="structure">
			<label for="structure">Structure only</label>
			<input id="full" type="radio" name="structure" value="full" checked>
			<label for="full">Full backup</label>
			<div class="formexplain">Select if you with to backup the structure only, or if you wish to make a full backup including all data.</div>
		</fieldset>
		<fieldset>
			<legend class="highlight">Download method</legend>
			<input id="screen" type="radio" name="display" value="screen">
			<label for="screen">Display on screen</label>
			<input id="download" type="radio" name="display" value="download">
			<label for="full">Download uncompressed</label>
			<input id="gzip" type="radio" name="display" value="gzip" checked>
			<label for="gzip">Download gzip</label>
			<div class="formexplain">Display the backup on screen or download uncompressed for small databases or structure backup.<br />For big databases, download as <em>gzip</em>.</div>
		</fieldset>
		<input type="submit" name="backup" value="Backup Database" class="mainoption" />
	</form>
</div>
<div class="contentheader">Page cache</div>
<div class="contentsection">
	<form name="clearcacheform" action="{CACHEACTIONVARS}" method="post">
		<input type="submit" name="clearpagecache" value="Clear Page Cache" class="mainoption">
	</form>
</div>
