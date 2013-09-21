{HEADER}
<script type="text/javascript">
<!--
function previewImage(displayimage)
{
  var previewpage= 'includes/preview.php?image='+ displayimage;
  window.open(previewpage,'mywindow','width=600,height=600,scrollbars=yes,resizable=yes');
}
//-->
</script>
{IMAGEMODESELECTION}
<!-- BEGIN switch MESSAGETITLE -->
<h2 class="pagetitle">{MESSAGETITLE}</h2>
<!-- END switch MESSAGETITLE -->
<!-- BEGIN switch MESSAGE -->
<p class="highlight">{MESSAGE}</p>
<!-- END switch MESSAGE -->
<!-- BEGIN switch EDITIMAGEFORM -->
{EDITIMAGEFORM}
<br />
<!-- END switch EDITIMAGEFORM -->
<!-- BEGIN switch ADDIMAGEFORM -->
{ADDIMAGEFORM}
<!-- END switch ADDIMAGEFORM -->
<table border="0" cellspacing="0" cellpadding="10" class="bodyline" width="100%">
<tr>
<td class="table">
{FORM}
</td></tr></table>
<br />
<form>
    <input type="button" name="close" value="Done" onClick="window.close()" class="mainoption">
</form>
<p>
<a href="admin.php?sid={SID}" target="_top" class="gen">Return to page editing</a>
</p>
{FOOTER}
