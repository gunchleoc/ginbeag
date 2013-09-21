<!-- BEGIN switch IMAGE -->
<span class="smalltext"><br>{IMAGEPROPERTIES}</span>
<!-- END switch IMAGE -->
<!-- BEGIN switch THUMBNAILPROPERTIES -->
<br /><span class="smalltext"><b>Thumbnail:</b>&nbsp;{THUMBNAILPROPERTIES}</span>
<!-- END switch THUMBNAILPROPERTIES -->
<!-- BEGIN switch RESIZED -->
<p class="smalltext highlight">Resized image for viewing.<a href="{IMAGEPATH}" target=_blank>View full size</a></p>
<!-- END switch RESIZED -->
<div class="smalltext">
	<!-- BEGIN switch IMAGE -->
	<!-- BEGIN switch THUMBNAIL -->
	<a href="{IMAGEPATH}" target=_blank><img src="{THUMBNAILPATH}" alt="{IMAGEFILE}" /></a>
	<!-- END switch THUMBNAIL -->
	<!-- BEGIN switch NO_THUMBNAIL -->
	<img src="{IMAGEPATH}" width="{WIDTH}" height="{HEIGHT}" alt="{IMAGEFILE}" />
	<!-- END switch NO_THUMBNAIL -->
	<!-- END switch IMAGE -->
	<!-- BEGIN switch NO_IMAGE -->
	<p class="highlight">File <i>{IMAGEFILE}</i> not found</p>
	<!-- BEGIN switch THUMBNAIL -->
	<br />&nbsp;<br /><img src="{THUMBNAILPATH}"  alt="Thumbnail for {IMAGEFILE}" />
	<!-- END switch THUMBNAIL -->
	<!-- END switch NO_IMAGE -->
	<!-- BEGIN switch CAPTION -->
	<br />{CAPTION}
	<!-- END switch CAPTION -->
</div>