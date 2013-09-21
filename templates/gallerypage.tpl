<table border="0" cellpadding="10" cellspacing="0" width="100%">
  <tr>
    <td>
      <h2 class="pagetitle">{PAGETITLE}</h2>
    </td>
  </tr>
</table>
<table border="0" width="*" cellpadding="10" cellspacing="1">
  <tr>
    <td valign="top">
<!-- BEGIN switch IMAGE -->
      <div align="left"><p>{IMAGE}</p></div>
<!-- END switch IMAGE -->
      <div align="justify" class="gen">{TEXT}</div>
    </td>
  </tr>
</table>
<table border="0" cellpadding="10" cellspacing="0" width="100%">
  <!-- BEGIN switch PAGEMENU -->
  <tr>
    <td>
      <table width="100%">
        <tr>
          <td align="right">{PAGEMENU}</td>
        </tr>
      </table>
    </td>
  </tr>
  <!-- END switch PAGEMENU -->
  <!-- BEGIN switch GALLERYROW -->
  <tr>
    <td>
      <table width="100%" border="0" cellpadding="10">
        {GALLERYROW}
      </table>
    </td>
  </tr>
  <!-- END switch GALLERYROW -->
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
