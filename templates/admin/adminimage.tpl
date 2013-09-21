<div>
<span class="gensmall">
  <!-- BEGIN switch IMAGE -->
  {IMAGEPROPERTIES}
    <!-- BEGIN switch THUMBNAIL -->
      <!-- BEGIN switch THUMBNAILPROPERTIES -->
      <br /><b>Thumbnail:</b>&nbsp;{THUMBNAILPROPERTIES}
      <!-- END switch THUMBNAILPROPERTIES -->
    <br />&nbsp;<br /><a href="{IMAGEPATH}" target=_blank><img src="{THUMBNAILPATH}" alt="{IMAGEFILE}" /></a>
    <!-- END switch THUMBNAIL -->
    <!-- BEGIN switch NO_THUMBNAIL -->
    <!-- BEGIN switch RESIZED -->
    <br />Resized image for viewing.
    <a href="{IMAGEPATH}" target=_blank>View full size</a>
    <!-- END switch RESIZED -->
    <br />&nbsp;<br /><img src="{IMAGEPATH}" width="{WIDTH}" height="{HEIGHT}" alt="{IMAGEFILE}" />
    <!-- END switch NO_THUMBNAIL -->
  <!-- END switch IMAGE -->

  <!-- BEGIN switch NO_IMAGE -->
  <p class="highlight">File <i>{IMAGEFILE}</i> not found</p>
    <!-- BEGIN switch THUMBNAIL -->
      <!-- BEGIN switch THUMBNAILPROPERTIES -->
      <br>Thumbnail:&nbsp;{THUMBNAILPROPERTIES}
      <!-- END switch THUMBNAILPROPERTIES -->
    <br />&nbsp;<br /><img src="{THUMBNAILPATH}"  alt="Thumbnail for {IMAGEFILE}" />
    <!-- END switch THUMBNAIL -->
  <!-- END switch NO_IMAGE -->
<!-- BEGIN switch CAPTION -->
</span>
{CAPTION}
<!-- END switch CAPTION -->
</div>
