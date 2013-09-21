{PAGEINTRO}
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
<!-- start filterform -->
  <tr>
    <td>
      <hr>
      <form name="articlefilterform" method="get">
        <input type="hidden" name="page" value="{PAGE}" />
        <input type="hidden" name="sid" value="{SID}" />

        <p class="highlight">{L_DISPLAYOPTIONS}</p>
        <table border="0" cellspacing="0" cellpadding="10">
          <tr>
            <td valign="top" align="left" valign="bottom">
              <span class="gen">{L_CATEGORIES}</span>{CATEGORYSELECTION}
            </td>
            <td valign="top" align="left" valign="bottom">
              <span class="gen">{L_FROM}</span>{FROM_YEAR}
            </td>
            <td valign="top" align="left" valign="bottom">
              <span class="gen">{L_TO}</span>{TO_YEAR}
            </td>
            <td valign="bottom">
              <input type="submit" name="filter" value="{L_GO}" class="mainoption" />
            </td>
          </tr>
        </table>
        <table border="0" cellspacing="0" cellpadding="10">
          <tr>
            <td valign="top" align="left" valign="bottom">
              <span class="gen">{L_ORDERBY}</span>{ORDER}
          	</td>
            <td valign="top" align="left" valign="bottom">{ASCDESC}</td>
            <td valign="bottom">{INCLUDE_SUBPAGES}</td>
          </tr>
        </table>
      </form>
      <hr>
    </td>
  </tr>

<!-- end filterform -->
      {SEARCHFORM}
    </td>
  </tr>
  <!-- BEGIN switch SEARCH_RESULT -->
  <tr>
    <td>
      <p class="highlight">{MESSAGE}</p>
      <p><a href="?page={PAGE}&sid={SID}" class="gen">{L_CLEARSEARCH}</a>
      <br>&nbsp;</p>
    </td>
  </tr>
  <!-- END switch SEARCH_RESULT -->
  <!-- BEGIN switch SUBPAGES -->
  <tr>
    <td>
      <div style="margin-left:3em;">
        {SUBPAGES}
      </div>
    </td>
  </tr>
  <!-- END switch SUBPAGES -->
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
