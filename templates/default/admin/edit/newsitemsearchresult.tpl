<div class="contentheader">News Item: {SECTIONTITLE}</div>
<div class="contentsection">
	<a href="{ACTIONVARS}">Edit this newsitem</a>
	<p class="sectiontitle">{SECTIONTITLE}</p>
	<div><b>{SYNOPSIS}</b><br />...<br />...<br />...</div>

	<!-- BEGIN switch LOCATION -->
	<div>
		<span class="articlelocation">Location: {LOCATION}</span>
	</div>
	<!-- END switch LOCATION -->

	<span class="articlesource">
		Source: <!-- BEGIN switch SOURCELINK -->
		<a href="{SOURCELINK}" target="_blank">
		<!-- END switch SOURCELINK -->
		{SOURCE}
		<!-- BEGIN switch SOURCELINK -->
		</a>
		<!-- END switch SOURCELINK -->
	</span>

	<!-- BEGIN switch CONTRIBUTOR -->
	<span class="articlesource">Found by: {CONTRIBUTOR}</span>
	<!-- END switch CONTRIBUTOR -->

	<p class="articledate">{DATE}<br />Added by {EDITOR}</p>
	<p class="copyright">{COPYRIGHT}</p>
</div>
