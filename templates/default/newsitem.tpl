<div class="newline newsitem">
		<!-- BEGIN switch SHOW_TOPLINK -->
		<div align="right" class="medtext">
			<a href="#top">{L_TOPOFTHISPAGE}</a>
		</div>
		<!-- END switch SHOW_TOPLINK -->
		<!-- BEGIN switch PRINTVIEWBUTTON -->
        	<span class="medtext">{PRINTVIEWBUTTON}</span>
        	{ITEMLINK}
    	<!--  END switch PRINTVIEWBUTTON -->
    	<!-- BEGIN switch TITLE -->
    	<h3 class="sectiontitle">{TITLE}</h3>
    	<!-- END switch TITLE -->
    	<!-- BEGIN switch LOCATION_DATE -->
      		<span class="articlelocation">{LOCATION}</span>
      		<span class="articledate">{DATE}</span>
      	<br />
	   	<!-- END switch LOCATION_DATE -->
    	<!-- BEGIN switch SOURCE -->
    	<span class="articlesource">{L_SOURCE}
    	<!-- BEGIN switch SOURCE_LINK --><a href="{SOURCE_LINK}" target="_blank"><!-- END switch SOURCE_LINK -->{SOURCE}<!-- BEGIN switch SOURCE_LINK --></a><!-- END switch SOURCE_LINK --><!-- BEGIN switch CONTRIBUTOR -->.<!-- END switch CONTRIBUTOR --></span>
    	<!-- END switch SOURCE -->

    	<!-- BEGIN switch CONTRIBUTOR -->
    	<span class="articlesource"> {L_CONTRIBUTOR} {CONTRIBUTOR}</span>
    	<!-- END switch CONTRIBUTOR -->

    	<!-- BEGIN switch SYNOPSIS_IMAGE -->
    	<div class="introtext">
	      	<br>&nbsp;<br>
	      	{IMAGE}
	      	<br>&nbsp;<br>
	      	{TEXT}
    	</div>
    	<!-- END switch SYNOPSIS_IMAGE -->
    <!-- BEGIN switch SECTION -->
    {SECTION}
    <!-- END switch SECTION -->
    <!-- BEGIN switch EDITOR -->
    <p class="articledate">Added by {EDITOR}</p>
    <!-- END switch EDITOR -->
    <p class="smalltext">{COPYRIGHT}</p>
</div>