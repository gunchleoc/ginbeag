<h2 class="pagetitle">{PAGETITLE}</h2>
<!-- BEGIN switch ARTICLE_AUTHOR -->
<p class="articleauthor">{L_AUTHOR} {ARTICLE_AUTHOR}</p>
<!-- END switch ARTICLE_AUTHOR -->
<p>
	<!-- BEGIN switch LOCATION -->
	<span class="articlelocation">{LOCATION},</span>
	<!-- END switch LOCATION -->
	<span class="articledate">{DATE}</span>
</p>
<!-- BEGIN switch SOURCE -->
<span class="articlesource">{L_SOURCE}
	<!-- BEGIN switch SOURCE_LINK --><a href="{SOURCE_LINK}" target="_blank"><!-- END switch SOURCE_LINK -->{SOURCE}<!-- BEGIN switch SOURCE_LINK --></a><!-- END switch SOURCE_LINK -->
</span>
<!-- END switch SOURCE -->
<div class="introtext">{PAGEINTRO}</div>
<div class="newline">
<!-- BEGIN switch PRINTVIEWBUTTON -->
<p class="smalltext">{PRINTVIEWBUTTON}</p>
<!-- END switch PRINTVIEWBUTTON -->
<!-- BEGIN switch PAGEMENU -->
<p align="right">{PAGEMENU}</p>
<!-- END switch PAGEMENU -->
<!-- BEGIN switch TOC -->
{TOC}
<!-- END switch TOC -->
</div>
<!-- BEGIN switch ARTICLESECTION -->
{ARTICLESECTION}
<!-- END switch ARTICLESECTION -->
<!-- BEGIN switch PAGEMENU -->
<p align="right">{PAGEMENU}</p>
<!-- END switch PAGEMENU -->
{EDITDATA}
