<table border="0" cellpadding="10" cellspacing="0" width="100%">
  <tr>
    <td>
      <h2 class="pagetitle">{PAGETITLE}</h2>
      <!-- BEGIN switch JUMPFORM -->
      {JUMPFORM}
      <!-- END switch JUMPFORM -->
      <table width="100%">
        <tr>
          <!-- BEGIN switch RSS -->
          <td>{RSS}</td>
          <!-- END switch RSS -->
          <!-- BEGIN switch PAGEMENU -->
          <td align="right">{PAGEMENU}</td>
          <!-- END switch PAGEMENU -->
        </tr>
      </table>
      <!-- BEGIN switch SEARCH_RESULT -->
    </td>
  </tr>
  <tr>
    <td>
      <p class="highlight">{MESSAGE}</p>
      <p><a href="?page={PAGE}&sid={SID}" class="gen">{L_SHOWALL}</a>
      <br>&nbsp;</p>
      <!-- END switch SEARCH_RESULT -->
      <hr>
    </td>
  </tr>
<!-- BEGIN switch NEWSITEM -->
  {NEWSITEM}
<!-- END switch NEWSITEM -->
  <tr>
    <td>
      <table width="100%">
        <tr>
          <!-- BEGIN switch RSS -->
          <td>{RSS}</td>
          <!-- END switch RSS -->
          <!-- BEGIN switch PAGEMENU -->
          <td align="right">{PAGEMENU}</td>
          <!-- END switch PAGEMENU -->
        </tr>
      </table>
    </td>
  </tr>
<!-- start filterform -->
  <tr>
    <td>
      <hr>
      <form name="newsfilterform" method="get">
        <input type="hidden" name="page" value="{PAGE}" />
        <input type="hidden" name="sid" value="{SID}" />
        <p class="highlight">{L_DISPLAYOPTIONS}</p>
        <table border="0" cellspacing="0" cellpadding="10">
          <tr>
            <td colspan="4" valign="top" align="left" valign="bottom">
              <span class="gen">{L_CATEGORIES}</span>{CATEGORYSELECTION}
            </td>
            <td valign="bottom">
              <input type="submit" name="filter" value="{L_GO}" class="mainoption" />
            </td>
          </tr>
          <tr>
            <td valign="top" align="left" valign="bottom">
              <span class="gen">{L_FROM}</span>
            </td>
            <td valign="top" align="left" valign="bottom">{FROM_DAY}</td>
            <td valign="top" align="left" valign="bottom">{FROM_MONTH}</td>
            <td valign="top" align="left" valign="bottom">{FROM_YEAR}</td>
          </tr>
          <tr>
            <td valign="top" align="left" valign="bottom">
              <span class="gen">{L_TO}</span>
            </td>
            <td valign="top" align="left" valign="bottom">{TO_DAY}</td>
            <td valign="top" align="left" valign="bottom">{TO_MONTH}</td>
            <td valign="top" align="left" valign="bottom">{TO_YEAR}</td>
          </tr>
        </table>
        <table border="0" cellspacing="0" cellpadding="10">
          <tr>
            <td valign="top" align="left" valign="bottom">
              <span class="gen">{L_ORDERBY}</span>{ORDER}
            </td>
            <td valign="top" align="left" valign="bottom">{ASCDESC}</td>
          </tr>
      </table>
      </form>
<!-- end filterform -->
    </td>
  </tr>
  <tr>
    <td>
      <table width="100%">
        <tr>
          <td align="right" colspan="2">
            <!-- BEGIN switch JUMPFORM -->
            {JUMPFORM}
            <!-- END switch JUMPFORM -->
          </td>
        </tr>
        <tr>
          <!-- BEGIN switch RSS -->
          <td>{RSS}</td>
          <!-- END switch RSS -->
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
