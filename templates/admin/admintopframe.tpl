{HEADER}
<table width="100%">
  <tr>
    <td valign="top">
      <!-- BEGIN switch LOGOUTLINK -->
      <!-- BEGIN switch PAGE -->
      <p class="highlight">Page #{PAGE} - {PAGETITLE}</p>
      <form name="publishform" action="{PUBLISHFORMACTIONLINK}?sid={SID}&page={PAGE}&action=setpublish" target="contents" method="post">
        <!-- BEGIN switch IS_PUBLISHED -->
        <input type="submit" name="unpublish" value="Hide page" class="mainoption" />
        <!-- END switch IS_PUBLISHED -->
        <!-- BEGIN switch NOT_PUBLISHED -->
        <input type="submit" name="publish" value="Publish page" class="mainoption" />
        <!-- END switch NOT_PUBLISHED -->
      </form>
      <!-- END switch PAGE -->
      <!-- END switch LOGOUTLINK -->
    </td>
    <td class="highlight" align="right" valign="top">
      <h1 class="maintitle">{SITENAME}</h1>
    </td>
  </tr>
</table>
<table width="100%">
  <tr>
    <td style="white-space:nowrap;">
      <!-- BEGIN switch LOGOUTLINK -->
      {NEWPAGELINK}
      &nbsp;-&nbsp;
      {EDITPAGELINK}
      &nbsp;-&nbsp;
      {PREVIEWPAGELINK}
      &nbsp;-&nbsp;
      {DELETEPAGELINK}
      <!-- END switch LOGOUTLINK -->
    </td>
    <td align="center" style="white-space:nowrap;">
      <!-- BEGIN switch LOGOUTLINK -->
      {IMAGESLINK}
      &nbsp;-&nbsp;
      {CATEGORIESLINK}
      &nbsp;-&nbsp;
      {SITEADMINLINK}
      <!-- END switch LOGOUTLINK -->
    </td>
    <td align="right" style="white-space:nowrap;">
      <!-- BEGIN switch LOGOUTLINK -->
      {PROFILELINK}
      &nbsp;-&nbsp;
      {LOGOUTLINK}
      <!-- END switch LOGOUTLINK -->
      <!-- BEGIN switch LOGINLINK -->
      {REGISTERLINK}
      &nbsp;-&nbsp;
      {LOGINLINK}
      <!-- END switch LOGINLINK -->
    </td>
  </tr>
</table>
{FOOTER}
