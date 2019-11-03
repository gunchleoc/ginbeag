{HEADER}

<div class="invisible"><a href="#contentarea" accesskey="n" class="invisible">Skip navigation</a></div>

<div id="navigator">
    {NAVIGATOR}
<!-- BEGIN switch BANNERS -->
    {BANNERS}
<!-- END switch BANNERS -->
</div>
<div id="contentarea">
	<!-- BEGIN switch BLANKFORM -->
	{INTRO}
	<br />
	{CONTACTFORM}
	<!-- END switch BLANKFORM -->

	<!-- BEGIN switch ERROR -->
	<h1 class="highlight">{L_TRYAGAIN}</h1>
	<div align="left">{ERRORMESSAGE}</div>
	{CONTACTFORM}
	<!-- END switch ERROR -->

	<!-- BEGIN switch SENDMAIL -->
	{EMAILINFO}
	<div class="highlight">{L_SUCCESS}</div>
	<!-- END switch SENDMAIL -->
</div>

{FOOTER}
