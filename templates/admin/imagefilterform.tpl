<p>
  <form name="imagefilterform" method="get">
  {HIDDENFIELDS}
  <h2 class="pagetitle">Display Options</h2>
   <table border="0" cellspacing="0" cellpadding="10">
      <tr>
        <td  valign="top" align="left" valign="bottom" class="table">
          <span class="gen">Filename:</span>&nbsp;<input type="text" maxlength="255" size="30" name="filename" value="{FILENAME}" />
          &nbsp;&nbsp;&nbsp;&nbsp;
          <span class="gen">Caption:</span>&nbsp;<input type="text" maxlength="255" size="30" name="caption" value="{CAPTION}" />
      	</td>
        <td rowspan="2" class="table">
        &nbsp;&nbsp;
        </td>
        <td  valign="top" rowspan="3" align="left" valign="top" class="table">
          <span class="gen">Categories:</span><br>
          {CATEGORYSELECTION}
          <br /><input type="checkbox" name="categoriesblank" {CATEGORIESBLANK} /><span class="gen">Search for images without categories</span>
      	</td>
      </tr>

      <tr>
        <td valign="top" align="left" valign="bottom" class="table">
          <table><tr>
            <td valign="top" align="left" valign="bottom" class="table">
              <span class="gen">Source:</span>&nbsp;<input type="text" maxlength="255" size="30" name="source" value="{SOURCE}" />
              &nbsp;&nbsp;&nbsp;&nbsp;
              <br /><input type="checkbox" name="sourceblank" {SOURCEBLANK} /><span class="gen">Search for blank source</span>
            </td>
            <td valign="top" align="left" valign="bottom" class="table">
              <span class="gen">Copyright&nbsp;Holder:</span>&nbsp;<input type="text" maxlength="255" size="30" name="copyright" value="{COPYRIGHT}" />
              <br /><input type="checkbox" name="copyrightblank" {COPYRIGHTBLANK} /><span class="gen">Search for blank copyright holder</span>
            </td>
          </tr></table>
        </td>
      </tr>
        <td  valign="top" align="left" valign="bottom" class="table">
          <span class="gen">Uploader:</span>
            {USERSSELECTIONFORM}
      	</td>
       </tr>
    </table>
    <table border="0" cellspacing="0" cellpadding="10" width="100%">
      <tr>
        <td  valign="top" align="left" valign="bottom" class="table">
          <input type="submit" name="filter" value="Display Selection" class="mainoption" />
          <input type="submit" name="clear" value="Show all images" class="liteoption" />
        </td>
      </tr>
    </table>
    <hr>
    <table border="0" cellspacing="2" cellpadding="10" width="100%">
      <tr>
        <td  valign="top" align="left" valign="bottom" class="table">
          &nbsp;
        </td>
        <td  valign="top" align="left" valign="bottom" class="table">
          <p class="highlight">Webpage Status:</p>
        </td>
        <td  valign="top" align="left" valign="bottom" class="table">
          <p class="highlight">File System:</p>
        </td>
      </tr>
      <tr>
        <td  valign="top" align="left" valign="bottom" class="table">
          <p class="highlight">Image options:</p>
        </td>
        <td  valign="top" align="left" valign="bottom" class="table">
          <input type="submit" name="unused" value="Show Unused Images" class="mainoption" />
        </td>
        <td  valign="top" align="left" valign="bottom" class="table">
          <input type="submit" name="missing" value="Missing Image Files" class="mainoption" />
          &nbsp;&nbsp;&nbsp;<input type="submit" name="unknown" value="Unknown Image Files" class="mainoption" />
        </td>
      </tr>
      <tr><td  valign="top" align="left" valign="bottom" class="spacer" colspan="3"></td></tr>
      <tr>
        <td  valign="top" align="left" valign="bottom" class="table">
          <p class="highlight">Thumbnail options:</p>
        </td>
        <td  valign="top" align="left" valign="bottom" class="table">
          <input type="submit" name="nothumb" value="Images without Thumbnails" class="mainoption" />
        </td>
        <td  valign="top" align="left" valign="bottom" class="table">
          <input type="submit" name="missingthumb" value="Missing Thumbnail Files" class="mainoption" />
        </td>
      </tr>
  </td></tr>
  </table>
  </form>
  <form method="get">
  {ORDERSELECTIONHIDDENFIELDS}
    <hr>
    <table border="0" cellspacing="0" cellpadding="10" width="100%">
      <tr>
        <td  valign="top" align="left" valign="bottom" class="table">
          <span class="gen">Order by:</span>
          {IMAGEORDERSELECTION}
          &nbsp;&nbsp;&nbsp;
          {ASCDESCSELECTION}
          &nbsp;&nbsp;&nbsp;
          <input type="submit" name="filter" value="Go" class="mainoption" />
      	</td>
        <td  valign="top" align="right" valign="bottom" class="table" width="*">
        <!-- BEGIN switch PAGEMENU -->
          {PAGEMENU}
        <!-- END switch PAGEMENU -->
        </td>
      </tr>
    </table>
  </form>
</p>
<!-- BEGIN switch MESSAGE -->
<span class="highlight">{MESSAGE}</span>
<!-- END switch MESSAGE -->
