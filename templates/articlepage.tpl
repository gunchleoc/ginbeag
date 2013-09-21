<table border="0" cellpadding="10" cellspacing="0" width="100%">
  <tr>
    <td>
      <h2 class="pagetitle">{PAGETITLE}</h2>
      <!-- BEGIN switch ARTICLE_AUTHOR -->
      <p class="articleauthor">By {ARTICLE_AUTHOR}</p>
      <!-- END switch ARTICLE_AUTHOR -->
      <p>
        <span class="articlelocation">{LOCATION}</span>
        <span class="articledate">{DATE}</span>
      </p>
      <!-- BEGIN switch SOURCE -->
      <span class="articlesource">{L_SOURCE}
      <!-- BEGIN switch SOURCE_LINK --><a href="{SOURCE_LINK}" target="_blank"><!-- END switch SOURCE_LINK -->{SOURCE}<!-- BEGIN switch SOURCE_LINK --></a><!-- END switch SOURCE_LINK --></span>
      <!-- END switch SOURCE -->
    </td>
  </tr>
  <tr>
    <td>&nbsp;
<!-- BEGIN switch IMAGE -->
<!-- BEGIN switch IMAGE_ALIGN_RIGHT -->
    </td>
  </tr>
</table>
<table border="0" width="*" cellpadding="10" cellspacing="1">
  <tr>
    <td valign="{IMAGE_VALIGN}">
      <div align="justify" class="articlesynopsis">{TEXT}</div>
    </td>
    <td valign="{IMAGE_VALIGN}">
      <div>{IMAGE}</div>
    </td>
  </tr>
</table>
<table border="0" cellpadding="10" cellspacing="0" width="100%">
  <tr>
    <td>
<!-- END switch IMAGE_ALIGN_RIGHT -->
<!-- BEGIN switch IMAGE_ALIGN_CENTER -->
<!-- BEGIN switch IMAGE_VALIGN_BOTTOM -->
    </td>
  </tr>
</table>
<table border="0" width="*" cellpadding="10" cellspacing="1">
  <tr>
    <td valign="{IMAGE_VALIGN}">
      <div align="justify" class="articlesynopsis">{TEXT}</div>
      <div align="center"><p>{IMAGE}</p></div>
    </td>
  </tr>
</table>
<table border="0" cellpadding="10" cellspacing="0" width="100%">
  <tr>
    <td>
<!-- END switch IMAGE_VALIGN_BOTTOM -->
<!-- BEGIN switch IMAGE_VALIGN_TOP -->
    </td>
  </tr>
</table>
<table border="0" width="*" cellpadding="10" cellspacing="1">
  <tr>
    <td valign="{IMAGE_VALIGN}">
      <div align="center"><p>{IMAGE}</p></div>
      <div align="justify" class="articlesynopsis">{TEXT}</div>
    </td>
  </tr>
</table>
<table border="0" cellpadding="10" cellspacing="0" width="100%">
  <tr>
    <td>
<!-- END switch IMAGE_VALIGN_TOP -->
<!-- END switch IMAGE_ALIGN_CENTER -->
<!-- BEGIN switch IMAGE_ALIGN_LEFT -->
    </td>
  </tr>
</table>
<table border="0" width="*" cellpadding="10" cellspacing="1">
  <tr>
    <td valign="{IMAGE_VALIGN}">
      <div>{IMAGE}</div>
    </td>
    <td valign="{IMAGE_VALIGN}">
      <div align="justify" class="articlesynopsis">{TEXT}</div>
    </td>
  </tr>
</table>
<table border="0" cellpadding="10" cellspacing="0" width="100%">
  <tr>
    <td>
<!-- END switch IMAGE_ALIGN_LEFT -->
<!-- END switch IMAGE -->
      <!-- BEGIN switch NO_IMAGE -->
      <div align="justify" class="articlesynopsis">{TEXT}</div>
      <!-- END switch NO_IMAGE -->
      <table width="100%">
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <!-- BEGIN switch PRINTVIEWBUTTON -->
          <td align="left"><span class="gensmall">{PRINTVIEWBUTTON}</span></td>
          <!-- END switch PRINTVIEWBUTTON -->
          <!-- BEGIN switch PAGEMENU -->
          <td align="right">{PAGEMENU}</td>
          <!-- END switch PAGEMENU -->
        </tr>
      </table>
    </td>
  </tr>
<!-- BEGIN switch ARTICLESECTION -->
  <tr>
    <td>
  {ARTICLESECTION}
    </td>
  </tr>
<!-- END switch ARTICLESECTION -->
  <tr>
    <td>
      <table width="100%">
        <tr>
          <!-- BEGIN switch PAGEMENU -->
          <td align="right">{PAGEMENU}</td>
          <!-- END switch PAGEMENU -->
        </tr>
      </table>
      <hr>
      {EDITDATA}
    </td>
  </tr>
</table>
