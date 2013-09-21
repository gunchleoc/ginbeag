<form name="newsitemimagepropertiesform" action="?sid={SID}&page={PAGE}&offset={OFFSET}&newsitem={NEWSITEM}&imageid={IMAGEID}&action=editcontents" method="post">
  <table>
    <tr>
      <td class="bodyline">
        <table>
          <tr>
            <th class="thHead" colspan="3">
              Image
            </th>
          </tr>
          <tr>
            <td class="table" valign="top">
              <span class="gen">Filename:</span>
              <br><span class="gensmall">
                <a href="{IMAGELISTPATH}?sid={SID}" target="_blank">View files</a>
              </span>
            </td>
            <td valign="top" class="table">
              <input type="text" name="imagefilename" size="50" maxlength="255" value="{IMAGEFILENAME}" />
              <p>
                <input type="submit" name="editnewsitemsynopsisimage" value="Change Image" class="mainoption" />
                &nbsp;&nbsp;<input type="reset" value="Reset" class="liteoption">
              </p>
              <p>
                <input type="submit" name="removenewsitemsynopsisimage" value="Remove image" class="liteoption" />
                <input type="checkbox" name="removeconfirm" value="Confirm remove" class="gen" />
                <span class="gen">Confirm remove</span>
              </p>
            </td>
            <td rowspan="3" class="table" valign="top">
              <!-- BEGIN switch IMAGE -->
              <!-- BEGIN switch IMAGEPATH -->
              {IMAGE}
              <br />
              <a href="{IMAGEPATH}" target="_blank" class="gensmall">View full size</a>
              <!-- END switch IMAGEPATH -->
              <!-- BEGIN switch NO_IMAGEPATH -->
              <span class="highlight">{NO_IMAGEPATH}</span>
              <!-- END switch NO_IMAGEPATH -->
              <!-- END switch IMAGE -->
              <!-- BEGIN switch NO_IMAGE -->
              &nbsp;
              <!-- END switch NO_IMAGE -->
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</form>
