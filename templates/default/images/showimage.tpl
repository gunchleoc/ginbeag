{HEADER}

<div class="invisible"><a href="#contentarea" accesskey="n" class="invisible">Skip navigation</a></div>

<div id="navigator">
    {NAVIGATOR}
<!-- BEGIN switch BANNERS -->
    {BANNERS}
<!-- END switch BANNERS -->
</div>
<div id="contentarea">

	{PAGEINTRO}

	<div class="leftalign">
		<!-- BEGIN switch PREVIOUS -->
		<form name="prevform" action="{PREVIOUSITEM}" method="post">
			{PREVIOUS}
			<input type="submit" name="prev" value="<" class="mainoption">
		</form>
		<!-- END switch PREVIOUS -->
	</div>

	<div class="rightalign">
		<!-- BEGIN switch NEXT -->
		<form name="nextform" action="{NEXTITEM}" method="post">
			{NEXT}
			<input type="submit" name="next" value=">" class="mainoption">
		</form>
		<!-- END switch NEXT -->
	</div>

	<!-- BEGIN switch RETURNPAGE -->
	<div class="centeralign">
		<form name="returnpageform" action="{RETURNPAGE}" method="post">
			<input type="submit" name="next" value="{RETURNPAGETITLE}" />
		</form>
	</div>
	<!-- END switch RETURNPAGE -->

	<div align="center">
		<!-- BEGIN switch IMAGEPATH -->
		<div class="captionedimage" align="center">
			<img src="{IMAGEPATH}" alt="{SIMPLECAPTION}" width="{WIDTH}" height="{HEIGHT}" />
			<div class="imagecaption">{CAPTION}</div>
		</div>
		<!-- END switch IMAGEPATH -->
		<!-- BEGIN switch NOIMAGE -->
		<span class="highlight"><i>{NOIMAGE}</i></span>
		<!-- END switch NOIMAGE -->
	</div>


	<div class="leftalign">
		<!-- BEGIN switch PREVIOUS -->
		<form name="prevform" action="{PREVIOUSITEM}" method="post">
			{PREVIOUS}
			<input type="submit" name="prev" value="<" class="mainoption">
		</form>
		<!-- END switch PREVIOUS -->
	</div>

	<div class="rightalign">
		<!-- BEGIN switch NEXT -->
		<form name="nextform" action="{NEXTITEM}" method="post">
			{NEXT}
			<input type="submit" name="next" value=">" class="mainoption">
		</form>
		<!-- END switch NEXT -->
	</div>

	<!-- BEGIN switch RETURNPAGE -->
	<div class="centeralign">
		<form name="returnpageform" action="{RETURNPAGE}" method="post">
			<input type="submit" name="next" value="{RETURNPAGETITLE}" />
		</form>
	</div>
	<!-- END switch RETURNPAGE -->

	{EDITDATA}
</div>
{FOOTER}
