{HEADER}
<div align="right">{BACKBUTTONS}</div>
<hr>
{INTRO}

<table width="100%">
  <tr>
    <td>
      <form name="changelinklistimage" action="?sid={SID}&page={PAGE}&action=editcontents" method="post">
        <table>
          <tr>
            <td class="bodyline">
              <table>
                <tr>
                  <th class="thHead" colspan="3">Image</th>
                </tr>
                <tr>
                  <td class="table" valign="top">
                    <span class="gen">Filename:</span>
                    <br><span class="gensmall"><a href="editimagelist.php?sid={SID}" target="_blank">View files</a></span>
                  </td>
                  <td class="table" valign="top">
                    <input type="text" name="imagefilename" size="50" maxlength="255" value="{IMAGEFILENAME}" />
                    <br /><br /><input type="submit" name="changelinklistimage" value="Add/Change Image" class="mainoption">
                    &nbsp;&nbsp;<input type="reset" value="Reset" class="liteoption">
                    <!-- BEGIN switch IMAGEFILENAME -->
                    <p>&nbsp;</p>
                    <input type="submit" name="removelinklistimage" value="Remove image" class="liteoption">
                    <input type="checkbox" name="removeconfirm" value="Confirm remove" class="gen" />
                    <span class="gen">Confirm remove</span>
                    <!-- END switch IMAGEFILENAME -->
                  </td>
                  <td rowspan="3" class="table" valign="top">
                    {IMAGE}
                    <br>
                    <a href="{IMAGELINKPATH}" target="_blank" class="gensmall">View full size</a>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </form>
    </td>
  </tr>
</table>

<br />&nbsp;
{LINKFORM}
{ADDFORM}
<hr>
<table width="100%"><tr><td><a href="#top" class="gensmall">Top of this page</a></td>
</tr></table>
<br />
<div align="right">{BACKBUTTONS}</div>
{FOOTER}
