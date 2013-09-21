{HEADER}
<div align="right">{BACKBUTTONS}</div>
<hr>
<table width="100%">
  <tr>
    <td align="left">
      <table cellpadding="5">
        <tr>
          <td class="highlight" valign="top" style="white-space:nowrap;">Edit pages:</td>
          {ARTICLEPAGEBUTTON}
        </tr>
      </table>
    </td>
    <td valign="top" align="right" width="*">&nbsp;&nbsp;</td>
    <td valign="top" align="right">
      <table cellpadding="5">
        <tr>
          <td valign="top" align="right">
            <form name="addarticlepageform" action="?sid={SID}&page={PAGE}&action=editcontents" method="post">
              <input type="submit" name="addarticlepage" value="Add Page" class="mainoption" />
            </form>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<hr>
<br />

<table>
  <tr>
    <td class="bodyline">
      <table cellpadding="5">
        {INTRO}
        <form name="articlesourceform" action="?sid={SID}&page={PAGE}&action=editcontents" method="post">
          <tr>
            <th class="thHead" colspan="3">Source</th>
          </tr>
          <tr>
            <td class="gen">Author:</td>
            <td class="table" colspan="2">
              <input type="text" name="author" size="80" maxlength="255" value="{AUTHOR}" />
            </td>
          </tr>
          <tr>
            <td class="gen">Location:</td>
            <td class="table" colspan="2">
              <input type="text" name="location" size="80" maxlength="255" value="{LOCATION}">
            </td>
          </tr>
          <tr>
            <td class="gen">Date:</td>
            <td class="table" colspan="2">
              <span class="gen">&nbsp;Day:</span>
              {DAYFORM}
              <span class="gen">&nbsp;&nbsp;Month:</span>
              {MONTHFORM}
              <span class="gen">&nbsp;&nbsp;Year (4-digit):</span>
              <input type="text" name="year" size="5" maxlength="4" value="{YEAR}" />
            </td>
          </tr>
          <tr>
            <td class="gen">Source name:</td>
            <td class="table" colspan="2">
              <input type="text" name="source" size="80" maxlength="255" value="{SOURCE}" />
            </td>
          </tr>
          <tr>
            <td class="gen">Source  link:</td>
            <td class="table" colspan="2">
              <input type="text" name="sourcelink" size="80" maxlength="255" value="{SOURCELINK}" />
            </td>
          </tr>
          <tr>
            <td class="table" colspan="3" align="center">
              <input type="submit" name="articlesource" value="Save Source" class="mainoption" />
              &nbsp;&nbsp;<input type="reset" value="Reset" class="liteoption" />
            </td>
          </tr>
          <tr><td class="spacer" colspan="3"></td></tr>
        </form>
        <form name="catform" action="?sid={SID}&page={PAGE}&action=editcontents" method="post">
          <tr>
            <th class="thHead" colspan="3">Categories</th>
          </tr>
          <tr>
            <td class="table" colspan="3">
              {CATEGORYLIST}
            </td>
          </tr>
          <tr>
            <td class="table" colspan="3" align="center">
              {CATEGORYSELECTION}
            </td>
          </tr>
          <tr>
            <td class="table" colspan="3" align="center">
              <input type="submit" name="addcat" value="Add Categories" class="liteoption" />
              &nbsp;&nbsp;<input type="submit" name="removecat" value="Remove" class="liteoption" />
            </td>
          </tr>
          <tr><td class="spacer" colspan="3"></td></tr>
        </form>
      </table>
    </td>
  </tr>
</table>
<br />
<hr>
<div align="right">{BACKBUTTONS}</div>
{FOOTER}
