{HEADER}

<div class="invisible"><a href="#contentarea" accesskey="n" class="invisible">Skip navigation</a></div>

<div id="navigator">
    {NAVIGATOR}
<!-- BEGIN switch BANNERS -->
    {BANNERS}
<!-- END switch BANNERS -->
</div>
<div id="contentarea">

<!-- BEGIN switch GUESTBOOKFORM -->
<h2 class="pagetitle">{TITLE}</h2>
<!-- END switch GUESTBOOKFORM -->

<!-- BEGIN switch DISABLED -->
<p class="highlight">{DISABLED}</p>
<!-- END switch DISABLED -->

<!-- BEGIN switch ENABLED -->

	<!-- BEGIN switch MESSAGE -->
	<p class="highlight">{MESSAGE}</p>
	<!-- END switch MESSAGE -->

	<!-- BEGIN switch ERROR -->
	{ERROR}
	<!-- END switch ERROR -->

	<!-- BEGIN switch EMAILERROR -->
	<p align="left">{ERREMAILERROROR}</p>
	<p>{L_TRYAGAIN}<p>
	<!-- END switch EMAILERROR -->

	<!-- BEGIN switch EMAILINFO -->
	{EMAILINFO}
	<!-- END switch EMAILINFO -->

	<!-- BEGIN switch POST -->
	{POST}
	<!-- END switch POST -->

	<!-- BEGIN switch POSTADDED -->
	<p class="highlight">{POSTADDED}</p>

	<form name="postform" method="post">
		<input type="submit" value="OK" class="mainoption" />
	</form>
	<!-- END switch POSTADDED -->

	<!-- BEGIN switch RETURN -->
	<form name="returnform" method="get">
		<input type="submit" value="{RETURN}" class="mainoption" />
	</form>
	<!-- END switch RETURN -->

	<!-- BEGIN switch LEAVEMESSAGE -->
	{INTRO}
	<br />
	<form name="postform" method="post">
		<input type="submit" name="post" value="{LEAVEMESSAGE}" class="mainoption" />
	</form>
	<!-- END switch LEAVEMESSAGE -->

	<!-- BEGIN switch GUESTBOOKFORM -->
	{GUESTBOOKFORM}
	<!-- END switch GUESTBOOKFORM -->

	<!-- BEGIN switch GUESTBOOKEMAILFORM -->
	{GUESTBOOKEMAILFORM}
	<!-- END switch GUESTBOOKEMAILFORM -->

	<!-- BEGIN switch ENTRIES -->
	{ENTRIES}
	<!-- END switch ENTRIES -->
<!-- END switch ENABLED -->

</div>

{FOOTER}
